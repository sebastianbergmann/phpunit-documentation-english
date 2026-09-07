

.. _appendixes.phpt:

**********
PHPT Tests
**********

PHPT is the test format that the PHP project uses for testing PHP itself.
A PHPT test is a plain text file with the ``.phpt`` suffix that is divided into sections.
In its simplest form, such a file contains a PHP script and the output that this script is expected to produce.
Running a PHPT test means running that script in a new PHP process and comparing the output of that process with the expected output.

In addition to tests that are implemented as methods of test classes, PHPUnit's test runner can run PHPT tests.
This appendix documents how PHPUnit runs PHPT tests, which sections of the PHPT format PHPUnit supports, and how PHPUnit's implementation differs from the one used by the PHP project.

The PHPT format is documented by the PHP project at `php.github.io/php-src/miscellaneous/writing-tests.html <https://php.github.io/php-src/miscellaneous/writing-tests.html>`_.
PHPUnit implements a subset of that format.

Support for running PHPT tests was added to PHPUnit to make it easier and cleaner to test PHPUnit's own test runner with end-to-end tests.
Testing a command-line tool means running it as a process, with a set of arguments, and checking what it prints.
This is precisely what PHPT was designed for, and expressing such a test as a PHPT file is usually shorter and more readable than expressing it as a test method that has to set up a child process, capture its output, and assert on it.

.. note::

   PHPT mostly serves niche use cases.
   Just because PHPUnit can run PHPT tests does not mean that you should consider PHPT as your default test format.

   A PHPT test has no fixtures, no test doubles, no data providers, and only a single implicit assertion: the comparison of the whole output of a PHP process with an expected output.
   Tests that are implemented as methods of test classes should be your default choice.
   Reach for PHPT when the subject under test is the observable behaviour of a PHP process, for instance a command-line tool.


.. _appendixes.phpt.anatomy:

Anatomy of a PHPT Test
======================

Consider the small command-line tool shown below:

.. literalinclude:: examples/phpt/src/greet.php
   :caption: A command-line tool (declared in ``src/greet.php``)
   :name: appendixes.phpt.examples.greet.php
   :language: php

The PHPT test shown below runs this tool with the command-line argument ``Alice`` and expects it to print ``Hello, Alice!``:

.. literalinclude:: examples/phpt/greet-with-argument.phpt
   :caption: A PHPT test (declared in ``tests/greet-with-argument.phpt``)
   :name: appendixes.phpt.examples.greet-with-argument.phpt

A PHPT file consists of sections.
A section begins with a line that contains only the section's name, enclosed in ``--`` on both sides, and extends to the beginning of the next section or the end of the file.
Section names are written in uppercase letters; a section may not be used more than once in a PHPT file.

A PHPT file must have exactly one section that contains the code to be run (``--FILE--``, ``--FILEEOF--``, or ``--FILE_EXTERNAL--``) as well as exactly one section that describes the expected output (``--EXPECT--``, ``--EXPECTF--``, ``--EXPECTREGEX--``, or one of their ``_EXTERNAL`` variants).
All other sections are optional.

.. note::

   Unlike PHP's own test runner, PHPUnit does not require a ``--TEST--`` section and does not use its contents.
   A PHPT test is identified by the path to its file.
   Nevertheless, a ``--TEST--`` section that describes what the test verifies should be the first section of every PHPT file: it is the only place where the intent of the test can be documented.


.. _appendixes.phpt.running:

Running PHPT Tests
==================

``.phpt`` is one of the default test file suffixes, together with ``Test.php``.
The path to a PHPT file can be passed to the test runner directly:

.. parsed-literal::

    $ ./tools/phpunit tests/greet-with-argument.phpt
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.5.3

    .                                                                   1 / 1 (100%)

    Time: 00:00.077, Memory: 20.00 MB

    OK (1 test, 1 assertion)

When a directory is passed to the test runner, PHPT files in that directory and its subdirectories are discovered along with test classes.
The ``--test-suffix`` CLI option (documented in :ref:`appendixes.cli-options.selection`) can be used to change which suffixes are considered.

PHPT tests can also be configured in the XML configuration file:

.. code-block:: xml

    <testsuites>
      <testsuite name="end-to-end">
        <directory suffix=".phpt">tests/end-to-end</directory>
      </testsuite>
    </testsuites>

