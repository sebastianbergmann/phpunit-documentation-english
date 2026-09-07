

.. _appendixes.attributes:

**********
Attributes
**********

`Attributes <https://wiki.php.net/rfc/attributes_v2>`_ are "a form of structured, syntactic metadata
to declarations of classes, properties, functions, methods, parameters and constants. Attributes allow
to define configuration directives directly embedded with the declaration of that code."

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


.. _appendixes.attributes.AllowMockObjectsWithoutExpectations:

``AllowMockObjectsWithoutExpectations``
=======================================

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | no         |
+-------------+--------------+------------+

The ``AllowMockObjectsWithoutExpectations`` attribute can be used to opt out of the check that emits the notice for mock objects without expectations.


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
| yes         | yes          | yes        |
+-------------+--------------+------------+

The ``IgnoreDeprecations(null|string $messagePattern = null)`` attribute can be used to configure PHPUnit's error handler to
not emit events for ``E_DEPRECATED`` and ``E_USER_DEPRECATED`` errors.
When a ``$messagePattern`` is specified, only deprecations whose message matches the given regular expression pattern are ignored.


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


.. _appendixes.attributes.IgnorePhpunitWarnings:

``IgnorePhpunitWarnings``
=========================

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``IgnorePhpunitWarnings(null|string $messagePattern = null)`` attribute can be used to suppress warnings emitted by PHPUnit for a test method.
When a ``$messagePattern`` is specified, only warnings whose message matches the given regular expression pattern are suppressed.


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


``CoversClassesThatImplementInterface``
---------------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``CoversClassesThatImplementInterface(string $interfaceName)`` attribute can be used to
:ref:`specify <code-coverage.targeting-units-of-code>` that a test intends to cover
implementations of the given interface.


``CoversClassesThatExtendClass``
--------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``CoversClassesThatExtendClass(string $className)`` attribute can be used to
:ref:`specify <code-coverage.targeting-units-of-code>` that a test intends to cover
child classes of the given parent class.


.. _appendixes.attributes.CoversTrait:

``CoversTrait``
---------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``CoversTrait(string $traitName)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
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


.. _appendixes.attributes.CoversNamespace:

``CoversNamespace``
-------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``CoversNamespace(string $namespace)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>` that a test intends to cover code in the given namespace.


.. _appendixes.attributes.CoversFile:

``CoversFile``
--------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``CoversFile(string $path)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test intends to cover code in the given source code file.

The given file must be part of the code that is configured to be first-party code using
:ref:`\<source\> <appendixes.xml-configuration-file.source.include>`. If it is not, a warning
is emitted and the attribute is ignored.


.. _appendixes.attributes.CoversDirectory:

``CoversDirectory``
-------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``CoversDirectory(string $directory)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test intends to cover code in the source code files located in the given directory.
Source code files located in subdirectories of the given directory are not considered;
use :ref:`CoversDirectoryRecursively <appendixes.attributes.CoversDirectoryRecursively>` for that.

The given directory must be part of the code that is configured to be first-party code using
:ref:`\<source\> <appendixes.xml-configuration-file.source.include>`. If it is not, a warning
is emitted and the attribute is ignored.


.. _appendixes.attributes.CoversDirectoryRecursively:

``CoversDirectoryRecursively``
------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``CoversDirectoryRecursively(string $directory)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test intends to cover code in the source code files located in the given directory
and its subdirectories.

The given directory must be part of the code that is configured to be first-party code using
:ref:`\<source\> <appendixes.xml-configuration-file.source.include>`. If it is not, a warning
is emitted and the attribute is ignored.


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

.. admonition:: Deprecation: using ``CoversNothing`` on a test method is deprecated

   As of PHPUnit 12.3, using the ``CoversNothing`` attribute on a test method is hard-deprecated.
   Doing so will trigger a deprecation warning. Use the attribute on the test class instead.


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


.. _appendixes.attributes.UsesClassesThatImplementInterface:

``UsesClassesThatImplementInterface``
-------------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``UsesClassesThatImplementInterface(string $interfaceName)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test allows the execution of code in classes that implement the given interface, but does not intend to cover it. This is relevant
in the context of :ref:`preventing unintentionally covered code <risky-tests.unintentionally-covered-code>`.


.. _appendixes.attributes.UsesClassesThatExtendClass:

``UsesClassesThatExtendClass``
------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``UsesClassesThatExtendClass(string $className)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test allows the execution of code in child classes that extend the given parent class, but does not intend to cover it. This is relevant
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


.. _appendixes.attributes.UsesNamespace:

``UsesNamespace``
-----------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``UsesNamespace(string $namespace)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>` that a test allows the execution of code in the given namespace, but does not intend to cover it.
This is relevant in the context of :ref:`preventing unintentionally covered code <risky-tests.unintentionally-covered-code>`.


