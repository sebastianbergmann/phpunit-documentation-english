<?php declare(strict_types=1);
interface Observer
{
    public function update(string $argument): void;
}
