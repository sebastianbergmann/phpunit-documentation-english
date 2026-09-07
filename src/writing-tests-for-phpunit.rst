

.. _writing-tests-for-phpunit:

*************************
Writing Tests for PHPUnit
*************************

A test is a public method of a class that inherits from ``PHPUnit\Framework\TestCase``.
Inside such a method, assertion methods such as ``assertSame()`` (see :ref:`appendixes.assertions`) are used to assert that an actual value matches an expected value.
A test passes when none of its assertions fail, none of its expectations are violated, and no unexpected exception is thrown.

The tests for a class ``Email`` go into a class ``EmailTest``.
The test methods are public methods that are named ``test*``.

Alternatively, you can use the ``PHPUnit\Framework\Attributes\Test`` attribute on a method to mark it as a test method. See :ref:`appendixes.attributes.Test` for details.

This chapter is organised around three questions:

* :ref:`writing-tests-for-phpunit.what-a-test-verifies` covers what a test can verify: return values, exceptions, side effects, output, and error log output.
* :ref:`writing-tests-for-phpunit.parameterizing-and-relating-tests` covers how a test can be run with varying input and how one test can consume the result of another.
* :ref:`writing-tests-for-phpunit.test-outcomes` covers the outcomes a test can have besides passed and failed: incomplete and skipped.

:ref:`writing-tests-for-phpunit.best-practices` summarises the recommendations that follow from these topics.

Creating and disposing of the objects that a test works with is the topic of the chapter on :ref:`fixtures`.


.. _writing-tests-for-phpunit.example-code:

Example Code
============

Most examples in this chapter use a class named ``Email``.
It is introduced now so that we are familiar with it when it appears in the examples.

.. literalinclude:: examples/writing-tests-for-phpunit/src/Email.php
   :caption: A class named ``Email`` (declared in ``src/Email.php``)
   :name: writing-tests-for-phpunit.examples.Email.php
   :language: php

**Explanation:**

* ``Email`` is a ``final`` value object that represents an email address
* ``fromString()`` is a named constructor: it creates an ``Email`` object from a string
* ``asString()`` returns the string representation of the email address
* The constructor is ``private``, so an ``Email`` object can only be created through ``fromString()``
* The constructor validates its argument and throws an ``InvalidArgumentException`` when the string is not a valid email address, which means that an ``Email`` object can never hold an invalid email address

.. literalinclude:: examples/writing-tests-for-phpunit/EmailTest.php
   :caption: A test class named ``EmailTest`` (declared in ``tests/EmailTest.php``)
   :name: writing-tests-for-phpunit.examples.EmailTest.php
   :language: php

**Explanation:**

* ``EmailTest`` is the test class for ``Email``: it inherits from ``PHPUnit\Framework\TestCase`` and its test methods are named ``test*``
* ``testCanBeCreatedFromValidEmail()`` verifies the **happy path**: a valid string yields an ``Email`` object whose string representation is that string
* ``testCannotBeCreatedFromInvalidEmail()`` verifies the **unhappy path**: an invalid string makes ``fromString()`` throw an exception

Running the test shown above yields the output shown below:

.. literalinclude:: examples/writing-tests-for-phpunit/EmailTest.php.out

``Email`` and ``EmailTest`` are the running example for the fundamentals explained in this chapter.
Topics that require a different kind of code, for instance a test that is skipped when a PHP extension is not available, use their own small examples.

**Key Concept: System Under Test**

The **system under test** (SUT) is the code that a test exercises: the ``Email`` class in the examples above.
Everything else in a test method exists to bring the SUT into a known state, to invoke it, and to verify what it did.

**Key Concept: Happy Path and Unhappy Path**

The **happy path** is the scenario where everything works as expected: valid input is provided and the system behaves correctly.
Its opposite is the **unhappy path** (also called the **error path**): the scenario where something goes wrong, for instance when invalid input is provided and an exception is expected.
A unit of code is not adequately tested until both have been covered.