.. _appendixes.attributes.UsesFile:

``UsesFile``
------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``UsesFile(string $path)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test allows the execution of code in the given source code file, but does not intend to cover it.
This is relevant in the context of :ref:`preventing unintentionally covered code <risky-tests.unintentionally-covered-code>`.

The given file must be part of the code that is configured to be first-party code using
:ref:`\<source\> <appendixes.xml-configuration-file.source.include>`. If it is not, a warning
is emitted and the attribute is ignored.


.. _appendixes.attributes.UsesDirectory:

``UsesDirectory``
-----------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``UsesDirectory(string $directory)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test allows the execution of code in the source code files located in the given directory,
but does not intend to cover it. This is relevant in the context of
:ref:`preventing unintentionally covered code <risky-tests.unintentionally-covered-code>`.
Source code files located in subdirectories of the given directory are not considered;
use :ref:`UsesDirectoryRecursively <appendixes.attributes.UsesDirectoryRecursively>` for that.

The given directory must be part of the code that is configured to be first-party code using
:ref:`\<source\> <appendixes.xml-configuration-file.source.include>`. If it is not, a warning
is emitted and the attribute is ignored.


.. _appendixes.attributes.UsesDirectoryRecursively:

``UsesDirectoryRecursively``
----------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | no           | yes        |
+-------------+--------------+------------+

The ``UsesDirectoryRecursively(string $directory)`` attribute can be used to :ref:`specify <code-coverage.targeting-units-of-code>`
that a test allows the execution of code in the source code files located in the given directory
and its subdirectories, but does not intend to cover it. This is relevant in the context of
:ref:`preventing unintentionally covered code <risky-tests.unintentionally-covered-code>`.

The given directory must be part of the code that is configured to be first-party code using
:ref:`\<source\> <appendixes.xml-configuration-file.source.include>`. If it is not, a warning
is emitted and the attribute is ignored.

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

The ``DataProvider(string $methodName, bool $validateArgumentCount = true, bool $skipWhenEmpty = false)``
attribute can be used on a test method to specify a static method that is declared in the same
class as the test method as a :ref:`data provider <writing-tests-for-phpunit.data-providers>`.

By default, PHPUnit reports an error when a data provider returns no data sets. Setting
``skipWhenEmpty`` to ``true`` causes the test to be marked as skipped instead. This is useful
when a data provider legitimately produces no data sets in certain environments, for example
when the data depends on optional fixtures or platform-specific conditions.


.. _appendixes.attributes.DataProviderExternal:

``DataProviderExternal``
------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``DataProviderExternal(string $className, string $methodName, bool $validateArgumentCount = true, bool $skipWhenEmpty = false)``
attribute can be used on a test method to specify a static method that is declared in another
class as a :ref:`data provider <writing-tests-for-phpunit.data-providers>`.

As with ``DataProvider``, setting ``skipWhenEmpty`` to ``true`` causes the test to be marked
as skipped when the data provider returns no data sets instead of reporting an error.


.. _appendixes.attributes.DataProviderClosure:

``DataProviderClosure``
-----------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | yes        |
+-------------+--------------+------------+

The ``DataProviderClosure(Closure $closure, bool $validateArgumentCount = true)`` attribute can be used to define
a :ref:`data provider <writing-tests-for-phpunit.data-providers>` for a test method using a static closure
instead of a separate static method.

The closure must return an iterable (array or ``Traversable``) of arrays. Each array entry provides the arguments
for one invocation of the test method.

