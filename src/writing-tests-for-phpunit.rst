

.. _writing-tests-for-phpunit:

=========================
Writing Tests for PHPUnit
=========================

:numref:`writing-tests-for-phpunit.examples.StackTest.php` shows
how we can write tests using PHPUnit that exercise PHP's array operations.
The example introduces the basic conventions and steps for writing tests
with PHPUnit:

#.

   The tests for a class ``Class`` go into a class ``ClassTest``.

#.

   ``ClassTest`` inherits (most of the time) from ``PHPUnit\Framework\TestCase``.

#.

   The tests are public methods that are named ``test*``.

   Alternatively, you can use the ``@test`` annotation in a method's docblock to mark it as a test method.

#.

   Inside the test methods, assertion methods such as ``assertSame()`` (see :ref:`appendixes.assertions`) are used to assert that an actual value matches an expected value.

.. code-block:: php
    :caption: Testing array operations with PHPUnit
    :name: writing-tests-for-phpunit.examples.StackTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class StackTest extends TestCase
    {
        public function testPushAndPop(): void
        {
            $stack = [];
            $this->assertSame(0, count($stack));

            array_push($stack, 'foo');
            $this->assertSame('foo', $stack[count($stack)-1]);
            $this->assertSame(1, count($stack));

            $this->assertSame('foo', array_pop($stack));
            $this->assertSame(0, count($stack));
        }
    }

|
    *Martin Fowler*:

    Whenever you are tempted to type something into a
    ``print`` statement or a debugger expression, write it
    as a test instead.

.. _writing-tests-for-phpunit.test-dependencies:

Test Dependencies
#################

    *Adrian Kuhn et. al.*:

    Unit Tests are primarily written as a good practice to help developers
    identify and fix bugs, to refactor code and to serve as documentation
    for a unit of software under test. To achieve these benefits, unit tests
    ideally should cover all the possible paths in a program. One unit test
    usually covers one specific path in one function or method. However a
    test method is not necessarily an encapsulated, independent entity. Often
    there are implicit dependencies between test methods, hidden in the
    implementation scenario of a test.

PHPUnit supports the declaration of explicit dependencies between test
methods. Such dependencies do not define the order in which the test
methods are to be executed but they allow the returning of an instance of
the test fixture by a producer and passing it to the dependent consumers.

-

  A producer is a test method that yields its unit under test as return value.

-

  A consumer is a test method that depends on one or more producers and their return values.

:numref:`writing-tests-for-phpunit.examples.StackTest2.php` shows
how to use the ``@depends`` annotation to express
dependencies between test methods.

.. code-block:: php
    :caption: Using the ``@depends`` annotation to express dependencies
    :name: writing-tests-for-phpunit.examples.StackTest2.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class StackTest extends TestCase
    {
        public function testEmpty(): array
        {
            $stack = [];
            $this->assertEmpty($stack);

            return $stack;
        }

        /**
         * @depends testEmpty
         */
        public function testPush(array $stack): array
        {
            array_push($stack, 'foo');
            $this->assertSame('foo', $stack[count($stack)-1]);
            $this->assertNotEmpty($stack);

            return $stack;
        }

        /**
         * @depends testPush
         */
        public function testPop(array $stack): void
        {
            $this->assertSame('foo', array_pop($stack));
            $this->assertEmpty($stack);
        }
    }

In the example above, the first test, ``testEmpty()``,
creates a new array and asserts that it is empty. The test then returns
the fixture as its result. The second test, ``testPush()``,
depends on ``testEmpty()`` and is passed the result of that
depended-upon test as its argument. Finally, ``testPop()``
depends upon ``testPush()``.

.. admonition:: Note

   The return value yielded by a producer is passed "as-is" to its
   consumers by default. This means that when a producer returns an object,
   a reference to that object is passed to the consumers. Instead of
   a reference either (a) a (deep) copy via ``@depends clone``, or (b) a
   (normal shallow) clone (based on PHP keyword ``clone``) via
   ``@depends shallowClone`` are possible too.

