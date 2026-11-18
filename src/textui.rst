

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

Both options can be used more than once. A test is run when it is in *any* of the groups selected with ``--group``, and it is not run when it is in *any* of the groups selected with ``--exclude-group``:

.. parsed-literal::

    $ ./tools/phpunit --group database --group network

The command shown above runs the tests that are in the group ``database``, the tests that are in the group ``network``, and the tests that are in both.

Selecting the tests that are in every one of several groups
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Separating the names of groups with ``+`` selects the tests that are in *all* of them:

.. parsed-literal::

    $ ./tools/phpunit --group database+slow

The command shown above runs only the tests that are in the group ``database`` *and* in the group ``slow``. A test that is in only one of them is not run.

Any number of group names can be combined this way, and such a combination can be used wherever a single group name can be used. The two ways of combining groups can be mixed: each value of the option is a combination of groups a test has to be in, and a test is run when it matches at least one of these values.

.. parsed-literal::

    $ ./tools/phpunit --group database+slow --group smoke

The command shown above runs the tests that are in both ``database`` and ``slow``, as well as the tests that are in ``smoke``.

``--exclude-group`` works the same way:

.. parsed-literal::

    $ ./tools/phpunit --exclude-group database+slow

The command shown above runs every test except the ones that are in both ``database`` and ``slow``. A test that is only in ``database``, or only in ``slow``, is still run.

.. admonition:: Caution

    Because ``+`` combines group names, it cannot also be part of a group name. A group named ``database+slow`` cannot be selected: ``--group database+slow`` asks for the tests that are in ``database`` and in ``slow``, not for the tests that are in the group whose name is ``database+slow``.

    PHPUnit emits a warning where such a group name is declared so that this does not go unnoticed. Only a ``+`` between two group names is treated as a combination, though: ``+name``, ``name+``, and ``+`` are ordinary group names and can be selected as before.


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


.. _textui.selecting-tests.impacted:

Running only the tests that are affected by a change
-----------------------------------------------------

The ``--only-impacted`` option runs only the tests that can be affected by what changed since PHPUnit recorded what each test depends on. The ``--impacted-by`` and ``--impacted-by-file`` options run only the tests that can be affected by a change to the files and directories that are named. See :ref:`test-impact-analysis` for details.


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

This section contains information about the PHPUnit version, PHP version, and PHPUnit's XML configuration file. When PHPUnit seeds random number generators, this section also shows the seed (see :ref:`textui.random-numbers`). When the tests are run in parallel, this section also shows the number of worker processes (see :ref:`parallel-test-execution`). When only the tests that are affected by a change are run, this section also shows how many tests can be affected and when the test impact data that this is based on was recorded (see :ref:`test-impact-analysis.selecting`).

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

The ``--compact`` option replaces the default progress and result output with a compact format that is stripped down to what is actionable: there is no progress section, no timing or memory usage line, and no use of color. When all tests pass, only the runtime information section, a single summary line, and the receipt of the test run are printed:

.. parsed-literal::

    $ ./tools/phpunit --compact tests
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime: PHP 8.5.5

    OK (4699 tests, 13576 assertions)

    --- RECEIPT
    PHPUnit: |version|.0
    PHP: 8.5.5
    Arguments: --compact tests
    Working directory: /path/to/project
    Configuration: (none)
    Bootstrap: (none)
    Extensions: (none)
    Started: 2027-02-05T10:00:00+00:00
    Ended: 2027-02-05T10:00:02+00:00 (completed)
    Shell exit code: 0
    Tests: 4699 run, 13576 assertions
    Issues triggered by tests: (none)
    Issues triggered by the test runner: (none)

The record that begins with ``--- RECEIPT`` is the receipt of the test run. It records what was run and how the run ended, and it is always the last record of the compact output (see :ref:`textui.receipt`). It is left out of the other examples in this section.

When tests fail or have other issues, each defect is printed as a clearly delimited block with the test name, the assertion message, and the file location:

.. parsed-literal::

    $ ./tools/phpunit --compact tests
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime: PHP 8.5.5


    --- FAILURE: ExampleTest::testSomething
    Failed asserting that false is true.

    /path/to/tests/ExampleTest.php:47

    FAILURES (23 tests, 42 assertions, 1 failure)

