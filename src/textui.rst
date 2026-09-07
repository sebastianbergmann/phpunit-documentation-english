

.. _textui:

****************************
The Command-Line Test Runner
****************************

The PHPUnit command-line test runner can be invoked through the :file:`phpunit` command.


.. _textui.running-tests:

Running tests
=============

The most common way to run tests is with a :file:`phpunit.xml` configuration file (see :ref:`organizing-tests.xml-configuration`). When a configuration file is present in the current working directory, simply running :file:`phpunit` without any arguments will execute all configured test suites:

.. parsed-literal::

    $ ./tools/phpunit
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.5.3
    Configuration: /path/to/project/phpunit.xml

    ..                                                                  2 / 2 (100%)

    Time: 00:00.077, Memory: 10.00 MB

    OK (2 tests, 2 assertions)

You can also run the tests of a specific test source file by passing its path as an argument:

.. parsed-literal::

    $ ./tools/phpunit tests/ExampleTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.5.3

    ..                                                                  2 / 2 (100%)

    Time: 00:00.077, Memory: 10.00 MB

    OK (2 tests, 2 assertions)

When invoked as shown above, the PHPUnit command-line test runner will look for a :file:`ExampleTest.php` source code file in the current working directory, load it, and expect to find an ``ExampleTest`` test case class. It will then run the tests found in that class.


.. _textui.running-tests.phpt:

PHPT tests
----------

In addition to test classes, PHPUnit's test runner can also execute `PHPT <https://php.github.io/php-src/miscellaneous/writing-tests.html>`_ tests.
PHPT is a simple test format used by the PHP project itself.
Files with the ``.phpt`` suffix are automatically discovered by the test runner (see the ``--test-suffix`` option in :ref:`appendixes.cli-options.selection`).

This can be useful, for instance, for end-to-end tests of CLI tools or for getting basic regression tests in place for legacy codebases.

PHPT tests are documented in :ref:`appendixes.phpt`.


.. _textui.selecting-tests:

Selecting tests
===============

PHPUnit provides several command-line options for selecting which tests to run.


.. _textui.selecting-tests.filter:

Filtering by test name
----------------------

The ``--filter`` option selects tests whose name matches a pattern:

.. parsed-literal::

    $ ./tools/phpunit --filter testDoesSomething
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.5.3
    Configuration: /path/to/project/phpunit.xml

    .                                                                   1 / 1 (100%)

    Time: 00:00.077, Memory: 10.00 MB

    OK (1 test, 1 assertion)

The pattern is matched against the full test name, which has one of these forms:

.. parsed-literal::

    Fully\\Qualified\\ClassName::testMethodName
    Fully\\Qualified\\ClassName::testMethodName with data set #0
    Fully\\Qualified\\ClassName::testMethodName with data set "named set"

Matching is a partial match (the pattern does not need to cover the entire name) and *not* anchored. For example, ``--filter testAdd`` also matches a test named ``testAddress``.

PHPT tests (see :ref:`textui.running-tests.phpt`) are never selected by ``--filter``.

The ``--exclude-filter`` option accepts the same pattern syntax described below and removes matching tests from the run. It can be combined with ``--filter``.


.. _textui.selecting-tests.filter.shortcuts:

Shortcut syntax
^^^^^^^^^^^^^^^

For common cases you do not need to write a regular expression. The following shortcut forms are recognized and translated into an appropriate pattern internally:

``testMethodName``
    Selects all tests whose name contains ``testMethodName``.

``ClassName::testMethodName``
    Selects a specific test method in a specific class.

``testMethodName#3``
    Selects the test method together with data set ``#3`` (a numeric data set key).

``testMethodName#1-3``
    Selects the test method together with numeric data sets in the inclusive range ``1`` to ``3``.

``#1-3``
    Selects numeric data sets in the inclusive range ``1`` to ``3`` across all test methods.

``testMethodName@one plus one``
    Selects the test method together with the named data set ``"one plus one"``. Quote the argument so the shell preserves spaces.