.. _writing-tests-for-phpunit.what-a-test-verifies:

What a Test Verifies
====================

A test can verify a return value, that an exception is raised, that a collaborating object is used in an expected way, that output is generated, or that a message is written to the error log.
The sections below cover these in turn.


.. _writing-tests-for-phpunit.asserting-return-values:

Asserting return values
-----------------------

Asserting return values is the most common operation in a test method.

**Use an assertion when you want to:**

* Verify the value that a method or function returns
* Verify the state of an object after an operation was performed on it
* Verify the contents of a data structure

**Key Concept: Arrange, Act, Assert**

A test that asserts a return value usually follows the "Arrange, Act, Assert" structure:

- **Arrange**: Set up all necessary preconditions and inputs
- **Act**: Perform the action under test
- **Assert**: Verify that the expected results have occurred


.. _writing-tests-for-phpunit.asserting-return-values.walkthrough:

A test, step by step
^^^^^^^^^^^^^^^^^^^^

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

**1. Arrange: setting up the input**

.. code-block:: php

    $string = 'user@example.org';

* **What**: Defines the input that the system under test is exercised with
* **How**: A local variable holds the string so that the same value can be used for the action and for the assertion
* **Why**: Using one variable instead of writing the literal twice makes it impossible for the input and the expected value to drift apart

**2. Act: invoking the system under test**

.. code-block:: php

    $email = Email::fromString($string);

* **What**: Creates an ``Email`` object from the string
* **How**: ``fromString()`` is the named constructor of ``Email``; it validates the string and returns an ``Email`` object
* **Why**: This is the behaviour we want to verify

**3. Assert: verifying the result**

.. code-block:: php

    $this->assertSame($string, $email->asString());

* **What**: Verifies that the ``Email`` object's string representation matches the original input
* **How**: ``assertSame()`` fails the test when the two values are not identical; the expected value is the first argument, the actual value is the second
* **Why**: An ``Email`` object that does not represent the address it was created from would be wrong, even if it was created successfully

See :ref:`appendixes.assertions` for the full list of assertion methods that PHPUnit provides.


.. _writing-tests-for-phpunit.asserting-return-values.failing-assertions:

What happens when an assertion fails
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

An assertion that fails ends the execution of the test method:
the statements that follow the failed assertion, including any further assertions, are not executed.
The first assertion that fails is therefore the only failure that is reported for a test.

.. literalinclude:: examples/writing-tests-for-phpunit/FailedAssertionTest.php
   :caption: A test method with three assertions, the second of which fails
   :name: writing-tests-for-phpunit.examples.FailedAssertionTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/writing-tests-for-phpunit/FailedAssertionTest.php.out

Only two assertions are counted: the third one was never executed.

.. admonition:: Note

    When you want to verify multiple values that belong together, assert the data structure as a whole using a single assertion instead of asserting each value separately.
    The failure of such an assertion shows all values that differ from what was expected, not just the first one.

    When you want each value to be verified and reported independently, use a :ref:`data provider <writing-tests-for-phpunit.data-providers>`.
    Each data set is reported as a separate test, so the failure of one does not hide the result of another.


.. _writing-tests-for-phpunit.asserting-return-values.one-aspect-per-test:

One aspect per test
^^^^^^^^^^^^^^^^^^^

Ideally, each test method should only verify one aspect of the system under test.
In ``testCanBeCreatedFromValidEmail()``, however, we technically test two things:
that an ``Email`` object can be constructed from a valid string (the named constructor
does not throw an exception and we do get an ``Email`` object) and that ``asString()``
returns the string representation of the ``Email`` value object.

This is a typical deviation from the norm when testing the happy path of a constructor:
verifying that an object was created successfully is only meaningful together with
verifying that it holds the expected state.


.. _writing-tests-for-phpunit.expecting-exceptions:

Expecting exceptions
--------------------

**Use an exception expectation when you want to:**

* Verify that invalid input is rejected
* Verify that a precondition or an invariant is enforced
* Verify the unhappy path of the system under test

**Key Concept: Arrange, Expect, Act**

When you expect an action to raise an exception, the test follows the
"Arrange, Expect, Act" structure. The assertion (expectation) is set up
**before** the action that is expected to throw the exception.

This is a consequence of how exceptions work: once the exception has been thrown, the
rest of the test method is not executed, so an expectation that is configured after the
action would never be reached.


.. _writing-tests-for-phpunit.expecting-exceptions.common-use-cases:

Common use cases
^^^^^^^^^^^^^^^^

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

**1. Expect: configuring the expectation**

.. code-block:: php

    $this->expectException(InvalidArgumentException::class);

* **What**: Configures the test to only be successful when an ``InvalidArgumentException`` is thrown
* **How**: We pass the name of the exception class we expect to ``expectException()``
* **Why**: The expectation has to be configured before the action, because the action does not return control to the test method

**2. Act: invoking the system under test**

.. code-block:: php

    Email::fromString('invalid');

* **What**: Calls the named constructor with a string that is not a valid email address
* **How**: The return value is not assigned to a variable because there is none: the call is expected to throw
* **Why**: This is the behaviour we want to verify; the test fails when no exception is thrown, and it also fails when an exception of a different type is thrown

There is no **Arrange** phase in this test because the input is a literal that is used only once.

.. admonition:: Note

   Only put the code that is expected to throw the exception after the call to ``expectException()``.
   When a test method continues after the action, the statements that follow it are not executed and can hide mistakes.
   Prefer one test method per expected exception.


.. _writing-tests-for-phpunit.expecting-exceptions.reference:

Reference
^^^^^^^^^

The methods described below configure expectations for an exception that is raised by the code under test.
They can be combined: every expectation that is configured has to be met for the test to pass.


.. _writing-tests-for-phpunit.expecting-exceptions.reference.expectException:

``expectException()``
"""""""""""""""""""""

Expects that an exception of the specified type is thrown.

.. code-block:: php

    $this->expectException(InvalidArgumentException::class);

    Email::fromString('invalid');

An exception of a subclass of the specified type also satisfies this expectation.


.. _writing-tests-for-phpunit.expecting-exceptions.reference.expectExceptionCode:

``expectExceptionCode()``
"""""""""""""""""""""""""

Expects that the thrown exception has the specified code.

.. code-block:: php

    $this->expectExceptionCode(1);

The code of an exception can be an ``int`` or a ``string``.


.. _writing-tests-for-phpunit.expecting-exceptions.reference.expectExceptionMessageIs:

``expectExceptionMessageIs()``
""""""""""""""""""""""""""""""

Expects that the message of the thrown exception is exactly the specified string.

.. code-block:: php

    $this->expectExceptionMessageIs('"invalid" is not a valid email address');

This method performs an exact string comparison between the expected and the actual exception message.


.. _writing-tests-for-phpunit.expecting-exceptions.reference.expectExceptionMessageIsOrContains:

``expectExceptionMessageIsOrContains()``
""""""""""""""""""""""""""""""""""""""""

Expects that the message of the thrown exception is equal to or contains the specified string.

.. code-block:: php

    $this->expectExceptionMessageIsOrContains('is not a valid email address');

When the expected message is an empty string, this method asserts that the actual exception message is also an empty string.
Otherwise, it asserts that the actual exception message contains the expected message.

.. admonition:: Deprecation: ``expectExceptionMessage()`` is deprecated

   The ``expectExceptionMessage()`` method was deprecated in PHPUnit 13.2 and will be removed in PHPUnit 15.
   Use ``expectExceptionMessageIsOrContains()`` instead.


.. _writing-tests-for-phpunit.expecting-exceptions.reference.expectExceptionMessageMatches:

``expectExceptionMessageMatches()``
"""""""""""""""""""""""""""""""""""

Expects that the message of the thrown exception matches the specified regular expression.

.. code-block:: php

    $this->expectExceptionMessageMatches('/is not a valid email address$/');

Use this when only part of the exception message is stable, for instance because the message embeds a value that varies.


.. _writing-tests-for-phpunit.expecting-exceptions.reference.expectExceptionObject:

``expectExceptionObject()``
"""""""""""""""""""""""""""

Expects an exception based on an example object: the type, the message, and the code of that object are used to configure the expectations.

.. code-block:: php

    $this->expectExceptionObject(new InvalidArgumentException('message', 1));

The call shown above is equivalent to the three calls shown below:

.. code-block:: php

    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('message');
    $this->expectExceptionCode(1);


.. _writing-tests-for-phpunit.verifying-side-effects:

Verifying side effects
----------------------

Not everything that needs testing is a return value or an exception.
A method may have side effects: it may call a method on a collaborating object or perform an I/O operation such as writing to a database or to the filesystem.

**Use a test double when you want to:**

* Control the values that a collaborating object returns to the system under test
* Verify that the system under test calls a collaborating object in the expected way
* Isolate the system under test from a slow, unavailable, or non-deterministic dependency

Consider a class ``Registration`` whose ``register()`` method sends a welcome message through a collaborating object that implements the ``Mailer`` interface shown below:

.. code-block:: php

    interface Mailer
    {
        public function send(Email $recipient, string $subject): bool;
    }

Sometimes the system under test depends on values returned by a collaborating object.
Test Stubs allow you to control these **indirect inputs**:

.. code-block:: php

    $mailer = $this->createStub(Mailer::class);

    $mailer
        ->method('send')
        ->willReturn(false);

    $registration = new Registration($mailer);

    $this->assertFalse($registration->register(Email::fromString('user@example.org')));

Configuring ``send()`` to return ``false`` forces ``Registration`` onto the code path that handles a message that could not be sent, without an actual mail server being involved.

Sometimes you need to verify that the system under test calls a method on a collaborating object with the expected arguments.
Mock Objects allow you to verify these **indirect outputs**:

.. code-block:: php

    $recipient = Email::fromString('user@example.org');

    $mailer = $this->createMock(Mailer::class);

    $mailer
        ->expects($this->once())
        ->method('send')
        ->with($recipient, 'Welcome');

    $registration = new Registration($mailer);

    $registration->register($recipient);

Here the interesting effect of ``register()`` is not its return value but the call it makes to ``send()``.
The mock object records that call and PHPUnit verifies it when the test method has finished.

Test Stubs and Mock Objects, including the concepts of indirect input and indirect output, are discussed in detail in the chapter on :ref:`test-doubles`.


.. _writing-tests-for-phpunit.testing-output:

Testing output
--------------

Sometimes you want to assert that the execution of a method, for instance, generates an expected output (via ``echo`` or ``print``, for example).
PHPUnit uses PHP's `Output Buffering <http://www.php.net/manual/en/ref.outcontrol.php>`_ feature to provide the functionality that is necessary for this.

**Use an output expectation when you want to:**

* Verify the text that a command-line application writes to standard output
* Verify the text that a template or a renderer prints instead of returning

An output expectation applies to everything that the test method prints after the expectation was configured.
It is verified when the test method has finished.


.. _writing-tests-for-phpunit.testing-output.common-use-cases:

Common use cases
^^^^^^^^^^^^^^^^

:numref:`writing-tests-for-phpunit.testing-output.examples.OutputTest.php` shows how to use the ``expectOutputString()`` method to set the expected output.
If this expected output is not generated, the test will be counted as a failure.

.. literalinclude:: examples/writing-tests-for-phpunit/OutputTest.php
   :caption: Testing the output of a function or method
   :name: writing-tests-for-phpunit.testing-output.examples.OutputTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/writing-tests-for-phpunit/OutputTest.php.out