The summary line begins with ``OK`` when all tests pass, ``FAILURES`` when at least one test failed, and ``ERRORS`` when at least one test errored. The counts that follow include the number of tests run and assertions made as well as, when non-zero, the number of errors, failures, deprecations, PHPUnit deprecations, warnings, PHPUnit warnings, notices, PHPUnit notices, skipped tests, incomplete tests, and risky tests.

Errors and failures are printed as soon as they occur, before the summary line. All other defects are listed after the summary line, in this order: errors, warnings, deprecations, and notices reported by PHPUnit itself, deprecations, warnings, notices, errors triggered by tests, risky tests, incomplete tests, skipped tests.

When the time limit for the test run configured with ``--timeout`` (see :ref:`textui.time-limit`) was exceeded, this is printed as a record of its own directly after the summary line, so that it cannot be mistaken for the output of a test:

.. parsed-literal::

    ERRORS (23 tests, 42 assertions, 1 error)

    --- TIME LIMIT EXCEEDED
    The time limit of 300 seconds for the test run was exceeded.

By default, only errors, failures, risky tests, and the errors and warnings reported by PHPUnit itself are shown in detail. Like the default output, ``--compact`` respects the ``--display-*`` flags described under :ref:`textui.output.controlling`: use ``--display-deprecations``, ``--display-warnings``, ``--display-notices``, ``--display-errors``, ``--display-incomplete``, ``--display-skipped``, ``--display-phpunit-deprecations``, ``--display-phpunit-notices``, or ``--display-all-issues`` to display additional details. Issues that are only counted in the summary line are not shown in detail unless the corresponding flag is used.

Compact output can also be activated by setting the ``PHPUNIT_COMPACT_OUTPUT`` environment variable to ``1``. This makes it easy to enable compact output globally without changing how PHPUnit is invoked, for example when running tests inside an LLM-based coding assistant where every token of test output consumes context window budget.

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
- ``--display-phpunit-deprecations`` shows details for deprecations reported by PHPUnit itself
- ``--display-phpunit-notices`` shows details for notices reported by PHPUnit itself
- ``--display-all-issues`` enables all of the above

The ``--colors=<flag>`` option controls the use of colors in terminal output. Accepted values are ``never``, ``auto`` (uses colors when the terminal supports it), and ``always``.

The ``--columns`` option controls the width of the progress output. Use ``--columns max`` to use the full terminal width.

The ``--reverse-list`` option prints defects in reverse order, showing the most recently encountered defects first.

The ``--triggering-tests-limit <n>`` option groups the tests that triggered a deprecation, notice, warning, or error and limits the list printed for each of these issues to ``n`` entries (see :ref:`error-handling.triggering-tests-limit`).

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

``124``

    The time limit for the test run configured with ``--timeout`` was exceeded (see :ref:`textui.time-limit`)

The exit code is a single number. It does not say whether the selected tests were run: a run in which no test matched the selection, in which every test was skipped, or which was stopped before its first test can exit with ``0``. The receipt of the test run records what the exit code cannot (see :ref:`textui.receipt`).

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

These options can be used when tests are run in parallel (see :ref:`parallel-test-execution.other-features`).

See :ref:`appendixes.cli-options.execution` for the complete reference.


.. _textui.time-limit:

Limiting the duration of the test run
=====================================

The ``--timeout <sec>`` option sets a wall-clock limit, in seconds, for the entire test run:

.. parsed-literal::

    $ ./tools/phpunit --timeout 300

The clock starts as soon as the configuration is known, so bootstrapping and loading the test suite count toward the limit. The limit is checked whenever a test has finished; no PHP extension is required for this. When the limit is exceeded, no further tests are started and the run ends the way a run that was stopped by a ``--stop-on-*`` option ends: the configured logs are written, the result is printed, and a message states that the time limit was exceeded.

When the ``pcntl`` extension is available, a test that is still running when the limit is exceeded is aborted. It is reported as an error whose stack trace shows where the test was stuck:

.. parsed-literal::

    $ ./tools/phpunit --timeout 300
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.5.5
    Configuration: /path/to/project/phpunit.xml

    .......E

    The time limit of 300 seconds for the test run was exceeded.

    Time: 05:00.012, Memory: 12.00 MB

    There was 1 error:

    1) ExampleTest::testSomething
    PHPUnit\\Runner\\TimeLimit\\TimeLimitExceededException: This test was aborted because the time limit of 300 seconds for the test run was exceeded

    /path/to/tests/ExampleTest.php:47

    ERRORS!
    Tests: 8, Assertions: 7, Errors: 1.

