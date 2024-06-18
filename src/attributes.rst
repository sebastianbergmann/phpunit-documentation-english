

.. _appendixes.attributes:

**********
Attributes
**********

Prior to PHPUnit 10, annotations in special PHP comments, so-called "DocBlocks" or "doc-comments",
were the only means of attaching metadata to code units. These annotations are documented in
:ref:`another appendix <appendixes.annotations>`.

PHP 8 introduced `attributes <https://wiki.php.net/rfc/attributes_v2>`_ as "a form of structured,
syntactic metadata to declarations of classes, properties, functions, methods, parameters and
constants. Attributes allow to define configuration directives directly embedded with the
declaration of that code."

PHPUnit will first look for metadata in attributes before it looks for annotations in comments.
When metadata is found in attributes, metadata in comments is ignored.

The attributes supported by PHPUnit are all declared in the ``PHPUnit\Framework\Attributes``
namespace. They are documented in this appendix.

.. _appendixes.attributes.Test:

``Test``
========

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

As an alternative to prefixing your test method names with ``test``,
you can use the ``Test`` attribute to mark it as a test method.

.. code-block:: php
    :caption: Using the ``Test`` attribute
    :name: appendixes.attributes.test.examples.ExampleTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\Test;
    use PHPUnit\Framework\TestCase;

    final class ExampleTest extends TestCase
    {
        #[Test]
        public function it_does_something(): void
        {
            // ...
        }
    }


.. _appendixes.attributes.TestDox:

``TestDox``
===========

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | no         |
+-------------+--------------+------------+

The ``TestDox(string $text)`` attribute can be used to customize the text that is printed for
a test when TestDox output is enabled.

.. code-block:: php
    :caption: Using the ``TestDox`` attribute
    :name: appendixes.attributes.testdox.examples.ExampleTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\TestDox;
    use PHPUnit\Framework\TestCase;

    final class ExampleTest extends TestCase
    {
        #[TestDox('It does something')]
        public function testOne(): void
        {
            // ...
        }
    }

Running the test shown above with TestDox output enabled yields the output shown below:

.. parsed-literal::

    $ ./tools/phpunit --no-progress --testdox tests/ExampleTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.2

    Time: 00:00.057, Memory: 6.00 MB

    Example
     ✔ It does something

    OK (1 test, 1 assertion)

When you use the ``TestDox`` attribute for a test method that uses a
:ref:`data provider <writing-tests-for-phpunit.data-providers>` then you
may use the method parameters as placeholders in your alternative description.

.. code-block:: php
    :caption: Using the ``TestDox`` attribute together with data providers
    :name: appendixes.attributes.testdox.examples.ExampleTest2.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\DataProvider;
    use PHPUnit\Framework\Attributes\TestDox;
    use PHPUnit\Framework\TestCase;

    final class ExampleTest extends TestCase
    {
        #[DataProvider('additionProvider')]
        #[TestDox('Adding $a to $b results in $expected')]
        public function testAdd(int $a, int $b, int $expected)
        {
            $this->assertSame($expected, $a + $b);
        }

        public static function additionProvider()
        {
            return [
                'data set 1' => [0, 0, 0],
                'data set 2' => [0, 1, 1],
                'data set 3' => [1, 0, 1],
                'data set 4' => [1, 1, 3]
            ];
        }
    }

Running the test shown above with TestDox output enabled yields the output shown below:

.. parsed-literal::

    $ ./tools/phpunit --no-progress --testdox tests/ExampleTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.2

    Time: 00:00.116, Memory: 8.00 MB

    Example
     ✔ Adding 0 to 0 results in 0
     ✔ Adding 1 to 0 results in 1
     ✔ Adding 0 to 1 results in 1
     ✘ Adding 1 to 1 results in 3
       │
       │ Failed asserting that 2 is identical to 3.
       │
       │ /path/to/ExampleTest.php:12
       │

    FAILURES!
    Tests: 4, Assertions: 4, Failures: 1.

Additionally, ``$_dataName`` is available and holds the name of the current data.
That would be ``data set 1`` through ``data set 4`` in the example shown above.


.. _appendixes.attributes.DisableReturnValueGenerationForTestDoubles:

``DisableReturnValueGenerationForTestDoubles``
==============================================

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | no         |
+-------------+--------------+------------+

