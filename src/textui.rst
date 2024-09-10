

.. _textui:

****************************
The Command-Line Test Runner
****************************

The PHPUnit command-line test runner can be invoked through the
:file:`phpunit` command. The following code shows how to run
tests with the PHPUnit command-line test runner:

.. parsed-literal::

    ./tools/phpunit tests/ArrayTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.2

    ..                                                                  2 / 2 (100%)

    Time: 00:00.077, Memory: 10.00 MB

    OK (2 tests, 2 assertions)

When invoked as shown above, the PHPUnit command-line test runner will look for a
:file:`ArrayTest.php` source code file in the current working directory, load it,
and expect to find an ``ArrayTest`` test case class. It will then run the tests
found in that class.

Outcome and Issues
==================

PHPUnit separates the *outcome* (errored, failed, incomplete, skipped, or passed) of a test
from the *issues* (considered risky, triggered a warning, ...) of a test.

With regard to outcome, PHPUnit distinguishes between *failures* and *errors*. A test fails
when an assertion failed. This is different from an unexpected exception or a PHP error that
occur while a test is running. When this happens, the test errors.

Errors tend to be easier to fix than failures. If you have a big list of problems, it is
best to tackle the errors first and see if you have any failures left when they are all
fixed.

For each test run, the PHPUnit command-line tool prints one character to
indicate progress:

``.``

    Printed when a successful test has no issues

``F``

    Printed when an assertion fails while running the test method

``E``

    Printed when an error occurs while running the test method

``W``

    Printed when the test triggered a warning

``R``

    Printed when the test has been considered risky (see :ref:`risky-tests`)

``D``

    Printed when the test triggered a deprecation

``N``

    Printed when the test triggered a notice

``I``

    Printed when the test is marked as incomplete (see :ref:`writing-tests-for-phpunit.incomplete-tests`)

``S``

    Printed when the test was skipped (see :ref:`writing-tests-for-phpunit.skipping-tests`)

.. _textui.command-line-options:

Command-Line Options
====================

Configuration
-------------

``--bootstrap <file>``

    Configures a PHP script that is included before the tests run. For common use cases, this script
    should not do more than register an autoloader so that PHP can find the tested units of code.

``-c|--configuration <file>``

    Configure PHPUnit's test runner using an XML configuration file. This is not required when the
    configuration file that is to be used is located in the current working directory and is named
    :file:`phpunit.xml`, :file:`phpunit.dist.xml`, or :file:`phpunit.xml.dist`.

``--no-configuration``

    Do not use an XML configuration named :file:`phpunit.xml`, :file:`phpunit.dist.xml`, or
    :file:`phpunit.xml.dist` that is located in the current working directory.

``--no-extensions``

    Do not load PHPUnit test runner extensions from PHP archives (PHARs) from a directory that is
    configured in the XML configuration file. Do not bootstrap PHPUnit test runner extensions that
    are configured in the XML configuration file.

``--include-path <path(s)>``

    Prepend PHP's ``include_path`` with given path(s).

``-d <key[=value]>``

    Set a PHP configuration setting (php.ini).

``--cache-directory <dir>``

    Configure a directory where the PHPUnit test runner can cache data such as test results
    (required for reordering tests based on previous failures, for instance) or information
    about tested code as well as test code generated using static analysis (significantly
    improves performance of code coverage analysis, for instance).

``--generate-configuration``

    Generate an XML configuration file with best practice defaults.

``--migrate-configuration``

    Migrate an XML configuration file from a previous version's format to the current format.

.. _textui.command-line-options.selection:

Selection
---------

``--list-suites``

    List available test suites as defined in the XML configuration file. See
    :ref:`organizing-tests.xml-configuration` for an example.

``--testsuite <name>``

    Only run tests from the specified list of comma-separated test suites that are
    defined in the XML configuration file. See :ref:`organizing-tests.xml-configuration`
    for an example.

``--exclude-testsuite <name>``

    Run all tests except for those from the specified list of comma-separated test suites
    that are defined in the XML configuration file.

