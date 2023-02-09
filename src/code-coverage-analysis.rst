

.. _code-coverage-analysis:

**********************
Code Coverage Analysis
**********************

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
by the `Xdebug <https://xdebug.org/>`_ or `PCOV <https://github.com/krakjoe/pcov>`_
extensions for PHP.

.. admonition:: Note

   If you see a warning while running tests that no code coverage driver is
   available, it means that you are using the PHP CLI binary (``php``) and do not
   have Xdebug or PCOV loaded.

.. admonition:: Note

   When you want to use Xdebug for the collection of code coverage data then you
   have to active Xdebug's `coverage <https://xdebug.org/docs/code_coverage#mode>`_
   mode.

PHPUnit can generate an HTML-based code coverage report as well as
XML-based logfiles with code coverage information in various formats
(Clover, Cobertura, Crap4J, PHPUnit). Code coverage information can also be reported
as text (and printed to STDOUT) and exported as PHP code for further
processing.

Please refer to :ref:`textui` for a list of command line switches
that control code coverage functionality as well as
:ref:`appendixes.configuration.logging` for the relevant
configuration settings.

.. _code-coverage-analysis.metrics:

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

.. _code-coverage-analysis.including-files:

Including Files
===============

It is mandatory to configure a filter for telling
PHPUnit which sourcecode files to include in the code coverage report.
This can either be done using the ``--coverage-filter``
:ref:`command line <textui.clioptions>` option or via the
configuration file (see :ref:`appendixes.configuration.coverage.include`).

The ``includeUncoveredFiles`` configuration setting is available to configure how the filter is used:

- ``includeUncoveredFiles="false"`` means that only files that have at least one line of executed code are included in the code coverage report

- ``includeUncoveredFiles="true"`` (default) means that all files are included in the code coverage report even if not a single line of code of such a file is executed

.. _code-coverage-analysis.ignoring-code-blocks:

Ignoring Code Blocks
====================

Sometimes you have blocks of code that you cannot test and that you may
want to ignore during code coverage analysis. PHPUnit lets you do this
using the ``@codeCoverageIgnore``,
``@codeCoverageIgnoreStart`` and
``@codeCoverageIgnoreEnd`` annotations as shown in
:numref:`code-coverage-analysis.ignoring-code-blocks.examples.Sample.php`.

.. code-block:: php
    :caption: Using the ``@codeCoverageIgnore``, ``@codeCoverageIgnoreStart`` and ``@codeCoverageIgnoreEnd`` annotations
    :name: code-coverage-analysis.ignoring-code-blocks.examples.Sample.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

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

The ignored lines of code (marked as ignored using the annotations)
are counted as executed (if they are executable) and will not be
highlighted.

.. _code-coverage-analysis.specifying-covered-parts:

Specifying Covered Code Parts
=============================

The ``@covers`` annotation (see the
:ref:`annotation documentation <appendixes.annotations.covers.tables.annotations>`)
can be used in the test code to specify which code parts a test class
(or test method) wants to test. If provided, this effectively filters the
code coverage report to include executed code from the referenced code parts only.
:numref:`code-coverage-analysis.specifying-covered-parts.examples.InvoiceTest.php`
shows an example.


.. admonition:: Note

    If a method is specified with the ``@covers`` annotation, only the
    referenced method will be considered as covered, but not methods called
    by this method.
    Hence, when a covered method is refactored using the *extract method*
    refactoring, corresponding ``@covers`` annotations need to be added.
    This is the reason it is recommended to use this annotation with class scope,
    not with method scope.

.. code-block:: php
    :caption: Test class that specifies which class it wants to cover
    :name: code-coverage-analysis.specifying-covered-parts.examples.InvoiceTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    /**
     * @covers \Invoice
     * @uses \Money
     */
    final class InvoiceTest extends TestCase
    {
        private $invoice;

        protected function setUp(): void
        {
            $this->invoice = new Invoice;
        }

        public function testAmountInitiallyIsEmpty(): void
        {
            $this->assertEquals(new Money, $this->invoice->getAmount());
        }
    }

.. code-block:: php
    :caption: Tests that specify which method they want to cover
    :name: code-coverage-analysis.specifying-covered-parts.examples.BankAccountTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class BankAccountTest extends TestCase
    {
        private $ba;

        protected function setUp(): void
        {
            $this->ba = new BankAccount;
        }

        /**
         * @covers \BankAccount::getBalance
         */
        public function testBalanceIsInitiallyZero(): void
        {
            $this->assertSame(0, $this->ba->getBalance());
        }

        /**
         * @covers \BankAccount::withdrawMoney
         */
        public function testBalanceCannotBecomeNegative(): void
        {
            try {
                $this->ba->withdrawMoney(1);
            }

            catch (BankAccountException $e) {
                $this->assertSame(0, $this->ba->getBalance());

                return;
            }

            $this->fail();
        }

        /**
         * @covers \BankAccount::depositMoney
         */
        public function testBalanceCannotBecomeNegative2(): void
        {
            try {
                $this->ba->depositMoney(-1);
            }

            catch (BankAccountException $e) {
                $this->assertSame(0, $this->ba->getBalance());

                return;
            }

            $this->fail();
        }

        /**
         * @covers \BankAccount::getBalance
         * @covers \BankAccount::depositMoney
         * @covers \BankAccount::withdrawMoney
         */
        public function testDepositWithdrawMoney(): void
        {
            $this->assertSame(0, $this->ba->getBalance());
            $this->ba->depositMoney(1);
            $this->assertSame(1, $this->ba->getBalance());
            $this->ba->withdrawMoney(1);
            $this->assertSame(0, $this->ba->getBalance());
        }
    }

It is also possible to specify that a test should not cover
*any* method by using the
``@coversNothing`` annotation (see
:ref:`appendixes.annotations.coversNothing`). This can be
helpful when writing integration tests to make sure you only
generate code coverage with unit tests.

.. code-block:: php
    :caption: A test that specifies that no method should be covered
    :name: code-coverage-analysis.specifying-covered-parts.examples.GuestbookIntegrationTest.php

    <?php declare(strict_types=1);
    use PHPUnit\DbUnit\TestCase

    final class GuestbookIntegrationTest extends TestCase
    {
        /**
         * @coversNothing
         */
        public function testAddEntry(): void
        {
            $guestbook = new Guestbook();
            $guestbook->addEntry("suzy", "Hello world!");

            $queryTable = $this->getConnection()->createQueryTable(
                'guestbook', 'SELECT * FROM guestbook'
            );

            $expectedTable = $this->createFlatXmlDataSet("expectedBook.xml")
                                  ->getTable("guestbook");

            $this->assertTablesEqual($expectedTable, $queryTable);
        }
    }

.. _code-coverage-analysis.edge-cases:

Edge Cases
==========

This section shows noteworthy edge cases that lead to confusing code
coverage information.

.. code-block:: php
    :name: code-coverage-analysis.edge-cases.examples.Sample.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    // Because it is "line based" and not statement base coverage
    // one line will always have one coverage status
    if (false) this_function_call_shows_up_as_covered();

    // Due to how code coverage works internally these two lines are special.
    // This line will show up as non executable
    if (false)
        // This line will show up as covered because it is actually the
        // coverage of the if statement in the line above that gets shown here!
        will_also_show_up_as_covered();

    // To avoid this it is necessary that braces are used
    if (false) {
        this_call_will_never_show_up_as_covered();
    }
