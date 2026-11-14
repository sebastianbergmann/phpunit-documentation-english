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

use function hash;
use function implode;

/**
 * Computes the label of a span, which identifies how the choices of the span
 * are drawn: code that draws choices the same way labels its spans the same
 * way, which tells the shrinker which spans can be swapped or replaced with
 * each other.
 *
 * A label is computed from a name, for instance the name of a generator, and
 * its components, for instance the parameters of the generator and the
 * labels of the generators it is composed of. It is hashed, so that it is
 * short however many components it has. Tools that compute labels in the
 * same way label spans whose choices are drawn in the same way with the same
 * label.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final readonly class Label
{
    /**
     * @param non-empty-string $name
     *
     * @return non-empty-string
     */
    public static function of(string $name, string ...$components): string
    {
        return hash('xxh3', $name . '(' . implode(',', $components) . ')');
    }
}
