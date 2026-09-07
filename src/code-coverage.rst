


.. _code-coverage:

*************
Code Coverage
*************

    *Wikipedia*:

    In computer science, code coverage is a measure used to describe the
    degree to which the source code of a program is tested by a particular
    test suite. A program with high code coverage has been more thoroughly
    tested and has a lower chance of containing software bugs than a program
    with low code coverage.

In this chapter you will learn all about PHPUnit's code coverage
functionality that provides an insight into what parts of the production
code are executed when the tests are run. It makes use of the
`php-code-coverage <https://github.com/sebastianbergmann/php-code-coverage>`_
library, which in turn leverages the code coverage functionality provided
by the `PCOV <https://github.com/krakjoe/pcov>`_ or `Xdebug <https://xdebug.org/>`_
extensions for PHP.

.. admonition:: Note

   If you see a warning while running tests that no code coverage driver is
   available, it means that you are using the PHP CLI binary (``php``) and do not
   have PCOV or Xdebug loaded.

.. admonition:: Note

   When you want to use Xdebug for the collection of code coverage data then you
   have to activate Xdebug's `coverage <https://xdebug.org/docs/code_coverage#mode>`_
   mode.

.. admonition:: Warning

   Code coverage of ``match`` expression arms is unreliable.
   The entire ``match`` expression may be reported as covered even when not all of its arms were executed by the test suite.
   This is a known limitation caused by how PHP's code coverage drivers (PCOV, Xdebug) report line coverage for ``match`` expressions.
   The accuracy of the reported coverage depends on autoloading runtime behavior and test execution order.

PHPUnit can generate a code coverage report in HTML format as well as
XML-based logfiles with code coverage information in various formats
(Clover, Cobertura, Crap4J, PHPUnit). Code coverage information can also be reported
as text (and printed to STDOUT), in :ref:`JSONL format <code-coverage.jsonl-report>` for
consumption by tooling, and exported as PHP code for further processing.

Please refer to :ref:`textui` for a list of command-line options
that control code coverage functionality as well as
:ref:`appendixes.xml-configuration-file.source` and :ref:`appendixes.xml-configuration-file.coverage`
for the relevant configuration settings for reporting code coverage.

.. _code-coverage.metrics:

Software Metrics for Code Coverage
==================================

Various software metrics exist to measure code coverage:

*Line Coverage*

    The *Line Coverage* software metric measures
    whether each executable line was executed.

*Branch Coverage*

    The *Branch Coverage* software metric measures
    whether the boolean expression of each control structure evaluated
    to both ``true`` and ``false`` while
    running the test suite.

*Path Coverage*

    The *Path Coverage* software metric measures
    whether each of the possible execution paths in a function or method
    has been followed while running the test suite. An execution path is
    a unique sequence of branches from the entry of the function or
    method to its exit.

*Function and Method Coverage*

    The *Function and Method Coverage* software
    metric measures whether each function or method has been invoked.
    php-code-coverage only considers a function or method as covered when
    all of its executable lines are covered.

*Class and Trait Coverage*

    The *Class and Trait Coverage* software metric
    measures whether each method of a class or trait is covered.
    php-code-coverage only considers a class or trait as covered when all
    of its methods are covered.

*Change Risk Anti-Patterns (CRAP) Index*

    The *Change Risk Anti-Patterns (CRAP) Index* is
    calculated based on the cyclomatic complexity and code coverage of a
    unit of code. Code that is not too complex and has an adequate test
    coverage will have a low CRAP index. The CRAP index can be lowered
    by writing tests and by refactoring the code to lower its
    complexity.

The library used by PHPUnit supports all code coverage software metrics listed above.
To report branch coverage and path coverage, code coverage data has to be collected
using Xdebug as PCOV only supports line coverage.

.. _code-coverage.including-files:

Including Files
===============

It is mandatory to configure which source code files you consider your own and therefore
want to be included in the code coverage report. As other features of PHPUnit also need
to know which source code files you consider your own, it is best practice to configure
this in the XML configuration file (see :ref:`appendixes.xml-configuration-file.source.include`).
Alternatively, you may use the ``--coverage-filter`` :ref:`command-line <appendixes.cli-options.code-coverage>`
option.

The ``includeUncoveredFiles`` configuration setting is available to configure how the filter is used:

- ``includeUncoveredFiles="true"`` (default) means that all files are included in the code coverage report even if not a single line of code of such a file is executed

- ``includeUncoveredFiles="false"`` means that only files that have at least one line of executed code are included in the code coverage report

In order to get a complete and honest code coverage report, it is highly recommended to use the default setting.

.. _code-coverage.targeting-units-of-code:

Targeting Units of Code
=======================

The ``PHPUnit\Framework\Attributes\CoversClass``, ``PHPUnit\Framework\Attributes\CoversTrait``,
``PHPUnit\Framework\Attributes\CoversMethod``, ``PHPUnit\Framework\Attributes\CoversFunction``,
``PHPUnit\Framework\Attributes\CoversNamespace``,
``PHPUnit\Framework\Attributes\CoversClassesThatExtendClass``, and
``PHPUnit\Framework\Attributes\CoversClassesThatImplementInterface`` attributes can be used in the
test code to specify which units of code a test class intends to cover. They are documented in
:ref:`the appendix on attributes <appendixes.attributes.CoversClass>`.

When these attributes are used on a test case class, code coverage information is only collected for
the listed units of code when the test methods of this test case class are executed.

:numref:`code-coverage.targeting-units-of-code.examples.InvoiceTest.php`
shows an example.

.. code-block:: php
    :caption: Test class that specifies which class it wants to cover
    :name: code-coverage.targeting-units-of-code.examples.InvoiceTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\CoversClass;
    use PHPUnit\Framework\Attributes\UsesClass;
    use PHPUnit\Framework\TestCase;

    #[CoversClass(Invoice::class)]
    #[UsesClass(Money::class)]
    final class InvoiceTest extends TestCase
    {
        public function testAmountInitiallyIsEmpty(): void
        {
            $this->assertEquals(new Money, (new Invoice)->amount());
        }
    }

The ``PHPUnit\Framework\Attributes\UsesClass``, ``PHPUnit\Framework\Attributes\UsesTrait``,
``PHPUnit\Framework\Attributes\UsesMethod``, ``PHPUnit\Framework\Attributes\UsesFunction``,
``PHPUnit\Framework\Attributes\UsesNamespace``,
``PHPUnit\Framework\Attributes\UsesClassesThatExtendClass``, and
``PHPUnit\Framework\Attributes\UsesClassesThatImplementInterface`` attributes can be used to specify
units of code that should be ignored for code coverage, but which are allowed to be used by the code
that is covered. This is explained in the section on :ref:`unintentionally covered code <risky-tests.unintentionally-covered-code>`.

In the example shown above, the ``#[CoversClass(Invoice::class)]`` attribute tells PHPUnit that
the tests of this test case class intend to cover the code of the ``Invoice`` class. When the
tests of this test case class are run, only code coverage information for the ``Invoice`` class
will be processed and code coverage information for all other code that may also be run while
these tests are running will be ignored.

In the example shown above, the ``#[UsesClass(Money::class)]`` attribute tells PHPUnit that
it is expected and allowed that code from the ``Money`` class is also run while the tests of this
test case class are run. This is important when it comes to considering a test risky when it
runs code that is not expected to be run.

As it is technically not possible to test a subclass in isolation from its base class(es),
the ``#[CoversClass]`` and ``#[UsesClass]`` attributes consider the class whose name has been
specified as well as all of its parent classes, if it has any.

Instead of targeting units of code by the name of a class, method, function, trait, or
namespace, you can target them by their location in the filesystem. The
``PHPUnit\Framework\Attributes\CoversFile``, ``PHPUnit\Framework\Attributes\CoversDirectory``,
and ``PHPUnit\Framework\Attributes\CoversDirectoryRecursively`` attributes can be used to
specify that a test intends to cover the code in the given source code file, in the source
code files located in the given directory, or in the source code files located in the given
directory and its subdirectories, respectively. The
``PHPUnit\Framework\Attributes\UsesFile``, ``PHPUnit\Framework\Attributes\UsesDirectory``,
and ``PHPUnit\Framework\Attributes\UsesDirectoryRecursively`` attributes are their
counterparts for specifying code that is allowed to be executed, but is not intended
to be covered.

.. code-block:: php
    :caption: Test class that specifies which directory it wants to cover
    :name: code-coverage.targeting-units-of-code.examples.DispatcherTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\CoversDirectory;
    use PHPUnit\Framework\TestCase;

    #[CoversDirectory(__DIR__ . '/../src/Dispatcher')]
    final class DispatcherTest extends TestCase
    {
        // ...
    }

