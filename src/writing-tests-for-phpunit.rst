


.. _writing-tests-for-phpunit:

*************************
Writing Tests for PHPUnit
*************************

The tests for a class ``Email`` go into a class ``EmailTest``. ``EmailTest`` inherits from ``PHPUnit\Framework\TestCase``.
The test methods are public methods that are named ``test*``.

Alternatively, you can use the ``PHPUnit\Framework\Attributes\Test`` attribute on a method to mark it as a test method. See :ref:`appendixes.attributes.Test` for details.

Inside the test methods, assertion methods such as ``assertSame()`` (see :ref:`appendixes.assertions`) are used to assert that an actual value matches an expected value.

.. literalinclude:: examples/writing-tests-for-phpunit/src/Email.php
   :caption: A class named ``Email`` (declared in ``src/Email.php``)
   :name: writing-tests-for-phpunit.examples.Email.php
   :language: php

.. literalinclude:: examples/writing-tests-for-phpunit/EmailTest.php
   :caption: A test class named ``EmailTest`` (declared in ``tests/EmailTest.php``)
   :name: writing-tests-for-phpunit.examples.EmailTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/writing-tests-for-phpunit/EmailTest.php.out

The rest of this chapter uses ``Email`` and ``EmailTest`` to explain the fundamentals of writing tests for PHPUnit.


.. _writing-tests-for-phpunit.asserting-return-values:

Asserting return values
=======================

A test usually follows the "Arrange, Act, Assert" structure:

- **Arrange**: Set up all necessary preconditions and inputs
- **Act**: Perform the action under test
- **Assert**: Verify that the expected results have occurred

Let us look at ``testCanBeCreatedFromValidEmail()`` from the example shown above through the lens of "Arrange, Act, Assert":

.. code-block:: php

    public function testCanBeCreatedFromValidEmail(): void
    {
        // Arrange
        $string = 'user@example.org';

        // Act
        $email = Email::fromString($string);

        // Assert
        $this->assertSame($string, $email->asString());
    }

The **Arrange** phase sets up the input: the string ``'user@example.org'``.

The **Act** phase calls ``Email::fromString()`` to create an ``Email`` object from this string.

The **Assert** phase uses ``assertSame()`` to verify that the ``Email`` object's string representation matches the original input.

Asserting return values like this is the most common operation in a test method.
See :ref:`appendixes.assertions` for the full list of assertion methods that PHPUnit provides.

Ideally, each test method should only verify one aspect of the system under test.
In ``testCanBeCreatedFromValidEmail()``, however, we technically test two things:
that an ``Email`` object can be constructed from a valid string (the named constructor
does not throw an exception and we do get an ``Email`` object) and that ``asString()``
returns the string representation of the ``Email`` value object. This is a typical
deviation from the norm when testing the **happy path** of a constructor.

The **happy path** is the scenario where everything works as expected: valid input is
provided and the system behaves correctly. Its opposite is the **unhappy path** (also
called the **error path**): the scenario where something goes wrong, for instance when
invalid input is provided and an exception is expected. Testing the happy path of a
constructor often involves verifying both that the object is created successfully and
that it holds the expected state.


.. _writing-tests-for-phpunit.expecting-exceptions:

Expecting exceptions
====================

When you expect an action to raise an exception, the test follows the
"Arrange, Expect, Act" structure. The assertion (expectation) is set up
**before** the action that is expected to throw the exception.

Let us look at ``testCannotBeCreatedFromInvalidEmail()`` from the example shown above
through the lens of "Arrange, Expect, Act":

.. code-block:: php

    public function testCannotBeCreatedFromInvalidEmail(): void
    {
        // Expect
        $this->expectException(InvalidArgumentException::class);

        // Act
        Email::fromString('invalid');
    }

The **Expect** phase tells PHPUnit that the code is expected to throw an ``InvalidArgumentException``.