``testMethodName@one.*``
    Selects the test method together with any named data set that matches the regular expression ``one.*``.

``testMethodName#one plus one``
    Selects the test method together with the named data set ``"one plus one"``. This form is equivalent to ``testMethodName@one plus one`` and is accepted so that the test ID format produced by ``--list-test-ids`` can be passed directly to ``--filter``. Quote the argument so the shell preserves spaces.

When the shortcut form is used, matching is **case-insensitive**.


.. _textui.selecting-tests.filter.regex:

Regular expression syntax
^^^^^^^^^^^^^^^^^^^^^^^^^

If the pattern begins with a non-alphanumeric character and is a valid `PCRE regular expression <https://www.php.net/manual/en/book.pcre.php>`_, it is used verbatim (including its delimiters and modifiers). This form is useful when the shortcut syntax is not expressive enough, or when you need case-sensitive matching:

.. parsed-literal::

    $ ./tools/phpunit --filter '/^App\\\\Domain\\\\.*Test::test/'

.. admonition:: Caution

    Fully-qualified class names contain backslashes, and backslashes are special in regular expressions. If you want to use ``--filter`` with a fully-qualified class name, either escape every backslash (``App\\\\Domain\\\\FooTest``) or wrap the pattern with explicit delimiters (``/App\\\\Domain\\\\FooTest/``). The filter is *not* passed through ``preg_quote()``, so all regular expression metacharacters remain active.


.. _textui.selecting-tests.testsuite:

Filtering by test suite
-----------------------

When you have multiple test suites defined in your XML configuration file, you can use the ``--testsuite`` option to run only the tests of a specific suite:

.. parsed-literal::

    $ ./tools/phpunit --testsuite unit
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.5.3
    Configuration: /path/to/project/phpunit.xml

    ...............                                                    15 / 15 (100%)

    Time: 00:00.100, Memory: 10.00 MB

    OK (15 tests, 30 assertions)

The ``--exclude-testsuite`` option excludes a named test suite from the run.

The ``--list-suites`` option prints a list of all test suites defined in the XML configuration file without running any tests.


.. _textui.selecting-tests.group:

Filtering by group
------------------

The ``--group`` option runs only the tests that belong to a specified group. Groups are assigned to tests using the ``#[Group]`` attribute (see :ref:`appendixes.attributes.Group`).

The ``--exclude-group`` option excludes tests belonging to a specified group.


.. _textui.selecting-tests.covers:

Filtering by covered unit
--------------------------

The ``--covers`` option runs only tests that declare they intend to cover a specified class or function. The ``--uses`` option runs only tests that declare they intend to use a specified class or function.


.. _textui.selecting-tests.test-id:

Filtering by test ID
--------------------

A test ID is the canonical, fully qualified identifier of a single test. It has one of these forms:

.. parsed-literal::

    Fully\\Qualified\\ClassName::testMethodName
    Fully\\Qualified\\ClassName::testMethodName#0
    Fully\\Qualified\\ClassName::testMethodName#named data set

Test IDs are matched strictly and exactly; they are not patterns.

The ``--run-test-id`` option runs the single test identified by the given test ID:

.. parsed-literal::

    $ ./tools/phpunit --run-test-id 'PHPUnit\\TestFixture\\ExampleTest::testOne#named data set'

The ``--test-id-filter-file`` option runs the tests whose IDs are listed in the specified file, with one test ID per line. Together with ``--list-test-ids`` (see :ref:`textui.selecting-tests.listing`) this enables a list/filter/run workflow that is useful, for example, for splitting a test suite across parallel CI jobs:

1. List all test IDs: ``phpunit --list-test-ids > test-ids.txt``
2. Split or filter the file into batches
3. Run a batch: ``phpunit --test-id-filter-file batch-1.txt``


.. _textui.selecting-tests.listing:

Listing tests
-------------

The ``--list-tests`` option prints a list of all tests that would be executed without actually running them. This is useful for verifying that your selection options are working as expected.

