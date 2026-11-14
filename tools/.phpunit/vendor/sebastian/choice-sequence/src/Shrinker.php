<?php declare(strict_types=1);
/*
 * This file is part of sebastian/choice-sequence.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\ChoiceSequence;

use const PHP_INT_MAX;
use function array_diff;
use function array_key_exists;
use function array_last;
use function array_map;
use function array_pop;
use function array_reverse;
use function array_search;
use function array_slice;
use function array_splice;
use function array_sum;
use function array_values;
use function assert;
use function count;
use function end;
use function hash;
use function implode;
use function intdiv;
use function max;
use function min;
use function range;
use function sort;
use function usort;
use Closure;

/**
 * Shrinks the choice sequence of a counterexample: it repeatedly tries
 * simpler choice sequences and keeps one when replaying it fails in the
 * same way.
 *
 * The shrinker works on choice sequences only. It neither knows the
 * generators that build values from the choices nor how a candidate is
 * checked, so composed generators shrink as well as the ones they are
 * composed of.
 *
 * Its passes are deleting the last elements of collections, searching for
 * the smallest number of elements with a binary search (see SpanKind),
 * setting blocks of consecutive choices to 0,
 * deleting spans, while the references after a span are
 * changed so that they keep referring to the same values, and an element
 * that the minimum size of a collection requires is deleted together with
 * the choice that added the first other element (see SpanKind),
 * replacing spans with shorter spans they
 * contain that have the same label, moving the values of spans that consist
 * of one choice into the later values of spans with the same label as far
 * as these can take them,
 * deleting blocks of choices, minimizing individual choices by trying
 * small values and then searching for the smallest value with a binary
 * search, redistributing pairs of nearby choices by moving as much as
 * possible from the first choice to the second, lowering a choice while
 * raising a later one to its largest value and minimizing the raised choice
 * right away, lowering a choice while deleting
 * as many spans of a group of spans that are part of the same span and have
 * the same label after it, lowering equal choices together, and sorting the
 * spans that are part of the same span and have the same label. The passes
 * are repeated until none of them succeeds, the maximum number of attempts
 * has been made, or shrinking is interrupted. Each round of passes starts
 * with the passes that made the counterexample simpler most often in the
 * round before, so that the attempts are spent on the passes that are most
 * likely to succeed.
 *
 * The passes that delete choices do not delete a part of an element that a
 * choice added to a collection together with choices that are not part of
 * the element, nor every choice of the element but the one that added it:
 * the choices that are left are not read as the elements that are left.
 *
 * The passes that lower choices do not change the choice that added an
 * element to a collection: lowering it ends the collection, which deletes
 * the element and the ones after it, and that is what deleting the last
 * elements of collections does in far fewer attempts than these passes need
 * for a collection with many elements.
 *
 * Only when none of these passes succeeds, choices are lowered in steps: by
 * a small amount, or by a power of two or of ten, and then by as many
 * multiples of the amount that kept the counterexample failing as possible.
 * This finds the smallest counterexample of a property that depends on the
 * remainder of a division, for which a binary search ends at a value that
 * only happens to fail. It needs attempts that are made in vain for every
 * other property, which is why the other passes are tried first. When it
 * succeeds, the other passes are repeated. A choice is lowered in steps right
 * away when a binary search lowers it below a value that is known not to
 * keep the counterexample failing, as only such a property allows that.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final class Shrinker
{
    private const array BLOCK_SIZES                   = [8, 4, 2, 1];
    private const int LARGEST_SMALL_VALUE             = 3;
    private const int LARGEST_STEP                    = 16;
    private const int LARGEST_REDISTRIBUTION_DISTANCE = 4;
    private const int LARGEST_RAISING_DISTANCE        = 3;

    /**
     * How many of the last elements of a collection are deleted, one number
     * of elements after the other, after the smallest number of elements
     * was searched for with a binary search.
     */
    private const int LARGEST_NUMBER_OF_LAST_ELEMENTS = 8;

    /**
     * How many of the values that are left when a span is deleted, the ones
     * that were contributed last first, the first reference to a value that
     * the span contributed is tried with.
     */
    private const int LARGEST_NUMBER_OF_VALUES_FOR_A_REFERENCE = 8;

    /**
     * How many of the groups of spans that are part of the same span, have the
     * same label, and follow a choice, the nearest first, are considered for
     * deleting spans while the choice is lowered.
     */
    private const int LARGEST_NUMBER_OF_GROUPS_AFTER_A_CHOICE = 2;

    /**
     * @var Closure(ChoiceSequence): ?ChoiceSequence
     */
    private readonly Closure $test;

    /**
     * @var non-negative-int
     */
    private readonly int $maximumNumberOfAttempts;

    /**
     * @var ?Closure(): bool
     */
    private readonly ?Closure $shouldStop;
    private ChoiceSequence $current;

    /**
     * @var non-negative-int
     */
    private int $numberOfAttempts = 0;

    /**
     * @var non-negative-int
     */
    private int $numberOfSteps    = 0;
    private bool $budgetExhausted = false;
    private bool $interrupted     = false;

    /**
     * The candidates that have been tried, by a hash of their choices, so
     * that the memory this needs does not grow with the number of choices.
     *
     * @var array<string, true>
     */
    private array $tried = [];

    /**
     * The choice sequence that the elements of collections were found in.
     */
    private ?ChoiceSequence $sequenceOfElements = null;

    /**
     * The elements of collections that a choice added (see
     * SpanKind::Element), in the order of their first choices.
     *
     * @var list<Span>
     */
    private array $elements = [];

    /**
     * The innermost element that a choice added and that an element that a
     * choice added is a part of, an element of a collection that is an
     * element of another collection, for instance, by the first choice of
     * the element that is a part of it.
     *
     * @var array<non-negative-int, Span>
     */
    private array $elementsAroundElements = [];

    /**
     * The innermost element that a choice added and that a choice is a part
     * of, by the choice.
     *
     * @var array<non-negative-int, Span>
     */
    private array $elementsOfChoices = [];

    /**
     * The choices that added an element to a collection: the first choices
     * of the elements that a choice added.
     *
     * @var array<non-negative-int, true>
     */
    private array $choicesThatAddedElements = [];

    /**
     * The elements that the minimum size of a collection requires (see
     * SpanKind::RequiredElement), by their first choice and the choice after
     * their last one.
     *
     * @var array<string, Span>
     */
    private array $requiredElements = [];

    /**
     * The collections that a choice added an element to, in the order of
     * the first of these elements, by the span that the elements of the
     * collection are part of and their label: the elements that a choice
     * added, and the choice after the last choice of the collection.
     *
     * @var array<string, array{elements: non-empty-list<Span>, end: non-negative-int}>
     */
    private array $collections = [];

    /**
     * @param Closure(ChoiceSequence): ?ChoiceSequence $test                    replays a candidate and returns the choice
     *                                                                          sequence that the replay consumed when the
     *                                                                          candidate fails in the same way, and null
     *                                                                          otherwise
     * @param non-negative-int                         $maximumNumberOfAttempts
     * @param ?Closure(): bool                         $shouldStop              whether shrinking is to be interrupted, which is asked before each attempt
     */
    public function __construct(Closure $test, int $maximumNumberOfAttempts, ?Closure $shouldStop = null)
    {
        $this->test                    = $test;
        $this->maximumNumberOfAttempts = $maximumNumberOfAttempts;
        $this->shouldStop              = $shouldStop;
        $this->current                 = new ChoiceSequence([]);
    }

    public function shrink(ChoiceSequence $sequence): ShrinkingResult
    {
        $this->current          = $sequence;
        $this->numberOfAttempts = 0;
        $this->numberOfSteps    = 0;
        $this->budgetExhausted  = false;
        $this->interrupted      = false;
        $this->tried            = [];

        // shrinking is turned off when no attempt may be made, which does not
        // exhaust the budget for shrinking
        if ($this->maximumNumberOfAttempts === 0) {
            return new ShrinkingResult($sequence, 0, 0, false, false);
        }

        $passes = [];

        foreach ([
            $this->deleteLastElementsOfCollections(...),
            $this->zeroBlocks(...),
            $this->deleteSpans(...),
            $this->replaceSpansWithDescendants(...),
            $this->moveValuesToLaterValuesWithTheSameLabel(...),
            $this->deleteBlocks(...),
            $this->minimizeChoices(...),
            $this->redistributeChoices(...),
            $this->lowerChoicesRaisingLaterOnes(...),
            $this->lowerChoicesDeletingLaterSpans(...),
            $this->lowerEqualChoices(...),
            $this->sortSiblingSpans(...),
        ] as $pass) {
            $passes[] = ['pass' => $pass, 'numberOfSteps' => 0];
        }

        do {
            do {
                $numberOfSteps = 0;

                foreach ($passes as $index => $pass) {
                    $numberOfStepsOfPass = $this->numberOfStepsOf($pass['pass']);

                    $passes[$index]['numberOfSteps'] = $numberOfStepsOfPass;

                    $numberOfSteps += $numberOfStepsOfPass;
                }

                // the sort is stable, so passes that made the counterexample
                // simpler equally often keep their order
                usort(
                    $passes,
                    static fn (array $a, array $b): int => $b['numberOfSteps'] <=> $a['numberOfSteps'],
                );
            } while ($numberOfSteps > 0 && !$this->isStopped());
        } while ($this->numberOfStepsOf($this->lowerChoicesInSteps(...)) > 0 && !$this->isStopped());

        return new ShrinkingResult(
            $this->current,
            $this->numberOfAttempts,
            $this->numberOfSteps,
            $this->budgetExhausted,
            $this->interrupted,
        );
    }

    /**
     * Runs a pass and returns how often it made the counterexample simpler.
     *
     * @param Closure(): void $pass
     *
     * @phpstan-impure
     */
    private function numberOfStepsOf(Closure $pass): int
    {
        $numberOfStepsBefore = $this->numberOfSteps;

        $pass();

        return $this->numberOfSteps - $numberOfStepsBefore;
    }

    /**
     * Replacing an element that a choice added to a collection, and
     * everything up to the end of the collection, with the choice that ends
     * a collection deletes the element and the ones after it. The smallest
     * number of elements that keeps the counterexample failing is searched
     * for with a binary search, so that a collection with many elements is
     * shortened in few attempts, where deleting its elements one after the
     * other needs an attempt for each of them.
     *
     * A binary search ends at a number of elements that only happens to
     * keep the counterexample failing when it does not fail for every
     * larger number of elements, when the number of elements has to be
     * divisible by a number, for instance. This is why some of the last
     * elements are deleted afterwards, one number of elements after the
     * other.
     */
    private function deleteLastElementsOfCollections(): void
    {
        $index = 0;

        while (!$this->isStopped()) {
            $this->findElements();

            $collections = array_values($this->collections);

            if (!isset($collections[$index])) {
                return;
            }

            $start = $collections[$index]['elements'][0]->start();

            $this->searchForSmallestNumberOfElements($collections[$index]);

            do {
                $deleted = $this->deleteSomeOfTheLastElements($index, $start);
            } while ($deleted);

            // a collection none of whose elements is left is not found
            // again, the collection after it has its index then
            if ($this->collectionAt($index, $start) !== null) {
                $index++;
            }
        }
    }

    /**
     * @param array{elements: non-empty-list<Span>, end: non-negative-int} $collection
     */
    private function searchForSmallestNumberOfElements(array $collection): void
    {
        // every candidate keeps the choices before the element that it
        // deletes, which are the ones of the sequence that the search
        // started with
        $choices = $this->current->choices();
        $lower   = -1;
        $upper   = count($collection['elements']);

        while ($upper - $lower > 1 && !$this->isStopped()) {
            $middle = $lower + intdiv($upper - $lower, 2);

            assert($middle >= 0);

            if ($this->consider(self::withoutElementsFrom($choices, $collection, $middle))) {
                $upper = $middle;
            } else {
                $lower = $middle;
            }
        }
    }

    /**
     * @param non-negative-int $index the index of the collection
     * @param non-negative-int $start the first choice of the first element that a choice added to the collection
     */
    private function deleteSomeOfTheLastElements(int $index, int $start): bool
    {
        $collection = $this->collectionAt($index, $start);

        if ($collection === null) {
            return false;
        }

        $numberOfElements = count($collection['elements']);

        $first = max(0, $numberOfElements - self::LARGEST_NUMBER_OF_LAST_ELEMENTS);

        for ($element = $numberOfElements - 1; $element >= $first; $element--) {
            if ($this->consider(self::withoutElementsFrom($this->current->choices(), $collection, $element))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param non-negative-int $index
     * @param non-negative-int $start
     *
     * @return ?array{elements: non-empty-list<Span>, end: non-negative-int}
     */
    private function collectionAt(int $index, int $start): ?array
    {
        $this->findElements();

        $collections = array_values($this->collections);

        if (!isset($collections[$index]) || $collections[$index]['elements'][0]->start() !== $start) {
            return null;
        }

        return $collections[$index];
    }

    /**
     * Deleting the span of an element of a collection deletes the element.
     * Longer spans are tried first. A span that holds every choice of an
     * element but the one that added it is not deleted.
     */
    private function deleteSpans(): void
    {
        $index = 0;

        while (!$this->isStopped()) {
            $spans = $this->spansOfCurrentSequence();

            if ($index >= count($spans)) {
                return;
            }

            // a span without choices is not a part of an element
            if ($spans[$index]->length() > 0 && $this->cutsElement($spans[$index]->start(), $spans[$index]->end())) {
                $index++;

                continue;
            }

            // after a successful deletion, the span at the same index of the
            // simpler sequence is tried next
            if (!$this->deleteRequiredElement($spans[$index]) &&
                !$this->deleteSpanKeepingReferences($spans[$index]) &&
                !$this->consider($this->withoutChoices($spans[$index]->start(), $spans[$index]->length())) &&
                !$this->deleteSpanRaisingLaterChoice($spans[$index])) {
                $index++;
            }
        }
    }

    /**
     * No choice decided that there is an element that the minimum size of a
     * collection requires, so deleting its span leaves the collection with
     * as many elements as before: the choice that added the first other
     * element is read as a part of an element. Deleting the span together
     * with that choice deletes the element, and the first other element
     * takes the place of the last element that the minimum size requires. A
     * collection that has no other element cannot have fewer elements.
     */
    private function deleteRequiredElement(Span $span): bool
    {
        $this->findElements();

        $key = $span->start() . ':' . $span->end();

        if (!isset($this->requiredElements[$key])) {
            return false;
        }

        $collection = self::collectionOf($this->requiredElements[$key]);

        if (!isset($this->collections[$collection])) {
            return false;
        }

        $choices = $this->current->choices();

        // the choice that added the element comes after the span
        array_splice($choices, $this->collections[$collection]['elements'][0]->start(), 1);
        array_splice($choices, $span->start(), $span->length());

        return $this->consider($choices);
    }

    /**
     * Finds the elements of the collections of the current sequence, once
     * for each sequence.
     */
    private function findElements(): void
    {
        if ($this->sequenceOfElements === $this->current) {
            return;
        }

        $this->sequenceOfElements       = $this->current;
        $this->elements                 = [];
        $this->elementsAroundElements   = [];
        $this->elementsOfChoices        = [];
        $this->choicesThatAddedElements = [];
        $this->requiredElements         = [];
        $this->collections              = [];

        $ends = [];

        foreach ($this->current->spans() as $span) {
            if ($span->kind() === SpanKind::RequiredElement) {
                $this->requiredElements[$span->start() . ':' . $span->end()] = $span;

                continue;
            }

            if ($span->kind() !== SpanKind::Element) {
                continue;
            }

            $collection = self::collectionOf($span);

            if (!isset($ends[$collection]) || $ends[$collection] < $span->end()) {
                $ends[$collection] = $span->end();
            }

            // a span of this kind that starts with the choice 0 ends its
            // collection
            if ($span->length() > 0 && $this->choiceAt($span->start()) === 1) {
                $this->elements[] = $span;
            }
        }

        usort(
            $this->elements,
            static fn (Span $a, Span $b): int => $a->start() <=> $b->start(),
        );

        $around = [];

        foreach ($this->elements as $element) {
            // the elements that started before this one and that it is not
            // a part of have ended
            while ($around !== [] && array_last($around)->end() <= $element->start()) {
                array_pop($around);
            }

            if ($around !== []) {
                $this->elementsAroundElements[$element->start()] = array_last($around);
            }

            $around[] = $element;

            for ($choice = $element->start(); $choice < $element->end(); $choice++) {
                $this->elementsOfChoices[$choice] = $element;
            }

            $this->choicesThatAddedElements[$element->start()] = true;

            $collection = self::collectionOf($element);

            if (isset($this->collections[$collection])) {
                $this->collections[$collection]['elements'][] = $element;

                continue;
            }

            // the end of the collection was found with its first element
            assert(isset($ends[$collection]));

            $this->collections[$collection] = ['elements' => [$element], 'end' => $ends[$collection]];
        }
    }

    /**
     * A reference is the position of a value among the values that earlier
     * spans contributed, counted from the last one, the value that a step of
     * a state machine takes from a pool, for instance. Deleting a span that
     * contributed a value, or that consumed one, changes the positions of
     * the values that were contributed before it, so that the references
     * after the span refer to other values and the counterexample no longer
     * fails, although the span is not needed for it to fail. The span is
     * therefore deleted while the references after it are changed so that
     * they refer to the values that they referred to before. This is tried
     * before the span is deleted without changing a reference, as it deletes
     * what the span contributed and nothing else.
     *
     * A reference to a value that the span contributed cannot refer to it
     * when the span is deleted. Another value can keep the counterexample
     * failing as well, one that is equal to it, for instance, as values
     * often are once they have been simplified. The first such reference is
     * therefore also tried with each of the values that are left, the one
     * that was contributed last first.
     */
    private function deleteSpanKeepingReferences(Span $span): bool
    {
        [$choices, $numberOfValuesForReferenceToDeletedValue] = $this->withReferencesKeptWithout($span, null);

        if ($choices !== null && $this->considerWithout($choices, $span)) {
            return true;
        }

        $numberOfValues = min($numberOfValuesForReferenceToDeletedValue, self::LARGEST_NUMBER_OF_VALUES_FOR_A_REFERENCE);

        for ($position = 0; $position < $numberOfValues; $position++) {
            [$choices] = $this->withReferencesKeptWithout($span, $position);

            if ($choices !== null && $this->considerWithout($choices, $span)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param list<non-negative-int> $choices
     */
    private function considerWithout(array $choices, Span $span): bool
    {
        array_splice($choices, $span->start(), $span->length());

        return $this->consider($choices);
    }

    /**
     * The choices of the current sequence, with the references after the
     * span that refer to another value when the span is deleted changed so
     * that they refer to the value that they refer to now, and null when no
     * reference is changed.
     *
     * A reference to a value that the span contributed is not changed,
     * except for the first one when a position is given for it.
     *
     * @param ?non-negative-int $positionOfFirstReferenceToDeletedValue
     *
     * @return array{0: ?list<non-negative-int>, 1: non-negative-int} the choices, and the number of values that the first reference to a value that the span contributed can refer to when the span is deleted, 0 when there is no such reference
     */
    private function withReferencesKeptWithout(Span $span, ?int $positionOfFirstReferenceToDeletedValue): array
    {
        $choices                                  = $this->current->choices();
        $changed                                  = false;
        $numberOfValuesForReferenceToDeletedValue = 0;

        // the values that can be referred to, by label, in the order in
        // which they were contributed: as they are now, and as they are when
        // the span is deleted
        $now     = [];
        $without = [];

        foreach ($this->referablesAndReferences() as $key => $other) {
            $label   = $other->label();
            $deleted = $other->start() >= $span->start() && $other->end() <= $span->end();

            if ($other->kind() === SpanKind::Referable) {
                $now[$label][] = $key;

                if (!$deleted) {
                    $without[$label][] = $key;
                }

                continue;
            }

            $choice = $this->choiceAt($other->start());
            $index  = false;

            if (isset($now[$label]) && $now[$label] !== []) {
                $value = $this->valueAt($now[$label], $choice);

                if ($other->kind() === SpanKind::ConsumingReference) {
                    $now[$label] = array_values(array_diff($now[$label], [$value]));
                }

                if (isset($without[$label])) {
                    $index = array_search($value, $without[$label], true);
                }
            }

            // a reference without a value to refer to is not drawn again
            if ($deleted || !isset($without[$label]) || $without[$label] === []) {
                continue;
            }

            $numberOfValues = count($without[$label]);
            $write          = false;

            if ($index === false) {
                // the span contributed the value that the reference refers
                // to: the reference is replayed as it is, unless it is the
                // first one and a position is given for it
                $position = min($choice, $numberOfValues - 1);

                if ($numberOfValuesForReferenceToDeletedValue === 0) {
                    $numberOfValuesForReferenceToDeletedValue = $numberOfValues;

                    if ($positionOfFirstReferenceToDeletedValue !== null) {
                        $position = min($positionOfFirstReferenceToDeletedValue, $numberOfValues - 1);
                        $write    = true;
                    }
                }

                $index = $numberOfValues - 1 - $position;
            } else {
                $position = $numberOfValues - 1 - $index;
                $write    = $position !== $choice;
            }

            assert($position >= 0);

            if ($write) {
                array_splice($choices, $other->start(), 1, [$position]);

                $changed = true;
            }

            if ($other->kind() === SpanKind::ConsumingReference) {
                array_splice($without[$label], $index, 1);
            }
        }

        if (!$changed) {
            return [null, $numberOfValuesForReferenceToDeletedValue];
        }

        return [$choices, $numberOfValuesForReferenceToDeletedValue];
    }

    /**
     * The value that a reference with the given choice refers to: a choice
     * that is greater than the last position refers to the value that was
     * contributed first.
     *
     * @param non-empty-list<non-negative-int> $values
     * @param non-negative-int                 $choice
     *
     * @return non-negative-int
     */
    private function valueAt(array $values, int $choice): int
    {
        $index = max(0, count($values) - 1 - $choice);

        assert(array_key_exists($index, $values));

        return $values[$index];
    }

    /**
     * The spans of the current sequence that contribute a value that can be
     * referred to, and the references, in the order in which they take
     * effect: a value can be referred to after the last choice of its span.
     *
     * @return list<Span>
     */
    private function referablesAndReferences(): array
    {
        $spans = [];

        foreach ($this->current->spans() as $span) {
            if ($span->kind() === SpanKind::Referable) {
                $spans[] = $span;
            } elseif (($span->kind() === SpanKind::Reference || $span->kind() === SpanKind::ConsumingReference) && $span->length() === 1) {
                $spans[] = $span;
            }
        }

        usort(
            $spans,
            static function (Span $a, Span $b): int
            {
                if ($a->end() !== $b->end()) {
                    return $a->end() <=> $b->end();
                }

                return $b->start() <=> $a->start();
            },
        );

        return $spans;
    }

    /**
     * What a span contributed to the counterexample can be needed to keep it
     * failing, the length of a domain name to which a label
     * contributes, for instance. The span is deleted while its contribution
     * is moved to a later group of spans of a kind that it contains, the
     * top-level domain, for instance, by raising the choice that determines
     * how many spans the group has by the number of choices of the span.
     * Other passes lower the raised choice again, as far as the
     * counterexample keeps failing.
     */
    private function deleteSpanRaisingLaterChoice(Span $span): bool
    {
        $labels = [];

        foreach ($this->current->spans() as $other) {
            if ($other->start() >= $span->start() && $other->end() <= $span->end() && $other->length() < $span->length()) {
                $labels[$other->label()] = $other->label();
            }
        }

        foreach ($labels as $label) {
            foreach ($this->choicesThatDetermineGroupsAfter($span->end(), $label, $this->groupsOfSpans()) as $later) {
                $raised = $later - $span->length();

                // the deleted span precedes the raised choice
                assert($raised >= 0);

                if ($this->consider($this->withRaisedChoice($this->withoutChoices($span->start(), $span->length()), $raised, $later, $span->length()))) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * A recursive value, a tree for instance, contains values that were
     * generated the same way, its subtrees, whose spans have the same label as
     * the span of the value. Replacing the span of the value with the span of
     * one of these values shrinks it by many levels at once. Longer spans are
     * replaced first, each with the shortest spans first.
     */
    private function replaceSpansWithDescendants(): void
    {
        while (!$this->isStopped()) {
            $replaced = false;

            foreach ($this->descendantsWithTheSameLabel() as [$span, $descendant]) {
                // once shrinking is stopped, no further candidate is replayed
                if ($this->consider($this->withChoicesOfSpanInsteadOf($descendant, $span))) {
                    $replaced = true;

                    break;
                }
            }

            if (!$replaced) {
                return;
            }
        }
    }

    /**
     * The pairs of a span of the current sequence and a shorter span that it
     * contains and that has the same label, longer spans first, and for each
     * of them shorter descendants first.
     *
     * @return list<array{Span, Span}>
     */
    private function descendantsWithTheSameLabel(): array
    {
        $spansByLabel = [];

        foreach ($this->current->spans() as $span) {
            $spansByLabel[$span->label()][] = $span;
        }

        $pairs = [];

        foreach ($spansByLabel as $spans) {
            // the spans are in the order in which they were started, so the
            // spans that a span contains follow it
            foreach ($spans as $index => $span) {
                for ($next = $index + 1; isset($spans[$next]) && $spans[$next]->start() < $span->end(); $next++) {
                    if ($spans[$next]->end() <= $span->end() && $spans[$next]->length() < $span->length()) {
                        $pairs[] = [$span, $spans[$next]];
                    }
                }
            }
        }

        usort(
            $pairs,
            static function (array $a, array $b): int
            {
                if ($a[0]->length() !== $b[0]->length()) {
                    return $b[0]->length() <=> $a[0]->length();
                }

                if ($a[0]->start() !== $b[0]->start()) {
                    return $a[0]->start() <=> $b[0]->start();
                }

                return $a[1]->length() <=> $b[1]->length();
            },
        );

        return $pairs;
    }

    /**
     * A counterexample for a property such as "the sum of the elements of a
     * list does not exceed 1000" has a sum just above 1000 once as many of its
     * elements as possible were deleted: deleting or lowering any element
     * then makes the property hold. It has fewer elements when the values of the earlier
     * elements are moved into the later ones, as far as these can take them,
     * so that the earlier ones become zero and can be deleted. A value is the
     * choice of a span that consists of one choice, and it is moved into the
     * values whose spans have the same label, the last ones first, wherever
     * they are: the elements of a list, or the parameters that are generated
     * alike. How much a value can take is known from the largest choice that
     * it could have been.
     */
    private function moveValuesToLaterValuesWithTheSameLabel(): void
    {
        $groups = $this->valuesWithTheSameLabel();

        foreach ($groups as $indexes) {
            foreach ($indexes as $position => $first) {
                foreach (array_reverse(array_slice($indexes, $position + 1)) as $second) {
                    if ($this->isStopped() || $this->choiceAt($first) === 0) {
                        break;
                    }

                    $room = $this->room($second);

                    if ($room === 0) {
                        continue;
                    }

                    if (!$this->consider($this->withMovedAmount($first, $second, min($this->choiceAt($first), $room)))) {
                        break;
                    }

                    // the values are found again in the next round when
                    // moving a value changed which choices are values
                    if ($this->valuesWithTheSameLabel() !== $groups) {
                        return;
                    }
                }
            }
        }
    }

    /**
     * The indexes of the choices of spans that consist of one choice, grouped
     * by the label of the spans, for the labels of at least two such spans
     * of which no two are adjacent. Adjacent values with the same label are
     * the characters of a string, for instance, whose value would be moved
     * from character to character, and redistributeChoices() moves values
     * between adjacent choices anyway.
     *
     * @return list<non-empty-list<non-negative-int>>
     */
    private function valuesWithTheSameLabel(): array
    {
        $indexes = [];

        foreach ($this->current->spans() as $span) {
            if ($span->length() === 1) {
                $indexes[$span->label()][$span->start()] = $span->start();
            }
        }

        $groups = [];

        foreach ($indexes as $group) {
            if (count($group) < 2) {
                continue;
            }

            sort($group);

            $previous = null;

            foreach ($group as $index) {
                if ($previous !== null && $index === $previous + 1) {
                    continue 2;
                }

                $previous = $index;
            }

            $groups[] = $group;
        }

        return $groups;
    }

    /**
     * How much the choice at the index can be raised without exceeding the
     * largest choice it can be.
     *
     * @param non-negative-int $index
     *
     * @return non-negative-int
     */
    private function room(int $index): int
    {
        $maximum = $this->current->maximumAt($index);

        if ($maximum === null) {
            $maximum = PHP_INT_MAX;
        }

        return max(0, $maximum - $this->choiceAt($index));
    }

    private function deleteBlocks(): void
    {
        foreach (self::BLOCK_SIZES as $size) {
            $start = $this->length() - $size;

            while ($start >= 0 && !$this->isStopped()) {
                if ($this->cutsElement($start, $start + $size)) {
                    $start--;

                    continue;
                }

                if ($this->consider($this->withoutChoices($start, $size))) {
                    $start = min($start, $this->length() - $size);

                    continue;
                }

                $start--;
            }
        }
    }

    /**
     * Blocks are set to 0 before spans and blocks are deleted, because a
     * collection that has its smallest size keeps the attempts to delete its
     * choices from succeeding, and these attempts would use up what the
     * attempts to set its choices to 0 need. Deleting one of its elements
     * that have the same choices leaves the same choices as deleting another
     * one, which is not tried again, so that elements that were set to 0 are
     * tried to be deleted in one attempt rather than in one for each of
     * them. A block that was set to 0 is skipped, so that the next attempt
     * does not overlap with it.
     */
    private function zeroBlocks(): void
    {
        foreach (self::BLOCK_SIZES as $size) {
            for ($start = 0; $start + $size <= $this->length() && !$this->isStopped(); $start++) {
                if ($this->isZero($start, $size)) {
                    continue;
                }

                if ($this->consider($this->withZeroedChoices($start, $size))) {
                    $start += $size - 1;
                }
            }
        }
    }

    private function minimizeChoices(): void
    {
        for ($index = 0; $index < $this->length() && !$this->isStopped(); $index++) {
            $this->minimizeChoice($index);
        }
    }

    /**
     * A binary search finds the smallest value of a choice that keeps the
     * counterexample failing when every larger value keeps it failing, too.
     * When a binary search ended at the choice before, the value below the
     * choice has been tried and does not keep the counterexample failing. A
     * smaller value that does shows that the property depends on the choice
     * in another way, on the remainder of a division, for instance, for which
     * each further binary search only happens to find a smaller value. The
     * choice is lowered in steps then, rather than using up the attempts for
     * one binary search after the other.
     *
     * @param non-negative-int $index
     */
    private function minimizeChoice(int $index): void
    {
        if ($this->addedElement($index)) {
            return;
        }

        $choice                = $this->choiceAt($index);
        $valueBelowDoesNotFail = $choice > 0 && $this->hasBeenTried($this->withChoice($index, $choice - 1));

        for ($value = 0; $value <= self::LARGEST_SMALL_VALUE && $value < $choice; $value++) {
            if ($this->consider($this->withChoice($index, $value))) {
                return;
            }
        }

        // the largest small value that was tried is a lower bound for which
        // the candidate does not fail, and the current choice is an upper
        // bound for which it does
        $lower = self::LARGEST_SMALL_VALUE;
        $upper = $choice;

        while ($upper - $lower > 1 && !$this->isStopped()) {
            $middle = $lower + intdiv($upper - $lower, 2);

            assert($middle >= 0);

            if (!$this->consider($this->withChoice($index, $middle))) {
                $lower = $middle;

                continue;
            }

            if ($index >= $this->length()) {
                return;
            }

            if ($valueBelowDoesNotFail) {
                if ($this->lowerChoiceInSteps($index)) {
                    return;
                }

                $valueBelowDoesNotFail = false;
            }

            $upper = $this->choiceAt($index);
        }
    }

    /**
     * A property such as $number % 15 !== 0 is falsified by every 15th value,
     * so a binary search for the smallest value of a choice ends at a value
     * whose predecessor does not falsify the property, which can be any
     * multiple of 15. The values that such a property is falsified by are
     * apart by a multiple of the divisor, which is found by lowering the
     * choice in steps.
     *
     * A value that could not be lowered in steps is not tried again for the
     * choices after the one that has it, so that a counterexample with many
     * equal values, the elements of a list that all have to exceed the same
     * value, for instance, does not use up the attempts.
     */
    private function lowerChoicesInSteps(): void
    {
        $notLowered = [];

        for ($index = 0; $index < $this->length() && !$this->isStopped(); $index++) {
            $choice = $this->choiceAt($index);

            if (isset($notLowered[$choice])) {
                continue;
            }

            if (!$this->lowerChoiceInSteps($index)) {
                $notLowered[$choice] = true;
            }
        }
    }

    /**
     * Lowers the choice by the smallest step that keeps the counterexample
     * failing, and then by as many multiples of this step as possible. Values
     * that are not larger than the largest small value are not tried, as
     * minimizing the choice has tried them.
     *
     * The choice is lowered by another step afterwards only when it could be
     * lowered by a further multiple of the step. A property whose outcome
     * does not depend on its choices alone lets any step keep the
     * counterexample failing by chance, and lowering the choice step by step
     * would use up the attempts for it.
     *
     * @param non-negative-int $index
     */
    private function lowerChoiceInSteps(int $index): bool
    {
        $lowered = false;

        do {
            $choice = $this->choiceAt($index);
            $step   = $this->stepThatLowers($index, $choice);

            if ($step === null) {
                break;
            }

            $lowered = true;

            if ($index >= $this->length() || $this->choiceAt($index) !== $choice - $step) {
                break;
            }
        } while ($this->lowerChoiceByMultiplesOf($index, $step) && $index < $this->length() && !$this->isStopped());

        return $lowered;
    }

    /**
     * @param non-negative-int $index
     * @param non-negative-int $choice
     *
     * @return ?positive-int the smallest step by which the choice was lowered, null when it was not lowered
     */
    private function stepThatLowers(int $index, int $choice): ?int
    {
        foreach ($this->stepsFor($choice) as $step) {
            $value = $choice - $step;

            if ($value <= self::LARGEST_SMALL_VALUE) {
                break;
            }

            if ($this->consider($this->withChoice($index, $value))) {
                return $step;
            }
        }

        return null;
    }

    /**
     * The steps by which a choice is lowered, smallest first: every step up
     * to the largest step, and the larger powers of two and of ten that are
     * not larger than the choice, as a property often depends on the
     * remainder of a division by such a number: on the last two digits of a
     * number or on its lowest byte, for instance.
     *
     * @param non-negative-int $choice
     *
     * @return list<positive-int>
     */
    private function stepsFor(int $choice): array
    {
        $steps = range(1, self::LARGEST_STEP);

        foreach ([2, 10] as $base) {
            $step = $base;

            while ($step <= intdiv($choice, $base)) {
                $step *= $base;

                if ($step > self::LARGEST_STEP) {
                    $steps[] = $step;
                }
            }
        }

        sort($steps);

        return $steps;
    }

    /**
     * Lowering the choice by the step kept the counterexample failing. When
     * that is so because the property depends on the remainder of a division
     * by the step, the smallest value that keeps the counterexample failing
     * is the remainder of the choice, or that remainder plus the step when
     * the property does not hold for small values. These two values are
     * tried first. Otherwise, the amount by which the choice is lowered is
     * doubled until the counterexample no longer fails and then halved until
     * it is the step again, which lowers the choice by as many multiples of
     * the step as a binary search finds.
     *
     * @param non-negative-int $index
     * @param positive-int     $step
     *
     * @return bool whether the choice was lowered
     */
    private function lowerChoiceByMultiplesOf(int $index, int $step): bool
    {
        $choice    = $this->choiceAt($index);
        $remainder = $choice % $step;

        foreach ([$remainder, $remainder + $step] as $value) {
            if ($value < $choice && $this->consider($this->withChoice($index, $value))) {
                return true;
            }
        }

        $lowered = false;
        $amount  = $step;

        while ($amount <= intdiv(PHP_INT_MAX, 2) && $this->lowerChoiceBy($index, $amount)) {
            $lowered = true;
            $amount *= 2;
        }

        while ($amount > $step) {
            $amount = intdiv($amount, 2);

            assert($amount >= 1);

            if ($this->lowerChoiceBy($index, $amount)) {
                $lowered = true;
            }
        }

        return $lowered;
    }

    /**
     * @param non-negative-int $index
     * @param positive-int     $amount
     */
    private function lowerChoiceBy(int $index, int $amount): bool
    {
        if ($index >= $this->length()) {
            return false;
        }

        $value = $this->choiceAt($index) - $amount;

        if ($value < 0) {
            return false;
        }

        return $this->consider($this->withChoice($index, $value));
    }

    /**
     * A counterexample for a property such as $a + $b > 10 cannot be shrunk
     * from [11, 0] to [0, 11] by minimizing one choice at a time. Moving an
     * amount from a choice to a later one keeps their sum, while the choice
     * sequence becomes simpler.
     */
    private function redistributeChoices(): void
    {
        for ($first = 0; $first < $this->length() && !$this->isStopped(); $first++) {
            for ($second = $first + 1; $second <= $first + self::LARGEST_REDISTRIBUTION_DISTANCE && $second < $this->length() && !$this->isStopped(); $second++) {
                if ($this->addedElement($first) || $this->addedElement($second)) {
                    continue;
                }

                $this->redistribute($first, $second);
            }
        }
    }

    /**
     * Moves as much as possible from the choice at $first to the choice at
     * $second: everything if that keeps the counterexample failing, otherwise the
     * largest amount that a binary search finds.
     *
     * @param non-negative-int $first
     * @param non-negative-int $second
     */
    private function redistribute(int $first, int $second): void
    {
        $firstChoice  = $this->choiceAt($first);
        $secondChoice = $this->choiceAt($second);

        // the amount that can be moved without exceeding the largest choice
        $movable = min($firstChoice, PHP_INT_MAX - $secondChoice);

        if ($movable < 1) {
            return;
        }

        if ($this->consider($this->withMovedAmount($first, $second, $movable))) {
            return;
        }

        // moving nothing keeps the counterexample failing, moving everything
        // that can be moved does not
        $lower = 0;
        $upper = $movable;

        while ($upper - $lower > 1 && !$this->isStopped()) {
            $middle = $lower + intdiv($upper - $lower, 2);

            assert($middle > 0);

            $candidate = $this->withMovedAmount($first, $second, $middle, $firstChoice, $secondChoice);

            if (!$this->consider($candidate)) {
                $upper = $middle;

                continue;
            }

            // the amounts are relative to the choices from which the pair is
            // redistributed, which the consumed choice sequence may not have
            if ($this->current->choices() !== $candidate) {
                return;
            }

            $lower = $middle;
        }
    }

    /**
     * A counterexample for a property such as $a * $b < 1000, with $a and $b
     * between 0 and 100, cannot be shrunk from [20, 50] to [10, 100] by
     * minimizing one choice at a time or by moving an amount from $a to $b,
     * which keeps their sum. Lowering a choice by one can keep the
     * counterexample failing when a later choice is raised to the largest
     * value it can have, which the choice source clamps a larger choice to.
     * The raised choice is minimized right away, so that a run of choices such
     * as the digits of a number is minimized from left to right in one go
     * rather than one choice for each round of passes.
     */
    private function lowerChoicesRaisingLaterOnes(): void
    {
        for ($index = 0; $index < $this->length() && !$this->isStopped(); $index++) {
            for ($later = $index + 1; $later <= $index + self::LARGEST_RAISING_DISTANCE && $later < $this->length(); $later++) {
                if ($this->choiceAt($index) === 0 || $this->addedElement($index)) {
                    break;
                }

                if ($this->addedElement($later)) {
                    continue;
                }

                if ($this->consider($this->withLoweredAndRaisedChoice($index, $later))) {
                    if ($later < $this->length()) {
                        $this->minimizeChoice($later);
                    }

                    break;
                }
            }
        }
    }

    /**
     * A choice can determine how many values are drawn after it, for instance
     * the size of a list, which is drawn before its elements, and an index
     * into the list that is drawn after them. Lowering such a choice alone
     * makes the choices of the elements that are no longer drawn be read as
     * the index, and deleting the choices of elements alone makes the list
     * read the choice of the index as an element. The choice is therefore
     * lowered by an amount while as many of the last spans of a group of
     * spans that are part of the same span, have the same label, and follow
     * the choice are deleted: the elements of the list that the lower choice
     * no longer draws. The amount is doubled after each success and starts
     * over at one after a failure.
     */
    private function lowerChoicesDeletingLaterSpans(): void
    {
        $groups = $this->groupsOfSpans();

        for ($index = 0; $index < $this->length() && !$this->isStopped(); $index++) {
            $amount = 1;

            while ($index < $this->length() && $this->choiceAt($index) > 0 && !$this->addedElement($index)) {
                if ($this->lowerChoiceDeletingLaterSpans($index, $amount, $groups)) {
                    $groups = $this->groupsOfSpans();
                    $amount *= 2;

                    continue;
                }

                if ($amount === 1) {
                    break;
                }

                $amount = 1;
            }
        }
    }

    /**
     * @param non-negative-int           $index
     * @param positive-int               $amount
     * @param list<non-empty-list<Span>> $groups the groups of spans of the current sequence (see groupsOfSpans())
     *
     * @return bool whether the choice was lowered
     */
    private function lowerChoiceDeletingLaterSpans(int $index, int $amount, array $groups): bool
    {
        $choice = $this->choiceAt($index);

        foreach ($this->groupsOfSpansAfter($index, $groups) as $spans) {
            $deleted = min($amount, $choice, count($spans));

            assert($deleted > 0);

            $lastSpans        = array_slice($spans, -$deleted);
            $withoutLastSpans = $this->withLoweredChoiceAndWithoutSpans($index, $deleted, $lastSpans);

            // the first spans are deleted when deleting the last ones does not
            // keep the counterexample failing, because the value that makes it
            // fail is one of the last ones, the last character of a string, for
            // instance
            if ($this->consider($withoutLastSpans) ||
                $this->consider($this->withLoweredChoiceAndWithoutSpans($index, $deleted, array_slice($spans, 0, $deleted)))) {
                return true;
            }

            // what the deleted spans contributed to the counterexample can be
            // needed to keep the counterexample failing, the length of a domain
            // name, for instance, and can be moved to a later group of spans
            // of the same kind by raising the choice that determines how
            // many spans it has
            $deletedChoices = array_sum(array_map(static fn (Span $span): int => $span->length(), $lastSpans));

            foreach ($this->choicesThatDetermineGroupsAfter(end($spans)->end(), $spans[0]->label(), $groups) as $later) {
                $raised = $later - $deletedChoices;

                // the deleted spans precede the raised choice
                assert($raised >= 0);

                if ($this->consider($this->withRaisedChoice($withoutLastSpans, $raised, $later, $deleted))) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * The indexes of the choices that are followed by the first span of a
     * group of spans with the label, that are at most LARGEST_RAISING_DISTANCE
     * choices after the index, and that can be raised: the choices that
     * determine how many spans these groups have, such as the length of the
     * top-level domain of a domain name.
     *
     * @param non-negative-int           $index
     * @param non-empty-string           $label
     * @param list<non-empty-list<Span>> $groups
     *
     * @return list<non-negative-int>
     */
    private function choicesThatDetermineGroupsAfter(int $index, string $label, array $groups): array
    {
        $choices = [];

        foreach ($groups as $group) {
            $choice = $group[0]->start() - 1;

            // a choice that is as large as it can be cannot be raised
            if ($group[0]->label() === $label && $choice >= $index && $choice < $index + self::LARGEST_RAISING_DISTANCE && $this->room($choice) > 0) {
                $choices[] = $choice;
            }
        }

        sort($choices);

        return $choices;
    }

    /**
     * The spans of the current sequence grouped by the span they are part of
     * and by their label, each group in order.
     *
     * @return list<non-empty-list<Span>>
     */
    private function groupsOfSpans(): array
    {
        $groups = [];

        foreach ($this->current->spans() as $span) {
            $groups[$span->parent() . ':' . $span->label()][] = $span;
        }

        return array_values($groups);
    }

    /**
     * The groups of spans, with only the spans that start after the choice at
     * the index, the groups whose first such span is nearest to the choice
     * first. A group of which a span contains the choice is left out, because
     * the choice is part of one of the values it groups rather than a choice
     * that determines how many values it has. So is a group with fewer than
     * two spans after the choice: such a span is mostly that of a value that
     * is composed of others, a tuple or a list as a whole, rather than one of
     * several values that are drawn the same way.
     *
     * @param non-negative-int           $index
     * @param list<non-empty-list<Span>> $groups
     *
     * @return list<non-empty-list<Span>>
     */
    private function groupsOfSpansAfter(int $index, array $groups): array
    {
        $groupsAfter = [];

        foreach ($groups as $group) {
            $after = [];

            foreach ($group as $span) {
                if ($span->start() <= $index && $index < $span->end()) {
                    continue 2;
                }

                if ($span->start() > $index) {
                    $after[] = $span;
                }
            }

            if (count($after) > 1) {
                $groupsAfter[] = $after;
            }
        }

        usort(
            $groupsAfter,
            static fn (array $a, array $b): int => $a[0]->start() <=> $b[0]->start(),
        );

        return array_slice($groupsAfter, 0, self::LARGEST_NUMBER_OF_GROUPS_AFTER_A_CHOICE);
    }

    /**
     * A counterexample for a property such as "a list has no duplicates"
     * cannot be shrunk from [-9, -9] to [0, 0] by minimizing one choice at a
     * time, because lowering a choice of one of two equal values makes them
     * unequal. Equal values are generated from spans of equal choices, and
     * the choices at the same position in these spans are lowered together,
     * which keeps the values equal while the choice sequence becomes simpler.
     */
    private function lowerEqualChoices(): void
    {
        $choices        = $this->current->choices();
        $spansByChoices = [];

        foreach ($this->spansOfCurrentSequence() as $span) {
            $spansByChoices[implode(',', array_slice($choices, $span->start(), $span->length()))][] = $span;
        }

        foreach ($spansByChoices as $spans) {
            if (count($spans) < 2) {
                continue;
            }

            for ($offset = 0; $offset < $spans[0]->length() && !$this->isStopped(); $offset++) {
                $indexes = [];

                foreach ($spans as $span) {
                    $indexes[] = $span->start() + $offset;
                }

                // spans with the same choices that start with a choice that
                // added an element are elements of collections
                if ($this->addedElement($indexes[0])) {
                    continue;
                }

                $this->lowerTogether($indexes);
            }
        }
    }

    /**
     * A counterexample for a property such as "a list does not contain both
     * [5, 6] and [1, 2]" can have its elements in any order, and the values
     * of parameters that are generated the same way can be swapped, too. The
     * spans of these values have the same label and are part of the same
     * span, or of none. They are sorted, so that the counterexample becomes
     * simpler, first all of them and then adjacent ones that are out of order.
     */
    private function sortSiblingSpans(): void
    {
        while (!$this->isStopped()) {
            $sorted = false;

            foreach ($this->siblingSpansWithTheSameLabel() as $siblings) {
                if ($this->sortSiblings($siblings)) {
                    $sorted = true;

                    break;
                }
            }

            if (!$sorted) {
                return;
            }
        }
    }

    /**
     * @return list<non-empty-list<Span>> the spans of the current sequence that have the same label and are part of the same span, in order, where there are at least two of them
     */
    private function siblingSpansWithTheSameLabel(): array
    {
        $groups = [];

        foreach ($this->current->spans() as $span) {
            // the span that ends a collection stays after its elements
            if ($this->endsCollection($span)) {
                continue;
            }

            $groups[$span->parent() . ':' . $span->label()][] = $span;
        }

        $siblings = [];

        foreach ($groups as $group) {
            if (count($group) > 1) {
                $siblings[] = $group;
            }
        }

        return $siblings;
    }

    /**
     * Considers the siblings in sorted order and, if that does not keep the
     * counterexample failing, with adjacent siblings that are out of order
     * swapped.
     *
     * @param non-empty-list<Span> $siblings
     *
     * @return bool whether the siblings were reordered
     */
    private function sortSiblings(array $siblings): bool
    {
        $choices = $this->current->choices();
        $slices  = [];

        foreach ($siblings as $sibling) {
            $slices[] = array_slice($choices, $sibling->start(), $sibling->length());
        }

        $sorted = $slices;

        usort($sorted, self::compareSlices(...));

        if ($sorted !== $slices && $this->consider($this->withSiblingsReplacedBy($siblings, $sorted))) {
            return true;
        }

        $previous = null;

        foreach ($slices as $position => $slice) {
            if ($previous !== null && self::compareSlices($previous, $slice) > 0) {
                $swapped = [...array_slice($slices, 0, $position - 1), $slice, $previous, ...array_slice($slices, $position + 1)];

                if ($this->consider($this->withSiblingsReplacedBy($siblings, $swapped))) {
                    return true;
                }
            }

            $previous = $slice;
        }

        return false;
    }

    /**
     * Lowers the choices at the indexes, which have to be equal, to the
     * smallest value that keeps the counterexample failing: a small value if
     * possible, otherwise the value that a binary search finds.
     *
     * @param non-empty-list<non-negative-int> $indexes
     */
    private function lowerTogether(array $indexes): void
    {
        // an earlier step may have changed the choices at the indexes
        $choice = $this->equalChoiceAt($indexes);

        if ($choice === null || $choice === 0) {
            return;
        }

        for ($value = 0; $value <= self::LARGEST_SMALL_VALUE && $value < $choice; $value++) {
            if ($this->consider($this->withChoices($indexes, $value))) {
                return;
            }
        }

        // the largest small value that was tried is a lower bound for which
        // the candidate does not fail, and the current choice is an upper
        // bound for which it does
        $lower = self::LARGEST_SMALL_VALUE;
        $upper = $choice;

        while ($upper - $lower > 1 && !$this->isStopped()) {
            $middle = $lower + intdiv($upper - $lower, 2);

            assert($middle >= 0);

            $candidate = $this->withChoices($indexes, $middle);

            if (!$this->consider($candidate)) {
                $lower = $middle;

                continue;
            }

            // the bounds refer to the choices at the indexes, which the
            // consumed choice sequence may not have
            if ($this->current->choices() !== $candidate) {
                return;
            }

            $upper = $middle;
        }
    }

    /**
     * Returns the value of the choices at the indexes when the current choice
     * sequence has all of them and they are equal, and null otherwise.
     *
     * @param non-empty-list<non-negative-int> $indexes
     *
     * @return ?non-negative-int
     */
    private function equalChoiceAt(array $indexes): ?int
    {
        $choices = $this->current->choices();
        $choice  = null;

        foreach ($indexes as $index) {
            if (!isset($choices[$index]) || ($choice !== null && $choices[$index] !== $choice)) {
                return null;
            }

            $choice = $choices[$index];
        }

        return $choice;
    }

    /**
     * @param non-empty-list<non-negative-int> $indexes
     * @param non-negative-int                 $value
     *
     * @return list<non-negative-int>
     */
    private function withChoices(array $indexes, int $value): array
    {
        $choices = $this->current->choices();

        foreach ($indexes as $index) {
            array_splice($choices, $index, 1, [$value]);
        }

        return $choices;
    }

    /**
     * Replays the candidate, unless it has been tried before, the maximum
     * number of attempts has been reached, or shrinking is interrupted, and
     * keeps the choice sequence that the replay consumed when the candidate
     * fails in the same way and that sequence is simpler
     * than the current one.
     *
     * @param list<non-negative-int> $choices
     */
    private function consider(array $choices): bool
    {
        $key = self::keyOf($choices);

        if (isset($this->tried[$key])) {
            return false;
        }

        if ($this->numberOfAttempts >= $this->maximumNumberOfAttempts) {
            $this->budgetExhausted = true;

            return false;
        }

        if ($this->shouldStop !== null && ($this->shouldStop)()) {
            $this->interrupted = true;

            return false;
        }

        $this->tried[$key] = true;

        $this->numberOfAttempts++;

        $consumed = ($this->test)(new ChoiceSequence($choices));

        if ($consumed === null || !$consumed->isSimplerThan($this->current)) {
            return false;
        }

        $this->current = $consumed;

        $this->numberOfSteps++;

        return true;
    }

    /**
     * @param list<non-negative-int> $choices
     */
    private function hasBeenTried(array $choices): bool
    {
        return isset($this->tried[self::keyOf($choices)]);
    }

    /**
     * Whether no more candidates are replayed, because the maximum number of
     * attempts has been made or shrinking was interrupted.
     */
    private function isStopped(): bool
    {
        return $this->budgetExhausted || $this->interrupted;
    }

    /**
     * @return list<Span> the distinct spans of the current sequence, longest first
     */
    private function spansOfCurrentSequence(): array
    {
        $spans = [];

        foreach ($this->current->spans() as $span) {
            $spans[$span->start() . ':' . $span->end()] = $span;
        }

        $spans = array_values($spans);

        usort(
            $spans,
            static function (Span $a, Span $b): int
            {
                if ($a->length() !== $b->length()) {
                    return $b->length() <=> $a->length();
                }

                return $a->start() <=> $b->start();
            },
        );

        return $spans;
    }

    /**
     * @param non-negative-int $index
     *
     * @return non-negative-int
     */
    private function choiceAt(int $index): int
    {
        $choices = $this->current->choices();

        assert(isset($choices[$index]));

        return $choices[$index];
    }

    /**
     * @return non-negative-int
     */
    private function length(): int
    {
        return count($this->current->choices());
    }

    /**
     * @param non-negative-int $start
     * @param positive-int     $size
     */
    private function isZero(int $start, int $size): bool
    {
        foreach (array_slice($this->current->choices(), $start, $size, true) as $index => $choice) {
            if ($choice !== 0 && !$this->addedElement($index)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param non-negative-int $start
     * @param positive-int     $size
     *
     * @return list<non-negative-int>
     */
    private function withoutChoices(int $start, int $size): array
    {
        $choices = $this->current->choices();

        array_splice($choices, $start, $size);

        return $choices;
    }

    /**
     * @param non-empty-list<Span>                   $siblings spans that do not overlap, in order
     * @param non-empty-list<list<non-negative-int>> $slices   the choices that replace those of the siblings, in the same order
     *
     * @return list<non-negative-int>
     */
    private function withSiblingsReplacedBy(array $siblings, array $slices): array
    {
        $choices   = $this->current->choices();
        $reordered = [];
        $end       = 0;

        // the choices before, between, and after the siblings stay where they
        // are
        $replacements = array_map(
            static fn (Span $sibling, array $slice): array => ['sibling' => $sibling, 'slice' => $slice],
            $siblings,
            $slices,
        );

        foreach ($replacements as $replacement) {
            $reordered = [...$reordered, ...array_slice($choices, $end, $replacement['sibling']->start() - $end), ...$replacement['slice']];
            $end       = $replacement['sibling']->end();
        }

        return [...$reordered, ...array_slice($choices, $end)];
    }

    /**
     * Lowers the choice at the index by the amount and deletes the spans.
     *
     * @param non-negative-int $index
     * @param positive-int     $amount
     * @param list<Span>       $spans  spans that follow the choice and do not overlap, in order
     *
     * @return list<non-negative-int>
     */
    private function withLoweredChoiceAndWithoutSpans(int $index, int $amount, array $spans): array
    {
        $lowered = $this->choiceAt($index) - $amount;

        assert($lowered >= 0);

        $choices = $this->current->choices();

        // the spans are deleted from the last one, so that the positions of
        // the others do not change
        foreach (array_reverse($spans) as $span) {
            array_splice($choices, $span->start(), $span->length());
        }

        array_splice($choices, $index, 1, [$lowered]);

        return $choices;
    }

    /**
     * @param non-negative-int $index
     * @param non-negative-int $later
     *
     * @return list<non-negative-int>
     */
    private function withLoweredAndRaisedChoice(int $index, int $later): array
    {
        $lowered = $this->choiceAt($index) - 1;

        assert($lowered >= 0);

        $choices = $this->current->choices();

        array_splice($choices, $index, 1, [$lowered]);
        array_splice($choices, $later, 1, [PHP_INT_MAX]);

        return $choices;
    }

    /**
     * @return list<non-negative-int>
     */
    private function withChoicesOfSpanInsteadOf(Span $replacement, Span $span): array
    {
        $choices = $this->current->choices();

        array_splice($choices, $span->start(), $span->length(), array_slice($choices, $replacement->start(), $replacement->length()));

        return $choices;
    }

    /**
     * @param non-negative-int $start
     * @param positive-int     $size
     *
     * @return list<non-negative-int>
     */
    private function withZeroedChoices(int $start, int $size): array
    {
        $choices = $this->current->choices();
        $zeroed  = [];

        for ($index = $start; $index < $start + $size; $index++) {
            if ($this->addedElement($index)) {
                $zeroed[] = 1;
            } else {
                $zeroed[] = 0;
            }
        }

        array_splice($choices, $start, $size, $zeroed);

        return $choices;
    }

    /**
     * @param non-negative-int  $first
     * @param non-negative-int  $second
     * @param positive-int      $amount
     * @param ?non-negative-int $firstChoice  the choice at $first to move the amount from, the current one when null
     * @param ?non-negative-int $secondChoice the choice at $second to move the amount to, the current one when null
     *
     * @return list<non-negative-int>
     */
    private function withMovedAmount(int $first, int $second, int $amount, ?int $firstChoice = null, ?int $secondChoice = null): array
    {
        if ($firstChoice === null) {
            $firstChoice = $this->choiceAt($first);
        }

        if ($secondChoice === null) {
            $secondChoice = $this->choiceAt($second);
        }

        $firstValue  = $firstChoice - $amount;
        $secondValue = $secondChoice + $amount;

        assert($firstValue >= 0 && $secondValue >= 0);

        $choices = $this->current->choices();

        array_splice($choices, $first, 1, [$firstValue]);
        array_splice($choices, $second, 1, [$secondValue]);

        return $choices;
    }

    /**
     * The choices with the choice at the index replaced by the choice at
     * $later of the current sequence, raised by the amount as far as it can
     * be raised.
     *
     * @param list<non-negative-int> $choices
     * @param non-negative-int       $index
     * @param non-negative-int       $later
     * @param positive-int           $amount
     *
     * @return list<non-negative-int>
     */
    private function withRaisedChoice(array $choices, int $index, int $later, int $amount): array
    {
        array_splice($choices, $index, 1, [$this->choiceAt($later) + min($amount, $this->room($later))]);

        return $choices;
    }

    /**
     * @param non-negative-int $index
     * @param non-negative-int $value
     *
     * @return list<non-negative-int>
     */
    private function withChoice(int $index, int $value): array
    {
        $choices = $this->current->choices();

        array_splice($choices, $index, 1, [$value]);

        return $choices;
    }

    /**
     * Whether deleting the choices from $start up to, but not including,
     * $end cuts an element that a choice added to a collection: what is
     * left of the element, or of the choices after it, is not read as an
     * element then.
     *
     * The choices that are deleted may be whole elements, with the choices
     * that added them, or some of the choices of one element after the
     * choice that added it. They cut an element when they are a part of it
     * together with choices that are not, and when they are every choice of
     * the element but the one that added it.
     *
     * @param non-negative-int $start
     * @param positive-int     $end   larger than $start
     */
    private function cutsElement(int $start, int $end): bool
    {
        $this->findElements();

        // the elements that the last choice is a part of and that start
        // with one of the choices
        $element = $this->elementOf($end - 1);

        while ($element !== null && $element->start() >= $start) {
            if ($element->end() > $end) {
                return true;
            }

            $element = $this->elementAround($element);
        }

        // the innermost element that all of the choices are a part of,
        // after the choice that added it
        $elementOfChoices = $element;

        if ($elementOfChoices !== null && $elementOfChoices->start() + 1 === $start && $elementOfChoices->end() === $end) {
            return true;
        }

        // the elements that the first choice is a part of and that do not
        // start with it
        $element = $this->elementOf($start);

        while ($element !== null && $element !== $elementOfChoices) {
            if ($element->start() < $start) {
                return true;
            }

            $element = $this->elementAround($element);
        }

        return false;
    }

    /**
     * The innermost element that a choice added and that the choice is a
     * part of.
     */
    private function elementOf(int $index): ?Span
    {
        if (!isset($this->elementsOfChoices[$index])) {
            return null;
        }

        return $this->elementsOfChoices[$index];
    }

    /**
     * The innermost element that a choice added and that the element is a
     * part of.
     */
    private function elementAround(Span $element): ?Span
    {
        if (!isset($this->elementsAroundElements[$element->start()])) {
            return null;
        }

        return $this->elementsAroundElements[$element->start()];
    }

    /**
     * Whether the choice added an element to a collection. Lowering such a
     * choice ends the collection, which deleting the last elements of the
     * collection does as well.
     */
    private function addedElement(int $index): bool
    {
        $this->findElements();

        return isset($this->choicesThatAddedElements[$index]);
    }

    /**
     * Whether the span consists of the choice that ends a collection (see
     * SpanKind::Element).
     */
    private function endsCollection(Span $span): bool
    {
        return $span->kind() === SpanKind::Element && $span->length() > 0 && $this->choiceAt($span->start()) === 0;
    }

    /**
     * @param list<non-negative-int>                                       $choices
     * @param array{elements: non-empty-list<Span>, end: non-negative-int} $collection
     * @param non-negative-int                                             $element    the index of the first element that is deleted
     *
     * @return list<non-negative-int>
     */
    private static function withoutElementsFrom(array $choices, array $collection, int $element): array
    {
        assert(isset($collection['elements'][$element]));

        $start = $collection['elements'][$element]->start();

        array_splice($choices, $start, $collection['end'] - $start, [0]);

        return $choices;
    }

    /**
     * The elements of a collection are part of the same span and have the
     * same label.
     */
    private static function collectionOf(Span $span): string
    {
        $parent = $span->parent();

        if ($parent === null) {
            $parent = -1;
        }

        return $parent . ':' . $span->label();
    }

    /**
     * @param list<non-negative-int> $choices
     *
     * @return non-empty-string
     */
    private static function keyOf(array $choices): string
    {
        return hash('xxh128', implode(',', $choices));
    }

    /**
     * Compares the choices of two spans so that sorting spans by it makes the
     * choices they form together as simple as possible: a span comes first
     * when its choices followed by those of the other span are simpler than
     * the other way round.
     *
     * @param list<non-negative-int> $a
     * @param list<non-negative-int> $b
     */
    private static function compareSlices(array $a, array $b): int
    {
        return [...$a, ...$b] <=> [...$b, ...$a];
    }
}
