#!/usr/bin/env php
<?php declare(strict_types=1);
const PROCESSED_JSON_FILE = __DIR__ . '/processed.json';

$exclude = [
    'assertions/DirectoryIsReadableTest.php',
    'assertions/DirectoryIsWritableTest.php',
    'assertions/FileIsReadableTest.php',
    'assertions/FileIsWritableTest.php',
    'assertions/IsReadableTest.php',
    'assertions/IsWritableTest.php',
    'error-handling/SourceClass.php',
    'error-handling/SourceClassTest.php',
    'error-handling/VendorClass.php',
    'extending-phpunit/OrderIdGeneratorExtendingAbstractTestCaseTest.php',
    'extending-phpunit/OrderIdGeneratorTest.php',
    'extending-phpunit/OrderIdGeneratorUsingAssertionTraitTest.php',
    'extending-phpunit/OrderIdGeneratorWithDomainSpecificAssertionTest.php',
    'writing-tests-for-phpunit/NumericDataSetsTestUsingExternalDataProvider.php',
];

$phpunit       = $command = __DIR__ . '/../tools/phpunit';
$version       = hash('sha256', trim(PHP_VERSION . shell_exec($phpunit . ' --version')));
$processed     = processed_read();
$rootDirectory = realpath(__DIR__ . '/../src/examples') . '/';

foreach (new GlobIterator(__DIR__ . '/../src/examples/**/*Test.php') as $test) {
    $currentFile = str_replace($rootDirectory, '', $test->getRealPath());

    if (in_array($currentFile, $exclude, true)) {
        print '[excluded ] ' . $currentFile . PHP_EOL;

        continue;
    }

    $currentFileHash = $version . hash_file('sha256', $test->getRealPath());

    if (isset($processed[$currentFile]) && $processed[$currentFile] === $currentFileHash) {
        print '[skipped  ] ' . $currentFile . PHP_EOL;

        continue;
    }

    $command       = $phpunit . ' ';
    $bootstrap     = dirname($test->getRealPath()) . '/src/autoload.php';
    $hiddenOptions = '--no-configuration --do-not-cache-result ';
    $options       = '';

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

    $processed[$currentFile] = $currentFileHash;

    print '[processed] ' . $currentFile . PHP_EOL;
}

ksort($processed);

file_put_contents(PROCESSED_JSON_FILE, json_encode($processed, JSON_PRETTY_PRINT));

function processed_read(): array
{
    if (!file_exists(PROCESSED_JSON_FILE)) {
        return [];
    }

    $json = file_get_contents(PROCESSED_JSON_FILE);

    if (!$json) {
        return [];
    }

    try {
        return json_decode($json, true, flags: JSON_THROW_ON_ERROR);
    } catch (JsonException) {
        return [];
    }
}
