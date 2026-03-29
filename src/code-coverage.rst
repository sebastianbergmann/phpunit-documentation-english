


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
as text (and printed to STDOUT) and exported as PHP code for further
processing.

Please refer to :ref:`textui` for a list of command-line options
that control code coverage functionality as well as
:ref:`appendixes.configuration.source` and :ref:`appendixes.configuration.coverage`
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
this in the XML configuration file (see :ref:`appendixes.configuration.source.include`).
Alternatively, you may use the ``--coverage-filter`` :ref:`command-line <appendixes.cli-options.code-coverage>`
option.

The ``includeUncoveredFiles`` configuration setting is available to configure how the filter is used:

- ``includeUncoveredFiles="true"`` (default) means that all files are included in the code coverage report even if not a single line of code of such a file is executed

- ``includeUncoveredFiles="false"`` means that only files that have at least one line of executed code are included in the code coverage report

In order to get a complete and honest code coverage report, it is highly recommended to use the default setting.

.. _code-coverage.targeting-units-of-code:

Targeting Units of Code
=======================

The ``PHPUnit\Framework\Attributes\CoversClass``, ``PHPUnit\Framework\Attributes\CoversMethod``,
and ``PHPUnit\Framework\Attributes\CoversFunction`` attributes can be used in the test code to
specify which units of code a test class intends to cover.

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

The ``PHPUnit\Framework\Attributes\UsesClass``, ``PHPUnit\Framework\Attributes\UsesMethod``,
and ``PHPUnit\Framework\Attributes\UsesFunction`` attributes can be used to specify units of code
that should be ignored for code coverage, but which are allowed to be used by the code that is
covered. This is explained in the section on :ref:`unintentionally covered code <risky-tests.unintentionally-covered-code>`.

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

.. _code-coverage.phpcov:

PHPCOV
======

`PHPCOV <https://github.com/sebastianbergmann/phpcov>`_ is a command-line tool for working with serialized code coverage data (``*.cov`` files) produced by PHPUnit.

PHPCOV provides two commands: ``merge`` for merging code coverage data from multiple test runs, and ``patch-coverage`` for analyzing the code coverage of changed lines in a patch.

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
