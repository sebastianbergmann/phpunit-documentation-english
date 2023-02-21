

.. _writing-tests-for-phpunit:

*************************
Writing Tests for PHPUnit
*************************

.. _writing-tests-for-phpunit.return-values:

Asserting Return Values
=======================

:numref:`writing-tests-for-phpunit.examples.GreeterTest.php` shows
how we can write tests using PHPUnit that exercise PHP's array operations.
The example introduces the basic conventions and steps for writing tests
with PHPUnit:

#.

   The tests for a class ``Class`` go into a class ``ClassTest``.

#.

   ``ClassTest`` inherits (most of the time) from ``PHPUnit\Framework\TestCase``.

#.

   The tests are public methods that are named ``test*``.

   Alternatively, you can use the ``PHPUnit\Framework\Attributes\Test`` attribute
   on a method to mark it as a test method. See the section on the
   :ref:`Test <appendixes.attributes.test>` attribute for details.

#.

   Inside the test methods, assertion methods such as ``assertSame()`` (see :ref:`appendixes.assertions`) are used to assert that an actual value matches an expected value, for instance.

.. literalinclude:: examples/GreeterTest.php
   :caption: Testing a return value
   :name: writing-tests-for-phpunit.examples.GreeterTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/GreeterTest.php.out

Martin Fowler once said:

    Whenever you are tempted to type something into a
    ``print`` statement or a debugger expression, write it
    as a test instead.

.. _writing-tests-for-phpunit.exceptions:

Expecting Exceptions
====================

:numref:`writing-tests-for-phpunit.exceptions.examples.ExceptionTest.php`
shows how to use the ``expectException()`` method to test
whether an exception is thrown by the code under test.

.. literalinclude:: examples/ExceptionTest.php
   :caption: Using the expectException() method
   :name: writing-tests-for-phpunit.exceptions.examples.ExceptionTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/ExceptionTest.php.out

In addition to the ``expectException()`` method the
``expectExceptionCode()``,
``expectExceptionMessage()``, and
``expectExceptionMessageMatches()`` methods exist to set up
expectations for exceptions raised by the code under test.

.. admonition:: Note

   Note that ``expectExceptionMessage()`` asserts that the ``$actual``
   message contains the ``$expected`` message and does not perform
   an exact string comparison.

Asserting return values and expecting exceptions are two of the three most commonly performed
operations in a test method. The third is verifying side effects. The verification of side
effects in object collaboration is discussed in the chapter on :ref:`test-doubles`.

.. _writing-tests-for-phpunit.data-providers:

Data Providers
==============

A test method can accept arbitrary arguments. These arguments are to be provided by one or
more data provider methods (``additionProvider()`` in the example shown below). The data
provider method to be used is specified using the ``PHPUnit\Framework\Attributes\DataProvider``
attribute.

A data provider method must be ``public`` and ``static``. It must either return
an array of arrays or an object that implements the ``Iterator``
interface. In each iteration step, it must yield an array. For each of these arrays,
the test method will be called with the contents of the array as its arguments.

.. literalinclude:: examples/NumericDataSetsTest.php
   :caption: Using a data provider that returns an array of arrays
   :name: writing-tests-for-phpunit.data-providers.examples.NumericDataSetsTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/NumericDataSetsTest.php.out

When using a large number of data sets it is useful to name each one with a string key.
Output will be more verbose as it will contain that name of a dataset that breaks a test.

.. literalinclude:: examples/NamedDataSetsTest.php
   :caption: Using a data provider with named data sets
   :name: writing-tests-for-phpunit.data-providers.examples.NamedDataSetsTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/NamedDataSetsTest.php.out

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

.. literalinclude:: examples/OutputTest.php
   :caption: Testing the output of a function or method
   :name: writing-tests-for-phpunit.output.examples.OutputTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/OutputTest.php.out

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

By calling the method ``markTestIncomplete()`` in a test method, we can mark the test
as incomplete:

.. literalinclude:: examples/WorkInProgressTest.php
   :caption: Marking a test as incomplete
   :name: writing-tests-for-phpunit.incomplete-tests.examples.WorkInProgressTest.php
   :language: php

An incomplete test is denoted by an ``I`` in the output
of the PHPUnit command-line test runner, as shown in the following
example:

.. literalinclude:: examples/WorkInProgressTest.php.out

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

.. literalinclude:: examples/DatabaseTest.php
   :caption: Skipping a test
   :name: writing-tests-for-phpunit.skipping-tests.examples.DatabaseTest.php
   :language: php

A test that has been skipped is denoted by an ``S`` in
the output of the PHPUnit command-line test runner, as shown in the
following example:

.. literalinclude:: examples/DatabaseTest.php.out

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

    #[RequiresPhpExtension('pgsql')]
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

Adrian Kuhn et. al. `wrote <https://doi.org/10.1007/978-3-540-68255-4_8>`_:

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

This example shows how to use the ``PHPUnit\Framework\Attributes\Depends`` attribute to express
dependencies between test methods:

.. literalinclude:: examples/StackTest.php
   :caption: Using the ``Depends`` attribute to express dependencies
   :name: writing-tests-for-phpunit.examples.StackTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/StackTest.php.out

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

.. literalinclude:: examples/DependencyFailureTest.php
   :caption: Leveraging the dependencies between tests
   :name: writing-tests-for-phpunit.examples.DependencyFailureTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/DependencyFailureTest.php.out

A test may have more than one test dependency attribute.

By default, PHPUnit does not change the order in which tests are executed,
so you have to ensure that the dependencies of a test can actually be met
before the test is run.

A test that has more than one test dependency attribute will get a fixture
from the first producer as the first argument, a fixture from the second
producer as the second argument, and so on.

.. _writing-tests-for-phpunit.failure-output:

Failure Output
==============

Whenever a test fails, PHPUnit tries its best to provide you with as much
context as possible that can help to identify the problem.

.. literalinclude:: examples/ArrayDiffTest.php
   :caption: Output generated when an array comparison fails
   :name: writing-tests-for-phpunit.failure-output.examples.ArrayDiffTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/ArrayDiffTest.php.out

In this example only one of the array values differs and the other values
are shown to provide context on where the error occurred.

When the generated output would be long to read PHPUnit will split it up
and provide a few lines of context around every difference.

.. literalinclude:: examples/LongArrayDiffTest.php
   :caption: Output when an array comparison of a long array fails
   :name: writing-tests-for-phpunit.failure-output.examples.LongArrayDiffTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/LongArrayDiffTest.php.out

.. _writing-tests-for-phpunit.failure-output.edge-cases:

Edge Cases
----------

When a comparison fails PHPUnit creates textual representations of the
input values and compares those. Due to that implementation a diff
might show more problems than actually exist.

This only happens when using ``assertEquals()`` or other "weak" comparison
functions on arrays or objects.

.. literalinclude:: examples/ArrayWeakComparisonTest.php
   :caption: Edge case in the diff generation when using weak comparison
   :name: writing-tests-for-phpunit.failure-output.edge-cases.examples.ArrayWeakComparisonTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/ArrayWeakComparisonTest.php.out

In this example the difference in the first index between
``1`` and ``'1'`` is
reported even though ``assertEquals()`` considers the values as a match.