Without the ``pcntl`` extension, the test that is running when the limit is exceeded is not aborted. The test run stops after it has finished. The same applies to tests that run in a separate process (see :ref:`appendixes.attributes.RunInSeparateProcess`) and to PHPT tests, regardless of whether ``pcntl`` is available.

When tests are run in parallel (see :ref:`parallel-test-execution`), the tests that the worker processes are running when the limit is exceeded are aborted.

The shell exit code of a run that exceeded its time limit is ``124``, the value that GNU ``timeout(1)`` uses for a command that exceeded its time limit, regardless of whether the tests that did run passed (see :ref:`textui.exit-codes`). Code coverage reports and the baseline are not generated for such a run, as for a run that was interrupted by a signal. The ``PHPUnit\Event\TestRunner\TimeLimitExceeded`` event (see :ref:`appendixes.events`) is emitted when the limit is exceeded.

``--timeout`` limits the test run as a whole, whereas ``--enforce-time-limit`` limits individual tests based on their size (see :ref:`risky-tests.test-execution-timeout`). The two options can be combined.

``--timeout`` is intended for situations in which PHPUnit is invoked without a person watching the run, for instance by a CI pipeline or by an LLM-based coding assistant. Such a caller cannot tell a hung test run from a long one. Without a time limit, its only option is to kill the process, which leaves it without a summary, an exit code, a log file, or any record of which test was running. With ``--timeout``, the run ends on its own with all of these.


.. _textui.logging:

Logging
=======

PHPUnit can write test results to log files in various formats:

- ``--log-otr <file>`` writes test results in Open Test Reporting (OTR) XML format (recommended)
- ``--log-junit <file>`` writes test results in JUnit XML format (legacy)
- ``--log-teamcity <file>`` writes test results in TeamCity format (used by PhpStorm)
- ``--testdox-html <file>`` writes test results in TestDox format as an HTML file
- ``--testdox-text <file>`` writes test results in TestDox format as a plain text file
- ``--log-events-text <file>`` writes all test runner events as plain text; when combined with ``--with-telemetry``, timing and memory usage information is included for each event

These logfiles are commonly used for CI tool integration and reporting.

The ``--testdox-html`` and ``--testdox-text`` options write a report to a file; they are not intended to replace or augment the console output. For TestDox-formatted console output use ``--testdox`` (see :ref:`textui.output.alternative-formats.testdox`).

The logfile in Open Test Reporting XML format contains the receipt of the test run, which records what was run and how the run ended (see :ref:`textui.receipt`).

Logging can also be configured in the XML configuration file (see :ref:`appendixes.xml-configuration-file.logging`).

The ``--no-logging`` option ignores all logging configured in the XML configuration file.


.. _textui.receipt:

Receipt of the test run
=======================

A process that runs PHPUnit learns one number from it: the shell exit code (see :ref:`textui.exit-codes`). That number cannot say which version of PHPUnit was run with which arguments and which configuration, and it cannot say whether the selected tests were run at all.

The receipt of the test run records this information. It is written whenever ``--log-otr`` or ``--compact`` is used; there is no option to request it. It is intended for callers that are not a person watching the run:

- A tool that runs PHPUnit and interprets the result, for instance a mutation testing tool, reads the receipt instead of parsing output that is meant for humans.
- A person who checks the work of a CI pipeline or of an LLM-based coding assistant can verify afterwards what "all tests pass" refers to.

The receipt has two parts.

The first part records what is being run. It is determined when the test run starts, before the bootstrap script, extensions, or tests are loaded:

- the version of PHPUnit and, when PHPUnit is run from a PHAR, the path of that PHAR and the SHA-256 checksum of its contents
- the version of PHP
- the arguments that PHPUnit was invoked with, without the name of the script
- the working directory
- the path of the XML configuration file and the SHA-256 checksum of its contents, when an XML configuration file is used
- the bootstrap script, followed by the bootstrap scripts of the test suites that are not left out of the test run
- the bootstrap classes of the extensions that are configured; they are not recorded when ``--no-extensions`` is used
- the time at which the test run started

The second part records how the test run ended. It is the last thing that PHPUnit writes, after the shell exit code is known:

- the time at which the test run ended
- how the test run ended (see :ref:`textui.receipt.status`)
- the shell exit code
- the number of tests that were run and, of those, the number of tests that errored, failed, were skipped, were marked incomplete, or were considered risky
- the number of assertions
- the number of issues, separately for the issues that were triggered by tests and for the issues that were triggered by the test runner (see :ref:`textui.receipt.issues`)

