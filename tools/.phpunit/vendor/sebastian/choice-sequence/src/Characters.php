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

use function assert;
use function mb_check_encoding;
use function mb_chr;
use function mb_ord;
use function mb_str_split;
use function var_export;

/**
 * Draws the characters of a string of Unicode characters, as
 * SpanKind::Characters describes them: the characters that the minimum
 * length requires as the index of their code point in the order of
 * CodePoints, each in a span of the kind SpanKind::RequiredElement, and
 * every other character as the choice 1 followed by that index, in a span of
 * the kind SpanKind::Element, until the choice 0 ends the string or it has
 * its maximum length (see Repetition). The string is encoded as UTF-8, and
 * its length is the number of its characters.
 *
 * The code that draws a string marks the span of the string, with label()
 * and the kind SpanKind::Characters, around the characters that draw()
 * draws, as it marks the spans of the other values that it draws.
 *
 * Printable ASCII characters are drawn most often, and the characters that
 * strings are most likely to be handled incorrectly for considerably more
 * often than other characters: the characters that are encoded with two,
 * three, and four bytes, combining characters, and emoji.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final readonly class Characters
{
    /**
     * @var non-empty-string
     */
    private string $label;
    private Repetition $repetition;
    private WeightedRangesDistribution $character;

    /**
     * @param non-negative-int  $minimumLength
     * @param ?non-negative-int $maximumLength not smaller than the minimum length, null when the string has no maximum length
     *
     * @throws InvalidArgumentException when the maximum length is smaller than the minimum length
     */
    public function __construct(int $minimumLength, ?int $maximumLength)
    {
        $this->label      = Label::of('characters', (string) $minimumLength, var_export($maximumLength, true));
        $this->repetition = new Repetition(Label::of('element', $this->label), $minimumLength, $maximumLength);

        $this->character = new WeightedRangesDistribution(
            [
                // printable ASCII characters
                ['weight' => 0.5, 'minimum' => 0, 'maximum' => 94],
                // ASCII control characters
                ['weight' => 0.03, 'minimum' => 95, 'maximum' => 127],
                // characters that are encoded with two bytes
                ['weight' => 0.12, 'minimum' => CodePoints::index(0x80), 'maximum' => CodePoints::index(0x7FF)],
                // combining diacritical marks
                ['weight' => 0.1, 'minimum' => CodePoints::index(0x300), 'maximum' => CodePoints::index(0x36F)],
                // characters that are encoded with three bytes
                ['weight' => 0.1, 'minimum' => CodePoints::index(0x800), 'maximum' => CodePoints::index(0xFFFF)],
                // emoji, which are encoded with four bytes
                ['weight' => 0.1, 'minimum' => CodePoints::index(0x1F300), 'maximum' => CodePoints::index(0x1FAFF)],
                // any character
                ['weight' => 0.05, 'minimum' => 0, 'maximum' => CodePoints::LARGEST_INDEX],
            ],
        );
    }

    /**
     * The label of the span of a string that is drawn this way.
     *
     * @return non-empty-string
     */
    public function label(): string
    {
        return $this->label;
    }

    /**
     * @throws TooManyChoicesException
     */
    public function draw(ChoiceSource $source): string
    {
        $characters = '';

        $this->repetition->repeat(
            $source,
            function () use ($source, &$characters): bool
            {
                $index = $source->draw(CodePoints::LARGEST_INDEX, $this->character);

                assert($index <= CodePoints::LARGEST_INDEX);

                $character = mb_chr(CodePoints::codePoint($index), 'UTF-8');

                // every code point that is not a surrogate is a character
                assert($character !== false);

                $characters .= $character;

                return true;
            },
        );

        return $characters;
    }

    /**
     * The choices from which draw() draws the string. A string that has more
     * characters than the maximum length is cut off, and the choices of a
     * string that has fewer characters than the minimum length are replayed
     * as the string followed by as many characters "a", the simplest
     * character, as it is short of (see Repetition::choicesFor()).
     *
     * @throws InvalidArgumentException when the string is not valid UTF-8
     *
     * @return list<non-negative-int>
     */
    public function choicesFor(string $characters): array
    {
        if (!mb_check_encoding($characters, 'UTF-8')) {
            throw InvalidArgumentException::stringNotValidUtf8();
        }

        $elements = [];

        foreach (mb_str_split($characters, 1, 'UTF-8') as $character) {
            $codePoint = mb_ord($character, 'UTF-8');

            // a string that is valid UTF-8 has no surrogates
            assert($codePoint !== false && $codePoint >= 0 && $codePoint <= 0x10FFFF);

            $elements[] = [CodePoints::index($codePoint)];
        }

        return $this->repetition->choicesFor($elements);
    }
}
