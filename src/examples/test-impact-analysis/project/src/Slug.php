<?php declare(strict_types=1);
final class Slug
{
    public static function from(string $title): string
    {
        return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($title)), '-');
    }
}