The ``DisableReturnValueGenerationForTestDoubles`` attribute can be used to disable the return value generation
for test doubles created using ``createMock()``, ``createMockForIntersectionOfInterfaces()``, ``createPartialMock()``,
``createStub()``, and ``createStubForIntersectionOfInterfaces()`` for all tests of a test case class.


.. _appendixes.attributes.DoesNotPerformAssertions:

``DoesNotPerformAssertions``
============================

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | no         |
+-------------+--------------+------------+

By default, PHPUnit considers a test that does not perform assertions and does not configure
expectations on mock objects as :ref:`risky <risky-tests.useless-tests>`. The
``DoesNotPerformAssertions`` attribute can be used to prevent this.


.. _appendixes.attributes.IgnoreDeprecations:

``IgnoreDeprecations``
======================

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | no         |
+-------------+--------------+------------+

The ``IgnoreDeprecations`` attribute can be used to configure PHPUnit's error handler to
not emit events for ``E_DEPRECATED`` and ``E_USER_DEPRECATED`` errors.


.. _appendixes.attributes.WithoutErrorHandler:

``WithoutErrorHandler``
=======================

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``WithoutErrorHandler`` attribute can be used to disable PHPUnit's error handler for
a test method.

.. admonition:: Warning

   Features of PHPUnit that rely on PHPUnit's error handler to be active while a test method
   is executed will not work when PHPUnit's error handler is disabled. No ``E_(USER_)*`` errors
   triggered by PHP will be processed by PHPUnit when its error handler is disabled.

   You should only disable PHPUnit's error handler when it interferes with the code you are testing,
   for instance when it uses ``error_get_last()`` to react to ``E_(USER_)*`` errors triggered by PHP.


Code Coverage
=============

.. _appendixes.attributes.CoversClass:

``CoversClass``
---------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``CoversClass(string $className)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test intends to cover the given class.


.. _appendixes.attributes.CoversTrait:

``CoversTrait``
---------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``CoversTrait(string $traintName)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test intends to cover the given trait.


.. _appendixes.attributes.CoversMethod:

``CoversMethod``
----------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``CoversMethod(string $className, string $methodName)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test intends to cover the given method.


.. _appendixes.attributes.CoversFunction:

``CoversFunction``
------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``CoversFunction(string $functionName)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test intends to cover the given function.


.. _appendixes.attributes.CoversNothing:

``CoversNothing``
-----------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | no         |
+-------------+--------------+------------+

