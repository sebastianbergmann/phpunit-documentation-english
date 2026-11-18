<?php declare(strict_types=1);
spl_autoload_register(
    static function (string $className): void
    {
        $file = __DIR__ . '/../src/' . $className . '.php';

        if (is_file($file)) {
            require $file;
        }
    },
);