``testExpectFooActualFoo()`` passes because the generated output is the expected output.
``testExpectBarActualBaz()`` fails because it prints ``baz`` while ``bar`` was expected.


.. _writing-tests-for-phpunit.testing-output.reference:

Reference
^^^^^^^^^

.. _writing-tests-for-phpunit.testing-output.reference.expectOutputString:

``expectOutputString()``
""""""""""""""""""""""""

Expects that the generated output is exactly the specified string.

.. code-block:: php

    $this->expectOutputString('foo');

    print 'foo';

If the generated output is not equal to the expected string, the test will be counted as a failure.


.. _writing-tests-for-phpunit.testing-output.reference.expectOutputRegex:

``expectOutputRegex()``
"""""""""""""""""""""""

Expects that the generated output matches the specified regular expression.

.. code-block:: php

    $this->expectOutputRegex('/foo .+ bar/');

If the generated output does not match the regular expression, the test will be counted as a failure.
Use this instead of ``expectOutputString()`` when only part of the generated output is stable.


.. _writing-tests-for-phpunit.testing-output.reference.combining-and-repeating-output-expectations:

Combining and repeating output expectations
"""""""""""""""""""""""""""""""""""""""""""

``expectOutputString()`` and ``expectOutputRegex()`` may be combined with one another and each may be called more than once.
Every expectation that is configured is verified against the generated output, and the test only passes when all of them are met:

.. code-block:: php

    $this->expectOutputRegex('/^f/');
    $this->expectOutputRegex('/o$/');
    $this->expectOutputString('foo');

    print 'foo';

Calling either method repeatedly with an argument that has already been configured has no effect:
duplicate expectations are ignored.

Because the generated output cannot be identical to two different strings at the same time, calling ``expectOutputString()`` more than once with different arguments triggers a PHPUnit warning:

.. code-block:: php

    $this->expectOutputString('foo');
    $this->expectOutputString('bar'); // triggers a PHPUnit warning


.. _writing-tests-for-phpunit.testing-error-log:

Testing error log output
------------------------

Sometimes you want to assert that the code under test calls PHP's ``error_log()`` function.

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
            $this->expectErrorLog();

            // Code under test that calls error_log()
            error_log('something happened');
        }
    }

Calling ``expectErrorLog()`` redirects ``error_log()`` output to a temporary file for the
remainder of the test. Only output that is written after ``expectErrorLog()`` was called
satisfies the expectation, so the method has to be called before the code under test.

When ``expectErrorLog()`` is not used, PHPUnit does not interfere with ``error_log()``:
the logged output goes to the error log that is configured for the PHP process. When no
error log is configured, PHP writes the message to the standard error stream. For a test
that :ref:`runs in a separate process <appendixes.attributes.RunInSeparateProcess>`, output
on the standard error stream of the child process is reported as a test error.


.. _writing-tests-for-phpunit.parameterizing-and-relating-tests:

Parameterizing and Relating Tests
=================================

The topics covered so far describe what a single test method verifies.
The topics covered below describe how a test method can be run more than once with varying input, and how a test method can consume the result of another test method.


.. _writing-tests-for-phpunit.data-providers:

Data providers
--------------

**Use a data provider when you want to:**

* Run the same test logic against many combinations of input and expected output
* Have each combination reported, and counted, as a test of its own
* Add a new case by adding data instead of by adding code

**Do not use a data provider when:**

* The cases require different assertions or a different structure. Write separate test methods instead.
* The cases only differ in a value that has no bearing on the behaviour under test. One test method is enough then.
* The data would have to be a service or a large object graph (see :ref:`what a data set may contain <writing-tests-for-phpunit.data-providers.reference.contents-of-a-data-set>`).


.. _writing-tests-for-phpunit.data-providers.common-use-cases:

Common use cases
^^^^^^^^^^^^^^^^

A test method can accept arbitrary arguments.
These arguments are to be provided by one or more data provider methods.
The data provider method to be used is specified using the ``PHPUnit\Framework\Attributes\DataProvider`` attribute.

The example below runs ``testCanBeCreatedFromValidEmail()`` once for each of the four email addresses that ``validEmailAddressProvider()`` returns:

.. literalinclude:: examples/writing-tests-for-phpunit/EmailDataProviderTest.php
   :caption: Using a data provider to run one test method with varying input
   :name: writing-tests-for-phpunit.data-providers.examples.EmailDataProviderTest.php
   :language: php

**1. Declaring the data provider method**

.. code-block:: php

    public static function validEmailAddressProvider(): array
    {
        return [
            ['user@example.org'],
            // ...
        ];
    }

* **What**: Provides the input for the test method
* **How**: The method is ``public`` and ``static``, and it returns an array of arrays; each inner array is one **data set**
* **Why**: Each data set provides the arguments for one invocation of the test method

**2. Connecting the test method to the data provider method**

.. code-block:: php

    #[DataProvider('validEmailAddressProvider')]
    public function testCanBeCreatedFromValidEmail(string $string): void

* **What**: Declares that this test method is run with the data sets of ``validEmailAddressProvider()``
* **How**: The ``DataProvider`` attribute takes the name of the data provider method
* **Why**: The parameters of the test method receive the values of a data set, in order

**3. Running the test**

Running the test shown above yields the output shown below:

.. literalinclude:: examples/writing-tests-for-phpunit/EmailDataProviderTest.php.out

Four tests are reported, not one: each data set is a test of its own.
When one data set fails, the others are still executed and reported.

Alternatively, the :ref:`DataProviderClosure <appendixes.attributes.DataProviderClosure>` attribute can be used to define
a data provider inline as a static closure, without having to implement a separate static method.


.. _writing-tests-for-phpunit.data-providers.common-use-cases.naming-data-sets:

Naming data sets
""""""""""""""""

