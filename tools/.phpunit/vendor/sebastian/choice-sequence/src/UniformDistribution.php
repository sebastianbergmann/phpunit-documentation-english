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

use Random\Randomizer;

/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final readonly class UniformDistribution implements Distribution
{
    public function sample(Randomizer $randomizer, int $maximum): int
    {
        return $randomizer->getInt(0, $maximum);
    }
}