The **Act** phase calls ``Email::fromString()`` with an invalid email address. If the expected exception is not thrown, the test fails.

The ``expectException()`` method **must** be called before the code that is expected to throw the exception. This is why the structure is "Arrange, Expect, Act" and not "Arrange, Act, Assert".

In addition to the ``expectException()`` method, the ``expectExceptionCode()``, ``expectExceptionMessage()``, and ``expectExceptionMessageMatches()`` methods exist to set up expectations for exceptions raised by the code under test.

.. admonition:: Note

   Note that ``expectExceptionMessage()`` asserts that the ``$actual`` message contains the ``$expected`` message and does not perform an exact string comparison.


.. _writing-tests-for-phpunit.verifying-side-effects:

Verifying side effects
======================

Not everything that needs testing is a return value or an exception.
A method may have side effects: it may call a method on a collaborating object or perform an I/O operation such as writing to a database or to the filesystem.

Sometimes the system under test depends on values returned by a collaborating object.
Test Stubs allow you to control these **indirect inputs**.

Sometimes you need to verify that the system under test calls a method on a collaborating object with the expected arguments.
Mock Objects allow you to verify these **indirect outputs**.

Test Stubs and Mock Objects are discussed in detail in the chapter on :ref:`test-doubles`.


.. _writing-tests-for-phpunit.data-providers:

Data Providers
==============

A test method can accept arbitrary arguments.
These arguments are to be provided by one or more data provider methods (``additionProvider()`` in the example shown below).
The data provider method to be used is specified using the ``PHPUnit\Framework\Attributes\DataProvider`` attribute.

A data provider method must be ``public`` and ``static`` and its name must not start with ``test``.
It must return a value that is `iterable <https://www.php.net/manual/en/language.types.iterable.php>`_, either an array or an object that implements the ``Traversable`` interface.
In each iteration step, it must yield an array.
For each of these arrays, the test method will be called with the contents of the array as its arguments.

.. literalinclude:: examples/writing-tests-for-phpunit/NumericDataSetsTest.php
   :caption: Using a data provider that returns an array of arrays
   :name: writing-tests-for-phpunit.data-providers.examples.NumericDataSetsTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/writing-tests-for-phpunit/NumericDataSetsTest.php.out

It is useful to name each data set with a string key.
Output will be more verbose as it will contain the name of the data set that breaks a test.

.. literalinclude:: examples/writing-tests-for-phpunit/NamedDataSetsTest.php
   :caption: Using a data provider with named data sets
   :name: writing-tests-for-phpunit.data-providers.examples.NamedDataSetsTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/writing-tests-for-phpunit/NamedDataSetsTest.php.out

.. admonition:: Note

    You can make the test output more verbose by defining a sentence and using the test's parameter names as placeholders
    (``$a``, ``$b`` and ``$expected`` in the example above) with the :ref:`TestDox <appendixes.attributes.TestDox>` attribute.
    You can also refer to the name of a named data set with ``$_dataName``.

When a test receives input from both a data provider method and from one or more tests it depends on, the arguments from the data provider will come before the ones from depended-upon tests.
The arguments from depended-upon tests will be the same for each data set.

When a test depends on a test that uses data providers, the depending test will be executed when the test it depends upon is successful for at least one data set.
The result of a test that uses data providers cannot be injected into a depending test.

All data providers are executed before both the call to a before-class method such as ``setUpBeforeClass()`` and the first call to a before-test method such as ``setUp()``.
Because of this, you cannot access any properties of the actual test case object within a data provider.
This also means that no code coverage data is collected while data provider methods are executed.

The data sets provided by a data provider method should only contain (arrays of) scalar values, immutable value objects, or test stubs.
Services or large object graphs should not be created in a data provider method.
Mock objects cannot be created in a data provider method.


.. _writing-tests-for-phpunit.test-dependencies:

Test Dependencies
=================

