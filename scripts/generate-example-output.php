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
    'writing-tests-for-phpunit/DatabaseTest.php',
    'error-handling/SourceClass.php',
    'error-handling/SourceClassTest.php',
    'error-handling/VendorClass.php',
    'extending-phpunit/IsValidOrderIdTest.php',
    'extending-phpunit/MoneyComparatorTest.php',
    'extending-phpunit/MoneyExporterTest.php',
    'extending-phpunit/OrderIdGeneratorExtendingAbstractTestCaseTest.php',
    'extending-phpunit/OrderIdGeneratorTest.php',
    'extending-phpunit/OrderIdGeneratorUsingAssertionTraitTest.php',
    'extending-phpunit/OrderIdGeneratorUsingCustomAssertionTraitTest.php',
    'extending-phpunit/OrderIdGeneratorWithDomainSpecificAssertionTest.php',
];

$additionalOptions = [
    'property-based-testing/BinarySearchTest.php'                           => '--random-seed 1234 --display-property-statistics ',
    'property-based-testing/BinarySearchWithDrawnNeedleTest.php'            => '--random-seed 1234 --display-property-statistics ',
    'property-based-testing/BinarySearchWithRequiredClassificationTest.php' => '--random-seed 1234 ',
    'property-based-testing/CustomerTest.php'                               => '--random-seed 1234 ',
    'property-based-testing/CustomerUsingSharedGeneratorsTest.php'          => '--random-seed 1234 ',
    'property-based-testing/CustomerWithoutGeneratorTest.php'               => '--random-seed 1234 ',
    'property-based-testing/EmailAddressByAssumptionTest.php'               => '--random-seed 1234 ',
    'property-based-testing/MoneySplitTest.php'                             => '--random-seed 1234 ',
    'property-based-testing/MoneySplitWithExplicitExampleTest.php'          => '--random-seed 1234 ',
    'property-based-testing/ParkingGarageTest.php'                          => '--random-seed 1234 ',
    'property-based-testing/RunLengthEncodingRobustnessTest.php'            => '--random-seed 1234 ',
    'property-based-testing/RunLengthEncodingTest.php'                      => '--random-seed 1234 ',
    'property-based-testing/RunLengthEncodingWithFormatterTest.php'         => '--random-seed 1234 ',
    'property-based-testing/ShippingCostTest.php'                           => '--random-seed 1234 --display-property-statistics ',
    'property-based-testing/SortedListByAssumptionTest.php'                 => '--random-seed 1234 --display-property-statistics ',
    'property-based-testing/SortedListByConstructionTest.php'               => '--random-seed 1234 --display-property-statistics ',
    'property-based-testing/SorterTest.php'                                 => '--random-seed 1234 ',
    'property-based-testing/TruckTest.php'                                  => '--random-seed 1234 ',
    'property-based-testing/TruckWithTargetTest.php'                        => '--random-seed 1234 ',
];

// Examples that run all tests in the tests/ directory of an example directory
// instead of a single test file: output file => options
$directories = [
    'parallel-test-execution/crash/parallel.out'        => '--parallel 2 ',
    'parallel-test-execution/dependencies/parallel.out' => '--parallel 2 --display-phpunit-notices ',
    'parallel-test-execution/slow-tests/parallel.out'   => '--parallel 3 ',
    'parallel-test-execution/slow-tests/sequential.out' => '',
];