Because the second part is written last, its presence shows that the test run reached its end.


.. _textui.receipt.status:

How the test run ended
----------------------

``completed``

    Every selected test was run. This is also the case when no test was selected.

``stopped``

    A ``--stop-on-*`` option, or its counterpart in the XML configuration file, ended the test run before every selected test was run (see :ref:`textui.stopping-early`).

``interrupted``

    An interrupt signal (``SIGINT``) ended the test run before every selected test was run.

``time-limit-exceeded``

    The time limit for the test run configured with ``--timeout`` was exceeded (see :ref:`textui.time-limit`).

``not-started``

    No test was run because the test run could not be started. This is the case when a code coverage report is requested and code coverage cannot be collected, for instance because no code coverage driver is available.

Only ``completed`` together with a number of tests run that is greater than zero means that the selected tests were run. A shell exit code of ``0`` does not: it is also the shell exit code of a test run in which every test was skipped and, when ``--do-not-fail-on-empty-test-suite`` is used, of a test run for which no test was selected.


.. _textui.receipt.issues:

Numbers of issues
-----------------

The numbers of issues are recorded twice: once for the issues that were triggered by tests and once for the issues that were triggered by the test runner. The latter are the issues that PHPUnit reports about the test run itself, for instance a warning about the configuration, as well as the issues that were triggered while no test was running, for instance in a bootstrap script or in a data provider.

This distinction is not available from the shell exit code. When ``--fail-on-warning`` and ``--fail-on-phpunit-warning`` are in effect, for instance, the shell exit code is ``1`` both when a test triggered a warning and when PHPUnit warned about the configuration.

For each origin, these issues are counted:

- errors, deprecations, notices, and warnings; the deprecations, notices, and warnings that are triggered by PHP itself are included
- the errors, deprecations, notices, and warnings that are reported by PHPUnit

The numbers are taken from the result that the shell exit code is calculated from, so the receipt and the shell exit code cannot disagree about what happened.


.. _textui.receipt.otr:

In the Open Test Reporting XML logfile
--------------------------------------

The first part of the receipt is the ``phpunit:invocation`` element in the ``infrastructure`` element. The second part is the ``phpunit:outcome`` element. It is carried by the last event of the logfile, which finishes the node for the test run:

.. code-block:: xml

    <?xml version="1.0"?>
    <e:events xmlns="https://schemas.opentest4j.org/reporting/core/0.2.0" xmlns:e="https://schemas.opentest4j.org/reporting/events/0.2.0" xmlns:php="https://schema.phpunit.de/otr/php/0.1.0" xmlns:phpunit="https://schema.phpunit.de/otr/phpunit/0.5.0">
     <infrastructure>
      <!-- ... -->
      <php:phpVersion>8.5.5</php:phpVersion>
      <php:threadModel>NTS</php:threadModel>
      <phpunit:randomSeed>1764842400</phpunit:randomSeed>
      <phpunit:invocation version="14.0.0" workingDirectory="/path/to/project" time="2027-02-05T10:00:00.000000Z">
       <phpunit:phar path="/path/to/project/tools/phpunit" sha256="..."/>
       <phpunit:argument>--log-otr</phpunit:argument>
       <phpunit:argument>build/otr.xml</phpunit:argument>
       <phpunit:configurationFile path="/path/to/project/phpunit.xml" sha256="..."/>
       <phpunit:bootstrap path="/path/to/project/tests/bootstrap.php"/>
       <phpunit:extension className="Vendor\Extension\Bootstrap"/>
      </phpunit:invocation>
     </infrastructure>
     <e:started id="0" name="PHPUnit" time="2027-02-05T10:00:00.000000Z"/>
     <e:started id="1" parentId="0" name="/path/to/project/phpunit.xml" time="2027-02-05T10:00:00.100000Z"/>
     <!-- the events for the test suites and the tests -->
     <e:finished id="1" time="2027-02-05T10:00:01.900000Z">
      <!-- ... -->
     </e:finished>
     <e:finished id="0" time="2027-02-05T10:00:02.000000Z">
      <attachments>
       <phpunit:outcome status="completed" shellExitCode="1" time="2027-02-05T10:00:02.000000Z">
        <phpunit:tests run="12" assertions="30" errored="0" failed="0" skipped="1" incomplete="0" risky="1"/>
        <phpunit:issuesTriggeredByTests errors="0" deprecations="2" notices="0" warnings="0" phpunitErrors="0" phpunitDeprecations="0" phpunitNotices="0" phpunitWarnings="0"/>
        <phpunit:issuesTriggeredByTestRunner errors="0" deprecations="0" notices="0" warnings="0" phpunitErrors="0" phpunitDeprecations="0" phpunitNotices="0" phpunitWarnings="1"/>
       </phpunit:outcome>
      </attachments>
     </e:finished>
    </e:events>

