<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class BiscuitTest extends TestCase
{
    public function testEquals(): void
    {
        $theBiscuit = new Biscuit('Ginger');
        $myBiscuit  = new Biscuit('Ginger');

        $this->assertThat(
            $theBiscuit,
            $this->logicalNot(
                $this->equalTo($myBiscuit)
            )
        );
    }
}