Relative paths are resolved based on the current working directory in which PHPUnit is run.
It is therefore recommended to build absolute paths using the ``__DIR__`` constant, as shown
in the example above.

Filesystem targets must be part of the code that is configured to be first-party code using
:ref:`\<source\> <appendixes.xml-configuration-file.source.include>`. When a target is outside
of the configured source, a warning is emitted and the attribute is ignored.

The ``PHPUnit\Framework\Attributes\CoversNothing`` attribute can be used to specify that tests
should not contribute to code coverage at all. This can be helpful when writing integration tests
and to make sure you only generate code coverage with smaller tests.

.. code-block:: php
    :caption: A test that specifies that it does not want to contribute to code coverage
    :name: code-coverage.targeting-units-of-code.examples.GuestbookIntegrationTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\CoversNothing;
    use PHPUnit\Framework\TestCase;

    #[CoversNothing]
    final class IntegrationTest extends TestCase
    {
        public function testRegisteredUserCanLogIn(): void
        {
            // ...
        }
    }

The ``#[CoversNothing]`` attribute can also be used on individual test methods. This is useful
when most tests of a test case class should contribute to code coverage, but a single test method
should be excluded:

.. code-block:: php
    :caption: A test method that does not contribute to code coverage
    :name: code-coverage.targeting-units-of-code.examples.InvoiceTestWithCoversNothing.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\CoversClass;
    use PHPUnit\Framework\Attributes\CoversNothing;
    use PHPUnit\Framework\TestCase;

    #[CoversClass(Invoice::class)]
    final class InvoiceTest extends TestCase
    {
        public function testAmountInitiallyIsEmpty(): void
        {
            // contributes to code coverage for Invoice
        }

        #[CoversNothing]
        public function testIntegrationWithExternalService(): void
        {
            // does not contribute to code coverage
        }
    }

.. _code-coverage.ignoring-code-blocks:

Ignoring Code Blocks
====================

Sometimes you have units of code, or even just individual lines of code, that you cannot test
and that you may want to ignore during code coverage analysis. PHPUnit lets you do this
using the ``@codeCoverageIgnore``, ``@codeCoverageIgnoreStart``, and ``@codeCoverageIgnoreEnd``
annotations that can be used in code comments in production code:

.. code-block:: php
    :caption: Using the ``@codeCoverageIgnore``, ``@codeCoverageIgnoreStart``, and ``@codeCoverageIgnoreEnd`` annotations
    :name: code-coverage.ignoring-code-blocks.examples.example.php

    <?php declare(strict_types=1);
    /**
     * @codeCoverageIgnore
     */
    final class Foo
    {
        public function bar(): void
        {
        }
    }

    final class Bar
    {
        /**
         * @codeCoverageIgnore
         */
        public function foo(): void
        {
        }
    }

    if (false) {
        // @codeCoverageIgnoreStart
        print '*';
        // @codeCoverageIgnoreEnd
    }

    exit; // @codeCoverageIgnore

In the example shown above, the ``@codeCoverageIgnore`` annotation is used to ignore
all code of the ``Foo`` class, all code of the ``Bar::foo()`` method, and the single
line of code with the ``exit;`` statement. The line with the ``print '*';`` statement
is ignored using ``// @codeCoverageIgnoreStart`` and ``// @codeCoverageIgnoreEnd``.

.. _code-coverage.html-report:

The Code Coverage Report in HTML Format
=======================================

The ``--coverage-html <dir>`` CLI option, and the corresponding
:ref:`\<html\> <appendixes.xml-configuration-file.coverage.report.html>` element in the XML
configuration file, generate a browsable code coverage report in HTML format.

This report has two views of the same code coverage data, and links between them:

- The *file view* organizes the code coverage information by directories and files.
  The page for a file shows the source code of that file with line-by-line coverage highlighting.

- The *class view* aggregates the code coverage information by classes, traits, and functions,
  organized by namespace, and has its own pages for namespaces and classes.

Each view has a dashboard that visualizes the code coverage information of the project,
for instance to identify the units of code that are least covered.

Both views are rendered by default. The ``--without-class-view`` and ``--without-file-view``
CLI options, as well as the ``classView`` and ``fileView`` attributes of the ``<html>`` element
in the XML configuration file, can be used to render only one of them. The two views cannot both
be disabled.