The ``CoversNothing()`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test does not intend to contribute to code coverage.


.. _appendixes.attributes.UsesClass:

``UsesClass``
-------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``UsesClass(string $className)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test allows the execution of code in the given class, but does not intend to cover it. This is relevant
in the context of :ref:`preventing unintentionally covered code <risky-tests.unintentionally-covered-code>`.


.. _appendixes.attributes.UsesTrait:

``UsesTrait``
-------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``UsesTrait(string $traitName)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test allows the execution of code in the given trait, but does not intend to cover it. This is relevant
in the context of :ref:`preventing unintentionally covered code <risky-tests.unintentionally-covered-code>`.


.. _appendixes.attributes.UsesMethod:

``UsesMethod``
--------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``UsesMethod(string $className)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test allows the execution of code in the given method, but does not intend to cover it. This is relevant
in the context of :ref:`preventing unintentionally covered code <risky-tests.unintentionally-covered-code>`.


.. _appendixes.attributes.UsesFunction:

``UsesFunction``
----------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``UsesFunction(string $functionName)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test allows the execution of code in the given global function, but does not intend to cover it. This is relevant
in the context of :ref:`preventing unintentionally covered code <risky-tests.unintentionally-covered-code>`.

Data Provider
=============

.. _appendixes.attributes.DataProvider:

``DataProvider``
----------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``DataProvider(string $methodName)`` attribute can be used on a test method
to specify a static method that is declared in the same class as the test method
as a :ref:`data provider <writing-tests-for-phpunit.data-providers>`.


.. _appendixes.attributes.DataProviderExternal:

``DataProviderExternal``
------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``DataProviderExternal(string $className, string $methodName)`` attribute can be used
on a test method to specify a static method that is declared in another class as a
:ref:`data provider <writing-tests-for-phpunit.data-providers>`.


.. _appendixes.attributes.TestWith:

``TestWith``
------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``TestWith(array $data)`` attribute can be used to define a
:ref:`data provider <writing-tests-for-phpunit.data-providers>` for a
test method without having to implement a static data provider method.

.. code-block:: php
    :caption: Using the ``TestWith`` attribute
    :name: appendixes.attributes.testwith.examples.ExampleTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\TestWith;
    use PHPUnit\Framework\TestCase;

    final class DataTest extends TestCase
    {
        #[TestWith([0, 0, 0])]
        #[TestWith([0, 1, 1])]
        #[TestWith([1, 0, 1])]
        #[TestWith([1, 1, 3])]
        public function testAdd(int $a, int $b, int $expected): void
        {
            $this->assertSame($expected, $a + $b);
        }
    }

Running the test shown above yields the output shown below:

.. parsed-literal::

    ./tools/phpunit tests/DataTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.2

    ...F                                                                4 / 4 (100%)

    Time: 00:00.058, Memory: 8.00 MB

    There was 1 failure:

    1) DataTest::testAdd with data set #3
    Failed asserting that 2 is identical to 3.

    /path/to/DataTest.php:10

    FAILURES!
    Tests: 4, Assertions: 4, Failures: 1.


.. _appendixes.attributes.TestWithJson:

``TestWithJson``
----------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``TestWithJson(string $json)`` attribute can be used to define a
:ref:`data provider <writing-tests-for-phpunit.data-providers>` for a
test method without having to implement a static data provider method.

.. code-block:: php
    :caption: Using the ``TestWithJson`` attribute
    :name: appendixes.attributes.testwithjson.examples.ExampleTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\TestWithJson;
    use PHPUnit\Framework\TestCase;

    final class DataTest extends TestCase
    {
        #[TestWithJson('[0, 0, 0]')]
        #[TestWithJson('[0, 1, 1]')]
        #[TestWithJson('[1, 0, 1]')]
        #[TestWithJson('[1, 1, 3]')]
        public function testAdd(int $a, int $b, int $expected): void
        {
            $this->assertSame($expected, $a + $b);
        }
    }

Running the test shown above yields the output shown below:

.. parsed-literal::

    ./tools/phpunit tests/DataTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.2

    ...F                                                                4 / 4 (100%)

    Time: 00:00.058, Memory: 8.00 MB

    There was 1 failure:

    1) DataTest::testAdd with data set #3
    Failed asserting that 2 is identical to 3.

    /path/to/DataTest.php:10

    FAILURES!
    Tests: 4, Assertions: 4, Failures: 1.


Test Dependencies
=================

.. _appendixes.attributes.Depends:

``Depends``
-----------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``Depends(string $methodName)`` attribute can be used to specify that a test
:ref:`depends <writing-tests-for-phpunit.test-dependencies>`
on another test that is declared in the same test case class.

Any value that is passed from a producer (a depended-upon test) to a consumer
(the depending test) is passed without cloning it.

.. _appendixes.attributes.DependsUsingDeepClone:

``DependsUsingDeepClone``
-------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``DependsUsingDeepClone(string $methodName)`` attribute can be used to specify that a test
:ref:`depends <writing-tests-for-phpunit.test-dependencies>`
on another test that is declared in the same test case class.

Any value that is passed from a producer (a depended-upon test) to a consumer
(the depending test) is passed after deep-cloning it.


.. _appendixes.attributes.DependsUsingShallowClone:

``DependsUsingShallowClone``
----------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``DependsUsingShallowClone(string $methodName)`` attribute can be used to specify that a test
:ref:`depends <writing-tests-for-phpunit.test-dependencies>`
on another test that is declared in the same test case class.

Any value that is passed from a producer (a depended-upon test) to a consumer
(the depending test) is passed after shallow-cloning it.


.. _appendixes.attributes.DependsExternal:

``DependsExternal``
-------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``DependsExternal(string $className, string $methodName)`` attribute can be used
to specify that a test :ref:`depends <writing-tests-for-phpunit.test-dependencies>`
on another test that is declared in another test case class.

Any value that is passed from a producer (a depended-upon test) to a consumer
(the depending test) is passed without cloning it.


.. _appendixes.attributes.DependsExternalUsingDeepClone:

``DependsExternalUsingDeepClone``
---------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``DependsExternalUsingDeepClone(string $className, string $methodName)`` attribute can be used
to specify that a test :ref:`depends <writing-tests-for-phpunit.test-dependencies>`
on another test that is declared in another test case class.

Any value that is passed from a producer (a depended-upon test) to a consumer
(the depending test) is passed after deep-cloning it.


.. _appendixes.attributes.DependsExternalUsingShallowClone:

``DependsExternalUsingShallowClone``
------------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``DependsExternalUsingShallowClone(string $className, string $methodName)`` attribute can be used
to specify that a test :ref:`depends <writing-tests-for-phpunit.test-dependencies>`
on another test that is declared in another test case class.

Any value that is passed from a producer (a depended-upon test) to a consumer
(the depending test) is passed after shallow-cloning it.


.. _appendixes.attributes.DependsOnClass:

``DependsOnClass``
------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``DependsOnClass(string $className)`` attribute can be used to specify that a test
:ref:`depends <writing-tests-for-phpunit.test-dependencies>`
on all tests of another test case class.

Any value that is passed from a producer (a depended-upon test) to a consumer
(the depending test) is passed without cloning it.


.. _appendixes.attributes.DependsOnClassUsingDeepClone:

``DependsOnClassUsingDeepClone``
--------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``DependsOnClassUsingDeepClone(string $className)`` attribute can be used to specify that a test
:ref:`depends <writing-tests-for-phpunit.test-dependencies>`
on all tests of another test case class.

Any value that is passed from a producer (a depended-upon test) to a consumer
(the depending test) is passed after deep-cloning it.


.. _appendixes.attributes.DependsOnClassUsingShallowClone:

``DependsOnClassUsingShallowClone``
-----------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``DependsOnClassUsingShallowClone(string $className)`` attribute can be used to specify that a test
:ref:`depends <writing-tests-for-phpunit.test-dependencies>`
on all tests of another test case class.

Any value that is passed from a producer (a depended-upon test) to a consumer
(the depending test) is passed after shallow-cloning it.


Test Groups
===========

.. _appendixes.attributes.Group:

``Group``
---------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | yes        |
+-------------+--------------+------------+

The ``Group(string $name)`` attribute can be used to assign tests to test groups.

Groups can be used, for instance, to :ref:`select <textui.command-line-options.selection>`
which tests should be run.

The strings ``small``, ``medium``, and ``large`` may not be used as group names.

.. _appendixes.attributes.Small:

``Small``
---------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | no         |
+-------------+--------------+------------+

The ``Small`` attribute marks the tests of a test case class as small. These tests are
added to a special test group named ``small`` that has special semantics.

The size of a test is relevant in the context of
:ref:`test execution timeouts <risky-tests.test-execution-timeout>`, for instance.

Tests that are marked as small cause the lines of code that they cover to be highlighted
by a darker shade of green in the HTML :ref:`code coverage <code-coverage>` report compared
to tests that are marked :ref:`medium <appendixes.attributes.Medium>` or
:ref:`large <appendixes.attributes.Large>`.

.. _appendixes.attributes.Medium:

``Medium``
----------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | no         |
+-------------+--------------+------------+

The ``Medium`` attribute marks the tests of a test case class as medium. These tests are
added to a special test group named ``medium`` that has special semantics.

The size of a test is relevant in the context of
:ref:`test execution timeouts <risky-tests.test-execution-timeout>`, for instance.

Tests that are marked as medium cause the lines of code that they cover to be highlighted
by a darker shade of green in the HTML :ref:`code coverage <code-coverage>` report compared
to tests that are marked :ref:`large <appendixes.attributes.Large>` and by a lighter shade
of green compared to test that are marked small :ref:`small <appendixes.attributes.Small>`.


.. _appendixes.attributes.Large:

``Large``
---------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | no         |
+-------------+--------------+------------+

The ``Large`` attribute marks the tests of a test case class as large. These tests are
added to a special test group named ``large`` that has special semantics.

The size of a test is relevant in the context of
:ref:`test execution timeouts <risky-tests.test-execution-timeout>`, for instance.

Tests that are marked as large cause the lines of code that they cover to be highlighted
by a lighter shade of green in the HTML :ref:`code coverage <code-coverage>` report compared
to tests that are marked :ref:`medium <appendixes.attributes.Medium>` or
:ref:`small <appendixes.attributes.Small>`.


.. _appendixes.attributes.Ticket:

``Ticket``
----------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | yes        |
+-------------+--------------+------------+

The ``Ticket(string $text)`` attribute is an alias for ``Group(string $text)``.


Template Methods
================

.. _appendixes.attributes.BeforeClass:

``BeforeClass``
---------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``BeforeClass`` attribute can be used to specify that a public static method should
be invoked before the first test method of a test case class is run. This is equivalent
to naming the method ``setUpBeforeClass()``.

The topic of template methods such as ``setUpBeforeClass()`` is discussed in the chapter
on :ref:`fixtures <fixtures>`.

.. _appendixes.attributes.Before:

``Before``
----------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``Before`` attribute can be used to specify that a protected non-static method should
be invoked before each test method of a test case class is run. This is equivalent
to naming the method ``setUp()``.

The topic of template methods such as ``setUp()`` is discussed in the chapter
on :ref:`fixtures <fixtures>`.


.. _appendixes.attributes.PreCondition:

``PreCondition``
----------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``PreCondition`` attribute can be used to specify that a protected non-static method should
be invoked before each test method (but after any ``setUp()`` methods) of a test case class is run.
This is equivalent to naming the method ``assertPreConditions()``.


.. _appendixes.attributes.PostCondition:

``PostCondition``
-----------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``PostCondition`` attribute can be used to specify that a protected non-static method should
be invoked after each test method (but before any ``tearDown()`` methods) of a test case class is run.
This is equivalent to naming the method ``assertPostConditions()``.


.. _appendixes.attributes.After:

``After``
---------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``After`` attribute can be used to specify that a protected non-static method should
be invoked after each test method of a test case class is run. This is equivalent
to naming the method ``tearDown()``.

The topic of template methods such as ``tearDown()`` is discussed in the chapter
on :ref:`fixtures <fixtures>`.


.. _appendixes.attributes.AfterClass:

``AfterClass``
--------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``AfterClass`` attribute can be used to specify that a public static method should
be invoked after the last test method of a test case class is run. This is equivalent
to naming the method ``tearDownAfterClass()``.

The topic of template methods such as ``tearDownAfterClass()`` is discussed in the chapter
on :ref:`fixtures <fixtures>`.


Test Isolation
==============

.. _appendixes.attributes.BackupGlobals:

``BackupGlobals``
-----------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | no         |
+-------------+--------------+------------+

The ``BackupGlobals`` attribute can be used to specify that global and super-global variables
should be backed up before a test and then restored after the test has been run.


.. _appendixes.attributes.ExcludeGlobalVariableFromBackup:

``ExcludeGlobalVariableFromBackup``
-----------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | yes        |
+-------------+--------------+------------+

The ``ExcludeGlobalVariableFromBackup($globalVariableName)`` attribute can be used to exclude
the specified global variable from the backup and restore operations for global and super-global
variables.


.. _appendixes.attributes.BackupStaticProperties:

``BackupStaticProperties``
--------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | no         |
+-------------+--------------+------------+

The ``BackupStaticProperties`` attribute can be used to specify that static properties of classes
should be backed up before a test and then restored after the test has been run.


.. _appendixes.attributes.ExcludeStaticPropertyFromBackup:

``ExcludeStaticPropertyFromBackup``
-----------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | yes        |
+-------------+--------------+------------+

The ``ExcludeStaticPropertyFromBackup(string $className, string $propertyName)`` attribute can be
used to exclude the specified static property from the backup and restore operations for static
properties of classes.


.. _appendixes.attributes.RunInSeparateProcess:

``RunInSeparateProcess``
------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``RunInSeparateProcess`` attribute can be used to specify that a test should
be run in a separate process.


.. _appendixes.attributes.RunTestsInSeparateProcesses:

``RunTestsInSeparateProcesses``
-------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | no         |
+-------------+--------------+------------+

The ``RunTestsInSeparateProcesses`` attribute can be used to specify that all tests
of a test case class should be run in separate processes (one separate process per test).


.. _appendixes.attributes.RunClassInSeparateProcess:

``RunClassInSeparateProcess``
-----------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | no         |
+-------------+--------------+------------+

The ``RunClassInSeparateProcess`` attribute can be used to specify that all tests
of a test case class should be run in a (single) separate process.


.. _appendixes.attributes.PreserveGlobalState:

``PreserveGlobalState``
-----------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | no         |
+-------------+--------------+------------+

The ``PreserveGlobalState(bool $enabled)`` attribute can be used to specify whether
the global state of the main PHPUnit test runner process should be made available in
the child process when a test is run in a separate process.


Skipping Tests
==============

.. _appendixes.attributes.RequiresPhp:

``RequiresPhp``
---------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | no         |
+-------------+--------------+------------+

The ``RequiresPhp(string $versionRequirement)`` attribute can be used to
:ref:`skip the execution of a test <writing-tests-for-phpunit.skipping-tests.skipping-tests-using-attributes>`
when the PHP version used to run PHPUnit does not match the specified version requirement.

``$versionRequirement`` can either be a `version number string <https://www.php.net/manual/en/function.version-compare.php>`_
that is optionally preceded by an operator supported by PHP's ``version_compare()``
function or a `version constraint <https://getcomposer.org/doc/articles/versions.md#writing-version-constraints>`_
in the syntax that is supported by Composer.