PHPUnit supports the declaration of explicit dependencies between test methods.
Such dependencies do not define the order in which the test methods are to be executed but they allow the returning of an instance of the test fixture by a producer and passing it to the dependent consumers.

-

  A producer is a test method that yields its unit under test as return value.

-

  A consumer is a test method that depends on one or more producers and their return values.

This example shows how to use the ``PHPUnit\Framework\Attributes\Depends`` attribute to express dependencies between test methods:

.. literalinclude:: examples/writing-tests-for-phpunit/StackTest.php
   :caption: Using the ``Depends`` attribute to express dependencies
   :name: writing-tests-for-phpunit.examples.StackTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/writing-tests-for-phpunit/StackTest.php.out

In the example above, the first test, ``testEmpty()``, creates a new array and asserts that it is empty.
The test then returns the fixture as its result.
The second test, ``testPush()``, depends on ``testEmpty()`` and is passed the result of that depended-upon test as its argument.
Finally, ``testPop()`` depends upon ``testPush()``.

.. admonition:: Note

   The return value yielded by a producer is passed "as-is" to its consumers by default.
   This means that when a producer returns an object, a reference to that object is passed to the consumers.
   Instead of a reference either (a) a (deep) copy via ``DependsUsingDeepClone``, or (b) a (normal shallow) clone (based on PHP keyword ``clone``) via ``DependsUsingShallowClone`` are possible, too.

To localize defects, we want our attention to be focussed on relevant failing tests.
This is why PHPUnit skips the execution of a test when a depended-upon test has failed.
This improves defect localization by exploiting the dependencies between tests as shown in :numref:`writing-tests-for-phpunit.examples.DependencyFailureTest.php`.

.. literalinclude:: examples/writing-tests-for-phpunit/DependencyFailureTest.php
   :caption: Leveraging the dependencies between tests
   :name: writing-tests-for-phpunit.examples.DependencyFailureTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/writing-tests-for-phpunit/DependencyFailureTest.php.out

A test may have more than one test dependency attribute.

By default, PHPUnit does not change the order in which tests are executed, so you have to ensure that the dependencies of a test can actually be met before the test is run.

A test that has more than one test dependency attribute will get a fixture from the first producer as the first argument, a fixture from the second producer as the second argument, and so on.


.. _writing-tests-for-phpunit.incomplete-tests:

Incomplete Tests
================

When you are working on a new test case class, you might want to begin by writing empty test methods such as:

.. code-block:: php

    public function testSomething(): void
    {
    }

to keep track of the tests that you have to write.

The problem with empty test methods is that they cannot fail and may be misinterpreted as a success.
This misinterpretation leads to the test reports being useless: you cannot see whether a test is actually successful or just not implemented yet.

Calling ``$this->assertTrue(false)``, for instance, in the unfinished test method does not help either, since then the test will be interpreted as a failure.
This would be just as wrong as interpreting an unimplemented test as a success.

If we think of a successful test as a green light and a test failure as a red light, then we need an additional yellow light to mark a test as being incomplete or not yet implemented.

By calling the method ``markTestIncomplete()`` in a test method, we can mark the test as incomplete:

.. literalinclude:: examples/writing-tests-for-phpunit/WorkInProgressTest.php
   :caption: Marking a test as incomplete
   :name: writing-tests-for-phpunit.incomplete-tests.examples.WorkInProgressTest.php
   :language: php

An incomplete test is denoted by an ``I`` in the output of the PHPUnit command-line test runner, as shown in the following example:

.. literalinclude:: examples/writing-tests-for-phpunit/WorkInProgressTest.php.out


.. _writing-tests-for-phpunit.skipping-tests:

Skipping tests
==============

Not all tests can be run in every environment.
Consider, for instance, a database abstraction layer that has several drivers for the different database systems it supports.
The tests for the PostgreSQL driver can only be run if a PostgreSQL server is available.