Each PHPT test counts as one test with one assertion.

The following limitations apply to PHPT tests:

- PHPT tests are never selected by name: using ``--filter`` or ``--exclude-filter`` removes all PHPT tests from the test run (see :ref:`textui.selecting-tests.filter`)
- PHPT tests cannot be assigned to a group: they are always members of the ``default`` group only, and the ``groups`` attribute of the ``<directory>`` and ``<file>`` elements shown in :ref:`appendixes.xml-configuration-file.testsuites` has no effect on them
- PHPT tests are not shown in the TestDox output (see :ref:`testdox`)

PHPT tests can be selected using ``--run-test-id``, ``--test-id-filter-file``, and ``--test-files-file``, though.
The identifier of a PHPT test is the path to its file, which is also what ``--list-tests``, ``--list-test-ids``, and ``--list-test-files`` print for it.


.. _appendixes.phpt.execution:

How a PHPT Test Is Executed
===========================

The code from the ``--FILE--`` section is executed in a new PHP process that is started using the same PHP binary that runs PHPUnit.
Everything the process writes to standard output and standard error is captured; the two streams are merged, meaning that warnings, notices, and fatal errors are part of the output that is compared with the expected output.
The exit code of the process is not taken into account.

Before the output is compared with the expected output, ``\r\n`` is normalized to ``\n`` and leading as well as trailing whitespace is removed from both the actual and the expected output.

The child process is not prepared in any way for the code from the ``--FILE--`` section:
Composer's autoloader is not registered, and the bootstrap script that is configured for the test run is not loaded.
The code from the ``--FILE--`` section must therefore load what it needs itself, as shown in :numref:`appendixes.phpt.examples.greet-with-argument.phpt`.

.. note::

   The code from the ``--FILE--`` section is passed to the PHP process through standard input, unless the PHPT file has a ``--STDIN--`` section, in which case it is written to a temporary file that the PHP process is told to execute.

   As a consequence, the magic constants ``__FILE__`` and ``__DIR__`` cannot be used to refer to the file the code is in.
   Before the code is executed, PHPUnit textually replaces ``__FILE__`` with the path of the PHPT file and ``__DIR__`` with the path of the directory the PHPT file is in.
   This textual replacement is performed on the ``--FILE--``, ``--SKIPIF--``, and ``--CLEAN--`` sections, and it does not take the syntactic context into account: an occurrence of ``__DIR__`` in a string literal or a comment is replaced as well.

The PHP process is started with the ``php.ini`` settings shown below so that the output of the test does not depend on the configuration of the PHP installation that is used to run it:

.. code-block:: ini

    allow_url_fopen=1
    auto_append_file=
    auto_prepend_file=
    date.timezone=UTC
    disable_functions=
    display_errors=1
    display_startup_errors=1
    docref_ext=.html
    docref_root=
    error_append_string=
    error_prepend_string=
    error_reporting=-1
    fatal_error_backtraces=Off
    html_errors=0
    ignore_repeated_errors=0
    log_errors=0
    open_basedir=
    output_buffering=Off
    output_handler=
    precision=14
    report_zend_debug=0
    serialize_precision=-1

Each of these settings can be overridden using the ``--INI--`` section of the PHPT file.


.. _appendixes.phpt.sections:

Sections
========

This section documents the PHPT sections that PHPUnit supports.


.. _appendixes.phpt.sections.test:

``--TEST--``
------------

A single-line description of what the test verifies.
PHPUnit does not use the contents of this section: a PHPT test is identified by the path to its file.


.. _appendixes.phpt.sections.code:

``--FILE--``, ``--FILEEOF--``, ``--FILE_EXTERNAL--``
----------------------------------------------------

The code to be executed.
Exactly one of these three sections must be present in a PHPT file.

``--FILE--``
    The PHP code to be executed, beginning with an opening ``<?php`` tag.

``--FILEEOF--``
    Same as ``--FILE--``, except that trailing line breaks are removed before the code is executed.
    This is useful for testing behaviour that depends on the absence of a trailing newline.

``--FILE_EXTERNAL--``
    The name of a file, relative to the directory the PHPT file is in, that contains the code to be executed.


.. _appendixes.phpt.sections.expectation:

``--EXPECT--``, ``--EXPECTF--``, ``--EXPECTREGEX--``
----------------------------------------------------