.. code-block:: php
    :caption: Using the ``DataProviderClosure`` attribute
    :name: appendixes.attributes.dataproviderclosure.examples.ExampleTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\DataProviderClosure;
    use PHPUnit\Framework\TestCase;

    final class DataTest extends TestCase
    {
        #[DataProviderClosure(static function (): array {
            return [[0, 0, 0], [0, 1, 1], [1, 0, 1], [1, 1, 2]];
        })]
        public function testAdd(int $a, int $b, int $expected): void
        {
            $this->assertSame($expected, $a + $b);
        }
    }

Named data sets can be used by providing string keys in the returned array:

.. code-block:: php
    :caption: Using the ``DataProviderClosure`` attribute with named data sets

    #[DataProviderClosure(static function (): array {
        return [
            'zeros'        => [0, 0, 0],
            'one plus one' => [1, 1, 2],
        ];
    })]
    public function testAdd(int $a, int $b, int $expected): void
    {
        $this->assertSame($expected, $a + $b);
    }

By default, PHPUnit warns when a data set provides more arguments than the test method accepts.
This can be disabled by setting ``validateArgumentCount`` to ``false``:

.. code-block:: php
    :caption: Disabling argument count validation

    #[DataProviderClosure(static function (): array {
        return [[1, 2, 3]];
    }, validateArgumentCount: false)]
    public function testOne(int $a, int $b): void
    {
        $this->assertGreaterThan(0, $a + $b);
    }


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


TestDox
=======

See :ref:`testdox` for a detailed discussion of the TestDox functionality.

.. _appendixes.attributes.TestDox:

``TestDox``
-----------

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
        public function testAdd(int $expected, int $a, int $b)
        {
            $this->assertSame($expected, $a + $b);
        }

        public static function additionProvider()
        {
            return [
                'data set 1' => [0, 0, 0],
                'data set 2' => [1, 0, 1],
                'data set 3' => [1, 1, 0],
                'data set 4' => [3, 1, 1]
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


.. _appendixes.attributes.TestDoxFormatter:

``TestDoxFormatter``
--------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``TestDoxFormatter(string $methodName)`` attribute can be used on a test method
to specify a static method that is declared in the same class as the test method
as a TestDox formatter.

.. code-block:: php
    :caption: Using the ``TestDoxFormatter`` attribute
    :name: appendixes.attributes.testdoxformatter.examples.ExampleTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\DataProvider;
    use PHPUnit\Framework\Attributes\TestDoxFormatter;
    use PHPUnit\Framework\TestCase;

    final class ExampleTest extends TestCase
    {
        public static function provider(): array
        {
            return [
                [
                    new DateTimeImmutable('2025-08-01'),
                    new DateTimeImmutable('2025-08-01'),
                ]
            ];
        }

        public static function formatter(DateTimeImmutable $expected, DateTimeImmutable $actual): string
        {
            return sprintf(
                '%s is expected to be %s',
                $actual->format('Y-m-d'),
                $expected->format('Y-m-d'),
            );
        }

        #[DataProvider('provider')]
        #[TestDoxFormatter('formatter')]
        public function testOne(DateTimeImmutable $expected, DateTimeImmutable $actual): void
        {
            $this->assertEquals($expected, $actual);
        }
    }

Running the test shown above with TestDox output enabled yields the output shown below:

.. parsed-literal::

    $ ./tools/phpunit --no-progress --testdox tests/ExampleTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.4.10

    Time: 00:00.224, Memory: 25.77 MB

    Example
     ✔ 2025-08-01 is expected to be 2025-08-01

    OK (1 test, 1 assertion)


.. _appendixes.attributes.TestDoxFormatterExternal:

``TestDoxFormatterExternal``
----------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``TestDoxFormatterExternal(string $className, string $methodName)`` attribute can be used
on a test method to specify a static method that is declared in another class as a
TestDox formatter.


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

Groups can be used, for instance, to :ref:`select <appendixes.cli-options.selection>`
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

The ``BeforeClass(int $priority = 0)`` attribute can be used to specify that a public static method should
be invoked before the first test method of a test case class is run. This is the same
phase where a method named ``setUpBeforeClass()`` would be invoked. We refer to such
methods as "before test class" methods.

When a test case class has more than one methods with the ``BeforeClass`` attribute then,
by default, the test runner assumes that the order in which these methods are invoked
does not matter. If this assumption is wrong and the order in which these methods are
invoked does matter then the attribute's optional ``$priority`` argument (non-negative
integer) can be used to define the desired invocation order: a method with a higher
``$priority`` value is invoked before a method with a lower ``$priority`` value.

.. _appendixes.attributes.Before:

``Before``
----------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``Before(int $priority = 0)`` attribute can be used to specify that a protected non-static method should
be invoked before each test method of a test case class is run. This is the same phase
where a method named ``setUp()`` would be invoked. We refer to such methods as "before test"
methods.

When a test case class has more than one methods with the ``Before`` attribute then,
by default, the test runner assumes that the order in which these methods are invoked
does not matter. If this assumption is wrong and the order in which these methods are
invoked does matter then the attribute's optional ``$priority`` argument (non-negative
integer) can be used to define the desired invocation order: a method with a higher
``$priority`` value is invoked before a method with a lower ``$priority`` value.


