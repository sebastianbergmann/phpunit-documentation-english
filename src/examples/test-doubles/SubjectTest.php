<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class SubjectTest extends TestCase
{
    public function testObserversAreUpdated(): void
    {
        $observer = $this->createMock(Observer::class);

        $observer->expects($this->once())
                 ->method('update')
                 ->with($this->identicalTo('something'));

        $subject = new Subject;

        $subject->attach($observer);

        $subject->doSomething();
    }
}
