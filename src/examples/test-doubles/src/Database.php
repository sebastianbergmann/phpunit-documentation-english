<?php declare(strict_types=1);
interface Database
{
    /**
     * @throws DatabaseException
     */
    public function execute(string $sql, float|int|string ...$parameters): true;

    /**
     * @throws DatabaseException
     */
    public function query(string $sql, float|int|string ...$parameters): array;
}
