

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

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

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

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

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
        public function testAdd(int $expected, int $a, int $b)
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


.. _appendixes.attributes.DoesNotPerformAssertions:

``DoesNotPerformAssertions``
============================

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

By default, PHPUnit considers a test that does not perform assertions and does not configure
expectations on mock objects as :ref:`risky <risky-tests.useless-tests>`. The
``DoesNotPerformAssertions`` attribute can be used to prevent this.


Code Coverage
=============

.. _appendixes.attributes.CoversClass:

``CoversClass``
---------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | yes        |
+------------+-------------+--------------+------------+

The ``CoversClass(string $className)`` attribute can be used to :ref:`specify <code-coverage.specifying-covered-parts>`
that a test intends to cover the given class.


.. _appendixes.attributes.CoversFunction:

``CoversFunction``
------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | yes        |
+------------+-------------+--------------+------------+

The ``CoversFunction(string $functionName)`` attribute can be used to :ref:`specify <code-coverage.specifying-covered-parts>`
that a test intends to cover the given global function.


.. _appendixes.attributes.CoversNothing:

``CoversNothing``
-----------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

The ``CoversNothing()`` attribute can be used to :ref:`specify <code-coverage.specifying-covered-parts>`
that a test does not intend contribute to code coverage.


.. _appendixes.attributes.UsesClass:

``UsesClass``
-------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | yes        |
+------------+-------------+--------------+------------+

The ``UsesClass(string $className)`` attribute can be used to :ref:`specify <code-coverage.specifying-covered-parts>`
that a test allows the execution of code in the given class, but does not intend to cover it. This is relevant
in the context of :ref:`preventing unintentionally covered code <risky-tests.unintentionally-covered-code>`.


.. _appendixes.attributes.UsesFunction:

``UsesFunction``
----------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | yes        |
+------------+-------------+--------------+------------+

The ``UsesFunction(string $functionName)`` attribute can be used to :ref:`specify <code-coverage.specifying-covered-parts>`
that a test allows the execution of code in the given global function, but does not intend to cover it. This is relevant
in the context of :ref:`preventing unintentionally covered code <risky-tests.unintentionally-covered-code>`.


.. _appendixes.attributes.CodeCoverageIgnore:

``CodeCoverageIgnore``
----------------------

+-----------------+-------------+--------------+------------+
| Context         | Class Level | Method Level | Repeatable |
+=================+=============+==============+============+
| Production Code | yes         | yes          | no         |
+-----------------+-------------+--------------+------------+

The ``CodeCoverageIgnore`` attribute can be used to to :ref:`ignore <code-coverage.ignoring-code-blocks>`
a class or method for code coverage.


Data Provider
=============

.. _appendixes.attributes.DataProvider:

``DataProvider``
----------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

The ``DataProvider(string $methodName)`` attribute can be used on a test method
to specify a static method that is declared in the same class as the test method
as a :ref:`data provider <writing-tests-for-phpunit.data-providers>`.


.. _appendixes.attributes.DataProviderExternal:

``DataProviderExternal``
------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

The ``DataProviderExternal(string $className, string $methodName)`` attribute can be used
on a test method to specify a static method that is declared in another class as a
:ref:`data provider <writing-tests-for-phpunit.data-providers>`.


.. _appendixes.attributes.TestWith:

``TestWith``
------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

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

    $ phpunit DataTest.php
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

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

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

    $ phpunit DataTest.php
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

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.DependsUsingDeepClone:

``DependsUsingDeepClone``
-------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.DependsUsingShallowClone:

``DependsUsingShallowClone``
----------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.DependsExternal:

``DependsExternal``
-------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.DependsExternalUsingDeepClone:

``DependsExternalUsingDeepClone``
---------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.DependsExternalUsingShallowClone:

``DependsExternalUsingShallowClone``
------------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.DependsOnClass:

``DependsOnClass``
------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.DependsOnClassUsingDeepClone:

``DependsOnClassUsingDeepClone``
--------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.DependsOnClassUsingShallowClone:

``DependsOnClassUsingShallowClone``
-----------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


Test Groups
===========

.. _appendixes.attributes.Group:

``Group``
---------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.Small:

``Small``
---------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | no         |
+------------+-------------+--------------+------------+

The ``Small`` attribute is an alias for ``Group('small')``.


.. _appendixes.attributes.Medium:

``Medium``
----------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | no         |
+------------+-------------+--------------+------------+

The ``Medium`` attribute is an alias for ``Group('medium')``.


.. _appendixes.attributes.Large:

``Large``
---------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | no         |
+------------+-------------+--------------+------------+

The ``Large`` attribute is an alias for ``Group('large')``.


.. _appendixes.attributes.Ticket:

``Ticket``
----------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

The ``Ticket(string $text)`` attribute is an alias for ``Group(string $text)``.


Template Methods
================

.. _appendixes.attributes.BeforeClass:

``BeforeClass``
---------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.Before:

``Before``
----------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.PreCondition:

``PreCondition``
----------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.PostCondition:

``PostCondition``
-----------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.After:

``After``
---------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.AfterClass:

``AfterClass``
--------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

...


Test Isolation
==============

.. _appendixes.attributes.BackupGlobals:

``BackupGlobals``
-----------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.ExcludeGlobalVariableFromBackup:

``ExcludeGlobalVariableFromBackup``
-----------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.BackupStaticProperties:

``BackupStaticProperties``
--------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.ExcludeStaticPropertyFromBackup:

``ExcludeStaticPropertyFromBackup``
-----------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.RunInSeparateProcess:

``RunInSeparateProcess``
------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.RunTestsInSeparateProcesses:

``RunTestsInSeparateProcesses``
-------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | no         |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.RunClassInSeparateProcess:

``RunClassInSeparateProcess``
-----------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | no         |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.PreserveGlobalState:

``PreserveGlobalState``
-----------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...


Skipping Tests
==============

.. _appendixes.attributes.RequiresPhp:

``RequiresPhp``
---------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.RequiresPhpExtension:

``RequiresPhpExtension``
------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.RequiresSetting:

``RequiresSetting``
-------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.RequiresPhpunit:

``RequiresPhpunit``
-------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.RequiresFunction:

``RequiresFunction``
--------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.RequiresMethod:

``RequiresMethod``
------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.RequiresOperatingSystem:

``RequiresOperatingSystem``
---------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...


.. _appendixes.attributes.RequiresOperatingSystemFamily:

``RequiresOperatingSystemFamily``
---------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...
