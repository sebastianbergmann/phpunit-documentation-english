

.. _appendixes.assertions:

**********
Assertions
**********

This appendix lists the various assertion methods that are available.

.. _appendixes.assertions.static-vs-non-static-usage-of-assertion-methods:

Static vs. Non-Static Usage of Assertion Methods
================================================

PHPUnit's assertions are implemented in ``PHPUnit\Framework\Assert``.
``PHPUnit\Framework\TestCase`` inherits from ``PHPUnit\Framework\Assert``.

The assertion methods are declared static and can be invoked
from any context using ``PHPUnit\Framework\Assert::assertTrue()``,
for instance, or using ``$this->assertTrue()`` or ``self::assertTrue()``,
for instance, in a class that extends ``PHPUnit\Framework\TestCase``.
You can even use global function wrappers such as ``assertTrue()``.

A common question, especially from developers new to PHPUnit, is whether
using ``$this->assertTrue()`` or ``self::assertTrue()``,
for instance, is "the right way" to invoke an assertion. The short answer
is: there is no right way. And there is no wrong way, either. It is a
matter of personal preference.

For most people it just "feels right" to use ``$this->assertTrue()``
because the test method is invoked on a test object. The fact that the
assertion methods are declared static allows for (re)using
them outside the scope of a test object. Lastly, the global function
wrappers allow developers to type less characters (``assertTrue()`` instead
of ``$this->assertTrue()`` or ``self::assertTrue()``).


.. _appendixes.assertions.boolean:

Boolean
=======

.. _appendixes.assertions.assertTrue:

``assertTrue()``
----------------

``assertTrue(bool $condition[, string $message])``

Reports an error identified by ``$message`` if ``$condition`` is ``false``.

``assertNotTrue()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/TrueTest.php
   :caption: Usage of assertTrue()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/TrueTest.php.out

.. _appendixes.assertions.assertFalse:

``assertFalse()``
-----------------

``assertFalse(bool $condition[, string $message])``

Reports an error identified by ``$message`` if ``$condition`` is ``true``.

``assertNotFalse()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/FalseTest.php
   :caption: Usage of assertFalse()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/FalseTest.php.out

.. _appendixes.assertions.identity:

Identity
========

.. _appendixes.assertions.assertSame:

``assertSame()``
----------------

``assertSame(mixed $expected, mixed $actual[, string $message])``

Reports an error identified by ``$message`` if the two variables ``$expected`` and ``$actual`` do not have the same type and value.

``assertNotSame()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/SameWithMixedTest.php
   :caption: Usage of assertSame()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/SameWithMixedTest.php.out

``assertSame(object $expected, object $actual[, string $message])``

Reports an error identified by ``$message`` if the two variables ``$expected`` and ``$actual`` do not reference the same object.

.. literalinclude:: examples/assertions/SameWithObjectsTest.php
   :caption: Usage of assertSame() with objects
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/SameWithObjectsTest.php.out

Identity is checked using the ``===`` operator.

.. _appendixes.assertions.equality:

Equality
========

.. _appendixes.assertions.assertEquals:

``assertEquals()``
------------------

``assertEquals(mixed $expected, mixed $actual[, string $message])``

Reports an error identified by ``$message`` if the two variables ``$expected`` and ``$actual`` are not equal.

``assertNotEquals()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/EqualsTest.php
   :caption: Usage of assertEquals()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/EqualsTest.php.out

Equality is checked using the ``==`` operator, but more specialized comparisons are used for specific
argument types for ``$expected`` and ``$actual``, see below.

``assertEquals(DateTimeInterface $expected, DateTimeInterface $actual[, string $message])``

Reports an error identified by ``$message`` if the two points in time represented by the two ``DateTimeInterface`` objects ``$expected`` and ``$actual`` are not equal.

.. literalinclude:: examples/assertions/EqualsWithDateTimeImmutableTest.php
   :caption: Usage of assertEquals() with DateTimeImmutable objects
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/EqualsWithDateTimeImmutableTest.php.out

``assertEquals(DOMDocument $expected, DOMDocument $actual[, string $message])``

Reports an error identified by ``$message`` if the uncommented canonical form of the XML documents represented by the two ``DOMDocument`` objects ``$expected`` and ``$actual`` are not equal.

.. literalinclude:: examples/assertions/EqualsWithDomDocumentTest.php
   :caption: Usage of assertEquals() with DOMDocument objects
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/EqualsWithDomDocumentTest.php.out

``assertEquals(object $expected, object $actual[, string $message])``

Reports an error identified by ``$message`` if the two objects ``$expected`` and ``$actual`` do not have equal property values.

.. literalinclude:: examples/assertions/EqualsWithObjectsTest.php
   :caption: Usage of assertEquals() with objects
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/EqualsWithObjectsTest.php.out

``assertEquals(array $expected, array $actual[, string $message])``

Reports an error identified by ``$message`` if the two arrays ``$expected`` and ``$actual`` are not equal.

.. literalinclude:: examples/assertions/EqualsWithArraysTest.php
   :caption: Usage of assertEquals() with arrays
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/EqualsWithArraysTest.php.out

