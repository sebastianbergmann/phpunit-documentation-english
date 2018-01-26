

.. _code-coverage-analysis:

======================
Code Coverage Analysis
======================

    *Wikipedia*:

    In computer science, code coverage is a measure used to describe the
    degree to which the source code of a program is tested by a particular
    test suite. A program with high code coverage has been more thoroughly
    tested and has a lower chance of containing software bugs than a program
    with low code coverage.

In this chapter you will learn all about PHPUnit's code coverage
functionality that provides an insight into what parts of the production
code are executed when the tests are run. It makes use of the
`PHP_CodeCoverage <https://github.com/sebastianbergmann/php-code-coverage>`_
component, which in turn leverages the code coverage functionality provided
by the `Xdebug <http://xdebug.org/>`_ extension for PHP.

.. admonition:: Note

   Xdebug is not distributed as part of PHPUnit. If you receive a notice
   while running tests that the Xdebug extension is not loaded, it means
   that Xdebug is either not installed or not configured properly. Before
   you can use the code coverage analysis features in PHPUnit, you should
   read `the Xdebug installation guide <http://xdebug.org/docs/install>`_.

PHPUnit can generate an HTML-based code coverage report as well as
XML-based logfiles with code coverage information in various formats
(Clover, Crap4J, PHPUnit). Code coverage information can also be reported
as text (and printed to STDOUT) and exported as PHP code for further
processing.

Please refer to :ref:`textui` for a list of commandline switches
that control code coverage functionality as well as :ref:`appendixes.configuration.logging` for the relevant
configuration settings.

.. _code-coverage-analysis.metrics:

Software Metrics for Code Coverage
##################################

Various software metrics exist to measure code coverage:

*Line Coverage*

    The *Line Coverage* software metric measures
    whether each executable line was executed.

*Function and Method Coverage*

    The *Function and Method Coverage* software
    metric measures whether each function or method has been invoked.
    PHP_CodeCoverage only considers a function or method as covered when
    all of its executable lines are covered.

*Class and Trait Coverage*

    The *Class and Trait Coverage* software metric
    measures whether each method of a class or trait is covered.
    PHP_CodeCoverage only considers a class or trait as covered when all
    of its methods are covered.

*Opcode Coverage*

    The *Opcode Coverage* software metric measures
    whether each opcode of a function or method has been executed while
    running the test suite. A line of code usually compiles into more
    than one opcode. Line Coverage regards a line of code as covered as
    soon as one of its opcodes is executed.

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

*Change Risk Anti-Patterns (CRAP) Index*

    The *Change Risk Anti-Patterns (CRAP) Index* is
    calculated based on the cyclomatic complexity and code coverage of a
    unit of code. Code that is not too complex and has an adequate test
    coverage will have a low CRAP index. The CRAP index can be lowered
    by writing tests and by refactoring the code to lower its
    complexity.

.. admonition:: Note

   The *Opcode Coverage*,
   *Branch Coverage*, and
   *Path Coverage* software metrics are not yet
   supported by PHP_CodeCoverage.

.. _code-coverage-analysis.whitelisting-files:

Whitelisting Files
##################

It is mandatory to configure a *whitelist* for telling
PHPUnit which sourcecode files to include in the code coverage report.
This can either be done using the ``--whitelist``
commandline option or via the configuration file (see :ref:`appendixes.configuration.whitelisting-files`).

Optionally, all whitelisted files can be added to the code coverage
report by setting ``addUncoveredFilesFromWhitelist="true"``
in your PHPUnit configuration (see :ref:`appendixes.configuration.whitelisting-files`). This allows the
inclusion of files that are not tested yet at all. If you want to get
information about which lines of such an uncovered file are executable,
for instance, you also need to set
``processUncoveredFilesFromWhitelist="true"`` in your
PHPUnit configuration (see :ref:`appendixes.configuration.whitelisting-files`).

.. admonition:: Note

   Please note that the loading of sourcecode files that is performed when
   ``processUncoveredFilesFromWhitelist="true"`` is set can
   cause problems when a sourcecode file contains code outside the scope of
   a class or function, for instance.

.. _code-coverage-analysis.ignoring-code-blocks:

Ignoring Code Blocks
####################

