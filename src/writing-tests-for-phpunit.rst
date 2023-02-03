

.. _writing-tests-for-phpunit:

*************************
Writing Tests for PHPUnit
*************************

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

   Alternatively, you can use the ``PHPUnit\Framework\Attributes\Test`` attribute on a method to mark it as a test method.

#.

   Inside the test methods, assertion methods such as ``assertSame()`` (see :ref:`appendixes.assertions`) are used to assert that an actual value matches an expected value, for instance.

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

.. _writing-tests-for-phpunit.incomplete-tests:

Incomplete Tests
================

When you are working on a new test case class, you might want to begin
by writing empty test methods such as:

.. code-block:: php

    public function testSomething(): void
    {
    }

to keep track of the tests that you have to write.

.. admonition:: Note

    Do yourself a favour and never use pointless names such as
    ``testSomething`` for your test methods.

The problem with empty test methods is that they cannot fail and may be
misinterpreted as a success. This misinterpretation leads to the
test reports being useless -- you cannot see whether a test is actually
successful or just not implemented yet.

Calling ``$this->assertTrue(false)``, for instance, in the unfinished
test method does not help either, since then the test will be interpreted
as a failure. This would be just as wrong as interpreting an unimplemented
test as a success.

If we think of a successful test as a green light and a test failure
as a red light, then we need an additional yellow light to mark a test
as being incomplete or not yet implemented.

:numref:`writing-tests-for-phpunit.incomplete-tests.examples.SampleTest.php`
shows a test case class, ``SampleTest``, that contains one test
method, ``testSomething()``. By calling the method ``markTestIncomplete()`` in
the test method, we mark the test as being incomplete:

.. code-block:: php
    :caption: Marking a test as incomplete
    :name: writing-tests-for-phpunit.incomplete-tests.examples.SampleTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class SampleTest extends TestCase
    {
        public function testSomething(): void
        {
            // Optional: Test anything here, if you want.
            $this->assertTrue(true, 'This should already work.');

            // Stop here and mark this test as incomplete.
            $this->markTestIncomplete(
              'This test has not been implemented yet.'
            );
        }
    }

An incomplete test is denoted by an ``I`` in the output
of the PHPUnit command-line test runner, as shown in the following
example:

.. parsed-literal::

    $ phpunit --display-incomplete SampleTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    I                                                                   1 / 1 (100%)

    Time: 00:00.092, Memory: 8.00 MB

    There was 1 incomplete test:

    1) SampleTest::testSomething
    This test has not been implemented yet.

    /home/sb/SampleTest.php:12

    OK, but some tests have issues!
    Tests: 1, Assertions: 1, Incomplete: 1.

.. _writing-tests-for-phpunit.skipping-tests:

Skipping Tests
==============

Not all tests can be run in every environment. Consider, for instance,
a database abstraction layer that has several drivers for the different
database systems it supports. The tests for the MySQL driver can
only be run if a MySQL server is available.

:numref:`writing-tests-for-phpunit.skipping-tests.examples.DatabaseTest.php`
shows a test case class, ``DatabaseTest``, that contains one test
method, ``testConnection()``. In the test case class'
``setUp()`` template method we check whether the MySQLi
extension is available and use the ``markTestSkipped()``
method to skip the test if it is not.

.. code-block:: php
    :caption: Skipping a test
    :name: writing-tests-for-phpunit.skipping-tests.examples.DatabaseTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class DatabaseTest extends TestCase
    {
        protected function setUp(): void
        {
            if (!extension_loaded('mysqli')) {
                $this->markTestSkipped(
                  'The MySQLi extension is not available.'
                );
            }
        }

        public function testConnection(): void
        {
            // ...
        }
    }

A test that has been skipped is denoted by an ``S`` in
the output of the PHPUnit command-line test runner, as shown in the
following example:

.. parsed-literal::

    $ phpunit --display-skipped SampleTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    S                                                                   1 / 1 (100%)

    Time: 00:00.092, Memory: 8.00 MB

    There was 1 skipped test:

    1) DatabaseTest::testConnection
    This test has not been implemented yet.

    /home/sb/DatabaseTest.php:9

    OK, but some tests have issues!
    Tests: 1, Assertions: 1, Incomplete: 1.

.. _writing-tests-for-phpunit.skipping-tests.skipping-tests-using-attributes:

Skipping Tests using Attributes
-------------------------------

In addition to the above methods it is also possible to use attributes
to express common preconditions for a test case:

* ``RequiresFunction(string $functionName)`` skips the test when no function with the specified name is declared
* ``RequiresMethod(string $className, string $functionName)`` skips the test when no method with the specified name is declared
* ``RequiresOperatingSystem(string $regularExpression)`` skips the test when the operating system's name does not match the specified regular expression
* ``RequiresOperatingSystemFamily(string $operatingSystemFamily)`` skips the test when the operating system's family is not the specified one
* ``RequiresPhp(string $versionRequirement)`` skips the test when the PHP version does not match the specified one
* ``RequiresPhpExtension(string $extension, ?string $versionRequirement)`` skips the test when the specified PHP extension is not available
* ``RequiresPhpunit(string $versionRequirement)`` skips the test when the PHPUnit version does not match the specified one
* ``RequiresSetting(string $setting, string $value)`` skips the test when the specified PHP configuration setting is not set to the specified value

All attributes listed above are declared in the ``PHPUnit\Framework\Attributes`` namespace.

.. code-block:: php
    :caption: Skipping a test using attributes
    :name: writing-tests-for-phpunit.skipping-tests.examples.DatabaseTest.php-attributes

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\RequiresPhpExtension;
    use PHPUnit\Framework\TestCase;

    #[RequiresPhpExtension('mysqli')]
    final class DatabaseTest extends TestCase
    {
        public function testConnection(): void
        {
            // ...
        }
    }

.. _writing-tests-for-phpunit.test-dependencies:

Test Dependencies
=================

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
how to use the ``PHPUnit\Framework\Attributes\Depends`` attribute to express
dependencies between test methods.

.. code-block:: php
    :caption: Using the ``Depends`` attribute to express dependencies
    :name: writing-tests-for-phpunit.examples.StackTest2.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\Depends;
    use PHPUnit\Framework\TestCase;

    final class StackTest extends TestCase
    {
        public function testEmpty(): array
        {
            $stack = [];
            $this->assertEmpty($stack);

            return $stack;
        }

        #[Depends('testEmpty')]
        public function testPush(array $stack): array
        {
            array_push($stack, 'foo');
            $this->assertSame('foo', $stack[count($stack)-1]);
            $this->assertNotEmpty($stack);

            return $stack;
        }

        #[Depends('testPush')]
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
   a reference either (a) a (deep) copy via ``DependsUsingDeepClone``, or (b) a
   (normal shallow) clone (based on PHP keyword ``clone``) via
   ``DependsUsingShallowClone`` are possible, too.

To localize defects, we want our attention to be focussed on
relevant failing tests. This is why PHPUnit skips the execution of a test
when a depended-upon test has failed. This improves defect localization by
exploiting the dependencies between tests as shown in
:numref:`writing-tests-for-phpunit.examples.DependencyFailureTest.php`.

.. code-block:: php
    :caption: Exploiting the dependencies between tests
    :name: writing-tests-for-phpunit.examples.DependencyFailureTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\Depends;
    use PHPUnit\Framework\TestCase;

    final class DependencyFailureTest extends TestCase
    {
        public function testOne(): void
        {
            $this->assertTrue(false);
        }

        #[Depends('testOne')]
        public function testTwo(): void
        {
        }
    }

.. parsed-literal::

    $ phpunit --display-skipped DependencyFailureTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    FS                                                                  2 / 2 (100%)

    Time: 00:00.065, Memory: 8.00 MB

    There was 1 failure:

    1) DependencyFailureTest::testOne
    Failed asserting that false is true.

    /home/sb/DependencyFailureTest.php:9

    --

    There was 1 skipped test:

    1) DependencyFailureTest::testTwo
    This test depends on "DependencyFailureTest::testOne" to pass

    FAILURES!
    Tests: 2, Assertions: 1, Failures: 1, Skipped: 1.

A test may have more than one test dependency attribute.

By default, PHPUnit does not change the order in which tests are executed,
so you have to ensure that the dependencies of a test can actually be met
before the test is run.

A test that has more than one test dependency attribute will get a fixture
from the first producer as the first argument, a fixture from the second
producer as the second argument, and so on.

.. _writing-tests-for-phpunit.data-providers:

Data Providers
==============