.. _appendixes.attributes.PreCondition:

``PreCondition``
----------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``PreCondition(int $priority = 0)`` attribute can be used to specify that a protected non-static method should
be invoked before each test method (but after any "before test" methods) of a test case class is run.
This is the same phase where a method named ``assertPreConditions()`` would be invoked.
We refer to such methods as "pre-condition" methods.

When a test case class has more than one methods with the ``PreCondition`` attribute then,
by default, the test runner assumes that the order in which these methods are invoked
does not matter. If this assumption is wrong and the order in which these methods are
invoked does matter then the attribute's optional ``$priority`` argument (non-negative
integer) can be used to define the desired invocation order: a method with a higher
``$priority`` value is invoked before a method with a lower ``$priority`` value.


.. _appendixes.attributes.PostCondition:

``PostCondition``
-----------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``PostCondition(int $priority = 0)`` attribute can be used to specify that a protected non-static method should
be invoked after each test method (but before any "after test" methods) of a test case class is run.
This is the same phase where a method named ``assertPostConditions()`` would be invoked.
We refer to such methods as "post-condition" methods.

When a test case class has more than one methods with the ``PostCondition`` attribute then,
by default, the test runner assumes that the order in which these methods are invoked
does not matter. If this assumption is wrong and the order in which these methods are
invoked does matter then the attribute's optional ``$priority`` argument (non-negative
integer) can be used to define the desired invocation order: a method with a higher
``$priority`` value is invoked before a method with a lower ``$priority`` value.


.. _appendixes.attributes.After:

``After``
---------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``After(int $priority = 0)`` attribute can be used to specify that a protected non-static method should
be invoked after each test method of a test case class is run. This is the same phase where
a method named ``tearDown()`` would be invoked. We refer to such methods as "after test" methods.

When a test case class has more than one methods with the ``After`` attribute then,
by default, the test runner assumes that the order in which these methods are invoked
does not matter. If this assumption is wrong and the order in which these methods are
invoked does matter then the attribute's optional ``$priority`` argument (non-negative
integer) can be used to define the desired invocation order: a method with a higher
``$priority`` value is invoked before a method with a lower ``$priority`` value.


.. _appendixes.attributes.AfterClass:

``AfterClass``
--------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``AfterClass(int $priority = 0)`` attribute can be used to specify that a public static method should
be invoked after the last test method of a test case class is run. This is the same phase
where a method named ``tearDownAfterClass()`` would be invoked. We refer to such methods
as "after test class" methods.

When a test case class has more than one methods with the ``AfterClass`` attribute then,
by default, the test runner assumes that the order in which these methods are invoked
does not matter. If this assumption is wrong and the order in which these methods are
invoked does matter then the attribute's optional ``$priority`` argument (non-negative
integer) can be used to define the desired invocation order: a method with a higher
``$priority`` value is invoked before a method with a lower ``$priority`` value.


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

This attribute is inherited from parent classes: if a parent class is annotated with
``#[RunTestsInSeparateProcesses]``, all child classes will also run their tests in
separate processes.


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

