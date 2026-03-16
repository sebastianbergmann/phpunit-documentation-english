

.. _test-doubles:

************
Test Doubles
************

Gerard Meszaros introduces the concept of test doubles in his "xUnit Test Patterns" book like so:

    Sometimes it is just plain hard to test the system under test (SUT) because it depends on other components that cannot be used in the test environment. This could be because they aren't available, they will not return the results needed for the test or because executing them would have undesirable side effects. In other cases, our test strategy requires us to have more control or visibility of the internal behavior of the SUT.

    When we are writing a test in which we cannot (or chose not to) use a real depended-on component (DOC), we can replace it with a Test Double. The Test Double doesn't have to behave exactly like the real DOC; it merely has to provide the same API as the real one so that the SUT thinks it is the real one!

PHPUnit provides a powerful and flexible API for creating and configuring test stubs and mock objects.
These test doubles are essential for unit testing, as they allow us to isolate the code under test from its dependencies and verify its behaviour without executing the code of the real collaborating objects.

A test stub replaces a real dependency and can be configured to return predefined values or throw exceptions.
Test stubs give us control over indirect inputs to our system under test, enabling us to force it onto specific execution paths and test different scenarios without relying on external systems or services.

A mock object is a type of test stub that can be configured with expectations about how it will be called.
Mock objects serve as observation points, enabling us to verify indirect outputs and communication between our system under test and its collaborators.
By expecting that specific methods were called with the expected arguments, we can ensure that our code interacts correctly with its dependencies.

This chapter focuses exclusively on test stubs and mock objects.
Other types of test double, such as dummies, fakes, and spies, are beyond the scope of this documentation.

**Use a test stub when:**

- You need to control what a dependency returns
- You are testing the logic of the SUT
- The interactions with the dependency do not matter
- You need to isolate the SUT from slow or unavailable dependencies

**Use a mock object when:**

- You need to verify that methods are called
- You are testing the communication between objects
- The number of method calls matters
- The arguments passed to methods matter

For a detailed discussion of the conceptual differences between test stubs and mock objects and when to use which, see "`Testing with(out) dependencies <https://phpunit.expert/articles/testing-with-and-without-dependencies.html?ref=phpunit>`_".


**Example Code**

Some of the examples in this chapter use an interface named ``Database`` and a class named ``Service``.
These are introduced now so that we are familiar with them when they appear in the examples.

.. literalinclude:: examples/test-doubles/src/Database.php
   :caption: An interface that defines a contract for database operations
   :language: php

**Explanation:**

* This interface defines a contract for database operations
* ``execute()`` is used for write operations (``INSERT``, ``UPDATE``, ``DELETE``), returns ``true`` on success, and throws an exception of failure
* ``query()`` is used for read operations (``SELECT``), returns an array of results on success, and throws an exception of failure
* Both methods accept SQL strings and variadic arguments for prepared statements

.. literalinclude:: examples/test-doubles/src/Service.php
   :caption: A class that depends on ``Database``
   :language: php

**Explanation:**

* ``Service`` is a ``final readonly`` class that depends on ``Database``
* The ``Database`` dependency is injected through the constructor, which makes the class testable in isolation from both the dependency and the database server
* ``doSomething()`` queries the database and returns ``true`` if rows are found, ``false`` otherwise
* ``doSomethingElse()`` executes an ``INSERT`` statement to add data to the database


.. _test-doubles.test-stubs:

Test Stubs
==========

.. _test-doubles.test-stubs.what-are-test-stubs:

What are test stubs?
--------------------

A **test stub** provides a replacement for a real collaborating object (a dependency) that your code interacts with.
Test stubs allow you to test code in isolation without executing the actual implementation of the dependency.

A test stub is a replacement for a real component on which the System Under Test (SUT) depends.
This gives the test a **control point** for the **indirect inputs** of the SUT.
By controlling these indirect inputs, you can force the SUT into specific execution paths that you want to verify in your test.

**Use test stubs when you want to:**

* Decouple your code from slow or unavailable dependencies (databases, external APIs)
* Provide specific return values to test different code paths
* Test error handling by simulating failures
* Focus on testing the logic of the SUT rather than its dependencies


.. _test-doubles.test-stubs.common-use-cases:

Common use cases
----------------

.. _test-doubles.test-stubs.common-use-cases.configuring-return-values:

Configuring return values
^^^^^^^^^^^^^^^^^^^^^^^^^

If your code depends on a service that returns data, you can use a test stub to control the results of that service without actually calling it.

.. literalinclude:: examples/test-doubles/src/ServiceTest_1.php
   :caption: We use a test stub to provide indirect input for the object we want to test
   :language: php

**1. Creating the test stub**

.. code-block:: php

   $database = $this->createStub(Database::class);

* **What**: Creates a test stub that implements the ``Database`` interface
* **How**: ``createStub()`` generates a test stub where all methods return default values unless configured otherwise
* **Why**: We need an object that "looks like" ``Database`` to test ``Service`` in isolation from a real database connection

Test stubs are ideal when we only need to control **what the dependency returns** (indirect input).

**2. Configuring the test stub**

.. code-block:: php

   $database
       ->method('query')
       ->willReturn([['foo' => 'bar']]);

* **What**: Configures the test stub to return a specific value when ``query()`` is called
* **How**:

  * ``method('query')`` specifies which method to configure
  * ``willReturn([['foo' => 'bar']])`` sets the return value to an array containing one row
* **Why**: This simulates the scenario where the database query finds matching rows

**3. Creating the system under test**

.. code-block:: php

   $service = new Service($database);

* **What**: Instantiates the ``Service`` class with the test stub as its dependency
* **How**: The test stub is passed to the constructor, satisfying the ``Database`` type requirement
* **Why**: This is the object we are actually testing and by injecting the test stub, we control the database behavior

**4. Asserting the expected behavior**

.. code-block:: php

   $this->assertTrue($service->doSomething());

* **What**: Verifies that ``doSomething()`` returns ``true``
* **How**: ``assertTrue()`` fails the test if the value is not exactly ``true``
* **Why**: When the query returns rows, ``doSomething()`` should return `true`

**Key Concept: Indirect Input**

**Indirect input** occurs when the system under test receives data from a dependency rather than directly from arguments passed to the tested method or function.
In this test:

1. The test cannot directly pass data to ``doSomething()`` as it takes no parameters
2. Instead, ``doSomething()`` gets its data by calling ``$this->database->query()``
3. The test stub provides this **indirect input** by returning ``[['foo' => 'bar']]``


.. _test-doubles.test-stubs.common-use-cases.configuring-exceptions:

Configuring exceptions
^^^^^^^^^^^^^^^^^^^^^^

You can configure test stubs to throw exceptions, enabling you to test how your code handles errors.

.. literalinclude:: examples/test-doubles/src/ServiceTest_2.php
   :caption: We use a test stub that throws an exception to test an error path
   :language: php

**1. Creating the test stub**

.. code-block:: php

   $database = $this->createStub(Database::class);

* **What**: Creates a test stub that implements the ``Database`` interface
* **How**: ``createStub()`` generates a test stub where all methods return default values unless configured otherwise
* **Why**: We need an object that "looks like" ``Database`` to test ``Service`` in isolation from a real database connection

Test stubs are ideal when we only need to control **what the dependency returns** (indirect input).

**2. Configuring the test stub**

.. code-block:: php

   $database
       ->method('query')
       ->willThrowException(new DatabaseException);

* **What**: Configures the test stub to throw a specific exception when ``query()`` is called
* **How**:

  * ``method('query')`` specifies which method to configure
  * ``willThrowException(new DatabaseException)`` sets the exception to be thrown
* **Why**: This simulates the scenario where an error occurs while querying the database

**3. Configuring the expectation**

.. code-block:: php

   $this->expectException(ServiceException::class);

* **What**: Configures the test to only be successful if a ``ServiceException`` is thrown
* **How**: We pass the name of the exception we expect to ``expectException()``
* **Why**: We want to test that a ``DatabaseException`` thrown by a ``Database`` implementation results in a ``ServiceException`` being thrown

**4. Creating the system under test**

.. code-block:: php

   $service = new Service($database);

* **What**: Instantiates the ``Service`` class with the test stub as its dependency
* **How**: The test stub is passed to the constructor, satisfying the ``Database`` type requirement
* **Why**: This is the object we are actually testing and by injecting the test stub, we control the database behavior

**4. Invoking what we want to test**

.. code-block:: php

   $service->doSomething();


.. _test-doubles.test-stubs.reference:

Reference
---------

.. _test-doubles.test-stubs.reference.creating-test-stubs:

Creating test stubs
^^^^^^^^^^^^^^^^^^^

.. _test-doubles.test-stubs.reference.creating-test-stubs.createStub:

``createStub()``
""""""""""""""""

Creates a test stub for the specified interface (or extendable class).

.. code-block:: php

   $stub = $this->createStub(InterfaceName::class);

All methods of the original type are replaced with an implementation that returns an automatically generated value that satisfies the method's return type declaration without calling the original method.
These methods are referred to as "doubled methods" or "stubbed methods".

Doubled methods can be configured using the methods described below.


.. admonition:: Limitation: final classes

   Please note that ``final`` classes cannot be doubled.


.. admonition:: Limitation: final, private, and static methods

   Please note that ``final``, ``private``, and ``static`` methods cannot
   be doubled. They are ignored by PHPUnit's test double functionality and
   retain their original behavior except for ``static`` methods which will
   be replaced by a method throwing an exception.


.. admonition:: Limitation: Enumerations

   Enumerations (``enum``) are ``final`` classes and therefore cannot be
   doubled.


.. _test-doubles.test-stubs.reference.creating-test-stubs.createStubForIntersectionOfInterfaces:

``createStubForIntersectionOfInterfaces()``
"""""""""""""""""""""""""""""""""""""""""""

Creates a test stub for an intersection of interfaces.

.. code-block:: php

   $stub = $this->createStubForIntersectionOfInterfaces(
       [InterfaceA::class, InterfaceB::class]
   );

This is useful when you need to replace an object that implements multiple interfaces.


.. _test-doubles.test-stubs.reference.creating-test-stubs.createConfiguredStub:

``createConfiguredStub()``
""""""""""""""""""""""""""

Creates a test stub with methods already configured to return specific values.

.. code-block:: php

   $stub = $this->createConfiguredStub(
       InterfaceName::class,
       [
           'methodOne' => 'return value one',
           'methodTwo' => 'return value two',
       ]
   );

   // $stub->methodOne() will return "return value one"
   // $stub->methodTwo() will return "return value two"

This is a convenience method for simple cases.


.. _test-doubles.test-stubs.reference.creating-test-stubs.getStubBuilder:

``getStubBuilder()``
""""""""""""""""""""

The ``getStubBuilder()`` method provides a fluent API for creating test stubs.
It should only be used for **edge cases** that are not supported by the simpler ``createStub()`` or ``createStubForIntersectionOfInterfaces()`` methods.

Use ``getStubBuilder()`` only when you need advanced configuration such as:

* Specifying a custom class name for the test stub
* Enabling the original constructor with custom arguments
* Creating partial test stubs (only doubling specific methods)
* Controlling clone behavior
* Disabling automatic return value generation

The ``getStubBuilder(string $type)`` method returns an object that can be used to configure and subsequently perform the creation of a test stub for the specified interface (or extendable class).

The object returned by ``getStubBuilder()`` has, among other methods, a method named ``getStub()``.
This creates and returns the configured test stub.
This method must be called last in the fluent API's method call chain.

The following methods can be used on the object returned by ``getStubBuilder()`` to configure the creation of the test stub:


**setStubClassName(string $name)**

Specifies a custom class name for the generated test stub class.

.. admonition:: Note

   The specified class name must not already exist.


**onlyMethods(array $methods)**

Specifies which methods should be doubled (stubbed).
Methods not in this list will retain their original implementation, creating a **partial double**.

.. admonition:: Note

   All specified methods must exist in the class.


**setConstructorArgs(array $arguments)**

Specifies the arguments to pass to the constructor when ``enableOriginalConstructor()`` (see below) is used.


**disableOriginalConstructor()**

This disables the invocation of the original constructor.
This is useful when you want to create a partial double and the constructor either has side effects or requires dependencies that you do not want to provide.

.. admonition:: Note

   ``createStub()``, ``createConfiguredStub()``, ``createMock()``, and ``createConfiguredMock()`` create test doubles without invoking the original constructor when they are used to create a test double for an extendable class.


**enableOriginalConstructor()**

Enables the invocation of the original constructor. Use this with ``setConstructorArgs()`` (see above) to pass required arguments.

.. admonition:: Note

   This is the default behaviour.
   The ``enableOriginalConstructor()`` method only exists in case you want to explicitly indicate in your test code that you are relying on this behaviour.


**disableOriginalClone()**

Disables the invocation of the original ``__clone()`` method when the test stub is cloned.

.. admonition:: Note

   The original ``__clone()`` method is not called for test doubles for extendable classes created by ``createStub()``, ``createConfiguredStub()``, ``createMock()``, and ``createConfiguredMock()``.


**enableOriginalClone()**

Enables the invocation of the original ``__clone()`` method when the test stub is cloned.

.. admonition:: Note

   This is the default behaviour.
   The ``enableOriginalClone()`` method only exists in case you want to explicitly indicate in your test code that you are relying on this behaviour.


**enableAutoReturnValueGeneration()**

Enables automatic generation of return values for doubled methods that do not have explicit return value configuration.

.. admonition:: Note

   This is the default behaviour.
   The ``enableAutoReturnValueGeneration()`` method only exists in case you want to explicitly indicate in your test code that you are relying on this behaviour.


**disableAutoReturnValueGeneration()**

Disables automatic generation of return values.
When disabled, stubbed methods without explicit configuration will return ``null`` or throw an exception depending on the declared return type.


.. _test-doubles.test-stubs.reference.configuring-return-values:

Configuring return values
^^^^^^^^^^^^^^^^^^^^^^^^^

.. _test-doubles.test-stubs.reference.configuring-return-values.willReturn:

``willReturn()``
""""""""""""""""

