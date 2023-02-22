<?php declare(strict_types=1);
final class Biscuit
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