To localize defects, we want our attention to be focussed on
relevant failing tests. This is why PHPUnit skips the execution of a test
when a depended-upon test has failed. This improves defect localization by
exploiting the dependencies between tests as shown in
:numref:`writing-tests-for-phpunit.examples.DependencyFailureTest.php`.

.. code-block:: php
    :caption: Exploiting the dependencies between tests
    :name: writing-tests-for-phpunit.examples.DependencyFailureTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class DependencyFailureTest extends TestCase
    {
        public function testOne(): void
        {
            $this->assertTrue(false);
        }

        /**
         * @depends testOne
         */
        public function testTwo(): void
        {
        }
    }

.. parsed-literal::

    $ phpunit --verbose DependencyFailureTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    FS

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) DependencyFailureTest::testOne
    Failed asserting that false is true.

    /home/sb/DependencyFailureTest.php:6

    There was 1 skipped test:

    1) DependencyFailureTest::testTwo
    This test depends on "DependencyFailureTest::testOne" to pass.

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1, Skipped: 1.

A test may have more than one ``@depends`` annotation.
PHPUnit does not change the order in which tests are executed, you have to
ensure that the dependencies of a test can actually be met before the test
is run.

A test that has more than one ``@depends`` annotation
will get a fixture from the first producer as the first argument, a fixture
from the second producer as the second argument, and so on.
See :numref:`writing-tests-for-phpunit.examples.MultipleDependencies.php`

.. code-block:: php
    :caption: Test with multiple dependencies
    :name: writing-tests-for-phpunit.examples.MultipleDependencies.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class MultipleDependenciesTest extends TestCase
    {
        public function testProducerFirst(): string
        {
            $this->assertTrue(true);

            return 'first';
        }

        public function testProducerSecond(): string
        {
            $this->assertTrue(true);

            return 'second';
        }

        /**
         * @depends testProducerFirst
         * @depends testProducerSecond
         */
        public function testConsumer(string $a, string $b): void
        {
            $this->assertSame('first', $a);
            $this->assertSame('second', $b);
        }
    }

.. parsed-literal::

    $ phpunit --verbose MultipleDependenciesTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    ...

    Time: 0 seconds, Memory: 3.25Mb

    OK (3 tests, 4 assertions)

.. _writing-tests-for-phpunit.data-providers:

Data Providers
##############

A test method can accept arbitrary arguments. These arguments are to be
provided by one or more data provider methods (``additionProvider()`` in
:numref:`writing-tests-for-phpunit.data-providers.examples.DataTest.php`).
The data provider method to be used is specified using the
``@dataProvider`` annotation.

A data provider method must be ``public`` and either return
an array of arrays or an object that implements the ``Iterator``
interface and yields an array for each iteration step. For each array that
is part of the collection the test method will be called with the contents
of the array as its arguments.

.. code-block:: php
    :caption: Using a data provider that returns an array of arrays
    :name: writing-tests-for-phpunit.data-providers.examples.DataTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class DataTest extends TestCase
    {
        /**
         * @dataProvider additionProvider
         */
        public function testAdd(int $a, int $b, int $expected): void
        {
            $this->assertSame($expected, $a + $b);
        }

        public function additionProvider(): array
        {
            return [
                [0, 0, 0],
                [0, 1, 1],
                [1, 0, 1],
                [1, 1, 3]
            ];
        }
    }

.. parsed-literal::

    $ phpunit DataTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    ...F

    Time: 0 seconds, Memory: 5.75Mb

    There was 1 failure:

    1) DataTest::testAdd with data set #3 (1, 1, 3)
    Failed asserting that 2 is identical to 3.

    /home/sb/DataTest.php:9

    FAILURES!
    Tests: 4, Assertions: 4, Failures: 1.

When using a large number of datasets it's useful to name each one with string key instead of default numeric.
Output will be more verbose as it'll contain that name of a dataset that breaks a test.

