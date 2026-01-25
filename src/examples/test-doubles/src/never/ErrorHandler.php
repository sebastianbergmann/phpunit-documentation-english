<?php declare(strict_types=1);
interface ErrorHandler
{
    public function handle(Exception $e): never;
}