The node for the test run has the id ``0`` and the name ``PHPUnit``. It is started right after the ``infrastructure`` element and it is the parent of the nodes for the outermost test suites. The ids of the nodes for test suites and tests are positive integers.

The ``phpunit:phar`` and ``phpunit:configurationFile`` elements are only present when PHPUnit is run from a PHAR and when an XML configuration file is used, respectively. There is one ``phpunit:argument`` element for each argument, one ``phpunit:bootstrap`` element for each bootstrap script, and one ``phpunit:extension`` element for the bootstrap class of each extension.

The first part of the receipt is written to the logfile, together with the start of the node for the test run, before the bootstrap script is loaded. The logfile of a test run that a bootstrap script, an extension, or a test ends therefore says what was run. Such a logfile ends after its last event, without the ``phpunit:outcome`` element. It is not a well-formed XML document, because its root element is not closed.

The elements of the receipt are in the namespace ``https://schema.phpunit.de/otr/phpunit/0.5.0``. A logfile with a receipt validates against the schemas of the Open Test Reporting format.


.. _textui.receipt.compact:

In the compact output
---------------------

Both parts of the receipt are printed as the last record of the compact output (see :ref:`textui.output.alternative-formats.compact`):

.. parsed-literal::

    FAILURES (12 tests, 30 assertions, 2 deprecations, 1 PHPUnit warning, 1 skipped, 1 risky)

    --- RECEIPT
    PHPUnit: |version|.0 (PHAR: /path/to/project/tools/phpunit, SHA-256: ...)
    PHP: 8.5.5
    Arguments: --compact
    Working directory: /path/to/project
    Configuration: /path/to/project/phpunit.xml (SHA-256: ...)
    Bootstrap: /path/to/project/tests/bootstrap.php
    Extensions: Vendor\\Extension\\Bootstrap
    Started: 2027-02-05T10:00:00+00:00
    Ended: 2027-02-05T10:00:02+00:00 (completed)
    Shell exit code: 1
    Tests: 12 run, 30 assertions, 1 skipped, 1 risky
    Issues triggered by tests: 2 deprecations
    Issues triggered by the test runner: 1 PHPUnit warning

Each line of the record has a label and a value:

- ``(none)`` is printed for a value that is empty.
- An argument that is not made of characters that are safe in every shell is enclosed in single quotes, so that it can be told where one argument ends and where the next one begins.
- Times are in UTC.
- How the test run ended is printed in parentheses after the time at which it ended.
- A number of tests or issues that is zero is not printed, with the exception of the number of tests run and the number of assertions.
- Control characters and line breaks in a value are made visible, so that a value cannot be mistaken for another line of the record.

A test run that does not reach its end does not print the record.

The record is not printed when an extension replaces the output of the test results.


.. _textui.receipt.limitations:

Limitations
-----------

The receipt is not a signature
    The receipt is written by the process that runs the test code, into a file and a stream that the test code can write to as well. It makes a result checkable; it does not make it tamper-proof. In the compact output, text that test code prints can imitate the record.

A test run that does not reach its end has no complete receipt
    A fatal error, ``exit()`` in a bootstrap script or in a test, a crash of PHPUnit, a second interrupt signal, and ``SIGKILL`` all end the process before the second part is written. When the CLI options or the XML configuration file are invalid, PHPUnit exits before the logfile is opened. A tool therefore has to make sure that it does not read the logfile of an earlier test run, for instance by deleting it before it runs PHPUnit.

Commands that do not run tests leave a logfile without the second part
    When ``--log-otr`` is combined with an option that makes PHPUnit do something other than running tests, for instance ``--list-tests``, the logfile has the first part of the receipt only.

The receipt describes the test run, not individual tests
    The outcome of each test, the type of the throwable for a test that errored or failed, and the issues that a test triggered are reported by the events of the Open Test Reporting XML logfile.