The example below uses a data provider whose data sets do not have names:

.. literalinclude:: examples/writing-tests-for-phpunit/NumericDataSetsTest.php
   :caption: Using a data provider that returns an array of arrays
   :name: writing-tests-for-phpunit.data-providers.examples.NumericDataSetsTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/writing-tests-for-phpunit/NumericDataSetsTest.php.out

The failing data set is identified by its number and by its values.

It is useful to name each data set with a string key.
Output will be more verbose as it will contain the name of the data set that breaks a test.

.. literalinclude:: examples/writing-tests-for-phpunit/NamedDataSetsTest.php
   :caption: Using a data provider with named data sets
   :name: writing-tests-for-phpunit.data-providers.examples.NamedDataSetsTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/writing-tests-for-phpunit/NamedDataSetsTest.php.out

The failing data set is now identified by a name that says what the case is about.

.. admonition:: Note

    You can make the test output more verbose by defining a sentence and using the test's parameter names as placeholders
    (``$a``, ``$b`` and ``$expected`` in the example above) with the :ref:`TestDox <appendixes.attributes.TestDox>` attribute.
    You can also refer to the name of a named data set with ``$_dataName``.


.. _writing-tests-for-phpunit.data-providers.reference:

Reference
^^^^^^^^^

.. _writing-tests-for-phpunit.data-providers.reference.requirements:

Requirements for a data provider method
"""""""""""""""""""""""""""""""""""""""

A data provider method must be ``public`` and ``static`` and its name must not start with ``test``.
It must return a value that is `iterable <https://www.php.net/manual/en/language.types.iterable.php>`_, either an array or an object that implements the ``Traversable`` interface.
In each iteration step, it must yield an array.
For each of these arrays, the test method will be called with the contents of the array as its arguments.


.. _writing-tests-for-phpunit.data-providers.reference.names-of-data-sets:

How the name of a data set is determined
""""""""""""""""""""""""""""""""""""""""