// Examples that run PHPUnit several times in a copy of an example project and
// change files of that copy between the runs: project directory => steps
//
// - ['run', output file, options] runs PHPUnit in the copy of the project and
//   writes its output to the output file next to the project directory
// - ['replace', file, search, replace] changes a file of the copy
// - ['write', file, contents] adds a file to the copy
$scenarios = [
    'test-impact-analysis/project' => [
        ['run', 'record.out', ''],
        ['run', 'nothing-changed.out', '--only-impacted '],
        ['replace', 'src/Vat.php', 'intdiv($netAmount * $this->rate, 100)', '(int) round($netAmount * $this->rate / 100)'],
        ['run', 'source-file-changed.out', '--only-impacted '],
        ['run', 'impacted-by.out', '--impacted-by src/Slug.php '],
        ['run', 'explain-impacted.out', '--explain-impacted --impacted-by src/Vat.php '],
        ['run', 'list-tests-that-depend-on.out', '--list-tests-that-depend-on src/Vat.php '],
        ['replace', 'tests/fixtures/vat.csv', '19,100,19', '19,200,38'],
        ['run', 'fixture-changed.out', '--explain-impacted '],
        ['write', 'src/Discount.php', "<?php declare(strict_types=1);\nfinal class Discount\n{\n}\n"],
        ['run', 'source-file-added.out', '--only-impacted '],
    ],
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
    $options         = $additionalOptions[$currentFile] ?? '';

    if ($options !== '') {
        $currentFileHash .= hash('sha256', $options);
    }

    if (isset($processed[$currentFile]) && $processed[$currentFile] === $currentFileHash) {
        print '[skipped  ] ' . $currentFile . PHP_EOL;

        continue;
    }

    $command       = $phpunit . ' ';
    $bootstrap     = dirname($test->getRealPath()) . '/src/autoload.php';
    $hiddenOptions = '--no-configuration --do-not-record-test-run-history ';

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

foreach ($directories as $outputFile => $options) {
    $directory = $rootDirectory . dirname($outputFile);
    $files     = array_merge(glob($directory . '/src/*.php'), glob($directory . '/tests/*.php'));

    sort($files);

    $currentHash = $version . hash('sha256', implode('', array_map(static fn (string $file): string => hash_file('sha256', $file), $files))) . hash('sha256', $options);

    if (isset($processed[$outputFile]) && $processed[$outputFile] === $currentHash) {
        print '[skipped  ] ' . $outputFile . PHP_EOL;

        continue;
    }

    $bootstrap     = $directory . '/src/autoload.php';
    $hiddenOptions = '--no-configuration --do-not-record-test-run-history ';

    if (file_exists($bootstrap)) {
        $hiddenOptions .= '--bootstrap ' . $bootstrap . ' ';
    }

    $output = shell_exec($phpunit . ' ' . $hiddenOptions . $options . $directory . '/tests');

    file_put_contents(
        $rootDirectory . $outputFile,
        './tools/phpunit ' . $options . 'tests' . PHP_EOL .
        str_replace($directory . '/tests', '/path/to/tests', $output)
    );

    $processed[$outputFile] = $currentHash;

    print '[processed] ' . $outputFile . PHP_EOL;
}

foreach ($scenarios as $project => $steps) {
    $directory = $rootDirectory . $project;
    $files     = files_in($directory);

    $currentHash = $version . hash('sha256', implode('', array_map(static fn (string $file): string => hash_file('sha256', $file), $files))) . hash('sha256', serialize($steps));

    if (isset($processed[$project]) && $processed[$project] === $currentHash) {
        print '[skipped  ] ' . $project . PHP_EOL;

        continue;
    }

    $copy = sys_get_temp_dir() . '/phpunit-documentation-' . bin2hex(random_bytes(8));

    copy_directory($directory, $copy);

    $copy = realpath($copy);

    foreach ($steps as $step) {
        switch ($step[0]) {
            case 'run':
                // XDEBUG_MODE only has an effect when Xdebug is the code coverage driver
                $output = shell_exec('cd ' . escapeshellarg($copy) . ' && XDEBUG_MODE=coverage ' . $phpunit . ' ' . $step[2]);

                file_put_contents(
                    dirname($directory) . '/' . $step[1],
                    rtrim('./tools/phpunit ' . $step[2]) . PHP_EOL .
                    rtrim(str_replace($copy, '/path/to/project', $output)) . PHP_EOL
                );

                break;

            case 'replace':
                file_put_contents(
                    $copy . '/' . $step[1],
                    str_replace($step[2], $step[3], file_get_contents($copy . '/' . $step[1]))
                );

                break;

            case 'write':
                file_put_contents($copy . '/' . $step[1], $step[2]);

                break;
        }
    }

    delete_directory($copy);

    $processed[$project] = $currentHash;

    print '[processed] ' . $project . PHP_EOL;
}

ksort($processed);

file_put_contents(PROCESSED_JSON_FILE, json_encode($processed, JSON_PRETTY_PRINT));

/**
 * @return list<string>
 */
function files_in(string $directory): array
{
    $files = [];

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)) as $file) {
        $files[] = $file->getPathname();
    }

    sort($files);

    return $files;
}

function copy_directory(string $from, string $to): void
{
    foreach (files_in($from) as $file) {
        $target = $to . substr($file, strlen($from));

        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0o777, true);
        }

        copy($file, $target);
    }
}

function delete_directory(string $directory): void
{
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isDir() && !$file->isLink()) {
            rmdir($file->getPathname());

            continue;
        }

        unlink($file->getPathname());
    }

    rmdir($directory);
}

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
