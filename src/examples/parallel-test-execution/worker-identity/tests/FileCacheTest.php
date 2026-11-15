<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class FileCacheTest extends TestCase
{
    private static string $directory;

    public static function setUpBeforeClass(): void
    {
        self::$directory = sys_get_temp_dir() . '/file-cache-test-' . self::suffix();

        if (!is_dir(self::$directory)) {
            mkdir(self::$directory);
        }
    }

    public static function tearDownAfterClass(): void
    {
        array_map(unlink(...), glob(self::$directory . '/*'));

        rmdir(self::$directory);
    }

    public function testValueCanBeStoredAndRetrieved(): void
    {
        file_put_contents(self::$directory . '/key', 'value');

        $this->assertStringEqualsFile(self::$directory . '/key', 'value');
    }

    private static function suffix(): string
    {
        $workerId = getenv('PHPUNIT_WORKER_ID');

        if ($workerId === false) {
            // Not run by a worker process
            return 'main';
        }

        return 'worker-' . $workerId;
    }
}
