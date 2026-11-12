<?php declare(strict_types=1);
/*
 * This file is part of sebastian/version-requirement.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\VersionRequirement;

/**
 * @immutable
 *
 * @no-named-arguments
 */
final readonly class InvalidVersionRequirement extends Requirement
{
    /**
     * @var non-empty-string
     */
    private string $message;

    /**
     * @param non-empty-string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function isSatisfiedBy(string $version): bool
    {
        return false;
    }

    /**
     * Unlike the other implementations of Requirement, this method does not return
     * a string representation of a version requirement: it returns the message that
     * explains why the version requirement is invalid.
     *
     * @return non-empty-string
     */
    public function asString(): string
    {
        return $this->message;
    }

    /**
     * @return non-empty-string
     */
    public function message(): string
    {
        return $this->message;
    }
}