.. code-block:: php
    :caption: Using a data provider with named datasets
    :name: writing-tests-for-phpunit.data-providers.examples.DataTest1.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class DataTest extends TestCase
    {
        /**
         * @dataProvider additionProvider
         */
        public function testAdd(int $a, int $b, int $expected): void
        {
            $this->assertSame($expected, $a + $b);
        }

        public function additionProvider(): array
        {
            return [
                'adding zeros'  => [0, 0, 0],
                'zero plus one' => [0, 1, 1],
                'one plus zero' => [1, 0, 1],
                'one plus one'  => [1, 1, 3]
            ];
        }
    }

.. parsed-literal::

    $ phpunit DataTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    ...F

    Time: 0 seconds, Memory: 5.75Mb

    There was 1 failure:

    1) DataTest::testAdd with data set "one plus one" (1, 1, 3)
    Failed asserting that 2 is identical to 3.

    /home/sb/DataTest.php:9

    FAILURES!
    Tests: 4, Assertions: 4, Failures: 1.

.. code-block:: php
    :caption: Using a data provider that returns an Iterator object
    :name: writing-tests-for-phpunit.data-providers.examples.DataTest2.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class DataTest extends TestCase
    {
        /**
         * @dataProvider additionProvider
         */
        public function testAdd(int $a, int $b, int $expected): void
        {
            $this->assertSame($expected, $a + $b);
        }

        public function additionProvider(): CsvFileIterator
        {
            return new CsvFileIterator('data.csv');
        }
    }

.. parsed-literal::

    $ phpunit DataTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    ...F

    Time: 0 seconds, Memory: 5.75Mb

    There was 1 failure:

    1) DataTest::testAdd with data set #3 ('1', '1', '3')
    Failed asserting that 2 is identical to 3.

    /home/sb/DataTest.php:11

    FAILURES!
    Tests: 4, Assertions: 4, Failures: 1.

.. code-block:: php
    :caption: The CsvFileIterator class
    :name: writing-tests-for-phpunit.data-providers.examples.CsvFileIterator.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class CsvFileIterator implements Iterator
    {
        private $file;
        private $key = 0;
        private $current;

        public function __construct(string $file)
        {
            $this->file = fopen($file, 'r');
        }

        public function __destruct()
        {
            fclose($this->file);
        }

        public function rewind(): void
        {
            rewind($this->file);

            $this->current = fgetcsv($this->file);
            $this->key     = 0;
        }

        public function valid(): bool
        {
            return !feof($this->file);
        }

        public function key(): int
        {
            return $this->key;
        }

        public function current(): array
        {
            return $this->current;
        }

        public function next(): void
        {
            $this->current = fgetcsv($this->file);

            $this->key++;
        }
    }

When a test receives input from both a ``@dataProvider``
method and from one or more tests it ``@depends`` on, the
arguments from the data provider will come before the ones from
depended-upon tests. The arguments from depended-upon tests will be the
same for each data set.
See :numref:`writing-tests-for-phpunit.data-providers.examples.DependencyAndDataProviderCombo.php`

.. code-block:: php
    :caption: Combination of @depends and @dataProvider in same test
    :name: writing-tests-for-phpunit.data-providers.examples.DependencyAndDataProviderCombo.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class DependencyAndDataProviderComboTest extends TestCase
    {
        public function provider(): array
        {
            return [['provider1'], ['provider2']];
        }

        public function testProducerFirst(): string
        {
            $this->assertTrue(true);

            return 'first';
        }

        public function testProducerSecond(): string
        {
            $this->assertTrue(true);

            return 'second';
        }

        /**
         * @depends testProducerFirst
         * @depends testProducerSecond
         * @dataProvider provider
         */
        public function testConsumer(): void
        {
            $this->assertSame(
                ['provider1', 'first', 'second'],
                func_get_args()
            );
        }
    }

.. parsed-literal::

    $ phpunit --verbose DependencyAndDataProviderComboTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    ...F

    Time: 0 seconds, Memory: 3.50Mb

    There was 1 failure:

    1) DependencyAndDataProviderComboTest::testConsumer with data set #1 ('provider2')
    Failed asserting that two arrays are identical.
    --- Expected
    +++ Actual
    @@ @@
    Array &0 (
    -    0 => 'provider1'
    +    0 => 'provider2'
         1 => 'first'
         2 => 'second'
    )
    /home/sb/DependencyAndDataProviderComboTest.php:32

    FAILURES!
    Tests: 4, Assertions: 4, Failures: 1.

