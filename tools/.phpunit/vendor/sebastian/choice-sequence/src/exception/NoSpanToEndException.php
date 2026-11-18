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

use LogicException;

/**
 * Thrown when a span is to be ended while no span that was started has not
 * been ended yet.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final class NoSpanToEndException extends LogicException implements Exception
{
    public function __construct()
    {
        parent::__construct('There is no span that was started and has not been ended');
    }
}