.. _code-coverage.html-report.test-size-filter:

Filtering by Test Size
----------------------

The pages that list directories, files, namespaces, classes, traits, and functions have a
``Small``, ``Medium``, ``Large``, and ``All`` filter. Selecting one or more test sizes
recalculates the reported line, method, and class coverage of the listed items considering only
code that was covered by tests of the selected sizes. ``All`` clears the filter.

This is useful, for instance, to see how much of the code under test is covered by small tests
alone, and which parts of it are only covered by larger tests.

The size of a test is declared using the ``#[Small]``, ``#[Medium]``, and ``#[Large]`` attributes
(see :ref:`appendixes.attributes.Small`). Code that was only covered by tests that do not declare
their size is only accounted for in ``All``, and not in any of the size-specific selections.

Filtering happens in the browser and therefore requires JavaScript.


.. _code-coverage.jsonl-report:

The Code Coverage Report in JSONL Format
========================================

The ``--coverage-jsonl <dir>`` CLI option, and the corresponding
:ref:`\<jsonl\> <appendixes.xml-configuration-file.coverage.report.jsonl>` element in the XML
configuration file, generate a code coverage report in JSONL format.

Unlike the other report formats, which are meant to be read in a browser or consumed by a CI
server, this one is meant to be read by tooling that works on the source code itself. It reports
the gap, the code that was not covered, rather than every executable line, and it exports the
test-to-code mapping that PHPUnit collects.

The report is a directory with three files:

.. code-block:: text

    <dir>/meta.json         one JSON object with the schema version and run metadata
    <dir>/coverage.jsonl    one JSON object per source file
    <dir>/tests.jsonl       one JSON object per test

The two ``.jsonl`` files are `JSON Lines <https://jsonlines.org/>`_: one complete JSON object per
line, not pretty-printed, with no enclosing array. Each line can therefore be read on its own,
without parsing the rest of the file, which is what makes ``grep`` a usable tool on these files
(see :ref:`code-coverage.jsonl-report.consuming`).

Two runs over identical code with identical test results produce byte-identical output, apart
from the ``generatedAt`` timestamp in ``meta.json``. Records are sorted, so a diff between two
runs is meaningful.

.. _code-coverage.jsonl-report.meta:

``meta.json``
-------------

.. code-block:: json

    {
        "schemaVersion": 1,
        "generator": "php-code-coverage 14.4.0",
        "generatedAt": "2026-08-17T08:16:48+02:00",
        "sourceRoot": "/path/to/project/src",
        "branchCoverage": true,
        "files": 42,
        "executableLines": 3117,
        "executedLines": 2988
    }

``sourceRoot`` is the longest common prefix of the paths of the files that appear in the report,
and every path in the other two files is relative to it. Note that this is derived from the
covered files, not from the project root: a report covering a single file has that file's
directory as its ``sourceRoot``.

``branchCoverage`` states whether branch coverage was collected at all, so that a consumer knows
whether the absence of a ``branches`` key means "no branch was missed" or "branches were not
measured". It is ``true`` only when the run used ``--branch-coverage`` or ``--path-coverage``.

.. _code-coverage.jsonl-report.coverage:

``coverage.jsonl``
------------------

One record per source file that contains executable code, sorted by path. Formatted here for
legibility; in the file each record is a single line:

.. code-block:: json

    {
        "file": "Util/Color.php",
        "executable": 65,
        "executed": 65,
        "symbols": [
            {
                "name": "PHPUnit\\Util\\Color::colorize",
                "lines": "89-114",
                "state": "partial",
                "branches": {
                    "executed": 12,
                    "total": 12,
                    "uncovered": ["91", "95", "101", "104", "109"]
                }
            }
        ]
    }

.. list-table::
    :header-rows: 1

    * - Key
      - Description
    * - ``file``
      - Path relative to ``sourceRoot``, ``/`` separated on all platforms.
    * - ``executable``
      - Number of executable lines in the file.
    * - ``executed``
      - Number of executable lines that were executed at least once.
    * - ``uncovered``
      - Lines that are executable but were not executed. Omitted when there are none.
    * - ``symbols``
      - The methods and functions in the file, ordered by start line. Omitted when the file contains none.