The name of a data set is the key that the data provider used for it, and PHPUnit uses this key verbatim.
A data set with an integer key is identified by its number, for instance ``testAdd#0``, while a data set with a string key is identified by its name, for instance ``testAdd@one plus one``.

Keep in mind that PHP canonicalizes array keys that represent an integer: the keys ``0``, ``'0'``, and ``'123'`` all become integer keys.
A numeric string that PHP does not canonicalize, on the other hand, remains a string key and is therefore used as the name of the data set as it is written.
The data sets named ``'1.5'`` and ``'1.9'``, for example, remain distinguishable in the test output, in log files, and when they are selected using ``--filter``.

Bidirectional control characters in the name of a data set are escaped, for instance as ``\u{202E}``, so that they cannot change the direction in which the surrounding text is displayed.


.. _writing-tests-for-phpunit.data-providers.reference.selecting-data-sets:

Selecting individual data sets
""""""""""""""""""""""""""""""

Individual data sets can be selected from the command line via the ``--filter`` option; see :ref:`textui.selecting-tests.filter` for the syntax.


.. _writing-tests-for-phpunit.data-providers.reference.execution-time:

When data provider methods are executed
"""""""""""""""""""""""""""""""""""""""

All data providers are executed before both the call to a before-class method such as ``setUpBeforeClass()`` and the first call to a before-test method such as ``setUp()``.
Because of this, you cannot access any properties of the actual test case object within a data provider.
This also means that no code coverage data is collected while data provider methods are executed.


.. _writing-tests-for-phpunit.data-providers.reference.contents-of-a-data-set:

What a data set may contain
"""""""""""""""""""""""""""

The data sets provided by a data provider method should only contain (arrays of) scalar values, immutable value objects, or test stubs.
Services or large object graphs should not be created in a data provider method.
Mock objects cannot be created in a data provider method.


.. _writing-tests-for-phpunit.data-providers.reference.combining-with-test-dependencies:

Combining data providers with test dependencies
"""""""""""""""""""""""""""""""""""""""""""""""

When a test receives input from both a data provider method and from one or more tests it depends on, the arguments from the data provider will come before the ones from depended-upon tests.
The arguments from depended-upon tests will be the same for each data set.

When a test depends on a test that uses data providers, the depending test will be executed when the test it depends upon is successful for at least one data set.
The result of a test that uses data providers cannot be injected into a depending test.


.. _writing-tests-for-phpunit.test-dependencies:

Test dependencies
-----------------

PHPUnit supports the declaration of explicit dependencies between test methods.
Such dependencies do not define the order in which the test methods are to be executed but they allow the returning of an instance of the test fixture by a producer and passing it to the dependent consumers.

**Producer**
  A test method that yields its unit under test as return value.

**Consumer**
  A test method that depends on one or more producers and their return values.

.. admonition:: Use test dependencies sparingly

   A test that depends on another test is not independent: it cannot be run, understood, or
   debugged on its own, and the value it receives is determined by code that lives in another
   test method.

   In most situations, creating the fixture in the test method itself, or in a ``setUp()``
   method (see :ref:`fixtures.template-methods`), is the better choice: every test then starts
   from a known state that is described in one place.

   Reach for test dependencies when creating the fixture is genuinely expensive, or when the
   creation of the fixture is itself the behaviour that an earlier test verifies.


.. _writing-tests-for-phpunit.test-dependencies.common-use-cases:

Common use cases
^^^^^^^^^^^^^^^^

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


.. _writing-tests-for-phpunit.test-dependencies.defect-localization:

Defect localization
^^^^^^^^^^^^^^^^^^^

To localize defects, we want our attention to be focussed on relevant failing tests.
This is why PHPUnit skips the execution of a test when a depended-upon test has failed.
This improves defect localization by exploiting the dependencies between tests as shown in :numref:`writing-tests-for-phpunit.examples.DependencyFailureTest.php`.

.. literalinclude:: examples/writing-tests-for-phpunit/DependencyFailureTest.php
   :caption: Leveraging the dependencies between tests
   :name: writing-tests-for-phpunit.examples.DependencyFailureTest.php
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/writing-tests-for-phpunit/DependencyFailureTest.php.out


.. _writing-tests-for-phpunit.test-dependencies.reference:

Reference
^^^^^^^^^

A test may have more than one test dependency attribute.

By default, PHPUnit does not change the order in which tests are executed, so you have to ensure that the dependencies of a test can actually be met before the test is run.

A test that has more than one test dependency attribute will get a fixture from the first producer as the first argument, a fixture from the second producer as the second argument, and so on.

See :ref:`appendixes.attributes.Depends` and the attributes documented next to it for dependencies on tests in other classes and for the cloning variants.


.. _writing-tests-for-phpunit.test-outcomes:

Test Outcomes Other Than Passed and Failed
==========================================

A test does not only pass or fail.
It can also be marked as **incomplete**, meaning that it has not been written yet, or as **skipped**, meaning that it cannot be run in the current environment.
Both outcomes prevent a test from being reported as successful without reporting it as a defect.

**Which one to use:**

* Use :ref:`writing-tests-for-phpunit.incomplete-tests` when the test itself is not finished. The reason lies in the test suite, and it goes away when you finish writing the test.
* Use :ref:`writing-tests-for-phpunit.skipping-tests` when the test is finished but cannot run here. The reason lies in the environment, for instance a missing PHP extension, and it goes away on a machine that meets the requirement.


.. _writing-tests-for-phpunit.incomplete-tests:

Incomplete tests
----------------

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

Assertions that are executed before the call to ``markTestIncomplete()`` are evaluated as usual:
when one of them fails, the test is reported as a failure and not as incomplete.


.. _writing-tests-for-phpunit.skipping-tests:

Skipping tests
--------------

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
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

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

.. admonition:: Custom Skip Logic

   The ``Requires*`` attributes listed above are convenience functionality for the most common cases.
   They replace custom skip logic that you would otherwise have to write in :ref:`before-class or before-test methods <fixtures.template-methods>`.

   When you need skip logic that goes beyond what the ``Requires*`` attributes support (for example, skipping a test when an extension *is* available, or when a combination of conditions is met), use ``markTestSkipped()`` in a ``setUp()`` or ``setUpBeforeClass()`` method (or in a method configured with the ``#[Before]`` or ``#[BeforeClass]`` attribute).


.. _writing-tests-for-phpunit.best-practices:

Best Practices
==============

1. **Verify one aspect per test:** A test method should have one reason to fail. When a test method needs more than one sentence to describe, it probably tests more than one thing.
2. **Name tests after behaviour, not after methods:** ``testCannotBeCreatedFromInvalidEmail()`` says what is expected; ``testFromString2()`` does not.
3. **Assert data structures as a whole:** A single assertion on a whole array or object reports every value that differs, not just the first one.
4. **Test the unhappy path, too:** Verifying that invalid input is rejected is as important as verifying that valid input is accepted.
5. **Configure exception expectations immediately before the action:** Call ``expectException()`` directly before the statement that is expected to throw, and let the test method end there.
6. **Name your data sets:** A failure reported as ``testAdd@invalid domain`` is easier to act on than one reported as ``testAdd#3``.
7. **Keep tests independent:** Prefer creating a fixture in the test method or in ``setUp()`` over passing it from one test to another with test dependencies.
8. **Never leave an empty test method behind:** Call ``markTestIncomplete()`` so that the test report distinguishes a test that passes from a test that was never written.
9. **Skip for environmental reasons only:** Use ``markTestSkipped()`` or a ``Requires*`` attribute when a test cannot run here. A test that is skipped because it fails is a test that hides a defect.