Sometimes you have blocks of code that you cannot test and that you may
want to ignore during code coverage analysis. PHPUnit lets you do this
using the ``@codeCoverageIgnore``,
``@codeCoverageIgnoreStart`` and
``@codeCoverageIgnoreEnd`` annotations as shown in
:numref:`code-coverage-analysis.ignoring-code-blocks.examples.Sample.php`.

.. code-block:: php
    :caption: Using the ``@codeCoverageIgnore``, ``@codeCoverageIgnoreStart`` and ``@codeCoverageIgnoreEnd`` annotations
    :name: code-coverage-analysis.ignoring-code-blocks.examples.Sample.php

    <?php
    use PHPUnit\Framework\TestCase;

    /**
     * @codeCoverageIgnore
     */
    class Foo
    {
        public function bar()
        {
        }
    }

    class Bar
    {
        /**
         * @codeCoverageIgnore
         */
        public function foo()
        {
        }
    }

    if (false) {
        // @codeCoverageIgnoreStart
        print '*';
        // @codeCoverageIgnoreEnd
    }

    exit; // @codeCoverageIgnore
    ?>

The ignored lines of code (marked as ignored using the annotations)
are counted as executed (if they are executable) and will not be
highlighted.

.. _code-coverage-analysis.specifying-covered-methods:

Specifying Covered Methods
##########################

The ``@covers`` annotation (see
:ref:`appendixes.annotations.covers.tables.annotations`) can be
used in the test code to specify which method(s) a test method wants to
test. If provided, only the code coverage information for the specified
method(s) will be considered.
:numref:`code-coverage-analysis.specifying-covered-methods.examples.BankAccountTest.php`
shows an example.

.. code-block:: php
    :caption: Tests that specify which method they want to cover
    :name: code-coverage-analysis.specifying-covered-methods.examples.BankAccountTest.php

    <?php
    use PHPUnit\Framework\TestCase;

    class BankAccountTest extends TestCase
    {
        protected $ba;

        protected function setUp()
        {
            $this->ba = new BankAccount;
        }

        /**
         * @covers BankAccount::getBalance
         */
        public function testBalanceIsInitiallyZero()
        {
            $this->assertEquals(0, $this->ba->getBalance());
        }

        /**
         * @covers BankAccount::withdrawMoney
         */
        public function testBalanceCannotBecomeNegative()
        {
            try {
                $this->ba->withdrawMoney(1);
            }

            catch (BankAccountException $e) {
                $this->assertEquals(0, $this->ba->getBalance());

                return;
            }

            $this->fail();
        }

        /**
         * @covers BankAccount::depositMoney
         */
        public function testBalanceCannotBecomeNegative2()
        {
            try {
                $this->ba->depositMoney(-1);
            }

            catch (BankAccountException $e) {
                $this->assertEquals(0, $this->ba->getBalance());

                return;
            }

            $this->fail();
        }

        /**
         * @covers BankAccount::getBalance
         * @covers BankAccount::depositMoney
         * @covers BankAccount::withdrawMoney
         */
        public function testDepositWithdrawMoney()
        {
            $this->assertEquals(0, $this->ba->getBalance());
            $this->ba->depositMoney(1);
            $this->assertEquals(1, $this->ba->getBalance());
            $this->ba->withdrawMoney(1);
            $this->assertEquals(0, $this->ba->getBalance());
        }
    }
    ?>

It is also possible to specify that a test should not cover
*any* method by using the
``@coversNothing`` annotation (see
:ref:`appendixes.annotations.coversNothing`). This can be
helpful when writing integration tests to make sure you only
generate code coverage with unit tests.

.. code-block:: php
    :caption: A test that specifies that no method should be covered
    :name: code-coverage-analysis.specifying-covered-methods.examples.GuestbookIntegrationTest.php

    <?php
    use PHPUnit\Framework\TestCase;

    class GuestbookIntegrationTest extends PHPUnit_Extensions_Database_TestCase
    {
        /**
         * @coversNothing
         */
        public function testAddEntry()
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
    ?>

.. _code-coverage-analysis.edge-cases:

Edge Cases
##########

This section shows noteworthy edge cases that lead to confusing code
coverage information.

.. code-block:: php
    :name: code-coverage-analysis.edge-cases.examples.Sample.php

    <?php
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
    ?>


