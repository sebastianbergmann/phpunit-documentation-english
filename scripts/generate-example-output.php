#!/usr/bin/env php
<?php declare(strict_types=1);
foreach (new GlobIterator(__DIR__ . '/../src/**/examples/*Test.php') as $test) {
    print $test->getRealPath() . PHP_EOL;

    $bootstrap     = dirname($test->getRealPath()) . '/src/autoload.php';
    $hiddenOptions = '--no-configuration --do-not-cache-result ';
    $options       = '';

    $command = __DIR__ . '/../tools/phpunit ';

    if (file_exists($bootstrap)) {
        $hiddenOptions .= '--bootstrap ' . $bootstrap . ' ';
    }

    $output = shell_exec($command . $hiddenOptions . $options . $test);

    if (str_contains($output, ', Incomplete:')) {
        $options .= '--display-incomplete ';

        $output = shell_exec($command . $hiddenOptions . $options . $test);
    }

    if (str_contains($output, ', Skipped:')) {
        $options .= '--display-skipped ';

        $output = shell_exec($command . $hiddenOptions . $options . $test);
    }

    file_put_contents(
        $test . '.out',
        './tools/phpunit ' . $options . 'tests/' . $test->getBasename() . PHP_EOL .
        str_replace(
            [
                dirname($test->getRealPath()),
            ],
            [
                '/path/to/tests',
            ],
            $output
        )
    );
}