Configures a method to return a specific value.

.. code-block:: php

   $stub = $this->createStub(InterfaceName::class);

   $stub
       ->method('doSomething')
       ->willReturn('result');

   // $stub->doSomething() always returns "result"

The return value must be compatible with the method's return type declaration.

``willReturn()`` can be used to configure a method to return different values on consecutive calls:

.. code-block:: php

   $stub = $this->createStub(InterfaceName::class);

   $stub
       ->method('doSomething')
       ->willReturn('first result', 'second result');

   // $stub->doSomething() returns "first result" when it is invoked for the first time
   // $stub->doSomething() returns "second result" when it is invoked for the second time
   // $stub->doSomething() will trigger an error when it is invoked more than twice

If a method is configured to return different values on consecutive calls, it can only be invoked a number of times equivalent to the number of configured return values.


.. _test-doubles.test-stubs.reference.configuring-return-values.willReturnSelf:

``willReturnSelf()``
""""""""""""""""""""

Configures a method to return the test stub object itself.

.. code-block:: php

   $stub = $this->createStub(InterfaceName::class);

   $stub
       ->method('doSomething')
       ->willReturnSelf();

This is useful for testing fluent interfaces.


.. _test-doubles.test-stubs.reference.configuring-return-values.willReturnArgument:

``willReturnArgument()``
""""""""""""""""""""""""

Configures a method to return one of its arguments.

.. code-block:: php

   $stub = $this->createStub(InterfaceName::class);

   $stub
       ->method('doSomething')
       ->willReturnArgument(0);

   // $stub->doSomething('some value') returns 'some value'

The argument index is zero-based.


.. _test-doubles.test-stubs.reference.configuring-return-values.willReturnMap:

``willReturnMap()``
"""""""""""""""""""

Configures a method to return different values based on the arguments it receives.

.. code-block:: php

   $stub = $this->createStub(InterfaceName::class);

   $stub
       ->method('doSomething')
       ->willReturnMap([
           ['foo', 'bar', 'baz'],
           ['one', 'two', 'three'],
       ]);

   // $stub->doSomething('foo', 'bar') returns 'baz'
   // $stub->doSomething('one', 'two') returns 'three'

Each inner array contains the method arguments followed by the return value.


.. _test-doubles.test-stubs.reference.configuring-return-values.willReturnCallback:

``willReturnCallback()``
""""""""""""""""""""""""

Configures a method to return the result of a callback function.

.. code-block:: php

   $stub = $this->createStub(InterfaceName::class);

   $stub
       ->method('doSomething')
       ->willReturnCallback(
           static fn(string $input) => strtoupper($input)
       );

   // $stub->doSomething('string') returns 'STRING'

The callback receives the method arguments and can implement complex logic.


.. _test-doubles.test-stubs.reference.configuring-return-values.get-hooked-properties:

