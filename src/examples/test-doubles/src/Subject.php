<?php declare(strict_types=1);
final class Subject
{
    private array $observers = [];

    public function attach(Observer $observer): void
    {
        $this->observers[] = $observer;
    }

    public function doSomething(): void
    {
        // ...

        $this->notify('something');
    }

    private function notify(string $argument): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($argument);
        }
    }

    // ...
}