The expected output.
Exactly one of these three sections, or one of their ``_EXTERNAL`` variants, must be present in a PHPT file.

``--EXPECT--``
    The output of the PHP process must be equal to the contents of this section.

``--EXPECTF--``
    The output of the PHP process must match the format string in this section.
    A format string may contain placeholders such as ``%s``, ``%d``, and ``%a``.
    This is the same format that :ref:`appendixes.assertions.assertStringMatchesFormat` uses, and the placeholders are documented there.
    Use this section when parts of the output are not deterministic, for instance because they contain a version number, a path, or a duration.

``--EXPECTREGEX--``
    The output of the PHP process must match the regular expression in this section.
    The regular expression is applied to the entire output, and ``.`` matches newline characters.
    Do not enclose the regular expression in delimiters: PHPUnit adds them.

The example shown below uses ``--EXPECTF--`` because the version of PHP that is part of the output is not known in advance:

.. literalinclude:: examples/phpt/greet-version.phpt
   :caption: Using ``--EXPECTF--`` for output that is not fully deterministic (declared in ``tests/greet-version.phpt``)
   :name: appendixes.phpt.examples.greet-version.phpt

``--EXPECT_EXTERNAL--``, ``--EXPECTF_EXTERNAL--``, and ``--EXPECTREGEX_EXTERNAL--`` work like the sections described above, except that they contain the name of a file, relative to the directory the PHPT file is in, that contains the expected output.
This is useful when the expected output is long enough that it would dominate the PHPT file.


.. _appendixes.phpt.sections.input:

``--ARGS--``, ``--STDIN--``
---------------------------

``--ARGS--``
    Command-line arguments for the PHP process, separated by whitespace.
    The arguments are available in ``$argv``, beginning at index ``1``.
    Do not rely on the value of ``$argv[0]``: it is not the path to the PHPT file.

``--STDIN--``
    Data that is written to the standard input of the PHP process.

The example shown below feeds a name to the tool through standard input instead of passing it as a command-line argument:

.. literalinclude:: examples/phpt/greet-with-stdin.phpt
   :caption: Using ``--STDIN--`` (declared in ``tests/greet-with-stdin.phpt``)
   :name: appendixes.phpt.examples.greet-with-stdin.phpt

.. note::

   When a PHPT file has no ``--STDIN--`` section, the standard input of the PHP process is used to pass the code to be executed to it and is closed afterwards.
   Code that reads from ``STDIN`` will therefore not read the code that is being executed, but it will also not receive any data.


.. _appendixes.phpt.sections.environment:

``--INI--``, ``--ENV--``
------------------------

``--INI--``
    ``php.ini`` settings for the PHP process, one ``name=value`` pair per line.
    These settings override the settings listed in :ref:`appendixes.phpt.execution`.
    ``extension`` and ``zend_extension`` may be used more than once.

``--ENV--``
    Environment variables for the PHP process, one ``name=value`` pair per line.
    These variables are added to the environment variables that PHPUnit itself was started with.

.. code-block:: none
   :caption: Using ``--INI--``

    --TEST--
    Floating point numbers are printed using the configured precision
    --INI--
    precision=3
    --FILE--
    <?php declare(strict_types=1);
    print 1/3;
    --EXPECT--
    0.333

The following placeholders may be used in the ``--INI--`` section:

``{PWD}``
    The path of the directory the PHPT file is in

``{TMP}``
    The path of the system's directory for temporary files

``{ENV:name}``
    The value of the environment variable ``name``

A PHPT test whose ``--INI--`` section refers to an environment variable that is not set is skipped.

The ``{PWD}`` placeholder may also be used in the ``--ENV--`` section.


.. _appendixes.phpt.sections.skipif:

``--SKIPIF--``
--------------

PHP code that decides whether the test should be run.
This is commonly used to skip a test when an extension it requires is not available or when the operating system it targets is not the one the test is run on.

The output of this code determines what happens:

``skip <reason>``
    The test is skipped and ``<reason>`` is reported as the reason for skipping it.
    ``skip: <reason>`` and ``skip - <reason>`` are supported as well.

``xfail <reason>``
    The test is run, but it is expected to fail; ``<reason>`` explains why.
    This has the same effect as an ``--XFAIL--`` section, but it allows the decision to be made at runtime.