A test method can accept arbitrary arguments. These arguments are to be
provided by one or more data provider methods (``additionProvider()`` in
:numref:`writing-tests-for-phpunit.data-providers.examples.DataTest.php`).
The data provider method to be used is specified using the
``PHPUnit\Framework\Attributes\DataProvider`` attribute.

A data provider method must be ``public`` and ``static``. It must either return
an array of arrays or an object that implements the ``Iterator``
interface. In each iteration step, it must yield an array. For each of these arrays,
the test method will be called with the contents of the array as its arguments.

.. code-block:: php
    :caption: Using a data provider that returns an array of arrays
    :name: writing-tests-for-phpunit.data-providers.examples.DataTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\DataProvider;
    use PHPUnit\Framework\TestCase;

    final class DataTest extends TestCase
    {
        #[DataProvider('additionProvider')]
        public function testAdd(int $a, int $b, int $expected): void
        {
            $this->assertSame($expected, $a + $b);
        }

        public static function additionProvider(): array
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

    $ phpunit DataTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    ...F                                                                4 / 4 (100%)

    Time: 00:00.058, Memory: 8.00 MB

    There was 1 failure:

    1) DataTest::testAdd with data set #3
    Failed asserting that 2 is identical to 3.

    /home/sb/DataTest.php:10

    FAILURES!
    Tests: 4, Assertions: 4, Failures: 1.

When using a large number of data sets it is useful to name each one with a string key.
Output will be more verbose as it will contain that name of a dataset that breaks a test.

.. code-block:: php
    :caption: Using a data provider with named datasets
    :name: writing-tests-for-phpunit.data-providers.examples.DataTest1.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\DataProvider;
    use PHPUnit\Framework\TestCase;

    final class DataTest extends TestCase
    {
        #[DataProvider('additionProvider')]
        public function testAdd(int $a, int $b, int $expected): void
        {
            $this->assertSame($expected, $a + $b);
        }

        public static function additionProvider(): array
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

    $ phpunit DataTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    ...F                                                                4 / 4 (100%)

    Time: 00:00.066, Memory: 8.00 MB

    There was 1 failure:

    1) DataTest::testAdd with data set "one plus one"
    Failed asserting that 2 is identical to 3.

    /home/sb/DataTest.php:10

    FAILURES!
    Tests: 4, Assertions: 4, Failures: 1.

.. admonition:: Note

    You can make the test output more verbose by defining a sentence and using the test's parameter names as placeholders
    (``$a``, ``$b`` and ``$expected`` in the example above) with the :ref:`appendixes.annotations.testdox` annotation.
    You can also refer to the name of a named data set with ``$_dataName``.

When a test receives input from both a data provider
method and from one or more tests it depends on, the
arguments from the data provider will come before the ones from
depended-upon tests. The arguments from depended-upon tests will be the
same for each data set.

 When a test depends on a test that uses data providers, the depending
 test will be executed when the test it depends upon is successful for at
 least one data set. The result of a test that uses data providers cannot
 be injected into a depending test.

All data providers are executed before both the call to the ``setUpBeforeClass()``
static method and the first call to the ``setUp()`` method.
Because of that you can't access any variables you create there from
within a data provider. This is required in order for PHPUnit to be able
to compute the total number of tests.

.. _writing-tests-for-phpunit.exceptions:

Testing Exceptions
==================

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

    $ phpunit ExceptionTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 00:00.066, Memory: 8.00 MB

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

.. _writing-tests-for-phpunit.output:

Testing Output
==============

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

    $ phpunit OutputTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    .F

    Time: 00:00.066, Memory: 8.00 MB

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

.. _writing-tests-for-phpunit.error-output:

Failure Output
==============

Whenever a test fails, PHPUnit tries its best to provide you with as much
context as possible that can help to identify the problem.

.. code-block:: php
    :caption: Output generated when an array comparison fails
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

    Time: 00:00.066, Memory: 8.00 MB

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
    :caption: Output when an array comparison of a long array fails
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

    $ phpunit LongArrayDiffTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 00:00.066, Memory: 8.00 MB

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
----------

When a comparison fails PHPUnit creates textual representations of the
input values and compares those. Due to that implementation a diff
might show more problems than actually exist.

This only happens when using ``assertEquals()`` or other "weak" comparison
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

    $ phpunit ArrayWeakComparisonTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 00:00.066, Memory: 8.00 MB

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