.. code-block:: php
    :caption: Using multiple data providers for a single test
    :name: writing-tests-for-phpunit.data-providers.examples2.DataTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class DataTest extends TestCase
    {
        /**
         * @dataProvider additionWithNonNegativeNumbersProvider
         * @dataProvider additionWithNegativeNumbersProvider
         */
        public function testAdd(int $a, int $b, int $expected): void
        {
            $this->assertSame($expected, $a + $b);
        }

        public function additionWithNonNegativeNumbersProvider(): array
        {
            return [
                [0, 1, 1],
                [1, 0, 1],
                [1, 1, 3]
            ];
        }

        public function additionWithNegativeNumbersProvider(): array
        {
            return [
                [-1, 1, 0],
                [-1, -1, -2],
                [1, -1, 0]
            ];
        }
     }

.. parsed-literal::

    $ phpunit DataTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    ..F...                                                              6 / 6 (100%)

    Time: 0 seconds, Memory: 5.75Mb

    There was 1 failure:

    1) DataTest::testAdd with data set #3 (1, 1, 3)
    Failed asserting that 2 is identical to 3.

    /home/sb/DataTest.php:12

    FAILURES!
    Tests: 6, Assertions: 6, Failures: 1.

.. admonition:: Note

   When a test depends on a test that uses data providers, the depending
   test will be executed when the test it depends upon is successful for at
   least one data set. The result of a test that uses data providers cannot
   be injected into a depending test.

.. admonition:: Note

   All data providers are executed before both the call to the ``setUpBeforeClass()``
   static method and the first call to the ``setUp()`` method.
   Because of that you can't access any variables you create there from
   within a data provider. This is required in order for PHPUnit to be able
   to compute the total number of tests.

.. _writing-tests-for-phpunit.exceptions:

Testing Exceptions
##################

:numref:`writing-tests-for-phpunit.exceptions.examples.ExceptionTest.php`
shows how to use the ``expectException()`` method to test
whether an exception is thrown by the code under test.

.. code-block:: php
    :caption: Using the expectException() method
    :name: writing-tests-for-phpunit.exceptions.examples.ExceptionTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ExceptionTest extends TestCase
    {
        public function testException(): void
        {
            $this->expectException(InvalidArgumentException::class);
        }
    }

.. parsed-literal::

    $ phpunit ExceptionTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) ExceptionTest::testException
    Failed asserting that exception of type "InvalidArgumentException" is thrown.

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

In addition to the ``expectException()`` method the
``expectExceptionCode()``,
``expectExceptionMessage()``, and
``expectExceptionMessageMatches()`` methods exist to set up
expectations for exceptions raised by the code under test.

.. admonition:: Note

   Note that ``expectExceptionMessage()`` asserts that the ``$actual``
   message contains the ``$expected`` message and does not perform
   an exact string comparison.

.. _writing-tests-for-phpunit.errors:

Testing PHP Errors, Warnings, and Notices
#########################################

By default, PHPUnit converts PHP errors, warnings, and notices that are
triggered during the execution of a test to an exception. Among other benefits,
this makes it possible to expect that a PHP error, warning, or notice is
triggered in a test as shown in
:numref:`writing-tests-for-phpunit.exceptions.examples.ErrorTest.php`.

.. admonition:: Note

   PHP's ``error_reporting`` runtime configuration can
   limit which errors PHPUnit will convert to exceptions. If you are
   having issues with this feature, be sure PHP is not configured to
   suppress the type of error you are interested in.