Get-Hooked Properties
"""""""""""""""""""""

PHP 8.4 introduced the language feature of `get-hooked properties <https://www.php.net/releases/8.4/en.php#property_hooks>`_.

The example below shows an interface that declares a get-hooked property:

.. literalinclude:: examples/test-doubles/src/InterfaceWithGetHookedProperty.php
   :caption: Interface that declares a get-hooked property
   :language: php

The behaviour of the get-hooked property ``property`` can be configured like so:

.. literalinclude:: examples/test-doubles/GetHookedPropertyStubExampleTest.php
   :caption: Test that uses a test stub of an interface with a get-hooked property
   :language: php

In the example shown above, ``PropertyHook::get('property')`` to specify that we want to configure the behaviour of the method that is called when the property named ``property`` is accessed for reading.


.. _test-doubles.test-stubs.reference.configuring-exceptions:

Configuring exceptions
^^^^^^^^^^^^^^^^^^^^^^

.. _test-doubles.test-stubs.reference.configuring-exceptions.willThrowException:

``willThrowException()``
""""""""""""""""""""""""

Configures a method to throw an exception instead of returning a value.

.. code-block:: php

   $stub = $this->createStub(InterfaceName::class);

   $stub
       ->method('doSomething')
       ->willThrowException(new Exception);

   // $stub->doSomething() throws the configured exception


.. _test-doubles.test-stubs.reference.return-value-generation:

Return Value Generation
-----------------------

Please note that methods with no configured behaviour will automatically and recursively stub return values based on the return type of the method.
Consider the example shown below:

.. literalinclude:: examples/test-doubles/src/C.php
   :caption: A method with a return type declaration
   :language: php

In the above example, the ``C::m()`` method has a return type declaration indicating that it returns an object of type ``D``.
When a test double for ``C`` is created and no return value is configured for ``m()`` using ``willReturn()``, for example, PHPUnit will automatically create a test double for ``D`` to be returned when ``m()`` is invoked.

Similarly, if ``m()`` had a return type declaration for a scalar type, a return value such as ``0`` (for ``int``), ``0.0`` (for ``float``), ``""`` (for ``string``), etc. would be generated.

You can disable this return value generation using the ``#[DisableReturnValueGenerationForTestDoubles]`` attribute on the test case class.


.. _test-doubles.mock-objects:

Mock Objects
============

.. _test-doubles.mock-objects.what-are-mock-objects:

What are mock objects?
----------------------

A **mock object** is a test stub that can additionally be configured with **expectations** about how it should be called.
Mock objects allow you to verify the communication between your System Under Test and its collaborators.

While a test stub provides control over **indirect inputs** (data flowing into the SUT), a mock object provides an **observation point** for **indirect outputs** (method calls from the SUT to its dependencies).
Mock objects verify that the SUT communicates correctly with its dependencies.

**Use mock objects when you want to:**

* Verify that specific methods are called
* Verify the arguments passed to methods
* Verify the number of times methods are called
* Test the coordination and communication between objects

If you create a mock object but do not configure any expectations on it, PHPUnit will emit a notice.
This indicates you should either add expectations or use ``createStub()`` instead.

Please read "`Testing with(out) dependencies <https://phpunit.expert/articles/testing-with-and-without-dependencies.html?ref=phpunit>`_" and "`The Stub/Mock Intervention <https://phpunit.expert/articles/the-stub-mock-intervention.html?ref=phpunit>`_" for some background on why this distinction between test stubs and mock objects is critical.


.. _test-doubles.mock-objects.common-use-cases:

Common use cases
----------------

.. _test-doubles.mock-objects.common-use-cases.using-a-mock-object-for-testing-direct-output:

Using a mock object for testing direct output
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Use a mock object to verify that actions occur when your code triggers them in its dependencies.

.. literalinclude:: examples/test-doubles/src/ServiceTest_3.php
   :caption: We use a test stub to provide indirect input for the object we want to test
   :language: php

**1. Creating the mock object**

.. code-block:: php

   $database = $this->createMock(Database::class);

* **What**: Creates a mock object that implements the ``Database`` interface
* **How**: ``createMock()`` generates a mock object that can verify method calls and their arguments
* **Why**: We need to verify that ``Service`` correctly calls the database's ``execute()`` method

Mock objects are ideal when we need to verify **how the system under test interacts with its dependencies** (indirect output).

**2. Setting up expectations**

.. code-block:: php

   $database
       ->expects($this->once())
       ->method('execute')
       ->with(
           'INSERT INTO bar (foo, baz) VALUES (?, ?);',
           'value',
           'another value',
       );

* **What**: Configures the mock object to expect a specific method call with specific arguments
* **How**:

  * ``expects($this->once())`` - The method must be called exactly once; the test fails if called zero times or more than once
  * ``method('execute')`` - Specifies which method should be called
  * `with(...)` - Specifies the arguments that must be passed; the test fails if different arguments are used
* **Why**: This is the core of mock-based testing. We verify that ``doSomethingElse()`` sends the correct SQL and parameters to the ``Database`` object

**3. Creating the system under test**

.. code-block:: php

   $service = new Service($database);

* **What**: Instantiates the ``Service`` class with the mock object as its dependency
* **How**: The mock object is passed to the constructor, satisfying the ``Database`` type requirement
* **Why**: This is the object we are actually testing and by injecting the mock object, we can verify the communication between ``Service`` and ``Database``

**4. Executing the system under test**

.. code-block:: php

   $service->doSomethingElse();

* **What**: Calls the method we want to test

When the test method completes, PHPUnit automatically verifies that all expectations set on the mock object were met.

**Key Concept: Indirect Output**

**Indirect output** occurs when the system under test sends data to a dependency rather than returning it directly.
In this test:

1. ``doSomethingElse()`` returns nothing (``void``), there is no direct output to assert
2. Instead, the method's effect is calling ``$this->database->execute()`` with specific arguments
3. The mock object captures this **indirect output** and verifies it matches our expectations


.. _test-doubles.mock-objects.reference:

Reference
---------

.. _test-doubles.mock-objects.reference.creating-mock-objects:

Creating mock objects
^^^^^^^^^^^^^^^^^^^^^

.. _test-doubles.mock-objects.reference.creating-mock-objects.createMock:

``createMock()``
""""""""""""""""

Creates a mock object for the specified interface (or extendable class).

.. code-block:: php

   $mock = $this->createMock(InterfaceName::class);

All methods can be configured with return values and expectations.


.. admonition:: Limitation: final classes

   Please note that ``final`` classes cannot be doubled.


.. admonition:: Limitation: final, private, and static methods

   Please note that ``final``, ``private``, and ``static`` methods cannot
   be doubled. They are ignored by PHPUnit's test double functionality and
   retain their original behavior except for ``static`` methods which will
   be replaced by a method throwing an exception.


.. admonition:: Limitation: Enumerations

   Enumerations (``enum``) are ``final`` classes and therefore cannot be
   doubled.


.. _test-doubles.mock-objects.reference.creating-mock-objects.createMockForIntersectionOfInterfaces:

``createMockForIntersectionOfInterfaces()``
"""""""""""""""""""""""""""""""""""""""""""

Creates a mock object for an intersection of interfaces.

.. code-block:: php

   $mock = $this->createMockForIntersectionOfInterfaces(
       [InterfaceA::class, InterfaceB::class]
   );

This is useful when you need to replace an object that implements multiple interfaces.


.. _test-doubles.mock-objects.reference.creating-mock-objects.createConfiguredMock:

``createConfiguredMock()``
""""""""""""""""""""""""""

Creates a mock object with methods already configured to return specific values.

.. code-block:: php

   $mock = $this->createConfiguredMock(
       InterfaceName::class,
       [
           'methodOne' => 'return value one',
           'methodTwo' => 'return value two',
       ]
   );

   // $mock->methodOne() will return "return value one"
   // $mock->methodTwo() will return "return value two"

