

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

Output
======

The default output of PHPUnit's test runner consists of up to four sections.

Versions and Configuration
--------------------------

This section contains information about the PHPUnit version, PHP version, and PHPUnit's XML
configuration file.

This section can be disabled using the ``--no-output`` CLI option.

Progress
--------

This section is printed to indicate progress while the tests are being run:

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

This section can be disabled using the ``--no-progress`` and ``--no-output`` CLI options.

Test Results
------------

This section contains information about errors, failures, skipped tests,
incomplete tests, and issues. By default, only information about errors and failures
is printed.

This section is only printed when there are errors, failures, or issues to report.
It can be disabled using the ``--no-results`` and ``--no-output`` CLI options.

Summary
-------

This section contains a summary of the test suite execution.

- When no test errored or failed then the summary will be ``OK``
- When a test errored then the summary will be ``ERRORED!``
- When a test failed and no test errored then the summary will be ``FAILED!``
- When there were no errors, failures, or issues, but a test was skipped, then the summary will be ``OK, but some tests were skipped!``
- When there were no errors, failures, skipped tests, or issues then the summary will be ``OK, but there were issues!``

.. admonition:: ``--fail-on-*``

   Please note that if you use CLI options such as ``--fail-on-deprecation``, for example,
   or their XML configuration counterparts, then this only affects the test runner's shell
   exit code. It does not have an effect on whether or not ``OK`` will be printed in the
   output's summary section.

This section can be disabled using the ``--no-output`` CLI option.
