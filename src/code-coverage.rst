

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
   have Xdebug or PCOV loaded.

.. admonition:: Note

   When you want to use Xdebug for the collection of code coverage data then you
   have to active Xdebug's `coverage <https://xdebug.org/docs/code_coverage#mode>`_
   mode.

PHPUnit can generate a code coverage report in HTML format as well as
XML-based logfiles with code coverage information in various formats
(Clover, Cobertura, Crap4J, PHPUnit). Code coverage information can also be reported
as text (and printed to STDOUT) and exported as PHP code for further
processing.

Please refer to :ref:`textui` for a list of command-line options
that control code coverage functionality as well as
:ref:`appendixes.configuration.coverage` for the relevant
configuration settings for reporting code coverage.

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

It is mandatory to configure a filter for telling
PHPUnit which sourcecode files to include in the code coverage report.
This can either be done using the ``--coverage-filter``
:ref:`command-line <textui.command-line-options>` option or via the
configuration file (see :ref:`appendixes.configuration.coverage.include`).

The ``includeUncoveredFiles`` configuration setting is available to configure how the filter is used:

- ``includeUncoveredFiles="false"`` means that only files that have at least one line of executed code are included in the code coverage report

- ``includeUncoveredFiles="true"`` (default) means that all files are included in the code coverage report even if not a single line of code of such a file is executed

.. _code-coverage.specifying-covered-parts:

Specifying Covered Code Parts
=============================

The ``PHPUnit\Framework\Attributes\CoversClass`` and ``PHPUnit\Framework\Attributes\CoversFunction``
attributes can be used in the test code to specify which units of code a test class intends to cover.

When these attributes are used on a test case class, code coverage information is only collected for
the listed units of code when the test methods of this test case class are executed.

:numref:`code-coverage.specifying-covered-parts.examples.InvoiceTest.php`
shows an example.

.. code-block:: php
    :caption: Test class that specifies which class it wants to cover
    :name: code-coverage.specifying-covered-parts.examples.InvoiceTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\CoversClass;
    use PHPUnit\Framework\Attributes\UsesClass;
    use PHPUnit\Framework\TestCase;

    #[CoversClass Invoice::class]
    #[UsesClass Money::class]
    final class InvoiceTest extends TestCase
    {
        public function testAmountInitiallyIsEmpty(): void
        {
            $this->assertEquals(new Money, (new Invoice)->amount());
        }
    }

The ``PHPUnit\Framework\Attributes\UsesClass`` and ``PHPUnit\Framework\Attributes\UsesFunction``
attributes can be used to specify units of code that should be ignored for code coverage, but which
are allowed to be used by the code that is covered. This is explained in the section on
:ref:`unintentionally covered code <risky-tests.unintentionally-covered-code>`.

The ``PHPUnit\Framework\Attributes\CoversNothing`` attribute can be used to specify that tests
should not contribute to code coverage at all. This can be helpful when writing integration tests
and to make sure you only generate code coverage with unit tests.

.. code-block:: php
    :caption: A test that specifies that it does not want to contribute to code coverage
    :name: code-coverage.specifying-covered-parts.examples.GuestbookIntegrationTest.php

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

Sometimes you have units of code that you cannot test and that you may
want to ignore during code coverage analysis. PHPUnit lets you do this
using the :ref:`IgnoreClassForCodeCoverage <appendixes.attributes.IgnoreClassForCodeCoverage>`,
:ref:`IgnoreMethodForCodeCoverage <appendixes.attributes.IgnoreMethodForCodeCoverage>`, and
:ref:`IgnoreFunctionForCodeCoverage <appendixes.attributes.IgnoreFunctionForCodeCoverage>` attributes.

... todo ...

As of PHPUnit 10.1, the ``PHPUnit\Framework\Attributes\CodeCoverageIgnore`` attribute and the
``@codeCoverageIgnoreStart`` and ``@codeCoverageIgnoreEnd`` annotations that can be used in
production code are deprecated and you should migrate to the attributes explained above at your
earliest convenience.

The ``PHPUnit\Framework\Attributes\CodeCoverageIgnore`` attribute can be used on the class level
as well as on the method level of production code. The ``@codeCoverageIgnoreStart`` and
``@codeCoverageIgnoreEnd`` annotations can be used inside the body of a method, for instance, to
ignore individual lines of production code:

.. code-block:: php
    :caption: Using the ``CodeCoverageIgnore`` attribute and the ``@codeCoverageIgnoreStart`` and ``@codeCoverageIgnoreEnd`` annotations
    :name: code-coverage.ignoring-code-blocks.examples.example.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
    use PHPUnit\Framework\TestCase;

    #[CodeCoverageIgnore]
    final class Foo
    {
        public function bar(): void
        {
        }
    }

    final class Bar
    {
        #[CodeCoverageIgnore]
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