``--list-groups``

    List available test groups. Tests can be put into multiple test groups using the attributes
    ``PHPUnit\Framework\Attributes\Group``, ``PHPUnit\Framework\Attributes\Small``,
    ``PHPUnit\Framework\Attributes\Medium``, ``PHPUnit\Framework\Attributes\Large``, and
    ``PHPUnit\Framework\Attributes\Ticket``.

``--group <name>``

    Only run tests from the specified list of comma-separated test groups.

``--exclude-group <name>``

    Run all tests except for those from the specified list of comma-separated test groups.

``--covers <name>``

    Only run tests that intend to cover ``<name>`` and use code coverage metadata such as
    ``PHPUnit\Framework\Attributes\CoversClass`` to document this.

``--uses <name>``

    Only run tests that intend to use ``<name>`` and use code coverage metadata such as
    ``PHPUnit\Framework\Attributes\UsesClass`` to document this.

``--list-tests``

    Print a list of tests.

``--list-tests-xml <file>``

    Write a list of tests in XML format to a file.

``--filter <pattern>``

    Filter which tests to run using pattern matching on the test name.
    ``--filter <pattern>`` may be used multiple times and then combines the individual
    filters into a single filter using a *logical and*.

``--test-suffix <suffixes>``

    Only search for tests in files with specified suffix(es). Default: ``Test.php``, ``.phpt``.


Execution
---------

Isolation
^^^^^^^^^

``--process-isolation``

    Run each test in a separate PHP process.

``--globals-backup``

    Backup global and super-global variables before each test, restore them after each test.

``--static-backup``

    Backup static properties of classes before each test, restore them after each test.


Risky Tests
^^^^^^^^^^^

``--strict-coverage``

    Be strict about code coverage metadata. See :ref:`risky-tests.unintentionally-covered-code`
    for more details.

``--strict-global-state``

    Be strict about changes to global state. See :ref:`risky-tests.global-state-manipulation`
    for more details.

``--disallow-test-output``

    Be strict about output during tests. See :ref:`risky-tests.output-during-test-execution`
    for more details.

``--enforce-time-limit``

    Enforce time limit based on test size. See :ref:`risky-tests.test-execution-timeout`
    for more details.

``--default-time-limit <sec>``

    Timeout in seconds for tests that have no declared size. See :ref:`risky-tests.test-execution-timeout`
    for more details.

``--dont-report-useless-tests``

    Do not report tests that do not test anything. See :ref:`risky-tests.useless-tests`
    for more details on the default behaviour.


Automatically stop when ...
^^^^^^^^^^^^^^^^^^^^^^^^^^^

``--stop-on-defect``

    Stop execution upon first that errored, failed, that triggered a warning, or that
    was considered risky.

``--stop-on-error``

    Stop execution upon first that errored.

``--stop-on-failure``

    Stop execution upon first that failed.

``--stop-on-warning``

    Stop execution upon first that triggered a warning.

``--stop-on-risky``

    Stop execution upon first that was considered risky.

``--stop-on-deprecation``

    Stop execution upon first that triggered a deprecation
    (``E_DEPRECATED``, ``E_USER_DEPRECATED``, or PHPUnit deprecation).

``--stop-on-notice``

    Stop execution upon first that triggered a notice (``E_STRICT``,
    ``E_NOTICE``, or ``E_USER_NOTICE``).

``--stop-on-skipped``

    Stop execution upon first that was skipped.

``--stop-on-incomplete``

    Stop execution upon first that was marked as incomplete.


Exit with error code when ...
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

``--fail-on-warning``

    Exit with a shell exit code that signals failure even when all tests passed
    but at least one test triggered a warning.

``--fail-on-risky``

    Exit with a shell exit code that signals failure even when all tests passed
    but at least one test was considered risky.

``--fail-on-deprecation``

    Exit with a shell exit code that signals failure even when all tests passed
    but at least one test triggered a deprecation (``E_DEPRECATED`` or ``E_USER_DEPRECATED``).

``--fail-on-phpunit-deprecation``

    Exit with a shell exit code that signals failure even when all tests passed
    but at least one PHPUnit deprecation was triggered.

``--fail-on-notice``

    Exit with a shell exit code that signals failure even when all tests passed
    but at least one test triggered a notice (``E_STRICT``, ``E_NOTICE``, or
    ``E_USER_NOTICE``).