A file that is fully covered still gets a record, without an ``uncovered`` key. A file with no
executable lines is omitted entirely.

Each symbol has a ``name`` (fully qualified), a ``lines`` range covering its declaration, a
``state``, and optionally ``uncovered`` and ``branches`` keys:

.. list-table::
    :header-rows: 1

    * - ``state``
      - Meaning
    * - ``covered``
      - Every executable line of the symbol was executed and, where branch coverage was collected, every branch was taken.
    * - ``partial``
      - Some but not all executable lines were executed, **or** every line was executed but a branch was never taken.
    * - ``uncovered``
      - No executable line of the symbol was executed.

Line numbers are encoded as ranges: a run of consecutive lines becomes ``"45-52"``, a single line
becomes ``"77"``. These are always strings, never integers, so that a consumer does not have to
handle two types. Ranges within a list are ordered ascending and never overlap. Because uncovered
code is usually contiguous — an untested method, an unreached ``catch`` block — this is
substantially more compact than a line-by-line list.

.. admonition:: Note

   No percentage appears anywhere in this format. The raw ``executable`` and ``executed`` counts
   are reported so that a consumer that needs a ratio can compute one.

.. _code-coverage.jsonl-report.branches:

Branch Information
------------------

When branch coverage was collected, a symbol with more than one basic block also has a
``branches`` key. ``executed`` and ``total`` count basic blocks, and agree with the numbers the
other report formats show. ``uncovered`` lists the lines at which control flow was never taken:
the lines of blocks that were never entered, and the line of the conditional jump for each block
that was entered but has an outgoing edge that was never followed.

The lines under ``branches.uncovered`` are therefore **not** a subset of the file's ``uncovered``
lines. A line can have been executed and still appear there. This is the point of the key: in the
example above, ``PHPUnit\Util\Color::colorize`` has every one of its 65 lines executed and every
one of its 12 blocks entered, yet five of its conditionals were only ever taken one way. Line
coverage alone reports that method as fully covered; ``"state": "partial"`` and the five listed
lines say what is actually still untested.

.. _code-coverage.jsonl-report.tests:

``tests.jsonl``
---------------

One record per test that covered at least one line, sorted by test name:

.. code-block:: json

    {"test":"PHPUnit\\Util\\ColorTest::testColorize#empty string","covers":{"Util/Color.php":["91-92"]}}

``test`` is the test's identifier as recorded during the run. ``covers`` maps each file path,
relative to ``sourceRoot``, to the ranges of lines that this test executed.

This is the same test-to-code mapping that the :ref:`PHPUnit XML report
<appendixes.xml-configuration-file.coverage.report.xml>` records, in a considerably more compact
form. It answers two questions that a coverage percentage cannot: which tests exercise a given
piece of code, and whether a newly written test actually reached the lines it was meant to reach.

``tests.jsonl`` is normally the largest of the three files.

.. _code-coverage.jsonl-report.consuming:

Consuming the Report
--------------------

Because each record is a single line, the report can be queried without a JSON parser. To get the
record for one source file:

.. code-block:: bash

    grep '"file":"Util/Color.php"' coverage.jsonl

To list the tests that cover a given file:

.. code-block:: bash

    grep '"Util/Color.php"' tests.jsonl

With `jq <https://jqlang.org/>`_, to list the symbols that are not fully covered:

.. code-block:: bash

    jq -r 'select(.symbols) | .file as $f | .symbols[]
           | select(.state != "covered") | "\($f) \(.lines) \(.name) \(.state)"' coverage.jsonl

.. _code-coverage.jsonl-report.stability:

Schema Stability
----------------

``meta.json`` carries a ``schemaVersion``. It is incremented only when a change breaks a consumer
that relies on the current version. Adding an optional key is not a breaking change, so a consumer
must tolerate keys it does not know about.


.. _code-coverage.phpcov:

PHPCOV
======

`PHPCOV <https://github.com/sebastianbergmann/phpcov>`_ is a command-line tool for working with serialized code coverage data (``*.cov`` files) produced by PHPUnit.

PHPCOV provides two commands: ``merge`` for merging code coverage data from multiple test runs, and ``patch-coverage`` for analyzing the code coverage of changed lines in a patch.