This is a convenience method for simple cases.


.. _test-doubles.mock-objects.reference.creating-mock-objects.getMockBuilder:

``getMockBuilder()``
""""""""""""""""""""

The ``getMockBuilder()`` method provides a fluent API for creating mock objects.
It should only be used for **edge cases** that are not supported by the simpler ``createMock()`` or ``createMockForIntersectionOfInterfaces()`` methods.

The documentation and recommendations for ``getStubBuilder()`` (see above) also apply to ``getMockBuilder()``, with two differences:

* The method for specifying a custom class name for the generated mock object class is ``setMockClassName()``
* The name of the method that must be called last in the fluent API's method call chain is ``getMock()``


.. _test-doubles.mock-objects.reference.configuring-behaviour:

Configuring behavior
^^^^^^^^^^^^^^^^^^^^

Mock objects support all the same methods for configuring behavior (return values, exceptions) as test stubs.
See the test stub reference section above for detailed examples of these methods.


.. _test-doubles.mock-objects.reference.configuring-expectations:

Configuring expectations
^^^^^^^^^^^^^^^^^^^^^^^^

Expectations define how many times and with what arguments a method should be called.


.. _test-doubles.mock-objects.reference.configuring-expectations.once:

``once()``
""""""""""

The method must be called exactly once.

.. code-block:: php

   $mock = $this->createMock(InterfaceName::class);

   $mock
       ->expects($this->once())
       ->method('doSomething');

``once()`` is a convenience wrapper for ``exactly(1)``.


.. _test-doubles.mock-objects.reference.configuring-expectations.exactly:

``exactly(int $count)``
"""""""""""""""""""""""

The method must be called exactly ``$count`` times.

.. code-block:: php

   $mock = $this->createMock(InterfaceName::class);

   $mock
       ->expects($this->exactly(2))
       ->method('doSomething');


.. _test-doubles.mock-objects.reference.configuring-expectations.atLeastOnce:

``atLeastOnce()``
"""""""""""""""""

The method must be called at least once.

.. code-block:: php

   $mock = $this->createMock(InterfaceName::class);

   $mock
       ->expects($this->atLeastOnce())
       ->method('doSomething');

``atLeastOnce()`` is a convenience wrapper for ``atLeast(1)``.

.. admonition:: Avoid using ``atLeastOnce()``

   Non-exact invocation expectations like ``atLeastOnce()`` should generally be avoided because they make test
   intent less explicit. When the exact number of invocations does not matter, a test stub is usually more
   appropriate than a mock object. When the number of invocations does matter, ``exactly()`` or ``once()``
   communicates intent more clearly.

   See `GitHub issue #6483 <https://github.com/sebastianbergmann/phpunit/issues/6483>`_ for details.


.. _test-doubles.mock-objects.reference.configuring-expectations.atLeast:

``atLeast(int $requiredInvocations)``
"""""""""""""""""""""""""""""""""""""

The method must be called at least ``$requiredInvocations`` times.

.. code-block:: php

   $mock = $this->createMock(InterfaceName::class);

   $mock
       ->expects($this->atLeast(2))
       ->method('doSomething');

.. admonition:: Avoid using ``atLeast()``

   Non-exact invocation expectations like ``atLeast()`` should generally be avoided because they make test
   intent less explicit. When the exact number of invocations does not matter, a test stub is usually more
   appropriate than a mock object. When the number of invocations does matter, ``exactly()`` communicates
   intent more clearly.

   See `GitHub issue #6483 <https://github.com/sebastianbergmann/phpunit/issues/6483>`_ for details.


.. _test-doubles.mock-objects.reference.configuring-expectations.atMost:

``atMost(int $allowedInvocations)``
"""""""""""""""""""""""""""""""""""

The method must not be called more than ``$allowedInvocations`` times.

.. code-block:: php

   $mock = $this->createMock(InterfaceName::class);

   $mock
       ->expects($this->atMost(2))
       ->method('doSomething');

.. admonition:: Avoid using ``atMost()``

   Non-exact invocation expectations like ``atMost()`` should generally be avoided because they make test
   intent less explicit. When the exact number of invocations does not matter, a test stub is usually more
   appropriate than a mock object. When the number of invocations does matter, ``exactly()`` or ``never()``
   communicates intent more clearly.

   See `GitHub issue #6483 <https://github.com/sebastianbergmann/phpunit/issues/6483>`_ for details.


.. _test-doubles.mock-objects.reference.configuring-expectations.never:

``never()``
"""""""""""

The method must not be called.

.. code-block:: php

   $mock = $this->createMock(InterfaceName::class);

   $mock
       ->expects($this->never())
       ->method('doSomething');

``never()`` is a convenience wrapper for ``exactly(0)``.


.. _test-doubles.mock-objects.reference.configuring-expectations.any:

``any()``
"""""""""

The method may be called zero or more times.
No expectation is set on the number of invocations, so the test will not fail regardless of how many times the method is called (including not at all).

.. code-block:: php

   $mock = $this->createMock(InterfaceName::class);

   $mock
       ->expects($this->any())
       ->method('doSomething')
       ->willReturn('value');

The mock object configuration shown above is equivalent to the test stub configuration shown below:

.. code-block:: php

   $stub = $this->createStub(InterfaceName::class);

   $stub
       ->method('doSomething')
       ->willReturn('value');

.. admonition:: Deprecation: ``any()`` is deprecated

   The ``any()`` matcher, used as ``$this->expects($this->any())``, is deprecated.
   It will be removed in PHPUnit 14.

   Using ``any()`` with ``expects()`` is contradictory: you are creating a mock object (designed to verify
   communication between objects) while essentially saying "I don't care if this communication happens at all".

   If you do not need to verify that a method is called, use ``createStub()`` instead of ``createMock()``.
   Test stubs are the appropriate choice when you only need to control what a dependency returns
   without verifying how many times it is called.

   See `GitHub issue #6461 <https://github.com/sebastianbergmann/phpunit/issues/6461>`_ for details.


.. _test-doubles.mock-objects.reference.configuring-expectations.with:

``with()``
""""""""""

The ``with()`` method verifies the arguments passed to the mocked method.

.. code-block:: php

   $mock = $this->createMock(InterfaceName::class);

   $mock
       ->expects($this->once())
       ->method('doSomething')
       ->with('argument 1', 'argument 2');

In the example shown above, we configure the mock object to expect exactly one call to the method ``doSomething()``.
For this single call, the arguments ``"argument 1"`` and ``argument 2"`` must be passed.

The example shown above is equivalent to the following:

.. code-block:: php

   $mock = $this->createMock(InterfaceName::class);

   $mock
       ->expects($this->once())
       ->method('doSomething')
       ->with($this->equalTo('argument 1'), $this->equalTo('argument 2'));

The ``with()`` method verifies the arguments passed to the mocked method using ``Constraint`` objects.
If a value passed to ``with()`` is not a ``Constraint`` object then that value is automatically wrapped in a ``Constraint`` object that verifies equality.
A ``Constraint`` object that verifies equality can be manually created using ``$this->equalTo()``.

.. admonition:: Deprecation: Using ``with()`` without ``expects()`` is deprecated

   Using ``with()`` on a mock object without also using ``expects()`` is deprecated.
   This will no longer be possible in PHPUnit 14.

   The ``with()`` method is used to verify the arguments passed to a method, which is an expectation about
   how the mock object is called. Using ``with()`` without ``expects()`` is contradictory: you are specifying
   which arguments a method should be called with while not setting up an expectation for how many times the
   method should be called. Use ``expects()`` together with ``with()`` to make the intent of your test explicit.

PHPUnit provides many constraint methods for argument verification:

**Identity and Equality**

* ``identicalTo()``
* ``equalTo()``
* ``equalToCanonicalizing()``
* ``equalToIgnoringCase()``
* ``equalToWithDelta()``
* ``objectEquals()``

**Cardinality**

* ``isEmpty()``
* ``countOf()``
* ``greaterThan()``
* ``greaterThanOrEqual()``
* ``lessThan()``
* ``lessThanOrEqual()``

**Math**

* ``isFinite()``
* ``isInfinite()``
* ``isNan()``

**Boolean**

* ``isFalse()``
* ``isTrue()``

**Operator**

* ``logicalAnd()``
* ``logicalNot()``
* ``logicalOr()``
* ``logicalXor()``

**String**

* ``isJson()``
* ``matches()``
* ``matchesRegularExpression()``
* ``stringContains()``
* ``stringEndsWith()``
* ``stringEqualsStringIgnoringLineEndings()``
* ``stringStartsWith()``

**Traversable**

* ``arrayHasKey()``
* ``containsEqual()``
* ``containsIdentical()``
* ``containsOnlyArray()``
* ``containsOnlyBool()``
* ``containsOnlyCallable()``
* ``containsOnlyClosedResource()``
* ``containsOnlyFloat()``
* ``containsOnlyInstancesOf()``
* ``containsOnlyInt()``
* ``containsOnlyIterable()``
* ``containsOnlyNull()``
* ``containsOnlyNumeric()``
* ``containsOnlyObject()``
* ``containsOnlyResource()``
* ``containsOnlyScalar()``
* ``containsOnlyString()``
* ``isList()``

**Type**

* ``isArray()``
* ``isBool()``
* ``isCallable()``
* ``isClosedResource()``
* ``isFloat()``
* ``isInstanceOf()``
* ``isInt()``
* ``isIterable()``
* ``isNull()``
* ``isNumeric()``
* ``isObject()``
* ``isResource()``
* ``isScalar()``
* ``isString()``

**Filesystem**

* ``directoryExists()``
* ``fileExists()``
* ``isReadable()``
* ``isWritable()``


Expecting calls to the same method with varying arguments in specific order
"""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""

When testing code that calls the same method multiple times with different arguments, we often need to verify that:

1. The method is called the expected number of times
2. Each call receives the correct arguments
3. The calls happen in a specific order

Consider a service that depends on a ``Dispatcher`` to dispatch events using its ``dispatch()`` method.
These are use cases where the order in which these events are dispatched matters.
We cannot handle this scenario using ``with()`` (see above) because setting up two separate expectations using ``expects()`` for the same method does not work (the second overwrites the first).

The ``withParameterSetsInOrder()`` method solves this by allowing us to specify multiple parameter sets that must be matched in sequence:

.. code-block:: php

   $dispatcher = $this->createMock(Dispatcher::class);

   $dispatcher
       ->expects($this->exactly(2))
       ->method('dispatch')
       ->withParameterSetsInOrder(
           [new AnEvent],
           [new AnotherEvent],
       );

   $service = new Service($dispatcher);

   $service->doSomething();

**Ordered parameter sets** allow us to verify that a method is called multiple times with different arguments in a specific order.
This is essential when the order of operations affects correctness or we need to verify a specific workflow or protocol.

The test will fail if:

* The method is called with the wrong arguments
* The calls happen in the wrong order
* The method is called fewer or more times than expected


Expecting calls to the same method with varying arguments in any order
""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""

Sometimes we need to verify that a method is called multiple times with specific arguments, but the order of the calls does not matter.
For example:

* Logging multiple events where sequence is irrelevant
* Sending notifications to multiple recipients
* Recording audit entries that are independent of each other

The ``withParameterSetsInAnyOrder()`` method allows us to specify multiple parameter sets that must all be used, but can be matched in any order:

.. code-block:: php

   $dispatcher = $this->createMock(Dispatcher::class);

   $dispatcher
       ->expects($this->exactly(2))
       ->method('dispatch')
       ->withParameterSetsInAnyOrder(
           [new AnotherEvent],
           [new AnEvent],
       );

   $service = new Service($dispatcher);

   $service->doSomething();

**Unordered parameter sets** allow us to verify that a method is called multiple times with specific arguments, without enforcing a particular sequence.
This is useful when:

1. The order of operations does not affect correctness
2. We want to allow implementation flexibility
3. We are testing code where calls might be reordered for optimization

The test will fail if:

- The method is called with arguments that do not match any expected parameter set
- Not all parameter sets are used (some expected calls are missing)
- The method is called fewer or more times than expected


.. _test-doubles.mock-objects.reference.configuring-expectations.verifying-relative-call-order-between-mock-object-expectations:

Verifying relative call order between mock object expectations
""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""