.. code-block:: php
    :caption: Expecting PHP errors, warnings, and notices
    :name: writing-tests-for-phpunit.exceptions.examples.ErrorTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ErrorTest extends TestCase
    {
        public function testDeprecationCanBeExpected(): void
        {
            $this->expectDeprecation();

            // Optionally test that the message is equal to a string
            $this->expectDeprecationMessage('foo');

            // Or optionally test that the message matches a regular expression
            $this->expectDeprecationMessageMatches('/foo/');

            \trigger_error('foo', \E_USER_DEPRECATED);
        }

        public function testNoticeCanBeExpected(): void
        {
            $this->expectNotice();

            // Optionally test that the message is equal to a string
            $this->expectNoticeMessage('foo');

            // Or optionally test that the message matches a regular expression
            $this->expectNoticeMessageMatches('/foo/');

            \trigger_error('foo', \E_USER_NOTICE);
        }

        public function testWarningCanBeExpected(): void
        {
            $this->expectWarning();

            // Optionally test that the message is equal to a string
            $this->expectWarningMessage('foo');

            // Or optionally test that the message matches a regular expression
            $this->expectWarningMessageMatches('/foo/');

            \trigger_error('foo', \E_USER_WARNING);
        }

        public function testErrorCanBeExpected(): void
        {
            $this->expectError();

            // Optionally test that the message is equal to a string
            $this->expectErrorMessage('foo');

            // Or optionally test that the message matches a regular expression
            $this->expectErrorMessageMatches('/foo/');

            \trigger_error('foo', \E_USER_ERROR);
        }
    }

When testing code that uses PHP built-in functions such as ``fopen()`` that
may trigger errors it can sometimes be useful to use error suppression while
testing. This allows you to check the return values by suppressing notices
that would lead to an exception raised by PHPUnit's error handler.

.. code-block:: php
    :caption: Testing return values of code that uses PHP Errors
    :name: writing-tests-for-phpunit.exceptions.examples.TriggerErrorReturnValue.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ErrorSuppressionTest extends TestCase
    {
        public function testFileWriting(): void
        {
            $writer = new FileWriter;

            $this->assertFalse(@$writer->write('/is-not-writeable/file', 'stuff'));
        }
    }

    final class FileWriter
    {
        public function write($file, $content)
        {
            $file = fopen($file, 'w');

            if ($file === false) {
                return false;
            }

            // ...
        }
    }

.. parsed-literal::

    $ phpunit ErrorSuppressionTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    .

    Time: 1 seconds, Memory: 5.25Mb

    OK (1 test, 1 assertion)

Without the error suppression the test would fail reporting
``fopen(/is-not-writeable/file): failed to open stream: No such file or directory``.

.. _writing-tests-for-phpunit.output:

Testing Output
##############

Sometimes you want to assert that the execution of a method, for
instance, generates an expected output (via ``echo`` or
``print``, for example). The
``PHPUnit\Framework\TestCase`` class uses PHP's
`Output
Buffering <http://www.php.net/manual/en/ref.outcontrol.php>`_ feature to provide the functionality that is
necessary for this.

:numref:`writing-tests-for-phpunit.output.examples.OutputTest.php`
shows how to use the ``expectOutputString()`` method to
set the expected output. If this expected output is not generated, the
test will be counted as a failure.

.. code-block:: php
    :caption: Testing the output of a function or method
    :name: writing-tests-for-phpunit.output.examples.OutputTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class OutputTest extends TestCase
    {
        public function testExpectFooActualFoo(): void
        {
            $this->expectOutputString('foo');

            print 'foo';
        }

        public function testExpectBarActualBaz(): void
        {
            $this->expectOutputString('bar');

            print 'baz';
        }
    }

.. parsed-literal::

    $ phpunit OutputTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    .F

    Time: 0 seconds, Memory: 5.75Mb

    There was 1 failure:

    1) OutputTest::testExpectBarActualBaz
    Failed asserting that two strings are equal.
    --- Expected
    +++ Actual
    @@ @@
    -'bar'
    +'baz'

    FAILURES!
    Tests: 2, Assertions: 2, Failures: 1.

:numref:`writing-tests-for-phpunit.output.tables.api`
shows the methods provided for testing output

