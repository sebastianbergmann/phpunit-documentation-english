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
 * Thrown when a reference to one of the values of a pool is to be drawn while
 * the pool has no value.
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for sebastian/choice-sequence
 */
final class NoValueToReferToException extends LogicException implements Exception
{
    public function __construct()
    {
        parent::__construct('The pool has no value that can be referred to');
    }
}