.. admonition:: Deprecation: ``RunClassInSeparateProcess`` is deprecated

   As of PHPUnit 12.4, the ``RunClassInSeparateProcess`` attribute is hard-deprecated.
   Using it will trigger a deprecation warning. Use :ref:`RunTestsInSeparateProcesses <appendixes.attributes.RunTestsInSeparateProcesses>` instead.


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


.. _appendixes.attributes.WithEnvironmentVariable:

``WithEnvironmentVariable``
---------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | yes        |
+-------------+--------------+------------+

The ``WithEnvironmentVariable(string $environmentVariableName, ?string $value = null)`` attribute can be
used to set an environment variable for the duration of a test. The environment variable is set before
before-test methods such as ```setUp()`` are called and restored to its original value after after-test
methods such as ``tearDown()`` have been called.

When used on a class, the environment variable is set for all tests in that class. When used on a method,
it applies only to that test method. A method-level attribute overrides a class-level attribute for the
same environment variable.

.. code-block:: php
    :caption: Using the ``WithEnvironmentVariable`` attribute

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\WithEnvironmentVariable;
    use PHPUnit\Framework\TestCase;

    #[WithEnvironmentVariable('APP_ENV', 'testing')]
    final class EnvironmentTest extends TestCase
    {
        public function testAppEnvIsSetFromClassAttribute(): void
        {
            $this->assertSame('testing', $_ENV['APP_ENV']);
            $this->assertSame('testing', getenv('APP_ENV'));
        }

        #[WithEnvironmentVariable('APP_ENV', 'production')]
        public function testAppEnvIsOverriddenByMethodAttribute(): void
        {
            $this->assertSame('production', $_ENV['APP_ENV']);
            $this->assertSame('production', getenv('APP_ENV'));
        }

        #[WithEnvironmentVariable('APP_DEBUG', 'true')]
        public function testAdditionalVariableFromMethodAttribute(): void
        {
            $this->assertSame('testing', $_ENV['APP_ENV']);
            $this->assertSame('true', $_ENV['APP_DEBUG']);
        }
    }

When ``$value`` is ``null`` (or omitted), the environment variable is removed for the duration
of the test:

.. code-block:: php
    :caption: Removing an environment variable for a test

    #[WithEnvironmentVariable('APP_ENV')]
    public function testAppEnvIsNotSet(): void
    {
        $this->assertFalse(isset($_ENV['APP_ENV']));
        $this->assertFalse(getenv('APP_ENV'));
    }

The environment variable is set in both ``$_ENV`` and via ``putenv()``, so it is accessible
through both ``$_ENV`` and ``getenv()``. After the test, the original state is restored.

When multiple ``WithEnvironmentVariable`` attributes are specified for the same variable name,
the last one wins.


Flaky Tests
===========

The attributes documented in this section are used for dealing with :ref:`flaky tests <flaky-tests>`.

.. _appendixes.attributes.Repeat:

``Repeat``
----------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``Repeat(int $times[, int $failureThreshold])`` attribute can be used to run a test method ``$times`` times,
stopping at the first failure. This helps :ref:`find flaky tests and stress-test stateful code <flaky-tests.repeating-tests>`.

The optional ``$failureThreshold`` argument, which defaults to ``1``, controls how many repetitions may fail before
the remaining repetitions are skipped.

.. code-block:: php
    :caption: Using the ``Repeat`` attribute

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\Repeat;
    use PHPUnit\Framework\TestCase;

    final class ExampleTest extends TestCase
    {
        #[Repeat(100)]
        public function testSomething(): void
        {
            // ...
        }
    }

The attribute applies only to test methods that declare an explicit ``void`` return type and do not depend on
another test. PHPUnit emits a warning when the attribute is used on a test method that does not meet these
requirements. A method-level ``Repeat`` attribute takes precedence over the ``--repeat`` and ``--retry``
:ref:`command-line options <appendixes.cli-options.execution>`.

``Repeat(1)`` runs the test method exactly once and thereby
:ref:`opts it out <flaky-tests.opting-out>` of the ``--repeat`` command-line option. A number of repetitions,
or a failure threshold, that is not a positive integer has the same effect and triggers a warning.


.. _appendixes.attributes.Retry:

``Retry``
---------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| no          | yes          | no         |
+-------------+--------------+------------+

The ``Retry(int $maxAttempts)`` attribute can be used to attempt a test method up to ``$maxAttempts`` times,
stopping at the first success. This helps :ref:`tolerate flaky tests that cannot be eliminated <flaky-tests.retrying-tests>`,
while keeping them visible. The first attempt whose status is neither a failure nor an error decides the test's result.

.. code-block:: php
    :caption: Using the ``Retry`` attribute

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\Retry;
    use PHPUnit\Framework\TestCase;

    final class ExampleTest extends TestCase
    {
        #[Retry(3)]
        public function testSomething(): void
        {
            // ...
        }
    }

The attribute applies only to test methods that declare an explicit ``void`` return type and do not depend on
another test. PHPUnit emits a warning when the attribute is used on a test method that does not meet these
requirements. A method-level ``Retry`` attribute takes precedence over the ``--repeat`` and ``--retry``
:ref:`command-line options <appendixes.cli-options.execution>`.

``Retry(1)`` attempts the test method exactly once and thereby
:ref:`opts it out <flaky-tests.opting-out>` of the ``--retry`` command-line option. A maximum number of
attempts that is not a positive integer has the same effect and triggers a warning.

When a test method is annotated with both ``Repeat`` and ``Retry``, PHPUnit emits a warning and ignores the
``Retry`` attribute.


Skipping Tests
==============

The ``Requires*`` attributes documented in this section are convenience functionality for replacing custom skip logic in :ref:`before-class or before-test methods <fixtures.template-methods>` for common cases.
For skip logic that goes beyond what these attributes support, use ``markTestSkipped()`` in a ``setUp()`` or ``setUpBeforeClass()`` method (or in a method configured with the ``#[Before]`` or ``#[BeforeClass]`` attribute).

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

``$versionRequirement`` must be one of the following:

* A version number string preceded by a comparison operator supported by PHP's `version_compare() <https://www.php.net/manual/en/function.version-compare.php>`_ function: ``>=``, ``>``, ``<=``, ``<``, ``==``, or ``!=``
* A `version constraint <https://getcomposer.org/doc/articles/versions.md#writing-version-constraints>`_ in the syntax supported by Composer (e.g. ``^8.3``, ``~8.3.0``, ``>=8.3 <8.5``)