.. rst-class:: table
.. list-table:: Methods for testing output
    :name: writing-tests-for-phpunit.output.tables.api
    :header-rows: 1

    * - Method
      - Meaning
    * - ``void expectOutputRegex(string $regularExpression)``
      - Set up the expectation that the output matches a ``$regularExpression``.
    * - ``void expectOutputString(string $expectedString)``
      - Set up the expectation that the output is equal to an ``$expectedString``.
    * - ``bool setOutputCallback(callable $callback)``
      - Sets up a callback that is used to, for instance, normalize the actual output.
    * - ``string getActualOutput()``
      - Get the actual output.

.. admonition:: Note

   A test that emits output will fail in strict mode.

.. _writing-tests-for-phpunit.error-output:

Error output
############

Whenever a test fails PHPUnit tries its best to provide you with as much
context as possible that can help to identify the problem.

.. code-block:: php
    :caption: Error output generated when an array comparison fails
    :name: writing-tests-for-phpunit.error-output.examples.ArrayDiffTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ArrayDiffTest extends TestCase
    {
        public function testEquality(): void
        {
            $this->assertSame(
                [1, 2,  3, 4, 5, 6],
                [1, 2, 33, 4, 5, 6]
            );
        }
    }

.. parsed-literal::

    $ phpunit ArrayDiffTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.25Mb

    There was 1 failure:

    1) ArrayDiffTest::testEquality
    Failed asserting that two arrays are identical.
    --- Expected
    +++ Actual
    @@ @@
     Array (
         0 => 1
         1 => 2
    -    2 => 3
    +    2 => 33
         3 => 4
         4 => 5
         5 => 6
     )

    /home/sb/ArrayDiffTest.php:7

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

In this example only one of the array values differs and the other values
are shown to provide context on where the error occurred.

When the generated output would be long to read PHPUnit will split it up
and provide a few lines of context around every difference.

.. code-block:: php
    :caption: Error output when an array comparison of an long array fails
    :name: writing-tests-for-phpunit.error-output.examples.LongArrayDiffTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class LongArrayDiffTest extends TestCase
    {
        public function testEquality(): void
        {
            $this->assertSame(
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 2,  3, 4, 5, 6],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 2, 33, 4, 5, 6]
            );
        }
    }

.. parsed-literal::

    $ phpunit LongArrayDiffTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.25Mb

    There was 1 failure:

    1) LongArrayDiffTest::testEquality
    Failed asserting that two arrays are identical.
    --- Expected
    +++ Actual
    @@ @@
         11 => 0
         12 => 1
         13 => 2
    -    14 => 3
    +    14 => 33
         15 => 4
         16 => 5
         17 => 6
     )

    /home/sb/LongArrayDiffTest.php:7

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _writing-tests-for-phpunit.error-output.edge-cases:

Edge Cases
==========

When a comparison fails PHPUnit creates textual representations of the
input values and compares those. Due to that implementation a diff
might show more problems than actually exist.

This only happens when using ``assertEquals()`` or other 'weak' comparison
functions on arrays or objects.

.. code-block:: php
    :caption: Edge case in the diff generation when using weak comparison
    :name: writing-tests-for-phpunit.error-output.edge-cases.examples.ArrayWeakComparisonTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ArrayWeakComparisonTest extends TestCase
    {
        public function testEquality(): void
        {
            $this->assertEquals(
                [1, 2, 3, 4, 5, 6],
                ['1', 2, 33, 4, 5, 6]
            );
        }
    }

.. parsed-literal::

    $ phpunit ArrayWeakComparisonTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.25Mb

    There was 1 failure:

    1) ArrayWeakComparisonTest::testEquality
    Failed asserting that two arrays are equal.
    --- Expected
    +++ Actual
    @@ @@
     Array (
    -    0 => 1
    +    0 => '1'
         1 => 2
    -    2 => 3
    +    2 => 33
         3 => 4
         4 => 5
         5 => 6
     )

    /home/sb/ArrayWeakComparisonTest.php:7

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

In this example the difference in the first index between
``1`` and ``'1'`` is
reported even though ``assertEquals()`` considers the values as a match.


