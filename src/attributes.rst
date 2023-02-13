

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

.. _appendixes.attributes.test:

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


``TestDox``
===========

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

The ``TestDox`` attribute can be used to customize the text that is printed for
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

``CoversClass``
---------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | yes        |
+------------+-------------+--------------+------------+

...


``CoversFunction``
------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | yes        |
+------------+-------------+--------------+------------+

...


``CoversNothing``
-----------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...


``UsesClass``
-------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | yes        |
+------------+-------------+--------------+------------+

...


``UsesFunction``
----------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | yes        |
+------------+-------------+--------------+------------+

...


``CodeCoverageIgnore``
----------------------

+-----------------+-------------+--------------+------------+
| Context         | Class Level | Method Level | Repeatable |
+=================+=============+==============+============+
| Production Code | yes         | yes          | no         |
+-----------------+-------------+--------------+------------+

...


Data Provider
=============

``DataProvider``
----------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


``DataProviderExternal``
------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


``TestWith``
------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


``TestWithJson``
----------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


Test Dependencies
=================

``Depends``
-----------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


``DependsUsingDeepClone``
-------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


``DependsUsingShallowClone``
----------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...

``DependsExternal``
-------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


``DependsExternalUsingDeepClone``
---------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


``DependsExternalUsingShallowClone``
------------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


``DependsOnClass``
------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


``DependsOnClassUsingDeepClone``
--------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | yes        |
+------------+-------------+--------------+------------+

...


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

``Group``
---------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


``Small``
---------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | no         |
+------------+-------------+--------------+------------+

...


``Medium``
----------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | no         |
+------------+-------------+--------------+------------+

...


``Large``
---------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | no         |
+------------+-------------+--------------+------------+

...


``Ticket``
----------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

This is an alias for ``Group``.


Template Methods
================

``BeforeClass``
---------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

...


``Before``
----------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

...


``PreCondition``
----------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

...


``PostCondition``
-----------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

...


``After``
---------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

...


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

``BackupGlobals``
-----------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...


``ExcludeGlobalVariableFromBackup``
-----------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


``BackupStaticProperties``
--------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...


``ExcludeStaticPropertyFromBackup``
-----------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


``RunInSeparateProcess``
------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | no          | yes          | no         |
+------------+-------------+--------------+------------+

...


``RunTestsInSeparateProcesses``
-------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | no         |
+------------+-------------+--------------+------------+

...


``RunClassInSeparateProcess``
-----------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | no           | no         |
+------------+-------------+--------------+------------+

...


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

``RequiresPhp``
---------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...


``RequiresPhpExtension``
------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


``RequiresSetting``
-------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


``RequiresPhpunit``
-------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...


``RequiresFunction``
--------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


``RequiresMethod``
------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | yes        |
+------------+-------------+--------------+------------+

...


``RequiresOperatingSystem``
---------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...


``RequiresOperatingSystemFamily``
---------------------------------

+------------+-------------+--------------+------------+
| Context    | Class Level | Method Level | Repeatable |
+============+=============+==============+============+
| Test Code  | yes         | yes          | no         |
+------------+-------------+--------------+------------+

...
