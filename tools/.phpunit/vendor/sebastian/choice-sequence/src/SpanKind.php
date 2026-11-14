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

/**
 * What the choices of a span stand for, as far as tools that change choice
 * sequences without knowing the code that draws them need to know it. A
 * fuzzer, for instance, inserts a token into a string at the boundaries
 * between its elements, as bytes into a binary string or as characters into a
 * string of Unicode characters.
 *
 * The label of a span tells which spans are drawn the same way, but it is an
 * opaque hash. The kind of a span is optional and tells what it holds.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
enum SpanKind
{
    /**
     * A binary string that is drawn as a collection of bytes. The bytes that
     * the minimum length of the string requires come first: each is a span of
     * the kind RequiredElement that consists of the value of the byte, in the
     * range [0, 255]. Every other byte is a span of the kind Element that
     * consists of the choice 1 ("another byte"), whose maximum is 1, followed
     * by the value of the byte. Unless the string has its maximum length, it
     * ends with a span of the kind Element that consists of the choice 0,
     * whose maximum is 1.
     *
     * A byte can be inserted where a choice decides whether there is another
     * byte: before a byte that consists of the choice 1 and its value, and
     * before the span that ends the string.
     *
     * Bytes draws the bytes of a binary string this way.
     */
    case Bytes;

    /**
     * A string of Unicode characters that is drawn as a collection of
     * characters. The characters that the minimum length of the string
     * requires come first: each is a span of the kind RequiredElement that
     * consists of the index of the code point of the character in the order
     * of CodePoints, in the range [0, CodePoints::LARGEST_INDEX]. Every
     * other character is a span of the kind Element that consists of the
     * choice 1 ("another character"), whose maximum is 1, followed by the
     * index of its code point. Unless the string has its maximum length, it
     * ends with a span of the kind Element that consists of the choice 0,
     * whose maximum is 1.
     *
     * A character can be inserted where a choice decides whether there is
     * another character, as a byte can be inserted into a binary string.
     */
    case Characters;

    /**
     * An element of a collection that a choice added: the choice 1 ("another
     * element"), whose maximum is 1, followed by the choices of the element.
     * A span of this kind that consists of the choice 0 ends the collection.
     * The spans of the elements of a collection are part of the same span and
     * have the same label.
     *
     * Deleting a span of this kind that starts with the choice 1 deletes the
     * element, and replacing such a span and everything up to the end of the
     * last span of the collection with the choice 0 deletes the element and
     * the ones after it.
     *
     * Repetition draws the elements of a collection this way.
     */
    case Element;

    /**
     * An element of a collection that the minimum size of the collection
     * requires: the choices of the element. No choice decided that there is
     * such an element, so deleting its span does not delete it: the choices
     * after the span are read as the element instead. The elements that the
     * minimum size requires come first, and have the same label as the
     * elements of the collection that are spans of the kind Element.
     *
     * Deleting a span of this kind together with the choice 1 that added the
     * first element after the ones that the minimum size requires deletes
     * the element: the element that this choice added takes the place of the
     * last element that the minimum size requires.
     *
     * Repetition draws the elements of a collection this way.
     */
    case RequiredElement;

    /**
     * A value that later choices can refer to, the value that a step of a
     * state machine added to a pool of values, for instance. Spans of the
     * kinds Reference and ConsumingReference that have the same label as
     * this span and start after its last choice can refer to the value.
     *
     * Pool marks the values that are added to it this way, and draws the
     * references to them.
     */
    case Referable;

    /**
     * A reference to one of the values that spans of the kind Referable with
     * the same label as this span contributed before it: the single choice
     * of the span is the position of the value among those that have not
     * been consumed, counted from the one that was contributed last. A
     * choice that is greater than the last position refers to the value
     * that was contributed first.
     *
     * When choices before a reference are deleted, a value that it refers
     * to has another position when a value that was contributed after it
     * is deleted, or when a reference that consumed such a value is. The
     * shrinker changes the choice of the reference then, so that it keeps
     * referring to the same value.
     */
    case Reference;

    /**
     * A reference, as described for the kind Reference, that consumes the
     * value that it refers to: references that are drawn after it cannot
     * refer to the value.
     */
    case ConsumingReference;
}