The ``--list-test-ids`` option prints a list of all tests that would be executed as test IDs (one per line) without actually running them. The output of this option is suitable for use with ``--test-id-filter-file`` and ``--run-test-id`` (see :ref:`textui.selecting-tests.test-id`).

The ``--list-tests-xml`` option writes this list in XML format to a specified file.

See :ref:`appendixes.cli-options.selection` for the complete reference of all test selection options.


.. _textui.outcome-and-issues:

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


.. _textui.output:

Output
======

The default output of PHPUnit's test runner consists of up to four sections.


.. _textui.output.runtime:

Runtime information
-------------------

This section contains information about the PHPUnit version, PHP version, and PHPUnit's XML configuration file.

This section can be disabled using the ``--no-output`` CLI option.


.. _textui.output.progress:

Progress
--------

This section is printed to indicate progress while the tests are being run.

For each test run, the PHPUnit command-line tool prints one character to indicate progress:

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

This section can be disabled using the ``--no-progress`` and ``--no-output`` CLI options.


.. _textui.output.test-results:

Test results
------------

This section contains information about errors, failures, skipped tests, incomplete tests, and issues. By default, only information about errors and failures is printed.

This section is only printed when there are errors, failures, or issues to report. It can be disabled using the ``--no-results`` and ``--no-output`` CLI options.


.. _textui.output.failure-output:

Failure output
^^^^^^^^^^^^^^

Whenever a test fails, PHPUnit tries its best to provide you with as much
context as possible that can help to identify the problem.

.. literalinclude:: examples/textui/ArrayDiffTest.php
   :caption: Output generated when an array comparison fails
   :name: textui.output.failure-output.examples.ArrayDiffTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/textui/ArrayDiffTest.php.out

In this example only one of the array values differs and the other values
are shown to provide context on where the error occurred.

When the generated output would be long to read PHPUnit will split it up
and provide a few lines of context around every difference.

.. literalinclude:: examples/textui/LongArrayDiffTest.php
   :caption: Output when an array comparison of a long array fails
   :name: textui.output.failure-output.examples.LongArrayDiffTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/textui/LongArrayDiffTest.php.out

.. _textui.output.failure-output.edge-cases:

Edge cases
""""""""""

When a comparison fails PHPUnit creates textual representations of the
input values and compares those. Due to that implementation a diff
might show more problems than actually exist.

This only happens when using ``assertEquals()`` or other "weak" comparison
functions on arrays or objects.

.. literalinclude:: examples/textui/ArrayWeakComparisonTest.php
   :caption: Edge case in the diff generation when using weak comparison
   :name: textui.output.failure-output.edge-cases.examples.ArrayWeakComparisonTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/textui/ArrayWeakComparisonTest.php.out

In this example the difference in the first index between
``1`` and ``'1'`` is
reported even though ``assertEquals()`` considers the values as a match.


.. _textui.output.summary:

Summary
-------

This section contains a summary of the test suite execution.

- When no test errored or failed then the summary will be ``OK``
- When a test errored then the summary will be ``ERRORED!``
- When a test failed and no test errored then the summary will be ``FAILED!``
- When there were no errors or failures, but a test was skipped, then the summary will be ``OK, but some tests were skipped!``
- When there were no errors, failures, or skipped tests, but there were issues, then the summary will be ``OK, but there were issues!``

.. admonition:: ``--fail-on-*``

   Please note that if you use CLI options such as ``--fail-on-deprecation``, for example,
   or their XML configuration counterparts, then this only affects the test runner's shell
   exit code. It does not have an effect on whether or not ``OK`` will be printed in the
   output's summary section.

This section can be disabled using the ``--no-output`` CLI option.


.. _textui.output.alternative-formats:

Alternative output formats
--------------------------

PHPUnit supports alternative output formats that replace the default progress and result output.


.. _textui.output.alternative-formats.testdox:

TestDox
^^^^^^^

The ``--testdox`` option replaces the default result output with TestDox format.
See :ref:`testdox` for details.


.. _textui.output.alternative-formats.debug:

Debug
^^^^^

The ``--debug`` option replaces the default progress and result output with detailed event-by-event information about the test execution process. When combined with ``--with-telemetry``, timing and memory usage information is included.


.. _textui.output.alternative-formats.teamcity:

TeamCity
^^^^^^^^

The ``--teamcity`` option replaces the default progress and result output with TeamCity format. This is used for integration with PhpStorm or TeamCity.


.. _textui.output.alternative-formats.compact:

Compact
^^^^^^^

The ``--compact`` option replaces the default progress and result output with a compact format that is stripped down to what is actionable: there is no progress section, no timing or memory usage line, and no use of color. When all tests pass, only the runtime information section and a single summary line are printed:

.. parsed-literal::

    $ ./tools/phpunit --compact tests
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime: PHP 8.5.5

    OK (4699 tests, 13576 assertions)

When tests fail or have other issues, each defect is printed as a clearly delimited block with the test name, the assertion message, and the file location:

.. parsed-literal::

    $ ./tools/phpunit --compact tests
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime: PHP 8.5.5

    FAILURES (23 tests, 42 assertions, 1 failure)

    --- FAILURE: ExampleTest::testSomething
    Failed asserting that false is true.

    /path/to/tests/ExampleTest.php:47

The summary line begins with ``OK`` when all tests pass, ``FAILURES`` when at least one test failed, and ``ERRORS`` when at least one test errored. The counts that follow include the number of tests run and assertions made as well as, when non-zero, the number of errors, failures, deprecations, warnings, notices, skipped tests, incomplete tests, and risky tests.

Defects are listed in this order: errors, failures, deprecations, warnings, notices, errors triggered by tests, risky tests, incomplete tests, skipped tests.

By default, only errors, failures, and risky tests are shown in detail. Like the default output, ``--compact`` respects the ``--display-*`` flags described under :ref:`textui.output.controlling`: use ``--display-deprecations``, ``--display-warnings``, ``--display-notices``, ``--display-errors``, ``--display-incomplete``, ``--display-skipped``, or ``--display-all-issues`` to display additional details.

Compact output can also be activated by setting the ``PHPUNIT_COMPACT_OUTPUT`` environment variable to ``1``. This makes it easy to enable compact output globally without changing how PHPUnit is invoked, for example when running tests inside an AI-based coding assistant where every token of test output consumes context window budget.

.. admonition:: Note

   When both the ``PHPUNIT_COMPACT_OUTPUT`` environment variable and the ``--compact`` CLI option are set, compact output is used. The CLI option ``--compact`` cannot be combined with ``--teamcity``, ``--testdox``, ``--testdox-summary``, ``--debug``, or ``--no-output``.


.. _textui.output.controlling:

Controlling output
------------------

The following options control what is displayed in the test results section. By default, only information about errors and failures is printed. Use these options to display additional details:

- ``--display-incomplete`` shows details for incomplete tests
- ``--display-skipped`` shows details for skipped tests
- ``--display-deprecations`` shows details for deprecations triggered by tests
- ``--display-notices`` shows details for notices triggered by tests
- ``--display-warnings`` shows details for warnings triggered by tests
- ``--display-errors`` shows details for errors triggered by tests
- ``--display-all-issues`` enables all of the above

The ``--colors=<flag>`` option controls the use of colors in terminal output. Accepted values are ``never``, ``auto`` (uses colors when the terminal supports it), and ``always``.

The ``--columns`` option controls the width of the progress output. Use ``--columns max`` to use the full terminal width.

The ``--reverse-list`` option prints defects in reverse order, showing the most recently encountered defects first.

See :ref:`appendixes.cli-options.reporting` for the complete reference of all reporting options.


.. _textui.output.interaction:

Interaction between output options
----------------------------------

The options described above fall into two groups:

- ``--no-output``, ``--no-progress``, and ``--no-results`` suppress sections of the **default** output format described in :ref:`textui.output`.
- ``--testdox``, ``--teamcity``, ``--compact``, and ``--debug`` select an **alternative** output format that replaces the default progress and result output.

The ``--no-*`` options apply to the default output format only. They do not suppress the output produced by an alternative output format. For example, ``--testdox --no-output`` will still produce TestDox output.

The ``--testdox-html`` and ``--testdox-text`` options are **logging** options (see :ref:`textui.logging`): they write a TestDox-formatted report to a file. They are not intended to replace or augment the console output. Use ``--testdox`` when you want TestDox-formatted output on the console.


.. _textui.exit-codes:

Exit codes
==========

The PHPUnit command-line test runner exits with an exit code that indicates the outcome of the test run. This is particularly important for Continuous Integration (CI) pipelines and scripts that need to act on the result of a test run.

``0``

    All tests passed (no errors, no failures)

``1``

    At least one test failed

``2``

    At least one test errored

By default, issues such as deprecations, notices, warnings, and risky tests do not affect the exit code. The ``--fail-on-*`` family of options changes this behaviour:

- ``--fail-on-warning`` causes a failure exit code when any test triggers a warning
- ``--fail-on-risky`` causes a failure exit code when any test is considered risky
- ``--fail-on-deprecation`` causes a failure exit code when any test triggers a deprecation
- ``--fail-on-notice`` causes a failure exit code when any test triggers a notice
- ``--fail-on-skipped`` causes a failure exit code when any test is skipped
- ``--fail-on-incomplete`` causes a failure exit code when any test is marked incomplete
- ``--fail-on-empty-test-suite`` causes a failure exit code when no tests were executed
- ``--fail-on-all-issues`` enables all of the above

These options are commonly used in CI pipelines to enforce strict quality standards:

.. parsed-literal::

    $ ./tools/phpunit --fail-on-deprecation --fail-on-notice --fail-on-warning
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.5.3
    Configuration: /path/to/project/phpunit.xml

    DW                                                                  2 / 2 (100%)

    Time: 00:00.077, Memory: 10.00 MB

    OK, but there were issues!
    Tests: 2, Assertions: 2, Deprecations: 1, Warnings: 1.

``--fail-on-all-issues`` and its XML configuration file counterpart,
``failOnAllIssues="true"``, take precedence over the fine-grained ``failOn*``
attributes of the XML configuration file: configuring a fine-grained
``failOn*`` attribute to ``false`` has no effect when ``failOnAllIssues``
is configured to ``true``.

The fine-grained ``--fail-on-*`` options have ``--do-not-fail-on-*``
counterparts that can be used to override settings from the XML configuration
file. A ``--do-not-fail-on-*`` option disables failing on the respective issue
type and takes precedence over all ``--fail-on-*`` options and
``failOn*`` attributes, including ``--fail-on-all-issues`` and
``failOnAllIssues="true"``. The ``--do-not-fail-on-*`` options are therefore
the only way to not fail on a specific issue type when
``failOnAllIssues="true"`` is configured.

For deprecations, the failure behaviour can be refined based on how a deprecation was triggered. The ``--fail-on-self-deprecation``, ``--fail-on-direct-deprecation``, and ``--fail-on-indirect-deprecation`` options (and their ``--do-not-fail-on-*`` counterparts) control the exit code separately for each trigger type. See :ref:`error-handling.failing-on-deprecations-by-trigger` for details.

See :ref:`appendixes.cli-options.execution` for the complete reference.


.. _textui.stopping-early:

Stopping early
==============

When a test suite is large, it can be useful to stop execution as soon as a problem is encountered instead of waiting for the entire suite to finish.

The ``--stop-on-*`` family of options controls this:

- ``--stop-on-defect[=<n>]`` stops after the first (or n-th) error, failure, warning, or risky test
- ``--stop-on-error[=<n>]`` stops after the first (or n-th) error
- ``--stop-on-failure[=<n>]`` stops after the first (or n-th) failure
- ``--stop-on-warning[=<n>]`` stops after the first (or n-th) warning
- ``--stop-on-risky[=<n>]`` stops after the first (or n-th) risky test
- ``--stop-on-deprecation[=<n>]`` stops after the first (or n-th) deprecation
- ``--stop-on-notice[=<n>]`` stops after the first (or n-th) notice
- ``--stop-on-skipped[=<n>]`` stops after the first (or n-th) skipped test
- ``--stop-on-incomplete[=<n>]`` stops after the first (or n-th) incomplete test

When used without a value, these options stop execution after the first occurrence. When a threshold
``n`` is specified (for example, ``--stop-on-failure=3``), execution continues until ``n`` occurrences
have been encountered.

See :ref:`appendixes.cli-options.execution` for the complete reference.


.. _textui.logging:

Logging
=======

PHPUnit can write test results to log files in various formats:

- ``--log-otr <file>`` writes test results in Open Test Reporting (OTR) XML format (recommended)
- ``--log-junit <file>`` writes test results in JUnit XML format (legacy)
- ``--log-teamcity <file>`` writes test results in TeamCity format (used by PhpStorm)
- ``--testdox-html <file>`` writes test results in TestDox format as an HTML file
- ``--testdox-text <file>`` writes test results in TestDox format as a plain text file
- ``--log-events-text <file>`` writes all test runner events as plain text
- ``--log-events-verbose-text <file>`` writes all test runner events with extended information

These logfiles are commonly used for CI tool integration and reporting.

The ``--testdox-html`` and ``--testdox-text`` options write a report to a file; they are not intended to replace or augment the console output. For TestDox-formatted console output use ``--testdox`` (see :ref:`textui.output.alternative-formats.testdox`).

Logging can also be configured in the XML configuration file (see :ref:`appendixes.xml-configuration-file.logging`).

The ``--no-logging`` option ignores all logging configured in the XML configuration file.


.. _textui.test-execution-order:

Test execution order
====================

The ``--order-by`` option controls the order in which tests are executed. Supported values include:

``default``

    Tests are executed in the order they are discovered

``defects``

    Tests that failed in a previous run are executed first. This requires the test run
    history (enabled by default, see ``--record-test-run-history``)

``duration``

    Tests are ordered by duration, shortest first. This requires the test run history

``random``

    Tests are executed in random order. Use ``--random-order-seed`` to make the order
    reproducible

``reverse``

    Tests are executed in reverse discovery order

``size``

    Tests are ordered by size (small, medium, large, unknown)

``depends``

    Tests are reordered to satisfy ``#[Depends]`` declarations

``no-depends``

    Tests are not reordered to satisfy ``#[Depends]`` declarations

Multiple values can be combined: ``--order-by defects,random`` runs previously failing tests first, then executes the remaining tests in random order.

.. admonition:: Note

    Ordering by defects and ordering by duration are based on the test run history.
    When the recording of the test run history is disabled, for instance using the
    ``--do-not-record-test-run-history`` CLI option or the ``recordTestRunHistory="false"``
    attribute in the XML configuration file, the tests cannot be ordered this way and PHPUnit
    emits a test runner warning:

    .. parsed-literal::

        Tests cannot be ordered by defects because recording of the test run history is disabled

    The tests are then executed in the order that the other ``--order-by`` values, if any,
    lead to.

Convenience aliases are available: ``--resolve-dependencies`` (for ``--order-by depends``), ``--ignore-dependencies`` (for ``--order-by no-depends``), ``--random-order`` (for ``--order-by random``), and ``--reverse-order`` (for ``--order-by reverse``).

.. parsed-literal::

    $ ./tools/phpunit --order-by random --random-order-seed 42
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.5.3
    Configuration: /path/to/project/phpunit.xml
    Random seed:   42

    ..                                                                  2 / 2 (100%)

    Time: 00:00.077, Memory: 10.00 MB

    OK (2 tests, 2 assertions)

See :ref:`appendixes.cli-options.execution` for the complete reference.