``info <text>``, ``warn <text>``, ``xleak <text>``, ``flaky <text>``, ``nocache <text>``
    These keywords are understood by PHP's own test runner, but they have no counterpart in PHPUnit.
    They are ignored and the test is run.

No output
    The test is run.

.. code-block:: none
   :caption: Using ``--SKIPIF--``

    --TEST--
    Something that requires the ftp extension
    --SKIPIF--
    <?php declare(strict_types=1);
    if (!extension_loaded('ftp')) print 'skip The ftp extension is not available';
    --FILE--
    <?php declare(strict_types=1);
    ...

A PHPT test is considered risky when its ``--SKIPIF--`` section produces output that is none of the above, as this usually means that the check itself is broken.
The same is true when the code in the ``--SKIPIF--`` section cannot produce output at all, as such a check can never skip the test.

.. note::

   The code from the ``--SKIPIF--`` section is executed in PHPUnit's own process when it has no side effects other than output and input/output and the PHPT file has no ``--INI--`` section.
   Otherwise it is executed in a new PHP process.
   Keep the code in this section simple and free of side effects.


.. _appendixes.phpt.sections.xfail:

``--XFAIL--``
-------------

Marks the test as expected to fail; the contents of the section explain why, for instance by referring to a bug report.

When the test fails, it is reported as incomplete instead of failed, with the contents of this section as the reason.
When the test passes, it is considered risky: this usually means that the problem has been fixed and that the ``--XFAIL--`` section should be removed.


.. _appendixes.phpt.sections.clean:

``--CLEAN--``
-------------

PHP code that is executed after the test has been run, for instance to delete files that the test created.
This code is executed regardless of whether the test passed or failed.

.. code-block:: none
   :caption: Using ``--CLEAN--``

    --TEST--
    The cache file is created
    --FILE--
    <?php declare(strict_types=1);
    ...
    --EXPECT--
    ...
    --CLEAN--
    <?php declare(strict_types=1);
    unlink(__DIR__ . '/cache.txt');

Like the code from the ``--SKIPIF--`` section, the code from the ``--CLEAN--`` section is executed in PHPUnit's own process when it has no side effects other than output and input/output and the PHPT file has no ``--INI--`` section.
Otherwise it is executed in a new PHP process.


.. _appendixes.phpt.sections.ignored:

Ignored Sections
----------------

The sections listed below are part of the PHPT format, but they have no effect when a PHPT test is run by PHPUnit.
They are accepted so that PHPT tests written for PHP's own test runner do not have to be modified:

- ``--CAPTURE_STDIO--``
- ``--CONFLICTS--``
- ``--CREDITS--``
- ``--DESCRIPTION--``
- ``--FLAKY--``
- ``--WHITESPACE_SENSITIVE--``
- ``--XLEAK--``

Instead of ``--FLAKY--``, use the ``--retry`` CLI option that is documented in :ref:`appendixes.phpt.repeating-and-retrying`.


.. _appendixes.phpt.sections.unsupported:

Unsupported Sections
--------------------

The sections listed below are part of the PHPT format, but they are not supported by PHPUnit.
Most of them exist for testing PHP's web-facing Server API implementations, which PHPUnit does not do: PHPT tests are always run using the PHP binary that runs PHPUnit, using the command-line interface.

- ``--CGI--``
- ``--COOKIE--``
- ``--DEFLATE_POST--``
- ``--EXPECTHEADERS--``
- ``--EXTENSIONS--``
- ``--GET--``
- ``--GZIP_POST--``
- ``--HEADERS--``
- ``--PHPDBG--``
- ``--POST--``
- ``--POST_RAW--``
- ``--PUT--``
- ``--REDIRECTTEST--``
- ``--REQUEST--``

A PHPT test that uses one of these sections errors:

.. parsed-literal::

    1) /path/to/tests/example.phpt
    PHPUnit\\Runner\\Phpt\\UnsupportedPhptSectionException: PHPUnit does not support PHPT --GET-- sections

Instead of ``--EXTENSIONS--``, use a ``--SKIPIF--`` section that checks with ``extension_loaded()`` whether the required extension is available.


.. _appendixes.phpt.invalid-files:

Invalid PHPT Files
==================

A PHPT file that cannot be processed leads to an error being reported for it.
This is the case when

