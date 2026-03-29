<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class GreeterTest extends TestCase
{
    public function testGreetsWithName(): void
    {
        $greeter = new Greeter;

        $this->assertSame('Hello, World!', $greeter->greet('World'));
    }

    public function testGreetsWithGoodMorningBeforeNoon(): void
    {
        $greeter = new Greeter;

        $this->assertSame(
            'Good morning, Alice!',
            $greeter->greetWithTimeOfDay('Alice', 9),
        );
    }

    public function testGreetsWithGoodAfternoonAfterNoon(): void
    {
        $greeter = new Greeter;

        $this->assertSame(
            'Good afternoon, Alice!',
            $greeter->greetWithTimeOfDay('Alice', 14),
        );
    }

    public function testGreetsWithGoodEveningAfter6Pm(): void
    {
        $greeter = new Greeter;

        $this->assertSame(
            'Good evening, Alice!',
            $greeter->greetWithTimeOfDay('Alice', 20),
        );
    }
}
