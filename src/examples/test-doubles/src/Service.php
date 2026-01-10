<?php declare(strict_types=1);
final readonly class Service
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @throws ServiceException
     */
    public function doSomething(): bool
    {
        try {
            $rows = $this->database->query(
                'SELECT foo FROM bar WHERE baz = ?;',
                'value',
            );
        } catch (DatabaseException) {
            throw new ServiceException;
        }

        if ($rows !== []) {
            // ...

            return true;
        }

        return false;
    }

    /**
     * @throws ServiceException
     */
    public function doSomethingElse(): void
    {
        // ...

        $this->database->execute(
            'INSERT INTO bar (foo, baz) VALUES (?, ?);',
            'value',
            'another value',
        );
    }
}