- the file uses a section that is not part of the PHPT format, for instance because the name of a section is misspelled,
- the file uses a section more than once,
- the file has more than one of the ``--FILE--``, ``--FILEEOF--``, and ``--FILE_EXTERNAL--`` sections or none of them,
- the file has more than one expectation section or none of them,
- the code section of the file is empty or references a file that is empty,
- the file contains text before its first section, or
- a file referenced by a ``_EXTERNAL`` section does not exist or cannot be read.

.. parsed-literal::

    1) /path/to/tests/example.phpt
    PHPUnit\\Runner\\Phpt\\UnknownPhptSectionException: --EXPCT-- is not a valid PHPT section


.. _appendixes.phpt.reporting:

How Failures Are Reported
=========================

A PHPT test fails when the output of the PHP process does not match the expected output.
The failure is reported with a diff of the expected and the actual output.
PHPUnit uses the diff to determine which line of the expectation section the first difference is in and reports the PHPT file and that line as the location of the failure.

The output shown below is what the test shown in :numref:`appendixes.phpt.examples.greet-with-argument.phpt` would report if the tool greeted ``Alice`` while the test expected it to greet ``Bob``:

.. parsed-literal::

    1) /path/to/tests/greet-with-argument.phpt
    Failed asserting that two strings are equal.
    --- Expected
    +++ Actual
    @@ @@
    -'Hello, Bob!'
    +'Hello, Alice!'

    /path/to/tests/greet-with-argument.phpt:9

Because standard error is merged into standard output, a PHP error that is triggered by the code from the ``--FILE--`` section does not lead to a separate error being reported: it becomes part of the actual output and shows up in the diff.

The example shown below relies on this and tests the error message that the tool prints when it is invoked without a name:

.. literalinclude:: examples/phpt/greet-without-name.phpt
   :caption: Testing output that is written to standard error (declared in ``tests/greet-without-name.phpt``)
   :name: appendixes.phpt.examples.greet-without-name.phpt


.. _appendixes.phpt.code-coverage:

Code Coverage
=============

Code coverage is collected for PHPT tests just like it is collected for tests that are implemented as methods of test classes, provided that a code coverage driver is available.
The code that is executed by a PHPT test is attributed to the PHPT file, and the test is treated as a large test with regard to the test size filter of the code coverage reports.

Two things are different when code coverage is collected for a PHPT test:

- The code from the ``--FILE--`` section is written to a temporary file next to the PHPT file, and the collected code coverage data is stored in a second temporary file next to the PHPT file. Both files are deleted after the test has been run. A PHPT test is not run when the file for the code coverage data already exists, as this indicates that a previous test run was aborted.
- Composer's autoloader as well as the bootstrap script that is configured for the test run are loaded in the child process before the code from the ``--FILE--`` section is executed. Keep this in mind when a PHPT test verifies behaviour that depends on which code is loaded.


.. _appendixes.phpt.repeating-and-retrying:

Repeating and Retrying
======================

PHPT tests can be repeated using the ``--repeat`` CLI option and retried using the ``--retry`` CLI option.
Because attributes cannot be used in a PHPT file, the ``#[Repeat]`` and ``#[Retry]`` attributes have no equivalent for PHPT tests.
This is documented in :ref:`the chapter on flaky tests <flaky-tests.phpt>`.


.. _appendixes.phpt.when-to-use:

When to Use PHPT
================

PHPT is a good fit when

- the subject under test is a command-line tool and the test is an end-to-end test that runs it and checks what it prints,
- the behaviour to be tested only manifests itself in a freshly started PHP process, for instance because it depends on ``php.ini`` settings, on the state of the process, or on a fatal error, or
- an existing PHPT test suite, for instance one that was written for PHP's own test runner, should be run using PHPUnit.

PHPT is not a good fit for the majority of tests:

- A PHPT test compares the entire output of a process with an expected output. This makes the test sensitive to changes that are unrelated to what it verifies, and it makes the intent of the test harder to see than a focused assertion does.
- The infrastructure that PHPUnit provides for writing tests (fixtures, test doubles, data providers, dependencies between tests, attributes, and the assertions documented in :ref:`appendixes.assertions`) is not available in a PHPT test.
- Starting a PHP process for each test is significantly slower than running a test method in the process that runs PHPUnit.

Use PHPT for the tests that need it, and tests that are implemented as methods of test classes for everything else.