``--fail-on-incomplete``

    Exit with a shell exit code that signals failure even when all tests passed
    but at least one test was marked as incomplete.

``--fail-on-skipped``

    Exit with a shell exit code that signals failure even when all tests passed
    but at least one test was skipped.


Test Result Cache
^^^^^^^^^^^^^^^^^

``--cache-result``

    Write test results to cache file. This is required for reordering tests based on
    previous failures, for instance.

``--do-not-cache-result``

    Do not write test results to cache file.


Test Order
^^^^^^^^^^

``--order-by <order>``

    Reorder tests using ``<order>`` strategy before running them. ``<order>`` can be a
    comma-separated list of ``default``, ``defects``, ``depends``, ``duration``,
    ``no-depends``, ``random``, ``reverse``, and ``size``.

``--random-order-seed <N>``

    Use the specified random seed when running tests in random order.


Reporting
---------

Console
^^^^^^^

``--colors <flag>``

    Use colors in output (``never``, ``auto``, or ``always``)

``--columns <n>``

    Number of columns to use for progress output.

``--columns max``

    Use maximum number of columns for progress output.

``--stderr``

    Write to `php://stderr` instead of `php://stdout`.


Progress and Result Printing
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

``--no-progress``

    Disable output of test execution progress.

``--no-results``

    Disable output of test results.

``--no-output``

    Disable all output.

Details about Issues
^^^^^^^^^^^^^^^^^^^^

``--display-incomplete``

    Display details for incomplete tests.

``--display-skipped``

    Display details for skipped tests.

``--display-deprecations``

    Display details for deprecations triggered by tests.

``--display-phpunit-deprecations``

    Display details for PHPUnit deprecations.

``--display-errors``

    Display details for errors triggered by tests.

``--display-notices``

    Display details for notices triggered by tests.

``--display-warnings``

    Display details for warnings triggered by tests.

``--reverse-list``

    Print defects in reverse order.

Alternative Output
^^^^^^^^^^^^^^^^^^

``--teamcity``

    Replace default progress and result output with TeamCity format.

``--testdox``

    Replace default result output with TestDox format.

``--testdox-summary``

    Repeat TestDox output for non-successful tests after the regular TestDox output.
    This only has an effect when the ``--testdox`` option (see above) is also used.

Logging
-------

``--log-junit <file>``

    Write test results in JUnit XML format to file.

``--log-teamcity <file>``

    Write test results in TeamCity format to file.

``--testdox-html <file>``

    Write test results in TestDox format (HTML) to file.

``--testdox-text <file>``

    Write test results in TestDox format (plain text) to file.

``--log-events-text <file>``

    Stream events as plain text to file.

``--log-events-verbose-text <file>``

    Stream events as plain text (with telemetry information) to file.

``--no-logging``

    Ignore logging configured in the XML configuration file.


Code Coverage
^^^^^^^^^^^^^

``--coverage-clover <file>``

    Write code coverage report in Clover XML format to file.

``--coverage-cobertura <file>``

    Write code coverage report in Cobertura XML format to file.

``--coverage-crap4j <file>``

    Write code coverage report in Crap4J XML format to file.

``--coverage-html <dir>``

    Write code coverage report in HTML format to directory.

``--coverage-php <file>``

    Write serialized code coverage data to file.

``--coverage-text=<file>``

    Write code coverage report in text format to file (default: ``php://stdout``).

``--coverage-xml <dir>``

    Write code coverage report in XML format to directory.

``--warm-coverage-cache``

    Warm cache for static analysis that is needed for code coverage reporting.

``--coverage-filter <dir>``

    Include ``<dir>`` in code coverage reporting.

``--path-coverage``

    Report path coverage in addition to line coverage.

``--disable-coverage-ignore``

    Disable metadata for ignoring code coverage.

``--no-coverage``

    Ignore code coverage reporting configured in the XML configuration file.


Miscellaneous
^^^^^^^^^^^^^

``-h|--help``

    Prints usage information.

``--version``

    Prints the version and exits.

``--atleast-version <min>``

    Checks that version is greater than ``<min>`` and exits.

``--check-version``

    Check whether PHPUnit is the latest version and exits.