The extensions are those that are configured
    The receipt names the bootstrap classes of the extensions before they are bootstrapped. When an extension cannot be bootstrapped, the test runner triggers a warning, which is counted in the second part.

Arguments are recorded as they were passed
    A command line that contains a secret puts that secret into the logfile and into the compact output.


.. _textui.test-execution-order:

Test execution order
====================

By default, PHPUnit executes the tests of each test suite in the order in which it discovered them. The ``--order-by`` option changes that: it configures a pipeline of reordering stages that is applied to the tests of every test suite, one stage after another. The stages are applied in the order in which the values that configure them are written.

Order
-----

An order is a stage that sorts the tests of a test suite. Exactly one order can be configured:

``default``

    Tests are executed in the order in which they were discovered

``duration-ascending``

    Tests are ordered by duration, fastest test first. This requires the test run
    history (enabled by default, see ``--record-test-run-history``)

``duration-descending``

    Tests are ordered by duration, slowest test first. This requires the test run
    history

``modified-ascending``

    Tests are ordered by the time their source files were last modified, least
    recently modified test first

``modified-descending``

    Tests are ordered by the time their source files were last modified, most
    recently modified test first

``random``

    Tests are executed in random order. Use ``--random-seed`` to make the order
    reproducible (see :ref:`textui.random-numbers`)

``reverse``

    Tests are executed in reverse discovery order

``size-ascending``

    Tests are ordered by size (small, medium, large, unknown)

``size-descending``

    Tests are ordered by size in reverse (large, medium, small, unknown)

``default`` resets what the values written before it configured, including ``defects``. ``--order-by random,default`` therefore executes the tests in the order in which they were discovered, and ``--order-by random,default,reverse`` executes them in reverse discovery order. Using ``default`` together with an order like this does not count as configuring more than one order.

Configuring more than one order is an error, because sorting the tests again would discard the order that the previous sort established:

.. parsed-literal::

    $ ./tools/phpunit --order-by duration-ascending,reverse
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Cannot use more than one order for --order-by: "duration-ascending" and "reverse"

Using a value more than once and using a value that PHPUnit does not know are errors as well. The same rules apply to the ``executionOrder`` attribute in the XML configuration file: an XML configuration file that violates them cannot be loaded.

Ordering by modification time
-----------------------------

``--order-by modified-descending`` runs the tests you have worked on most recently first. Combined with ``--stop-on-failure``, this shortens the feedback loop while a new test is being written.

The source files of a test are the files a change to which can change what the test does: the file that declares the test class, the files that declare the classes it extends, and the files that declare the traits any of them use. The most recent modification among them decides, so modifying a base test class or a trait moves every test class that uses it to the front as well. For a PHPT test, the ``.phpt`` file is the test. A test suite is as new as the newest test it contains.

.. admonition:: Note

    The modification time of a file is not a record of who changed what: checking out a
    branch, cloning a repository, installing dependencies, and copying or deploying files
    all set it to the time of that operation. On a continuous integration server, where
    every file is usually written at the same time, this order is therefore meaningless.
    It is intended for local development and should not be configured in a project's XML
    configuration file.

    Many file systems store modification times with a resolution of one second, so files
    that were written during the same second are equally new. Tests that are equally new
    keep the order that they had before this stage was applied.

Running previously defective tests first
----------------------------------------

The order can be followed by ``defects``, for instance ``--order-by duration-ascending,defects``. This adds a second stage that moves the tests that were defective during the previous test run to the front, so that a defect is reached as fast as possible. Tests that are not moved to the front keep the order that the first stage established.

Because the stages are applied in the order in which they are written, ``defects`` has to be written after the order. With ``--order-by defects,duration-ascending``, the tests are sorted by duration after the defective tests have been moved to the front, and the defective tests do not stay at the front.

Only tests that errored or failed count as defects. A test that was skipped, that was marked as incomplete, or that triggered an issue is not moved to the front. A test suite counts as defective when at least one of the tests it contains counts as defective, so that defects are moved to the front through arbitrarily deeply nested test suites as well.

``--order-by defects`` can also be used on its own, without configuring an order.

.. admonition:: Note

    Ordering by defects and ordering by duration are based on the test run history.
    When the recording of the test run history is disabled, for instance using the
    ``--do-not-record-test-run-history`` CLI option or the ``recordTestRunHistory="false"``
    attribute in the XML configuration file, the tests cannot be ordered this way and PHPUnit
    emits a test runner warning:

    .. parsed-literal::

        Tests cannot be ordered by defects because recording of the test run history is disabled

    The tests are then executed in the order that the remaining stages, if any, lead to.

