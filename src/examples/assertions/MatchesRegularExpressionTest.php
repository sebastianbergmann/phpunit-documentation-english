<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class MatchesRegularExpressionTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertMatchesRegularExpression('/foo/', 'bar');
    }
}