.. admonition:: Serialization format

   A ``*.cov`` file begins with the version of the serialization format that was used to write it.
   Only files that use the serialization format of the current version of ``phpunit/php-code-coverage`` can be read, merged, and reported on.

   The serialization format was changed, and its version was raised from ``1`` to ``2``, in ``phpunit/php-code-coverage`` 14.3, which is used by PHPUnit 13.3.
   A ``*.cov`` file that was written by an earlier version cannot be read any more, and an error such as the one shown below is reported:

   .. parsed-literal::

       Coverage data was written using serialization format 1 and cannot be read by code that supports serialization format 2

   Such a file has to be recreated by running the tests again.
   Keep this in mind when ``*.cov`` files are cached between builds, or when they are collected from build jobs that use different versions of PHPUnit.


Merging Code Coverage Data
--------------------------

When tests are run in parallel or across separate processes, each run can produce its own serialized code coverage file using the ``--coverage-php`` option of PHPUnit.
The ``phpcov merge`` command merges these files and generates a combined code coverage report.

.. code-block:: bash

    phpunit --coverage-php /tmp/coverage/FooTest.cov --filter FooTest
    phpunit --coverage-php /tmp/coverage/BarTest.cov --filter BarTest

    phpcov merge --html /tmp/coverage-report /tmp/coverage

The ``phpcov merge`` command requires a directory containing ``*.cov`` files as its argument.
At least one report format must be specified:

- ``--clover <file>`` generates a report in Clover XML format
- ``--openclover <file>`` generates a report in OpenClover XML format
- ``--cobertura <file>`` generates a report in Cobertura XML format
- ``--crap4j <file>`` generates a report in Crap4J XML format
- ``--html <directory>`` generates a report in HTML format
- ``--php <file>`` exports serialized code coverage data
- ``--text <file>`` generates a report in text format
- ``--xml <directory>`` generates a report in PHPUnit XML format

Multiple report formats can be generated in a single invocation:

.. code-block:: bash

    phpcov merge --html /tmp/html --openclover /tmp/clover.xml /tmp/coverage

The ``--source <directory>`` option can be used to specify the path to the source code when merging on a different machine than where the code coverage data was collected.

By default, the merge command requires that all ``*.cov`` files were created using the same PHP version, the same code coverage driver, and with matching git information.
These requirements can be relaxed using the following options:

- ``--do-not-require-matching-git-information``
- ``--do-not-require-matching-php-version``
- ``--do-not-require-matching-code-coverage-driver``

Patch Coverage
--------------

The ``phpcov patch-coverage`` command calculates the code coverage for changed lines in a unified diff.
This is useful, for instance, to determine whether the changes in a commit are covered by tests.

.. code-block:: bash

    git diff HEAD~1 > /tmp/patch.txt
    phpunit --coverage-php /tmp/coverage.cov

    phpcov patch-coverage /tmp/coverage.cov /tmp/patch.txt

This command requires a serialized code coverage file (``*.cov``) and a patch file in unified diff format as its arguments.
The ``--path-prefix <prefix>`` option can be used to strip a prefix from paths in the patch file so that they match the paths in the code coverage data.

The exit code indicates the result:

- ``0`` -- all changed executable lines are covered
- ``1`` -- some changed executable lines are not covered
- ``2`` -- no changed executable lines were detected (which may indicate a path mismatch)


.. _code-coverage.limitations:

Limitations
===========

Both Xdebug and PCOV collect code coverage information at the bytecode level, not at the source code level.
When PHP code is executed, it first goes through a compilation phase:
the PHP runtime compiles PHP source code into PHP bytecode, which is then executed.
Optionally, this PHP bytecode can be optimized and cached (with or without JIT).
Both Xdebug and PCOV hook into the execution of this bytecode and track which opcodes are actually executed.

This creates a fundamental mapping problem: the compiled bytecode is not isomorphic to the source code.
When PHP source code is compiled to PHP bytecode, "artificial" branches may be created that do not exist in the original PHP code.

Another critical issue arises from PHP's bytecode optimizer.
PHP's ``opcache.optimization_level`` configuration setting is a bitmask that controls which optimizations are applied to the bytecode.
This leads to a concerning consequence:

The generated bytecode differs depending on which optimizations OPcache performs.
The same PHP source code can be compiled to different PHP bytecode depending on the ``opcache.optimization_level`` setting.
This can make it impossible for coverage data to be mapped back exactly to the source code level.
The code coverage reports are thus a representation of bytecode execution, not of source code logic.
