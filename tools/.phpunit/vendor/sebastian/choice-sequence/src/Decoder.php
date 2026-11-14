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
use function array_pop;
use function assert;
use function count;
use function explode;
use function preg_match;
use function str_ends_with;
use function strcmp;
use function strlen;
use function substr;

/**
 * Decodes choices that were encoded by Encoder, as well as choices whose
 * lines all end with a carriage return and a line feed, which Git can turn
 * line feeds into when a file is checked out on Windows.
 *
 * @immutable
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final readonly class Decoder
{
    private const string HEADER = 'choice-sequence v1';

    /**
     * @throws InvalidChoiceSequenceException
     */
    public function decode(string $encoded): ChoiceSequence
    {
        $lines = explode("\n", $encoded);

        // what follows the last line feed, which is empty when every line ends
        // with a line break
        $rest = array_pop($lines);

        if ($lines === []) {
            if ($rest === self::HEADER) {
                throw InvalidChoiceSequenceException::missingLineBreak(1);
            }

            throw InvalidChoiceSequenceException::invalidHeader(self::HEADER);
        }

        $crlf    = str_ends_with($lines[0], "\r");
        $choices = [];

        foreach ($lines as $index => $line) {
            $number = $index + 1;

            if (str_ends_with($line, "\r") !== $crlf) {
                throw InvalidChoiceSequenceException::inconsistentLineBreak($number);
            }

            if ($crlf) {
                $line = substr($line, 0, -1);
            }

            if ($index === 0) {
                if ($line !== self::HEADER) {
                    throw InvalidChoiceSequenceException::invalidHeader(self::HEADER);
                }

                continue;
            }

            if (!self::isChoice($line)) {
                throw InvalidChoiceSequenceException::invalidChoice($number, $line);
            }

            $choice = (int) $line;

            assert($choice >= 0);

            $choices[] = $choice;
        }

        if ($rest !== '') {
            throw InvalidChoiceSequenceException::missingLineBreak(count($lines) + 1);
        }

        return new ChoiceSequence($choices);
    }

    /**
     * Whether the line is a decimal integer between 0 and PHP_INT_MAX
     * without sign and without leading zeros. Integers larger than
     * PHP_INT_MAX are told apart by their digits, as casting them to int
     * would not preserve their value.
     */
    private static function isChoice(string $line): bool
    {
        if (preg_match('/\A(?:0|[1-9][0-9]*)\z/', $line) !== 1) {
            return false;
        }

        $maximum = (string) PHP_INT_MAX;

        return strlen($line) < strlen($maximum) ||
               (strlen($line) === strlen($maximum) && strcmp($line, $maximum) <= 0);
    }
}