.. _appendixes.assertions.assertEqualsCanonicalizing:

``assertEqualsCanonicalizing()``
--------------------------------

``assertEqualsCanonicalizing(mixed $expected, mixed $actual[, string $message])``

Reports an error identified by ``$message`` if the two variables ``$expected`` and ``$actual`` are not equal.

The contents of ``$expected`` and ``$actual`` are canonicalized before they are compared. For instance, when the two variables ``$expected`` and ``$actual`` are arrays, then these arrays are sorted before they are compared. When ``$expected`` and ``$actual`` are objects, each object is converted to an array containing all private, protected and public properties.

``assertNotEqualsCanonicalizing()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/EqualsWithArraysCanonicalizingTest.php
   :caption: Usage of assertEqualsCanonicalizing()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/EqualsWithArraysCanonicalizingTest.php.out

.. _appendixes.assertions.assertEqualsIgnoringCase:

``assertEqualsIgnoringCase()``
------------------------------

``assertEqualsIgnoringCase(mixed $expected, mixed $actual[, string $message])``

Reports an error identified by ``$message`` if the two variables ``$expected`` and ``$actual`` are not equal.

Differences in casing are ignored for the comparison of ``$expected`` and ``$actual``.

``assertNotEqualsIgnoringCase()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/EqualsWithStringsIgnoringCaseTest.php
   :caption: Usage of assertEqualsIgnoringCase()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/EqualsWithStringsIgnoringCaseTest.php.out

.. _appendixes.assertions.assertEqualsWithDelta:

``assertEqualsWithDelta()``
---------------------------

``assertEqualsWithDelta(mixed $expected, mixed $actual, float $delta[, string $message])``

Reports an error identified by ``$message`` if the absolute difference between ``$expected`` and ``$actual`` is greater than ``$delta``.

Please read "`What Every Computer Scientist Should Know About Floating-Point Arithmetic <http://docs.oracle.com/cd/E19957-01/806-3568/ncg_goldberg.html>`_" to understand why ``$delta`` is necessary.

``assertNotEqualsWithDelta()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/EqualsWithFloatsAndDeltaTest.php
   :caption: Usage of assertEqualsWithDelta()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/EqualsWithFloatsAndDeltaTest.php.out

.. _appendixes.assertions.assertObjectEquals:

``assertObjectEquals()``
------------------------