Here are some examples:

* ``#[RequiresPhp('8.3.0')]``
* ``#[RequiresPhp('>= 8.3.0')]``
* ``#[RequiresPhp('^8.3')]``

.. _appendixes.attributes.RequiresPhpExtension:

``RequiresPhpExtension``
------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | yes        |
+-------------+--------------+------------+

The ``RequiresPhpExtension(string $extension[, string $versionRequirement])`` attribute can be used to
:ref:`skip the execution of a test <writing-tests-for-phpunit.skipping-tests.skipping-tests-using-attributes>`
when the specified PHP extension is not available. The optional ``$versionRequirement`` argument can be used
to specify a version requirement for this PHP extension and follows the same format that is described
:ref:`here <appendixes.attributes.RequiresPhp>`.

Here are some examples:

* ``#[RequiresPhpExtension('mysqli')]``
* ``#[RequiresPhpExtension('mysqli', '>= 8.3.0')]``
* ``#[RequiresPhpExtension('mysqli', '^8.3')]``


.. _appendixes.attributes.RequiresSetting:

``RequiresSetting``
-------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | yes        |
+-------------+--------------+------------+

The ``RequiresSetting(string $setting, string $value)`` attribute can be used to
:ref:`skip the execution of a test <writing-tests-for-phpunit.skipping-tests.skipping-tests-using-attributes>`
when the specified PHP configuration setting is not set to the expected value.