Here are some examples:

* ``#[RequiresPhp('>= 8.3')]`` — PHP 8.3.0 or newer
* ``#[RequiresPhp('^8.3')]`` — PHP 8.3.0 or newer, but below 9.0.0 (Composer syntax)
* ``#[RequiresPhp('~8.3.0')]`` — PHP 8.3.0 or newer, but below 8.4.0 (Composer syntax)


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

``$versionRequirement`` follows the same format that is described :ref:`here <appendixes.attributes.RequiresPhp>`.

Here are some examples:

* ``#[RequiresPhpunit('>= 10.1.0')]``
* ``#[RequiresPhpunit('^10.1')]``


.. _appendixes.attributes.RequiresPhpunitExtension:

``RequiresPhpunitExtension``
----------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | yes        |
+-------------+--------------+------------+

The ``RequiresPhpunitExtension(string $extensionClass)`` attribute can be used to
:ref:`skip the execution of a test <writing-tests-for-phpunit.skipping-tests.skipping-tests-using-attributes>`
when the PHPUnit extension identified by its bootstrap class is not available.


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


.. _appendixes.attributes.RequiresEnvironmentVariable:

``RequiresEnvironmentVariable``
-------------------------------

+-------------+--------------+------------+
| Class Level | Method Level | Repeatable |
+=============+==============+============+
| yes         | yes          | yes        |
+-------------+--------------+------------+

The ``RequiresEnvironmentVariable(string $environmentVariableName[, string $value])`` attribute can be used to
:ref:`skip the execution of a test <writing-tests-for-phpunit.skipping-tests.skipping-tests-using-attributes>`
when the specified environment variable is not set. Optionally, using the the ``$value`` argument, a required
value can be specified for the environment variable.
