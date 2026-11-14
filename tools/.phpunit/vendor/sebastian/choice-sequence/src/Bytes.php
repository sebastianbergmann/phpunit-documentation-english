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
use function chr;
use function var_export;

/**
 * Draws the bytes of a binary string, as SpanKind::Bytes describes them: the
 * bytes that the minimum length requires as their value, each in a span of
 * the kind SpanKind::RequiredElement, and every other byte as the choice 1
 * followed by its value, in a span of the kind SpanKind::Element, until the
 * choice 0 ends the string or it has its maximum length (see Repetition).
 *
 * The code that draws a binary string marks the span of the string, with
 * label() and the kind SpanKind::Bytes, around the bytes that draw() draws,
 * as it marks the spans of the other values that it draws.
 *
 * Printable ASCII characters are drawn most often, and the bytes 0x00 and
 * 0xFF considerably more often than other bytes.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final readonly class Bytes
{
    /**
     * @var non-empty-string
     */
    private string $label;
    private Repetition $repetition;
    private WeightedRangesDistribution $byte;

    /**
     * @param non-negative-int  $minimumLength
     * @param ?non-negative-int $maximumLength
     */
    public function __construct(int $minimumLength, ?int $maximumLength)
    {
        $this->label      = Label::of('bytes', (string) $minimumLength, var_export($maximumLength, true));
        $this->repetition = new Repetition(Label::of('element', $this->label), $minimumLength, $maximumLength);

        $this->byte = new WeightedRangesDistribution(
            [
                ['weight' => 0.5, 'minimum' => 0x20, 'maximum' => 0x7E],
                ['weight' => 0.1, 'minimum' => 0x00, 'maximum' => 0x00],
                ['weight' => 0.1, 'minimum' => 0xFF, 'maximum' => 0xFF],
                ['weight' => 0.3, 'minimum' => 0x00, 'maximum' => 0xFF],
            ],
        );
    }

    /**
     * The label of the span of a binary string that is drawn this way.
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
        $bytes = '';

        $this->repetition->repeat(
            $source,
            function () use ($source, &$bytes): bool
            {
                $byte = $source->draw(0xFF, $this->byte);

                assert($byte <= 0xFF);

                $bytes .= chr($byte);

                return true;
            },
        );

        return $bytes;
    }
}