The ``id()`` and ``after()`` methods allow us to define dependencies between mock expectations, ensuring that one method is called only after another method has been called first.

* ``id(string $id)``: Assigns a unique identifier to an expectation
* ``after(string $id)``: Specifies that this expectation should only be verified after the expectation with the given ID has been matched

Below is a complete example demonstrating how to use ``id()`` and ``after()`` to verify that ``two()`` is called after ``one()``:

.. code-block:: php

   public function testTwoIsCalledAfterOne(): void
   {
       $mock = $this->createMock(ServiceInterface::class);

       $mock
           ->expects($this->once())
           ->method('one')
           ->id('first-call');

       $mock
           ->expects($this->once())
           ->method('two')
           ->after('first-call');

       $mock->one();
       $mock->two();
   }

**1. Configuring the first expectation**

.. code-block:: php

   $mock
       ->expects($this->once())
       ->method('one')
       ->id('first-call');

* ``expects($this->once())`` sets up an expectation that the following method should be called exactly once
* ``method('one')`` specifies that this expectation applies to the `one()` method
* ``id('first-call')`` assigns the unique identifier ``"first-call"`` to this expectation. This ID can be referenced by other expectations using ``after()``.

**2. Configuring the second expectation**

.. code-block:: php

   $mock
       ->expects($this->once())
       ->method('two')
       ->after('first-call');

* ``expects($this->once())`` sets up an expectation that ``two()`` should be called exactly once
* ``method('two')`` specifies that this expectation applies to the ``two()`` method
* ``after('first-call')`` creates a dependency on the expectation with ID ``"first-call"'"``

This means:

* The expectation for ``two()`` will only be verified **after** ``one()`` has been called
* If ``two()`` is called before ``one()``, that call will not count toward satisfying this expectation
* The test will fail if ``two()`` is not called after ``one()`` has been called

**3. Executing the system under test**

.. code-block:: php

   $mock->one();
   $mock->two();

* The call to ``one()`` on the mock object satisfies the first expectation and "unlocks" the second expectation
* The call to ``two()`` on the mock object satisfies the second expectation, since ``one()`` was already called

If ``two()`` is called **before** ``one()``, that call does **not** count toward satisfying the ``after()`` expectation.

Attempting to register the same ID twice will cause the test to error.
Using ``after()`` with an ID that has not been registered with ``id()`` will cause the test to fail.

.. admonition:: Warning: Avoid using ``id()`` and ``after()``

   The ``id()`` and ``after()`` methods should be avoided as they introduce unnecessary complexity and make tests harder to understand and maintain.

   Tests that rely on strict call ordering are often brittle and may break when implementation details change, even if the behavior remains correct.

**Why using id() and after() should be avoided:**

* **Brittle Tests:** Tests that verify call order are tightly coupled to implementation details. Refactoring code that changes the order of internal calls (without changing behavior) will break these tests.
* **Complexity:** The ``id()`` and ``after()`` mechanism adds cognitive complexity. Readers of your tests need to understand this additional concept.
* **Hidden Dependencies:** The relationship between expectations is not immediately obvious, making tests harder to debug when they fail.
* **Limited Flexibility:** The mechanism only supports simple "A before B" relationships. More complex ordering requirements become even more unwieldy.
* **Better Alternatives Exist:** Most scenarios where call order matters can be tested more effectively:
   - Test the final state or output rather than intermediate calls
   - Use integration tests for workflows where order matters
   - Design interfaces that do not require specific call ordering
   - Use ``withParameterSetsInAnyOrder()`` or ``withParameterSetsInOrder()`` instead


.. _test-doubles.mock-objects.reference.configuring-expectations.methods-that-never-return:

Methods that never return
"""""""""""""""""""""""""

The ``never`` return type indicates that a function or method will never return normally. Such methods either:

* Throw an exception
* Call ``exit()`` or ``die()``
* Enter an infinite loop

When writing unit tests, mocking methods with a ``never`` return type requires special handling because the mock cannot actually "never return" as it must do something when called.

When you create a mock object of an interface or extendable class that has a method with a ``never`` return type, the mock object will throw a ``NeverReturningMethodException`` when that method is called.
This exception simulates the behavior of a method that never returns normally.

Consider the following scenario where we have an ``ErrorHandler`` interface with a ``handle()`` method that has a ``never`` return type:

.. literalinclude:: examples/test-doubles/src/never/ErrorHandler.php
   :caption: An interface that defines an error handler that never returns
   :language: php

.. literalinclude:: examples/test-doubles/src/never/ErrorHandlerImplementation.php
   :caption: An implementation of the error handler interface
   :language: php

.. literalinclude:: examples/test-doubles/src/never/ServiceImplementation.php
   :caption: A class that depends on the error handler
   :language: php

We want to test that, when an exception is thrown during the execution of ``ServiceImplementation::doSomething()``, the ``ErrorHandler::handle()`` method is called with an exception object of the expected type. Here is how to do that:

**1. Creating the mock object**

.. code-block:: php

   $errorHandler = $this->createMock(ErrorHandler::class);

This line creates a mock object that implements the ``ErrorHandler`` interface.
The mock object will have all the methods defined in the interface, including the ``handle()`` method with its ``never`` return type.

**2. Configuring the mock object**

.. code-block:: php

   $errorHandler
       ->expects($this->once())
       ->method('handle')
       ->with($this->isInstanceOf(Exception::class))
       ->seal();

This fluent chain configures the mock object's behavior and expectations:

* ``->expects($this->once())``: Sets up an expectation that the ``handle()`` method will be called exactly once during the test. If the method is not called, or called more than once, the test will fail.
* ``->method('handle')``: Specifies that we are configuring the ``handle()`` method of the mock object.
* ``->with($this->isInstanceOf(Exception::class))``: Specifies that the ``handle()`` method must be called with an argument that is an instance of the ``Exception`` class. This constraint validates the argument passed to the method.
* ``->seal()``: Finalizes the mock configuration and prevents any further configuration changes.

**3. Creating the system under test**

.. code-block:: php

   $service = new ServiceImplementation($errorHandler);

This line creates an instance of the ``ServiceImplementation`` class, injecting the mock object we created and configured to replace the ``ErrorHandler`` dependency.
This is standard dependency injection, allowing us to test ``ServiceImplementation`` in isolation from its dependency ``ErrorHandler``.