``assertObjectEquals(object $expected, object $actual, string $method = 'equals'[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not equal to ``$expected`` according to ``$actual->$method($expected)``.

It is a bad practice to use ``assertEquals()`` (and its inverse, ``assertNotEquals()``) on objects without registering a custom comparator that customizes how objects are compared. Unfortunately, though, implementing custom comparators for each and every object you want to assert in your tests is inconvenient at best.

The most common use case for custom comparators are Value Objects. These objects usually have an ``equals(self $other): bool`` method (or a method just like that but with a different name) for comparing two instances of the Value Object's type. ``assertObjectEquals()`` makes custom comparison of objects convenient for this common use case:

.. literalinclude:: examples/assertions/ObjectEqualsTest.php
   :caption: Usage of assertObjectEquals()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/ObjectEqualsTest.php.out

.. literalinclude:: examples/assertions/src/Email.php
   :caption: Email value object with equals() method
   :language: php

Please note:

* A method with name ``$method`` must exist on the ``$actual`` object
* The method must accept exactly one argument
* The respective parameter must have a declared type
* The ``$expected`` object must be compatible with this declared type
* The method must have a declared ``bool`` return type

If any of the aforementioned assumptions is not fulfilled or if ``$actual->$method($expected)`` returns ``false`` then the assertion fails.

.. _appendixes.assertions.assertFileEquals:

``assertFileEquals()``
----------------------

``assertFileEquals(string $expected, string $actual[, string $message])``

Reports an error identified by ``$message`` if the file specified by ``$expected`` does not have the same contents as the file specified by ``$actual``.

``assertFileNotEquals()`` is the inverse of this assertion and takes the same arguments.

``assertFileEqualsCanonicalizing()`` (and ``assertFileNotEqualsCanonicalizing()``) as well as ``assertFileEqualsIgnoringCase()`` (and ``assertFileNotEqualsIgnoringCase()``) do for files what ``assertEqualsCanonicalizing()`` (and ``assertNotEqualsCanonicalizing()``) as well as ``assertEqualsIgnoringCase()`` (and ``assertNotEqualsIgnoringCase()``) do for strings.

.. literalinclude:: examples/assertions/FileEqualsTest.php
   :caption: Usage of assertFileEquals()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/FileEqualsTest.php.out

.. _appendixes.assertions.iterable:

Iterable
========

.. _appendixes.assertions.assertArrayHasKey:

``assertArrayHasKey()``
-----------------------

``assertArrayHasKey(int|string $key, array|ArrayAccess $array[, string $message])``

Reports an error identified by ``$message`` if ``$array`` does not have the ``$key``.

``assertArrayNotHasKey()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/ArrayHasKeyTest.php
   :caption: Usage of assertArrayHasKey()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/ArrayHasKeyTest.php.out

.. _appendixes.assertions.assertContains:

``assertContains()``
--------------------

``assertContains(mixed $needle, iterable $haystack[, string $message])``

Reports an error identified by ``$message`` if ``$needle`` is not an element of ``$haystack``.

``assertNotContains()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/ContainsTest.php
   :caption: Usage of assertContains()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/ContainsTest.php.out

Whether ``$needle`` is an element of ``$haystack`` is checked using the ``===`` operator.
You can use ``assertContainsEquals()`` (and ``assertNotContainsEquals()``) if you need the
comparison logic implemented by the ``==`` operator.

.. _appendixes.assertions.assertContainsOnly:

``assertContainsOnly()``
------------------------

``assertContainsOnly(string $type, iterable $haystack[, boolean $isNativeType = null, string $message = ''])``

Reports an error identified by ``$message`` if ``$haystack`` does not contain only variables of type ``$type``.

``$isNativeType`` is a flag used to indicate whether ``$type`` is a native PHP type or not.

``assertNotContainsOnly()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/ContainsOnlyTest.php
   :caption: Usage of assertContainsOnly()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/ContainsOnlyTest.php.out

.. _appendixes.assertions.assertContainsOnlyInstancesOf:

``assertContainsOnlyInstancesOf()``
-----------------------------------

``assertContainsOnlyInstancesOf(string $classname, iterable $haystack[, string $message])``

Reports an error identified by ``$message`` if ``$haystack`` does not contain only instances of class ``$classname``.

.. literalinclude:: examples/assertions/ContainsOnlyInstancesOfTest.php
   :caption: Usage of assertContainsOnlyInstancesOf()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/ContainsOnlyInstancesOfTest.php.out

.. _appendixes.assertions.cardinality:

Objects
========

.. _appendixes.assertions.assertObjectHasProperty:

``assertObjectHasProperty()``
-----------------------------

``assertObjectHasProperty(string $propertyName, object $object, string $message = '')``

Reports an error identified by ``$message`` if ``$object`` does not have a property with the name ``$propertyName``.

``assertObjectNotHasProperty()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/ObjectHasPropertyTest.php
   :caption: Usage of assertObjectHasProperty()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/ObjectHasPropertyTest.php.out


Cardinality
===========

.. _appendixes.assertions.assertCount:

``assertCount()``
-----------------

``assertCount(int $expectedCount, Countable|iterable $haystack[, string $message])``

Reports an error identified by ``$message`` if the number of elements in ``$haystack`` is not ``$expectedCount``.

``assertNotCount()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/CountTest.php
   :caption: Usage of assertCount()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/CountTest.php.out

.. _appendixes.assertions.assertSameSize:

``assertSameSize()``
--------------------

``assertSameSize(Countable|iterable $expected, Countable|iterable $actual[, string $message])``

Reports an error identified by ``$message`` if the sizes of ``$actual`` and ``$expected`` are not the same.

``assertNotSameSize()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/SameSizeTest.php
   :caption: Usage of assertSameSize()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/SameSizeTest.php.out

.. _appendixes.assertions.assertEmpty:

``assertEmpty()``
-----------------

``assertEmpty(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not empty.

``assertNotEmpty()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/EmptyTest.php
   :caption: Usage of assertEmpty()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/EmptyTest.php.out

.. _appendixes.assertions.assertGreaterThan:

``assertGreaterThan()``
-----------------------

``assertGreaterThan(mixed $expected, mixed $actual[, string $message])``

Reports an error identified by ``$message`` if the value of ``$actual`` is not greater than the value of ``$expected``.

.. literalinclude:: examples/assertions/GreaterThanTest.php
   :caption: Usage of assertGreaterThan()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/GreaterThanTest.php.out

.. _appendixes.assertions.assertGreaterThanOrEqual:

``assertGreaterThanOrEqual()``
------------------------------

``assertGreaterThanOrEqual(mixed $expected, mixed $actual[, string $message])``

Reports an error identified by ``$message`` if the value of ``$actual`` is not greater than or equal to the value of ``$expected``.

.. literalinclude:: examples/assertions/GreaterThanOrEqualTest.php
   :caption: Usage of assertGreaterThanOrEqual()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/GreaterThanOrEqualTest.php.out

.. _appendixes.assertions.assertLessThan:

``assertLessThan()``
--------------------

``assertLessThan(mixed $expected, mixed $actual[, string $message])``

Reports an error identified by ``$message`` if the value of ``$actual`` is not less than the value of ``$expected``.

.. literalinclude:: examples/assertions/LessThanTest.php
   :caption: Usage of assertLessThan()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/LessThanTest.php.out

.. _appendixes.assertions.assertLessThanOrEqual:

``assertLessThanOrEqual()``
---------------------------

``assertLessThanOrEqual(mixed $expected, mixed $actual[, string $message])``

Reports an error identified by ``$message`` if the value of ``$actual`` is not less than or equal to the value of ``$expected``.

.. literalinclude:: examples/assertions/LessThanOrEqualTest.php
   :caption: Usage of assertLessThanOrEqual()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/LessThanOrEqualTest.php.out

.. _appendixes.assertions.types:

Types
=====

.. _appendixes.assertions.assertInstanceOf:

``assertInstanceOf()``
----------------------

``assertInstanceOf(string $expected, mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not an instance of ``$expected``.

``assertNotInstanceOf()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/InstanceOfTest.php
   :caption: Usage of assertInstanceOf()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/InstanceOfTest.php.out

.. _appendixes.assertions.assertIsArray:

``assertIsArray()``
-------------------

``assertIsArray(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``array``.

``assertIsNotArray()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/IsArrayTest.php
   :caption: Usage of assertIsArray()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/IsArrayTest.php.out

.. _appendixes.assertions.assertIsList:

``assertIsList()``
------------------

``assertIsList(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not an array where the keys are consecutive numbers from 0 to ``count($actual) - 1``.

.. literalinclude:: examples/assertions/IsListTest.php
   :caption: Usage of assertIsList()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/IsListTest.php.out

.. _appendixes.assertions.assertIsBool:

``assertIsBool()``
------------------

``assertIsBool(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``bool``.

``assertIsNotBool()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/IsBoolTest.php
   :caption: Usage of assertIsBool()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/IsBoolTest.php.out

.. _appendixes.assertions.assertIsCallable:

``assertIsCallable()``
----------------------

``assertIsCallable(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``callable``.

``assertIsNotCallable()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/IsCallableTest.php
   :caption: Usage of assertIsCallable()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/IsCallableTest.php.out

.. _appendixes.assertions.assertIsFloat:

``assertIsFloat()``
-------------------

``assertIsFloat(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``float``.

``assertIsNotFloat()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/IsFloatTest.php
   :caption: Usage of assertIsFloat()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/IsFloatTest.php.out

.. _appendixes.assertions.assertIsInt:

``assertIsInt()``
-----------------

``assertIsInt(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``int``.

``assertIsNotInt()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/IsIntTest.php
   :caption: Usage of assertIsInt()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/IsIntTest.php.out

.. _appendixes.assertions.assertIsIterable:

``assertIsIterable()``
----------------------

``assertIsIterable(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``iterable``.

``assertIsNotIterable()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/IsIterableTest.php
   :caption: Usage of assertIsIterable()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/IsIterableTest.php.out

.. _appendixes.assertions.assertIsNumeric:

``assertIsNumeric()``
---------------------

``assertIsNumeric(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``numeric``.

``assertIsNotNumeric()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/IsNumericTest.php
   :caption: Usage of assertIsNumeric()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/IsNumericTest.php.out

.. _appendixes.assertions.assertIsObject:

``assertIsObject()``
--------------------

``assertIsObject(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``object``.

``assertIsNotObject()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/IsObjectTest.php
   :caption: Usage of assertIsObject()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/IsObjectTest.php.out

.. _appendixes.assertions.assertIsResource:

``assertIsResource()``
----------------------

``assertIsResource(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``resource``.

``assertIsNotResource()`` is the inverse of this assertion and takes the same arguments.

``assertIsClosedResource()`` (and ``assertIsNotClosedResource()``) are provided to explicitly check for closed resources.

.. literalinclude:: examples/assertions/IsResourceTest.php
   :caption: Usage of assertIsResource()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/IsResourceTest.php.out

.. _appendixes.assertions.assertIsScalar:

``assertIsScalar()``
--------------------

``assertIsScalar(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``scalar``.

``assertIsNotScalar()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/IsScalarTest.php
   :caption: Usage of assertIsScalar()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/IsScalarTest.php.out

.. _appendixes.assertions.assertIsString:

``assertIsString()``
--------------------

``assertIsString(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``string``.

``assertIsNotString()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/IsStringTest.php
   :caption: Usage of assertIsString()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/IsStringTest.php.out

.. _appendixes.assertions.assertNull:

``assertNull()``
----------------

``assertNull(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not ``null``.

``assertNotNull()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/NullTest.php
   :caption: Usage of assertNull()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/NullTest.php.out

.. _appendixes.assertions.strings:

Strings
=======

.. _appendixes.assertions.assertStringStartsWith:

``assertStringStartsWith()``
----------------------------

``assertStringStartsWith(string $prefix, string $string[, string $message])``

Reports an error identified by ``$message`` if the ``$string`` does not start with ``$prefix``.

``assertStringStartsNotWith()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/StringStartsWithTest.php
   :caption: Usage of assertStringStartsWith()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/StringStartsWithTest.php.out

.. _appendixes.assertions.assertStringEndsWith:

``assertStringEndsWith()``
--------------------------

``assertStringEndsWith(string $suffix, string $string[, string $message])``

Reports an error identified by ``$message`` if the ``$string`` does not end with ``$suffix``.

``assertStringEndsNotWith()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/StringEndsWithTest.php
   :caption: Usage of assertStringEndsWith()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/StringEndsWithTest.php.out

.. _appendixes.assertions.assertStringContainsString:

``assertStringContainsString()``
--------------------------------

``assertStringContainsString(string $needle, string $haystack[, string $message])``

Reports an error identified by ``$message`` if ``$needle`` is not a substring of ``$haystack``.

``assertStringNotContainsString()`` is the inverse of this assertion and takes the same arguments.

``assertStringContainsStringIgnoringLineEndings()`` takes the same arguments and can be used if line endings should be ignored.

.. literalinclude:: examples/assertions/StringContainsStringTest.php
   :caption: Usage of assertStringContainsString()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/StringContainsStringTest.php.out

``assertStringContainsStringIgnoringCase()``
--------------------------------------------

``assertStringContainsStringIgnoringCase(string $needle, string $haystack[, string $message])``

Reports an error identified by ``$message`` if ``$needle`` is not a substring of ``$haystack``.

Differences in casing are ignored when ``$needle`` is searched for in ``$haystack``. This also works
for Unicode characters with diacritics (accents, umlauts, circumflex, etc.) as long as both strings
have the same `Normalization Form <https://www.php.net/manual/en/class.normalizer.php>`_.

``assertStringNotContainsStringIgnoringCase()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/StringContainsStringIgnoringCaseTest.php
   :caption: Usage of assertStringContainsStringIgnoringCase()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/StringContainsStringIgnoringCaseTest.php.out

.. _appendixes.assertions.assertStringEqualsStringIgnoringLineEndings:

``assertStringEqualsStringIgnoringLineEndings()``
-----------------------------------------------

``assertStringEqualsStringIgnoringLineEndings(string $expected, string $actual[, string $message])``

Reports an error identified by ``$message`` if the two strings ``$expected`` and ``$actual`` are not equal while ignoring line endings.

.. _appendixes.assertions.assertMatchesRegularExpression:

``assertMatchesRegularExpression()``
------------------------------------

``assertMatchesRegularExpression(string $pattern, string $string[, string $message])``

Reports an error identified by ``$message`` if ``$string`` does not match the regular expression ``$pattern``.

``assertDoesNotMatchRegularExpression()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/MatchesRegularExpressionTest.php
   :caption: Usage of assertMatchesRegularExpression()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/MatchesRegularExpressionTest.php.out

.. _appendixes.assertions.assertStringMatchesFormat:

``assertStringMatchesFormat()``
-------------------------------

``assertStringMatchesFormat(string $format, string $string[, string $message])``

Reports an error identified by ``$message`` if the ``$string`` does not match the ``$format`` string.

``assertStringNotMatchesFormat()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/StringMatchesFormatTest.php
   :caption: Usage of assertStringMatchesFormat()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/StringMatchesFormatTest.php.out

The format string may contain the following placeholders:

-

  ``%e``: Represents a directory separator, for example ``/`` on Linux.

-

  ``%s``: One or more of anything (character or white space) except the end of line character.

-

  ``%S``: Zero or more of anything (character or white space) except the end of line character.

-

  ``%a``: One or more of anything (character or white space) including the end of line character.

-

  ``%A``: Zero or more of anything (character or white space) including the end of line character.

-

  ``%w``: Zero or more white space characters.

-

  ``%i``: A signed integer value, for example ``+3142``, ``-3142``.

-

  ``%d``: An unsigned integer value, for example ``123456``.

-

  ``%x``: One or more hexadecimal character. That is, characters in the range ``0-9``, ``a-f``, ``A-F``.

-

  ``%f``: A floating point number, for example: ``3.142``, ``-3.142``, ``3.142E-10``, ``3.142e+10``.

-

  ``%c``: A single character of any sort.

-

  ``%%``: A literal percent character: ``%``.

.. _appendixes.assertions.assertStringMatchesFormatFile:

``assertStringMatchesFormatFile()``
-----------------------------------

``assertStringMatchesFormatFile(string $formatFile, string $string[, string $message])``

Reports an error identified by ``$message`` if the ``$string`` does not match the contents of the ``$formatFile``.

``assertStringNotMatchesFormatFile()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/StringMatchesFormatFileTest.php
   :caption: Usage of assertStringMatchesFormatFile()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/StringMatchesFormatFileTest.php.out

.. _appendixes.assertions.assertStringEqualsFile:

``assertStringEqualsFile()``
----------------------------

``assertStringEqualsFile(string $expectedFile, string $actualString[, string $message])``

Reports an error identified by ``$message`` if the file specified by ``$expectedFile`` does not have ``$actualString`` as its contents.

``assertStringNotEqualsFile()`` is the inverse of this assertion and takes the same arguments.

``assertStringEqualsFileCanonicalizing()`` (and ``assertStringNotEqualsFileCanonicalizing()``) as well as ``assertStringEqualsFileIgnoringCase()`` (and ``assertStringNotEqualsFileIgnoringCase()``) do for files what ``assertEqualsCanonicalizing()`` (and ``assertNotEqualsCanonicalizing()``) as well as ``assertEqualsIgnoringCase()`` (and ``assertNotEqualsIgnoringCase()``) do for strings.


.. literalinclude:: examples/assertions/StringEqualsFileTest.php
   :caption: Usage of assertStringEqualsFile()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/StringEqualsFileTest.php.out

.. _appendixes.assertions.json:

JSON
====

.. _appendixes.assertions.assertJson:

``assertJson()``
----------------

``assertJson(string $actual[, string $message])``

Reports an error identified by ``$message`` if the value of ``$actual`` is not valid JSON.

.. literalinclude:: examples/assertions/JsonTest.php
   :caption: Usage of assertJson()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/JsonTest.php.out

.. _appendixes.assertions.assertJsonFileEqualsJsonFile:

``assertJsonFileEqualsJsonFile()``
----------------------------------

``assertJsonFileEqualsJsonFile(string $expectedFile, string $actualFile[, string $message])``

Reports an error identified by ``$message`` if the value of ``$actualFile`` does not match the value of
``$expectedFile``.

``assertJsonFileNotEqualsJsonFile()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/JsonFileEqualsJsonFileTest.php
   :caption: Usage of assertJsonFileEqualsJsonFile()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/JsonFileEqualsJsonFileTest.php.out

.. _appendixes.assertions.assertJsonStringEqualsJsonFile:

``assertJsonStringEqualsJsonFile()``
------------------------------------

``assertJsonStringEqualsJsonFile(string $expectedFile, string $actualJson[, string $message])``

Reports an error identified by ``$message`` if the value of ``$actualJson`` does not match the value of
``$expectedFile``.

``assertJsonStringNotEqualsJsonFile()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/JsonStringEqualsJsonFileTest.php
   :caption: Usage of assertJsonStringEqualsJsonFile()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/JsonStringEqualsJsonFileTest.php.out

.. _appendixes.assertions.assertJsonStringEqualsJsonString:

``assertJsonStringEqualsJsonString()``
--------------------------------------

``assertJsonStringEqualsJsonString(string $expectedJson, string $actualJson[, string $message])``

Reports an error identified by ``$message`` if the value of ``$actualJson`` does not match the value of
``$expectedJson``.

``assertJsonStringNotEqualsJsonString()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/JsonStringEqualsJsonStringTest.php
   :caption: Usage of assertJsonStringEqualsJsonString()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/JsonStringEqualsJsonStringTest.php.out

.. _appendixes.assertions.xml:

XML
===

.. _appendixes.assertions.assertXmlFileEqualsXmlFile:

``assertXmlFileEqualsXmlFile()``
--------------------------------

``assertXmlFileEqualsXmlFile(string $expectedFile, string $actualFile[, string $message])``

Reports an error identified by ``$message`` if the XML document in ``$actualFile`` is not equal to the XML document in ``$expectedFile``.

``assertXmlFileNotEqualsXmlFile()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/XmlFileEqualsXmlFileTest.php
   :caption: Usage of assertXmlFileEqualsXmlFile()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/XmlFileEqualsXmlFileTest.php.out

.. _appendixes.assertions.assertXmlStringEqualsXmlFile:

``assertXmlStringEqualsXmlFile()``
----------------------------------

``assertXmlStringEqualsXmlFile(string $expectedFile, string $actualXml[, string $message])``

Reports an error identified by ``$message`` if the XML document in ``$actualXml`` is not equal to the XML document in ``$expectedFile``.

``assertXmlStringNotEqualsXmlFile()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/XmlStringEqualsXmlFileTest.php
   :caption: Usage of assertXmlStringEqualsXmlFile()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/XmlStringEqualsXmlFileTest.php.out

.. _appendixes.assertions.assertXmlStringEqualsXmlString:

``assertXmlStringEqualsXmlString()``
------------------------------------

``assertXmlStringEqualsXmlString(string $expectedXml, string $actualXml[, string $message])``

Reports an error identified by ``$message`` if the XML document in ``$actualXml`` is not equal to the XML document in ``$expectedXml``.

``assertXmlStringNotEqualsXmlString()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/XmlStringEqualsXmlStringTest.php
   :caption: Usage of assertXmlStringEqualsXmlString()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/XmlStringEqualsXmlStringTest.php.out

.. _appendixes.assertions.filesystem:

Filesystem
==========

.. _appendixes.assertions.assertDirectoryExists:

``assertDirectoryExists()``
---------------------------

``assertDirectoryExists(string $directory[, string $message])``

Reports an error identified by ``$message`` if the directory specified by ``$directory`` does not exist.

``assertDirectoryDoesNotExist()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/DirectoryExistsTest.php
   :caption: Usage of assertDirectoryExists()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/DirectoryExistsTest.php.out

.. _appendixes.assertions.assertDirectoryIsReadable:

``assertDirectoryIsReadable()``
-------------------------------

``assertDirectoryIsReadable(string $directory[, string $message])``

Reports an error identified by ``$message`` if the directory specified by ``$directory`` is not a directory or is not readable.

``assertDirectoryIsNotReadable()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/DirectoryIsReadableTest.php
   :caption: Usage of assertDirectoryIsReadable()
   :language: php

Running the test shown above yields the output shown below:

.. parsed-literal::

    ./tools/phpunit tests/DirectoryIsReadableTest.php
    PHPUnit 10.0.11 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.3

    F

    Time: 00:00, Memory: 14.29 MB

    There was 1 failure:

    1) DirectoryIsReadableTest::testFailure
    Failed asserting that "/path/to/directory" is readable.

    /path/to/DirectoryIsReadableTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertDirectoryIsWritable:

``assertDirectoryIsWritable()``
-------------------------------

``assertDirectoryIsWritable(string $directory[, string $message])``

Reports an error identified by ``$message`` if the directory specified by ``$directory`` is not a directory or is not writable.

``assertDirectoryIsNotWritable()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/DirectoryIsReadableTest.php
   :caption: Usage of assertDirectoryIsWritable()
   :language: php

Running the test shown above yields the output shown below:

.. parsed-literal::

    ./tools/phpunit tests/DirectoryIsWritableTest.php
    PHPUnit 10.0.11 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.3

    F

    Time: 00:00, Memory: 14.29 MB

    There was 1 failure:

    1) DirectoryIsWritableTest::testFailure
    Failed asserting that "/path/to/directory" is writable.

    /path/to/DirectoryIsWritableTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertFileExists:

``assertFileExists()``
----------------------

``assertFileExists(string $filename[, string $message])``

Reports an error identified by ``$message`` if the file specified by ``$filename`` does not exist.

``assertFileDoesNotExist()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/FileExistsTest.php
   :caption: Usage of assertFileExists()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/FileExistsTest.php.out

.. _appendixes.assertions.assertFileIsReadable:

``assertFileIsReadable()``
--------------------------

``assertFileIsReadable(string $filename[, string $message])``

Reports an error identified by ``$message`` if the file specified by ``$filename`` is not a file or is not readable.

``assertFileIsNotReadable()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/FileIsReadableTest.php
   :caption: Usage of assertFileIsReadable()
   :language: php

Running the test shown above yields the output shown below:

.. parsed-literal::

    ./tools/phpunit tests/FileIsReadableTest.php
    PHPUnit 10.0.11 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.3

    F

    Time: 00:00, Memory: 14.29 MB

    There was 1 failure:

    1) FileIsReadableTest::testFailure
    Failed asserting that "/path/to/file" is readable.

    /path/to/FileIsReadableTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertFileIsWritable:

``assertFileIsWritable()``
--------------------------

``assertFileIsWritable(string $filename[, string $message])``

Reports an error identified by ``$message`` if the file specified by ``$filename`` is not a file or is not writable.

``assertFileIsNotWritable()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/FileIsWritableTest.php
   :caption: Usage of assertFileIsWritable()
   :language: php

Running the test shown above yields the output shown below:

.. parsed-literal::

    ./tools/phpunit tests/FileIsWritableTest.php
    PHPUnit 10.0.11 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.3

    F

    Time: 00:00, Memory: 14.29 MB

    There was 1 failure:

    1) FileIsWritableTest::testFailure
    Failed asserting that "/path/to/file" is writable.

    /path/to/FileIsWritableTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertIsReadable:

``assertIsReadable()``
----------------------

``assertIsReadable(string $filename[, string $message])``

Reports an error identified by ``$message`` if the file or directory specified by ``$filename`` is not readable.

``assertIsNotReadable()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/IsReadableTest.php
   :caption: Usage of assertIsReadable()
   :language: php

Running the test shown above yields the output shown below:

.. parsed-literal::

    ./tools/phpunit tests/IsReadableTest.php
    PHPUnit 10.0.11 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.3

    F

    Time: 00:00, Memory: 14.29 MB

    There was 1 failure:

    1) IsReadableTest::testFailure
    Failed asserting that "/path/to/unreadable" is readable.

    /path/to/IsReadableTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertIsWritable:

``assertIsWritable()``
----------------------

``assertIsWritable(string $filename[, string $message])``

Reports an error identified by ``$message`` if the file or directory specified by ``$filename`` is not writable.

``assertIsNotWritable()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/IsWritableTest.php
   :caption: Usage of assertIsWritable()
   :language: php

Running the test shown above yields the output shown below:

.. parsed-literal::

    ./tools/phpunit tests/IsWritableTest.php
    PHPUnit 10.0.11 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.3

    F

    Time: 00:00, Memory: 14.29 MB

    There was 1 failure:

    1) IsWritableTest::testFailure
    Failed asserting that "/path/to/unwritable" is writable.

    /path/to/IsWritableTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.math:

Math
====

.. _appendixes.assertions.assertInfinite:

``assertInfinite()``
--------------------

``assertInfinite(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not ``INF``.

``assertFinite()`` is the inverse of this assertion and takes the same arguments.

.. literalinclude:: examples/assertions/InfiniteTest.php
   :caption: Usage of assertInfinite()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/InfiniteTest.php.out

.. _appendixes.assertions.assertNan:

``assertNan()``
---------------

``assertNan(mixed $actual[, string $message])``

Reports an error identified by ``$message`` if ``$actual`` is not ``NAN``.

.. literalinclude:: examples/assertions/NanTest.php
   :caption: Usage of assertNan()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/NanTest.php.out

.. _appendixes.assertions.constraints:

Constraints
===========

.. _appendixes.assertions.assertThat:

``assertThat()``
----------------

``assertThat(mixed $value, PHPUnit\Framework\Constraint $constraint[, string $message])``

Reports an error identified by ``$message`` if the ``$value`` does not match the ``$constraint``.

More complex assertions can be formulated using the
``PHPUnit\Framework\Constraint`` classes. They can be
evaluated using the ``assertThat()`` method.

This example shows how the ``logicalNot()`` and ``equalTo()`` constraints can be used,
for instance, to express the same assertion as ``assertNotEquals()``:

.. literalinclude:: examples/assertions/BiscuitTest.php
   :caption: Usage of assertThat()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/assertions/BiscuitTest.php.out

:numref:`appendixes.assertions.assertThat.tables.constraints` shows the
available ``PHPUnit\Framework\Constraint`` classes.

.. rst-class:: table
.. list-table:: Constraints
    :name: appendixes.assertions.assertThat.tables.constraints
    :header-rows: 1

    * - Constraint
      - Meaning
    * - ``PHPUnit\Framework\Constraint\IsAnything anything()``
      - Constraint that accepts any input value.
    * - ``PHPUnit\Framework\Constraint\ArrayHasKey arrayHasKey(mixed $key)``
      - Constraint that asserts that the array has a given key.
    * - ``PHPUnit\Framework\Constraint\TraversableContains contains(mixed $value)``
      - Constraint that asserts that the ``array`` or object that implements the ``Iterator`` interface contains a given value.
    * - ``PHPUnit\Framework\Constraint\TraversableContainsOnly containsOnly(string $type)``
      - Constraint that asserts that the ``array`` or object that implements the ``Iterator`` interface contains only values of a given type.
    * - ``PHPUnit\Framework\Constraint\TraversableContainsOnly containsOnlyInstancesOf(string $classname)``
      - Constraint that asserts that the ``array`` or object that implements the ``Iterator`` interface contains only instances of a given classname.
    * - ``PHPUnit\Framework\Constraint\IsEqual equalTo($value, $delta = 0, $maxDepth = 10)``
      - Constraint that checks if one value is equal to another.
    * - ``PHPUnit\Framework\Constraint\DirectoryExists directoryExists()``
      - Constraint that checks if the directory exists.
    * - ``PHPUnit\Framework\Constraint\FileExists fileExists()``
      - Constraint that checks if the file(name) exists.
    * - ``PHPUnit\Framework\Constraint\IsReadable isReadable()``
      - Constraint that checks if the file(name) is readable.
    * - ``PHPUnit\Framework\Constraint\IsWritable isWritable()``
      - Constraint that checks if the file(name) is writable.
    * - ``PHPUnit\Framework\Constraint\GreaterThan greaterThan(mixed $value)``
      - Constraint that asserts that the value is greater than a given value.
    * - ``PHPUnit\Framework\Constraint\LogicalOr greaterThanOrEqual(mixed $value)``
      - Constraint that asserts that the value is greater than or equal to a given value.
    * - ``PHPUnit\Framework\Constraint\IsIdentical identicalTo(mixed $value)``
      - Constraint that asserts that one value is identical to another.
    * - ``PHPUnit\Framework\Constraint\IsFalse isFalse()``
      - Constraint that asserts that the value is ``false``.
    * - ``PHPUnit\Framework\Constraint\IsInstanceOf isInstanceOf(string $className)``
      - Constraint that asserts that the object is an instance of a given class.
    * - ``PHPUnit\Framework\Constraint\IsNull isNull()``
      - Constraint that asserts that the value is ``null``.
    * - ``PHPUnit\Framework\Constraint\IsTrue isTrue()``
      - Constraint that asserts that the value is ``true``.
    * - ``PHPUnit\Framework\Constraint\IsType isType(string $type)``
      - Constraint that asserts that the value is of a specified type.
    * - ``PHPUnit\Framework\Constraint\LessThan lessThan(mixed $value)``
      - Constraint that asserts that the value is smaller than a given value.
    * - ``PHPUnit\Framework\Constraint\LogicalOr lessThanOrEqual(mixed $value)``
      - Constraint that asserts that the value is smaller than or equal to a given value.
    * - ``logicalAnd()``
      - Logical AND.
    * - ``logicalNot(PHPUnit\Framework\Constraint $constraint)``
      - Logical NOT.
    * - ``logicalOr()``
      - Logical OR.
    * - ``logicalXor()``
      - Logical XOR.
    * - ``PHPUnit\Framework\Constraint\PCREMatch matchesRegularExpression(string $pattern)``
      - Constraint that asserts that the string matches a regular expression.
    * - ``PHPUnit\Framework\Constraint\StringContains stringContains(string $string, bool $case)``
      - Constraint that asserts that the string contains a given string.
    * - ``PHPUnit\Framework\Constraint\StringEndsWith stringEndsWith(string $suffix)``
      - Constraint that asserts that the string ends with a given suffix.
    * - ``PHPUnit\Framework\Constraint\StringStartsWith stringStartsWith(string $prefix)``
      - Constraint that asserts that the string starts with a given prefix.