.. _appendixes.attributes.RequiresPhpunit:

``RequiresPhpunit``
-------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | no         |
+-------------+--------------+------------+

The ``RequiresPhpunit(string $versionRequirement)`` attribute can be used to
:ref:`skip the execution of a test <writing-tests-for-phpunit.skipping-tests.skipping-tests-using-attributes>`
when the PHPUnit version does not match the specified version requirement.

``$versionRequirement`` can either be a `version number string <https://www.php.net/manual/en/function.version-compare.php>`_
that is optionally preceded by an operator supported by PHP's ``version_compare()``
function or a `version constraint <https://getcomposer.org/doc/articles/versions.md#writing-version-constraints>`_
in the syntax that is supported by Composer.

Here are some examples:

* ``#[RequiresPhpunit('10.1.0')]``
* ``#[RequiresPhpunit('>= 10.1.0')]``
* ``#[RequiresPhpunit('^10.1')]``


.. _appendixes.attributes.RequiresFunction:

``RequiresFunction``
--------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | yes        |
+-------------+--------------+------------+

The ``RequiresFunction(string $functionName)`` attribute can be used to
:ref:`skip the execution of a test <writing-tests-for-phpunit.skipping-tests.skipping-tests-using-attributes>`
when the specified global function is not declared.


.. _appendixes.attributes.RequiresMethod:

``RequiresMethod``
------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | yes        |
+-------------+--------------+------------+

The ``RequiresMethod(string $className, string $methodName)`` attribute can be used to
:ref:`skip the execution of a test <writing-tests-for-phpunit.skipping-tests.skipping-tests-using-attributes>`
when the specified method is not declared.


.. _appendixes.attributes.RequiresOperatingSystem:

``RequiresOperatingSystem``
---------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | no         |
+-------------+--------------+------------+

The ``RequiresOperatingSystem(string $regularExpression)`` attribute can be used to
:ref:`skip the execution of a test <writing-tests-for-phpunit.skipping-tests.skipping-tests-using-attributes>`
when the specified regular expression does not match the value of the ``PHP_OS`` constant provided by PHP.


.. _appendixes.attributes.RequiresOperatingSystemFamily:

``RequiresOperatingSystemFamily``
---------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | no         |
+-------------+--------------+------------+

The ``RequiresOperatingSystemFamily(string $operatingSystemFamily)`` attribute can be used to
:ref:`skip the execution of a test <writing-tests-for-phpunit.skipping-tests.skipping-tests-using-attributes>`
when the specified string is not identical to the value of the ``PHP_OS_FAMILY`` constant provided by PHP.
