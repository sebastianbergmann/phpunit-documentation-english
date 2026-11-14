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
 * Encodes the choices of a choice sequence in version 1 of a text format
 * that is meant to be stored in files and compared with diff: the line
 * "choice-sequence v1" followed by one choice per line, each line ending with
 * a line feed.
 *
 * Spans and maxima are not encoded: they are recorded again when the choices
 * are replayed.
 *
 * @immutable
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final readonly class Encoder
{
    private const string HEADER = 'choice-sequence v1';

    public function encode(ChoiceSequence $sequence): string
    {
        $encoded = self::HEADER . "\n";

        foreach ($sequence->choices() as $choice) {
            $encoded .= $choice . "\n";
        }

        return $encoded;
    }
}