Resolving dependencies between tests
------------------------------------

Dependency resolution is the last stage of the pipeline: it reorders the tests of a test suite so that as many ``Depends*`` declarations as possible are satisfied. It is enabled by default and is controlled using the ``--resolve-dependencies`` and ``--ignore-dependencies`` CLI options, or the ``resolveDependencies`` attribute in the XML configuration file.

Because any stage that ran after it would undo the order it establishes, dependency resolution is always applied last.

Whether dependencies between tests are resolved does not depend on the value of ``--order-by`` or of the ``executionOrder`` attribute: ``default`` resets the stages that are configured using these, but it has no effect on dependency resolution.

Aliases
-------

Convenience aliases are available: ``--random-order`` (for ``--order-by random``) and ``--reverse-order`` (for ``--order-by reverse``).

.. parsed-literal::

    $ ./tools/phpunit --order-by random --random-seed 42
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.5.3
    Configuration: /path/to/project/phpunit.xml
    Random Seed:   42

    ..                                                                  2 / 2 (100%)

    Time: 00:00.077, Memory: 10.00 MB

    OK (2 tests, 2 assertions)

See :ref:`appendixes.cli-options.execution` for the complete reference.


.. _textui.random-numbers:

Random numbers
==============

A test that uses random numbers, for instance to generate test data, may fail for some of them only. To reproduce such a failure, the test has to get the same random numbers when it is run again. The ``--random-seed <N>`` option seeds PHP's random number generators with ``N``:

.. parsed-literal::

    $ ./tools/phpunit --random-seed 1234
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.5.3
    Configuration: /path/to/project/phpunit.xml
    Random Seed:   1234

    ..                                                                  2 / 2 (100%)

    Time: 00:00.077, Memory: 10.00 MB

    OK (2 tests, 2 assertions)

The random number generators are also seeded when the tests are run in random order (see :ref:`textui.test-execution-order`). Unless ``--random-seed`` is used, the current time is used as the seed in this case. The same seed is used for the random order and for the random numbers. Either way, PHPUnit shows the seed in the :ref:`runtime information <textui.output.runtime>`, so that it can be passed to ``--random-seed`` to run the tests with the same random numbers, and in the same random order, again. Otherwise, PHPUnit does not seed the random number generators.

The following random number generators are seeded:

* The Mt19937 random number generator that is used by ``mt_rand()``, ``rand()``, ``shuffle()``, ``str_shuffle()``, and ``array_rand()``
* The random number generator of the ``gmp`` extension, which is used by ``gmp_random_bits()`` and ``gmp_random_range()``, for instance, when this extension is loaded

The seed is also what the examples of :ref:`properties <property-based-testing>` are generated from. When a property is among the tests that are run, PHPUnit uses a seed, the current time unless ``--random-seed`` is used, and shows it in the runtime information, even when the tests are not run in random order. The ``--random-seed-from-git-commit`` option derives the seed from the Git commit that is checked out, so that all test runs of a commit use the same seed. See :ref:`property-based-testing.reproducing-failures.random-seed`.

``random_int()`` and ``random_bytes()`` use a cryptographically secure random number generator that cannot be seeded, and an object of the ``Random\Randomizer`` class uses the engine it was created with. They are not affected by the seed.

PHPUnit does not seed the random number generators only once. It seeds them again before each test, before the before-first-test methods of each test class, and before each data provider is called, using a seed that is derived from the seed of the test run and from the test, the test class, or the data provider, respectively. For a given seed, a test therefore gets the same random numbers, and a data provider provides the same data, regardless of which other tests are run, in which order the tests are run, and whether a test is run in a separate process. A test that failed can thus be run on its own with the seed of the test run in which it failed:

.. parsed-literal::

    $ ./tools/phpunit --random-seed 1234 --filter testSomething

Each repetition of a :ref:`repeated test <flaky-tests.repeating-tests>` and each attempt of a :ref:`retried test <flaky-tests.retrying-tests>` gets random numbers of its own, which are also the same for a given seed. The random number generators are not seeded for :ref:`PHPT tests <appendixes.phpt>`, which are run in a separate PHP process.

When ``N`` is not a positive integer, ``--random-seed`` is ignored and PHPUnit emits a test runner warning.