:numref:`writing-tests-for-phpunit.skipping-tests.examples.DatabaseTest.php` shows a test case class, ``DatabaseTest``, that contains one test method, ``testConnection()``.
In the test case class' ``setUp()`` template method we check whether the pgsql extension is available and use the ``markTestSkipped()`` method to skip the test if it is not.

.. literalinclude:: examples/writing-tests-for-phpunit/DatabaseTest.php
   :caption: Skipping a test
   :name: writing-tests-for-phpunit.skipping-tests.examples.DatabaseTest.php
   :language: php

A test that has been skipped is denoted by an ``S`` in the output of the PHPUnit command-line test runner, as shown in the following example:

.. literalinclude:: examples/writing-tests-for-phpunit/DatabaseTest.php.out


.. _writing-tests-for-phpunit.skipping-tests.skipping-tests-using-attributes:


Skipping tests using attributes
-------------------------------

In addition to using the ``markTestSkipped()`` method it is also possible to use attributes to express common preconditions for a test case:

* ``RequiresPhp(string $versionRequirement)`` skips the test when the PHP version does not match the specified one
* ``RequiresPhpExtension(string $extension, ?string $versionRequirement)`` skips the test when the specified PHP extension is not available
* ``RequiresSetting(string $setting, string $value)`` skips the test when the specified PHP configuration setting is not set to the specified value
* ``RequiresOperatingSystem(string $regularExpression)`` skips the test when the operating system's name does not match the specified regular expression
* ``RequiresOperatingSystemFamily(string $operatingSystemFamily)`` skips the test when the operating system's family is not the specified one
* ``RequiresMethod(string $className, string $functionName)`` skips the test when no method with the specified name is declared
* ``RequiresFunction(string $functionName)`` skips the test when no function with the specified name is declared
* ``RequiresPhpunit(string $versionRequirement)`` skips the test when the PHPUnit version does not match the specified one

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



.. _writing-tests-for-phpunit.testing-output:

Testing output
==============

Sometimes you want to assert that the execution of a method, for instance, generates an expected output (via ``echo`` or ``print``, for example).
PHPUnit uses PHP's `Output Buffering <http://www.php.net/manual/en/ref.outcontrol.php>`_ feature to provide the functionality that is necessary for this.

:numref:`writing-tests-for-phpunit.testing-output.examples.OutputTest.php` shows how to use the ``expectOutputString()`` method to set the expected output.
If this expected output is not generated, the test will be counted as a failure.

.. literalinclude:: examples/writing-tests-for-phpunit/OutputTest.php
   :caption: Testing the output of a function or method
   :name: writing-tests-for-phpunit.testing-output.examples.OutputTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/writing-tests-for-phpunit/OutputTest.php.out

The ``expectOutputRegex()`` method can be used instead of ``expectOutputString()`` when you want to
match the expected output against a regular expression:

.. code-block:: php

    $this->expectOutputRegex('/foo .+ bar/');

If the generated output does not match the regular expression, the test will be counted as a failure.


.. _writing-tests-for-phpunit.testing-error-log:

Testing error log output
========================

Sometimes you want to assert that the code under test calls PHP's ``error_log()`` function.
PHPUnit captures ``error_log()`` output during test execution.

The ``expectErrorLog()`` method can be used to expect that ``error_log()`` is called at least once
during the test. If ``error_log()`` is not called, the test will be counted as a failure.

.. code-block:: php
    :caption: Testing that error_log() is called

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ErrorLogTest extends TestCase
    {
        public function testSomethingIsLogged(): void
        {
            // Code under test that calls error_log()
            error_log('something happened');

            $this->expectErrorLog();
        }
    }

When ``expectErrorLog()`` is not used and the code under test calls ``error_log()``, the logged
output is printed as part of the test output (with date prefixes stripped).

.. admonition:: Note

   The ``expectErrorLog()`` method must be called during the test method, but it does not
   matter whether it is called before or after the code that calls ``error_log()``. This
   is consistent with how ``expectOutputString()`` works.
