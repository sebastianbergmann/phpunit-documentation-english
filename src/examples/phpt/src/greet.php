<?php declare(strict_types=1);
if (isset($argv[1]) && $argv[1] === '--version') {
    printf('greet 1.0.0 (PHP %s)' . PHP_EOL, PHP_VERSION);

    exit(0);
}

$name = $argv[1] ?? trim((string) fgets(STDIN));

if ($name === '') {
    fwrite(STDERR, 'Error: no name given' . PHP_EOL);

    exit(1);
}

printf('Hello, %s!' . PHP_EOL, $name);