**4. Configuring the expectation that the mocked method never returns**

.. code-block:: php

   $this->expectException(NeverReturningMethodException::class);

This line tells PHPUnit to expect that a ``NeverReturningMethodException`` will be thrown during the test.
This is the key to testing methods with ``never`` return type:

* When ``$service->doSomething()`` is called, it will internally call ``$this->errorHandler->handle($e)``
* Since ``handle()`` has a `never` return type, PHPUnit throws a ``NeverReturningMethodException``
* By expecting this exception, we document our assumption that the ``handle()`` method never returns

**5. Executing the system under test**

.. code-block:: php

   $service->doSomething();

This line calls the method we want to test. The execution flow is:

1. ``doSomething()`` is called on the service
2. Inside ``doSomething()``, an exception is thrown and caught
3. The caught exception is passed to ``$this->errorHandler->handle($e)``
4. The mock object's ``handle()`` method is invoked
5. PHPUnit verifies the expectation (called once with an ``Exception`` instance)
6. PHPUnit throws ``NeverReturningMethodException`` because the method has a ``never`` return type
7. The test passes because we expected this exception

Always use ``$this->expectException(NeverReturningMethodException::class)`` when testing code that calls a mocked method with ``never`` return type.

**The exception replaces the "never return" behavior:** In real code, a method with ``never`` return type would call ``exit()``, throw an exception, or loop forever.
In tests, ``NeverReturningMethodException`` simulates this by providing a controlled way to exit the method.

**Verification still works:** Even though an exception is thrown, PHPUnit still verifies that your expectations (like ``expects($this->once())`` and ``with()``) are met.

This pattern allows you to effectively test code that depends on methods that never return normally, while still being able to verify that those methods are called with the correct arguments.


.. _test-doubles.mock-objects.reference.configuring-expectations.set-hooked-properties:

Set-Hooked Properties
"""""""""""""""""""""

The example below shows an interface that declares a `set-hooked property <https://www.php.net/manual/en/language.oop5.property-hooks.php>`_:

.. literalinclude:: examples/test-doubles/src/InterfaceWithSetHookedProperty.php
   :caption: Interface that declares a set-hooked property
   :language: php

Expectations for the set-hooked property ``property`` can be configured like so:

.. literalinclude:: examples/test-doubles/SetHookedPropertyMockExampleTest.php
   :caption: Test that uses a mock object of an interface with a set-hooked property
   :language: php

In the example shown above, ``PropertyHook::set('property')`` to specify that we want to configure an expectation for the method that is called when the property named ``property`` is accessed for writing.


.. _test-doubles.best-practices:

Best Practices
==============

1. **Favour doubling interfaces** over doubling classes.
2. **Use meaningful names:** Name your test doubles clearly to indicate their purpose.
3. **Keep it simple:** Do not over-configure test doubles. Only configure the methods you need.
4. **Test at the right level:** Mock service boundaries (repositories, external services), not domain objects or value objects.
5. **One concept per test:** Test either state (with stubs) or behavior (with mocks), not both in the same test.
6. **Avoid brittle tests:** Do not mock internal implementation details. Mock interfaces and public contracts.
7. **Review PHPUnit notices:** If you see a notice about unused mock objects, consider whether you should add expectations or use a test stub instead.


Sealed Test Doubles
-------------------

The ``seal()`` method is part of the fluent API for configuring test stubs and mock objects.
When you seal a test double, you prevent any further configuration and signal that the test double is fully configured.

**Key Benefits**

* **Prevents accidental misconfiguration**: Once sealed, any attempt to configure additional behavior or expectations will error the test
* **Stricter mock behavior**: For mock objects, sealing adds implicit ``never()`` expectations for all methods that were not explicitly configured, causing the test to fail if those methods are called
* **Self-documenting tests**: Sealing explicitly communicates that the test double configuration is complete


Sealed Test Stubs
^^^^^^^^^^^^^^^^^

This example shows how a test stub is sealed using the ``seal()`` method:

.. code-block:: php

   public function testDemonstratingSealedTestStubs(): void
   {
       $stub = $this->createStub(InterfaceName::class);

       $stub
           ->method('doSomething')
           ->willReturn('value')
           ->seal();

       // ...

       $stub->doSomething();
       $stub->doSomethingElse();
   }

A sealed test stub's method that has not been configured, such as ``doSomethingElse()`` in the example above, can be called.

Unless the return value generator is disabled, a sealed test stub's method for which no behaviour has been configured (returning a value or throwing an exception) will return the minimum viable value compatible with the method's return type declaration.

Sealed Mock Objects
^^^^^^^^^^^^^^^^^^^

This example shows how a mock object is sealed using the ``seal()`` method:

.. code-block:: php

   public function testDemonstratingSealedMockObjects(): void
   {
       $mock = $this->createMock(InterfaceName::class);

       $mock
           ->expects($this->once())
           ->method('doSomething')
           ->seal();

       // ...

       $mock->doSomething();
       $mock->doSomethingElse();
   }

A sealed mock object's method that has not been configured, such as ``doSomethingElse()`` in the above example, is not expected to be called.
If it is called, then the test will fail. This is equivalent to the following:

.. code-block:: php

   public function testDemonstratingSealedMockObjects(): void
   {
       $mock = $this->createMock(InterfaceName::class);

       $mock
           ->expects($this->once())
           ->method('doSomething');

       $mock
           ->expects($this->never())
           ->method('doSomethingElse');

       // ...

       $mock->doSomething();
       $mock->doSomethingElse();
   }


.. _test-doubles.mock-objects.requiring-sealed-mock-objects:

Requiring sealed mock objects
"""""""""""""""""""""""""""""

The ``seal()`` method explained above provides developers with an opt-in mechanism to finalise the configuration of mock objects and prevent unexpected method calls.
However, individual developers must remember to call this method on their mock objects for this safety feature to take effect.

As projects mature and teams establish testing standards, it becomes valuable to enforce certain testing practices at the project level, rather than relying on individual developers to apply them consistently.
This is why ``requireSealedMockObjects="true"`` can be used in PHPUnit's XML configuration file to set up a project-level policy to enforce the best practice of sealing mock objects.
