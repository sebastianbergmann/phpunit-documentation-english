

.. _appendixes.assertions:

==========
Assertions
==========

This appendix lists the various assertion methods that are available.

.. _appendixes.assertions.static-vs-non-static-usage-of-assertion-methods:

Static vs. Non-Static Usage of Assertion Methods
################################################

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

.. _appendixes.assertions.assertArrayHasKey:

assertArrayHasKey()
###################

``assertArrayHasKey(mixed $key, array $array[, string $message = ''])``

Reports an error identified by ``$message`` if ``$array`` does not have the ``$key``.

``assertArrayNotHasKey()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertArrayHasKey()
    :name: appendixes.assertions.assertArrayHasKey.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ArrayHasKeyTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertArrayHasKey('foo', ['bar' => 'baz']);
        }
    }

.. parsed-literal::

    $ phpunit ArrayHasKeyTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) ArrayHasKeyTest::testFailure
    Failed asserting that an array has the key 'foo'.

    /home/sb/ArrayHasKeyTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertClassHasAttribute:

assertClassHasAttribute()
#########################

``assertClassHasAttribute(string $attributeName, string $className[, string $message = ''])``

Reports an error identified by ``$message`` if ``$className::attributeName`` does not exist.

``assertClassNotHasAttribute()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertClassHasAttribute()
    :name: appendixes.assertions.assertClassHasAttribute.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ClassHasAttributeTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertClassHasAttribute('foo', stdClass::class);
        }
    }

.. parsed-literal::

    $ phpunit ClassHasAttributeTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) ClassHasAttributeTest::testFailure
    Failed asserting that class "stdClass" has attribute "foo".

    /home/sb/ClassHasAttributeTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertClassHasStaticAttribute:

assertClassHasStaticAttribute()
###############################

``assertClassHasStaticAttribute(string $attributeName, string $className[, string $message = ''])``

Reports an error identified by ``$message`` if ``$className::attributeName`` does not exist.

``assertClassNotHasStaticAttribute()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertClassHasStaticAttribute()
    :name: appendixes.assertions.assertClassHasStaticAttribute.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ClassHasStaticAttributeTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertClassHasStaticAttribute('foo', stdClass::class);
        }
    }

.. parsed-literal::

    $ phpunit ClassHasStaticAttributeTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) ClassHasStaticAttributeTest::testFailure
    Failed asserting that class "stdClass" has static attribute "foo".

    /home/sb/ClassHasStaticAttributeTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertContains:

assertContains()
################

``assertContains(mixed $needle, iterable $haystack[, string $message = ''])``

Reports an error identified by ``$message`` if ``$needle`` is not an element of ``$haystack``.

``assertNotContains()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertContains()
    :name: appendixes.assertions.assertContains.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ContainsTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertContains(4, [1, 2, 3]);
        }
    }

.. parsed-literal::

    $ phpunit ContainsTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) ContainsTest::testFailure
    Failed asserting that an array contains 4.

    /home/sb/ContainsTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertContainsEquals()
################

``assertContainsEquals(mixed $needle, iterable $haystack[, string $message = ''])``

Reports an error identified by ``$message`` if ``$needle`` is not an element of ``$haystack``.

``assertNotContainsEquals()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertContainsEquals()
    :name: appendixes.assertions.assertContainsEquals.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ContainsEqualsTest extends TestCase
    {
        public function testFailure(): void
        {
            $needle = 'foo';
            $haystack = ['bar'];

            $this->assertContainsEquals($needle, $haystack);
        }
    }

.. parsed-literal::
    $ phpunit ContainsEqualsTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 00:00.004, Memory: 8.00 MB

    There was 1 failure:

    1) ContainsEqualsTest::testFailure
    Failed asserting that an array contains 'foo'.

    /home/sb/ContainsTest.php:11

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

``assertContainsEquals(object $needle, iterable $haystack[, string $message = ''])``

Reports an error identified by ``$message`` if the object ``$needle`` is not an element of ``$haystack``.
It only checks for the objects to have equal attribute values, but it does not check for the variables to reference the same object.

.. code-block:: php
    :caption: Usage of assertContainsEquals()
    :name: appendixes.assertions.assertContainsEquals.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;
    use \stdClass;

    final class ContainsEqualsTest extends TestCase
    {
        public function testFailure(): void
        {
            $needle = new stdClass();
            $needle->foo = 'foo';
            $needle->bar = 'bar';

            $actual = new stdClass;
            $actual->foo = 'bar';
            $actual->bar = 'bar';

            $this->assertContainsEquals($needle, [$actual]);
        }
    }

.. parsed-literal::

    $ phpunit ContainsEqualsTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 00:00.004, Memory: 8.00 MB

    There was 1 failure:

    1) ContainsEqualsTest::testFailure
    Failed asserting that an array contains stdClass Object &0000000077d10c82000000004b2dd749 (
        'foo' => 'foo'
        'bar' => 'bar'
    ).

    /home/sb/ContainsTest.php:17

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertStringContainsString()
############################

``assertStringContainsString(string $needle, string $haystack[, string $message = ''])``

Reports an error identified by ``$message`` if ``$needle`` is not a substring of ``$haystack``.

``assertStringNotContainsString()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertStringContainsString()
    :name: appendixes.assertions.assertStringContainsString.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class StringContainsStringTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertStringContainsString('foo', 'bar');
        }
    }

.. parsed-literal::

    $ phpunit StringContainsStringTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F                                                                   1 / 1 (100%)

    Time: 37 ms, Memory: 6.00 MB

    There was 1 failure:

    1) StringContainsStringTest::testFailure
    Failed asserting that 'bar' contains "foo".

    /home/sb/StringContainsStringTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertStringContainsStringIgnoringCase()
########################################

``assertStringContainsStringIgnoringCase(string $needle, string $haystack[, string $message = ''])``

Reports an error identified by ``$message`` if ``$needle`` is not a substring of ``$haystack``.

Differences in casing are ignored when ``$needle`` is searched for in ``$haystack``.

``assertStringNotContainsStringIgnoringCase()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertStringContainsStringIgnoringCase()
    :name: appendixes.assertions.assertStringContainsStringIgnoringCase.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class StringContainsStringIgnoringCaseTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertStringContainsStringIgnoringCase('foo', 'bar');
        }
    }

.. parsed-literal::

    $ phpunit StringContainsStringIgnoringCaseTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F                                                                   1 / 1 (100%)

    Time: 40 ms, Memory: 6.00 MB

    There was 1 failure:

    1) StringContainsStringTest::testFailure
    Failed asserting that 'bar' contains "foo".

    /home/sb/StringContainsStringIgnoringCaseTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertContainsOnly:

assertContainsOnly()
####################

``assertContainsOnly(string $type, iterable $haystack[, boolean $isNativeType = null, string $message = ''])``

Reports an error identified by ``$message`` if ``$haystack`` does not contain only variables of type ``$type``.

``$isNativeType`` is a flag used to indicate whether ``$type`` is a native PHP type or not.

``assertNotContainsOnly()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertContainsOnly()
    :name: appendixes.assertions.assertContainsOnly.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ContainsOnlyTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertContainsOnly('string', ['1', '2', 3]);
        }
    }

.. parsed-literal::

    $ phpunit ContainsOnlyTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) ContainsOnlyTest::testFailure
    Failed asserting that Array (
        0 => '1'
        1 => '2'
        2 => 3
    ) contains only values of type "string".

    /home/sb/ContainsOnlyTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertContainsOnlyInstancesOf:

assertContainsOnlyInstancesOf()
###############################

``assertContainsOnlyInstancesOf(string $classname, Traversable|array $haystack[, string $message = ''])``

Reports an error identified by ``$message`` if ``$haystack`` does not contain only instances of class ``$classname``.

.. code-block:: php
    :caption: Usage of assertContainsOnlyInstancesOf()
    :name: appendixes.assertions.assertContainsOnlyInstancesOf.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ContainsOnlyInstancesOfTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertContainsOnlyInstancesOf(
                Foo::class,
                [new Foo, new Bar, new Foo]
            );
        }
    }

.. parsed-literal::

    $ phpunit ContainsOnlyInstancesOfTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) ContainsOnlyInstancesOfTest::testFailure
    Failed asserting that Array ([0]=> Bar Object(...)) is an instance of class "Foo".

    /home/sb/ContainsOnlyInstancesOfTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertCount:

assertCount()
#############

``assertCount($expectedCount, $haystack[, string $message = ''])``

Reports an error identified by ``$message`` if the number of elements in ``$haystack`` is not ``$expectedCount``.

``assertNotCount()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertCount()
    :name: appendixes.assertions.assertCount.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class CountTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertCount(0, ['foo']);
        }
    }

.. parsed-literal::

    $ phpunit CountTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) CountTest::testFailure
    Failed asserting that actual size 1 matches expected size 0.

    /home/sb/CountTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertDirectoryExists:

assertDirectoryExists()
#######################

``assertDirectoryExists(string $directory[, string $message = ''])``

Reports an error identified by ``$message`` if the directory specified by ``$directory`` does not exist.

``assertDirectoryDoesNotExist()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertDirectoryExists()
    :name: appendixes.assertions.assertDirectoryExists.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class DirectoryExistsTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertDirectoryExists('/path/to/directory');
        }
    }

.. parsed-literal::

    $ phpunit DirectoryExistsTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) DirectoryExistsTest::testFailure
    Failed asserting that directory "/path/to/directory" exists.

    /home/sb/DirectoryExistsTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertDirectoryIsReadable:

assertDirectoryIsReadable()
###########################

``assertDirectoryIsReadable(string $directory[, string $message = ''])``

Reports an error identified by ``$message`` if the directory specified by ``$directory`` is not a directory or is not readable.

``assertDirectoryIsNotReadable()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertDirectoryIsReadable()
    :name: appendixes.assertions.assertDirectoryIsReadable.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class DirectoryIsReadableTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertDirectoryIsReadable('/path/to/directory');
        }
    }

.. parsed-literal::

    $ phpunit DirectoryIsReadableTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) DirectoryIsReadableTest::testFailure
    Failed asserting that "/path/to/directory" is readable.

    /home/sb/DirectoryIsReadableTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertDirectoryIsWritable:

assertDirectoryIsWritable()
###########################

``assertDirectoryIsWritable(string $directory[, string $message = ''])``

Reports an error identified by ``$message`` if the directory specified by ``$directory`` is not a directory or is not writable.

``assertDirectoryIsNotWritable()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertDirectoryIsWritable()
    :name: appendixes.assertions.assertDirectoryIsWritable.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class DirectoryIsWritableTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertDirectoryIsWritable('/path/to/directory');
        }
    }

.. parsed-literal::

    $ phpunit DirectoryIsWritableTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) DirectoryIsWritableTest::testFailure
    Failed asserting that "/path/to/directory" is writable.

    /home/sb/DirectoryIsWritableTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertEmpty:

assertEmpty()
#############

``assertEmpty(mixed $actual[, string $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not empty.

``assertNotEmpty()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertEmpty()
    :name: appendixes.assertions.assertEmpty.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class EmptyTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertEmpty(['foo']);
        }
    }

.. parsed-literal::

    $ phpunit EmptyTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) EmptyTest::testFailure
    Failed asserting that an array is empty.

    /home/sb/EmptyTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertEquals:

assertEquals()
##############

``assertEquals(mixed $expected, mixed $actual[, string $message = ''])``

Reports an error identified by ``$message`` if the two variables ``$expected`` and ``$actual`` are not equal.

``assertNotEquals()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertEquals()
    :name: appendixes.assertions.assertEquals.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class EqualsTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertEquals(1, 0);
        }

        public function testFailure2(): void
        {
            $this->assertEquals('bar', 'baz');
        }

        public function testFailure3(): void
        {
            $this->assertEquals("foo\nbar\nbaz\n", "foo\nbah\nbaz\n");
        }
    }

.. parsed-literal::

    $ phpunit EqualsTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    FFF

    Time: 0 seconds, Memory: 5.25Mb

    There were 3 failures:

    1) EqualsTest::testFailure
    Failed asserting that 0 matches expected 1.

    /home/sb/EqualsTest.php:6

    2) EqualsTest::testFailure2
    Failed asserting that two strings are equal.
    --- Expected
    +++ Actual
    @@ @@
    -'bar'
    +'baz'

    /home/sb/EqualsTest.php:11

    3) EqualsTest::testFailure3
    Failed asserting that two strings are equal.
    --- Expected
    +++ Actual
    @@ @@
     'foo
    -bar
    +bah
     baz
     '

    /home/sb/EqualsTest.php:16

    FAILURES!
    Tests: 3, Assertions: 3, Failures: 3.

More specialized comparisons are used for specific argument types for ``$expected`` and ``$actual``, see below.

``assertEquals(DOMDocument $expected, DOMDocument $actual[, string $message = ''])``

Reports an error identified by ``$message`` if the uncommented canonical form of the XML documents represented by the two DOMDocument objects ``$expected`` and ``$actual`` are not equal.

.. code-block:: php
    :caption: Usage of assertEquals() with DOMDocument objects
    :name: appendixes.assertions.assertEquals.example3

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class EqualsTest extends TestCase
    {
        public function testFailure(): void
        {
            $expected = new DOMDocument;
            $expected->loadXML('<foo><bar/></foo>');

            $actual = new DOMDocument;
            $actual->loadXML('<bar><foo/></bar>');

            $this->assertEquals($expected, $actual);
        }
    }

.. parsed-literal::

    $ phpunit EqualsTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) EqualsTest::testFailure
    Failed asserting that two DOM documents are equal.
    --- Expected
    +++ Actual
    @@ @@
     <?xml version="1.0"?>
    -<foo>
    -  <bar/>
    -</foo>
    +<bar>
    +  <foo/>
    +</bar>

    /home/sb/EqualsTest.php:12

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

``assertEquals(object $expected, object $actual[, string $message = ''])``

Reports an error identified by ``$message`` if the two objects ``$expected`` and ``$actual`` do not have equal attribute values.

.. code-block:: php
    :caption: Usage of assertEquals() with objects
    :name: appendixes.assertions.assertEquals.example4

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class EqualsTest extends TestCase
    {
        public function testFailure(): void
        {
            $expected = new stdClass;
            $expected->foo = 'foo';
            $expected->bar = 'bar';

            $actual = new stdClass;
            $actual->foo = 'bar';
            $actual->baz = 'bar';

            $this->assertEquals($expected, $actual);
        }
    }

.. parsed-literal::

    $ phpunit EqualsTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.25Mb

    There was 1 failure:

    1) EqualsTest::testFailure
    Failed asserting that two objects are equal.
    --- Expected
    +++ Actual
    @@ @@
     stdClass Object (
    -    'foo' => 'foo'
    -    'bar' => 'bar'
    +    'foo' => 'bar'
    +    'baz' => 'bar'
     )

    /home/sb/EqualsTest.php:14

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

``assertEquals(array $expected, array $actual[, string $message = ''])``

Reports an error identified by ``$message`` if the two arrays ``$expected`` and ``$actual`` are not equal.

.. code-block:: php
    :caption: Usage of assertEquals() with arrays
    :name: appendixes.assertions.assertEquals.example5

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class EqualsTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertEquals(['a', 'b', 'c'], ['a', 'c', 'd']);
        }
    }

.. parsed-literal::

    $ phpunit EqualsTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.25Mb

    There was 1 failure:

    1) EqualsTest::testFailure
    Failed asserting that two arrays are equal.
    --- Expected
    +++ Actual
    @@ @@
     Array (
         0 => 'a'
    -    1 => 'b'
    -    2 => 'c'
    +    1 => 'c'
    +    2 => 'd'
     )

    /home/sb/EqualsTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertEqualsCanonicalizing()
############################

``assertEqualsCanonicalizing(mixed $expected, mixed $actual[, string $message = ''])``

Reports an error identified by ``$message`` if the two variables ``$expected`` and ``$actual`` are not equal.

The contents of ``$expected`` and ``$actual`` are canonicalized before they are compared. For instance, when the two variables ``$expected`` and ``$actual`` are arrays, then these arrays are sorted before they are compared. When ``$expected`` and ``$actual`` are objects, each object is converted to an array containing all private, protected and public attributes.

``assertNotEqualsCanonicalizing()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertEqualsCanonicalizing()
    :name: appendixes.assertions.assertEqualsCanonicalizing.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class EqualsCanonicalizingTest extends TestCase
    {
        public function testFailure()
        {
            $this->assertEqualsCanonicalizing([3, 2, 1], [2, 3, 0, 1]);
        }
    }

.. parsed-literal::

    $ phpunit EqualsCanonicalizingTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F                                                                   1 / 1 (100%)

    Time: 42 ms, Memory: 6.00 MB

    There was 1 failure:

    1) EqualsCanonicalizingTest::testFailure
    Failed asserting that two arrays are equal.
    --- Expected
    +++ Actual
    @@ @@
     Array (
    -    0 => 1
    -    1 => 2
    -    2 => 3
    +    0 => 0
    +    1 => 1
    +    2 => 2
    +    3 => 3
     )

    /home/sb/EqualsCanonicalizingTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertEqualsIgnoringCase()
##########################

``assertEqualsIgnoringCase(mixed $expected, mixed $actual[, string $message = ''])``

Reports an error identified by ``$message`` if the two variables ``$expected`` and ``$actual`` are not equal.

Differences in casing are ignored for the comparison of ``$expected`` and ``$actual``.

``assertNotEqualsIgnoringCase()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertEqualsIgnoringCase()
    :name: appendixes.assertions.assertEqualsIgnoringCase.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class EqualsIgnoringCaseTest extends TestCase
    {
        public function testFailure()
        {
            $this->assertEqualsIgnoringCase('foo', 'BAR');
        }
    }

.. parsed-literal::

    $ phpunit EqualsIgnoringCaseTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F                                                                   1 / 1 (100%)

    Time: 51 ms, Memory: 6.00 MB

    There was 1 failure:

    1) EqualsIgnoringCaseTest::testFailure
    Failed asserting that two strings are equal.
    --- Expected
    +++ Actual
    @@ @@
    -'foo'
    +'BAR'

    /home/sb/EqualsIgnoringCaseTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertEqualsWithDelta()
#######################

``assertEqualsWithDelta(mixed $expected, mixed $actual, float $delta[, string $message = ''])``

Reports an error identified by ``$message`` if the absolute difference between ``$expected`` and ``$actual`` is greater than ``$delta``.

Please read "`What Every Computer Scientist Should Know About Floating-Point Arithmetic <http://docs.oracle.com/cd/E19957-01/806-3568/ncg_goldberg.html>`_" to understand why ``$delta`` is necessary.

``assertNotEqualsWithDelta()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertEqualsWithDelta()
    :name: appendixes.assertions.assertEqualsWithDelta.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class EqualsWithDeltaTest extends TestCase
    {
        public function testFailure()
        {
            $this->assertEqualsWithDelta(1.0, 1.5, 0.1);
        }
    }

.. parsed-literal::

    $ phpunit EqualsWithDeltaTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F                                                                   1 / 1 (100%)

    Time: 41 ms, Memory: 6.00 MB

    There was 1 failure:

    1) EqualsWithDeltaTest::testFailure
    Failed asserting that 1.5 matches expected 1.0.

    /home/sb/EqualsWithDeltaTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertObjectEquals:

assertObjectEquals()
####################

``assertObjectEquals(object $expected, object $actual, string $method = 'equals', string $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not equal to ``$expected`` according to ``$actual->$method($expected)``.

It is a bad practice to use ``assertEquals()`` (and its inverse, ``assertNotEquals()``) on objects without registering a custom comparator that customizes how objects are compared. Unfortunately, though, implementing custom comparators for each and every object you want to assert in your tests is inconvenient at best.

The most common use case for custom comparators are Value Objects. These objects usually have an ``equals(self $other): bool`` method (or a method just like that but with a different name) for comparing two instances of the Value Object's type. ``assertObjectEquals()`` makes custom comparison of objects convenient for this common use case:

.. code-block:: php
    :caption: Usage of assertObjectEquals()
    :name: appendixes.assertions.assertObjectEquals.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class SomethingThatUsesEmailTest extends TestCase
    {
        public function testSomething(): void
        {
            $a = new Email('user@example.org');
            $b = new Email('user@example.org');
            $c = new Email('user@example.com');

            // This passes
            $this->assertObjectEquals($a, $b);

            // This fails
            $this->assertObjectEquals($a, $c);
        }
    }

.. code-block:: php
    :caption: Email value object with equals() method
    :name: appendixes.assertions.Email.example

    <?php declare(strict_types=1);
    final class Email
    {
        private string $email;

        public function __construct(string $email)
        {
            $this->ensureIsValidEmail($email);

            $this->email = $email;
        }

        public function asString(): string
        {
            return $this->email;
        }

        public function equals(self $other): bool
        {
            return $this->asString() === $other->asString();
        }

        private function ensureIsValidEmail(string $email): void
        {
            // ...
        }
    }

.. parsed-literal::

    $ phpunit EqualsTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F                                                                   1 / 1 (100%)

    Time: 00:00.017, Memory: 4.00 MB

    There was 1 failure:

    1) SomethingThatUsesEmailTest::testSomething
    Failed asserting that two objects are equal.
    The objects are not equal according to Email::equals().

    /home/sb/SomethingThatUsesEmailTest.php:16

    FAILURES!
    Tests: 1, Assertions: 2, Failures: 1.

Please note:

* A method with name ``$method`` must exist on the ``$actual`` object
* The method must accept exactly one argument
* The respective parameter must have a declared type
* The ``$expected`` object must be compatible with this declared type
* The method must have a declared ``bool`` return type

If any of the aforementioned assumptions is not fulfilled or if ``$actual->$method($expected)`` returns ``false`` then the assertion fails.

.. _appendixes.assertions.assertFalse:

assertFalse()
#############

``assertFalse(bool $condition[, string $message = ''])``

Reports an error identified by ``$message`` if ``$condition`` is ``true``.

``assertNotFalse()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertFalse()
    :name: appendixes.assertions.assertFalse.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class FalseTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertFalse(true);
        }
    }

.. parsed-literal::

    $ phpunit FalseTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) FalseTest::testFailure
    Failed asserting that true is false.

    /home/sb/FalseTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertFileEquals:

assertFileEquals()
##################

``assertFileEquals(string $expected, string $actual[, string $message = ''])``

Reports an error identified by ``$message`` if the file specified by ``$expected`` does not have the same contents as the file specified by ``$actual``.

``assertFileNotEquals()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertFileEquals()
    :name: appendixes.assertions.assertFileEquals.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class FileEqualsTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertFileEquals('/home/sb/expected', '/home/sb/actual');
        }
    }

.. parsed-literal::

    $ phpunit FileEqualsTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.25Mb

    There was 1 failure:

    1) FileEqualsTest::testFailure
    Failed asserting that two strings are equal.
    --- Expected
    +++ Actual
    @@ @@
    -'expected
    +'actual
     '

    /home/sb/FileEqualsTest.php:6

    FAILURES!
    Tests: 1, Assertions: 3, Failures: 1.

.. _appendixes.assertions.assertFileExists:

assertFileExists()
##################

``assertFileExists(string $filename[, string $message = ''])``

Reports an error identified by ``$message`` if the file specified by ``$filename`` does not exist.

``assertFileDoesNotExist()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertFileExists()
    :name: appendixes.assertions.assertFileExists.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class FileExistsTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertFileExists('/path/to/file');
        }
    }

.. parsed-literal::

    $ phpunit FileExistsTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) FileExistsTest::testFailure
    Failed asserting that file "/path/to/file" exists.

    /home/sb/FileExistsTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertFileIsReadable:

assertFileIsReadable()
######################

``assertFileIsReadable(string $filename[, string $message = ''])``

Reports an error identified by ``$message`` if the file specified by ``$filename`` is not a file or is not readable.

``assertFileIsNotReadable()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertFileIsReadable()
    :name: appendixes.assertions.assertFileIsReadable.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class FileIsReadableTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertFileIsReadable('/path/to/file');
        }
    }

.. parsed-literal::

    $ phpunit FileIsReadableTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) FileIsReadableTest::testFailure
    Failed asserting that "/path/to/file" is readable.

    /home/sb/FileIsReadableTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertFileIsWritable:

assertFileIsWritable()
######################

``assertFileIsWritable(string $filename[, string $message = ''])``

Reports an error identified by ``$message`` if the file specified by ``$filename`` is not a file or is not writable.

``assertFileIsNotWritable()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertFileIsWritable()
    :name: appendixes.assertions.assertFileIsWritable.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class FileIsWritableTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertFileIsWritable('/path/to/file');
        }
    }

.. parsed-literal::

    $ phpunit FileIsWritableTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) FileIsWritableTest::testFailure
    Failed asserting that "/path/to/file" is writable.

    /home/sb/FileIsWritableTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertGreaterThan:

assertGreaterThan()
###################

``assertGreaterThan(mixed $expected, mixed $actual[, string $message = ''])``

Reports an error identified by ``$message`` if the value of ``$actual`` is not greater than the value of ``$expected``.

.. code-block:: php
    :caption: Usage of assertGreaterThan()
    :name: appendixes.assertions.assertGreaterThan.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class GreaterThanTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertGreaterThan(2, 1);
        }
    }

.. parsed-literal::

    $ phpunit GreaterThanTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) GreaterThanTest::testFailure
    Failed asserting that 1 is greater than 2.

    /home/sb/GreaterThanTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertGreaterThanOrEqual:

assertGreaterThanOrEqual()
##########################

``assertGreaterThanOrEqual(mixed $expected, mixed $actual[, string $message = ''])``

Reports an error identified by ``$message`` if the value of ``$actual`` is not greater than or equal to the value of ``$expected``.

.. code-block:: php
    :caption: Usage of assertGreaterThanOrEqual()
    :name: appendixes.assertions.assertGreaterThanOrEqual.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class GreatThanOrEqualTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertGreaterThanOrEqual(2, 1);
        }
    }

.. parsed-literal::

    $ phpunit GreaterThanOrEqualTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.25Mb

    There was 1 failure:

    1) GreatThanOrEqualTest::testFailure
    Failed asserting that 1 is equal to 2 or is greater than 2.

    /home/sb/GreaterThanOrEqualTest.php:6

    FAILURES!
    Tests: 1, Assertions: 2, Failures: 1.

.. _appendixes.assertions.assertInfinite:

assertInfinite()
################

``assertInfinite(mixed $variable[, string $message = ''])``

Reports an error identified by ``$message`` if ``$variable`` is not ``INF``.

``assertFinite()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertInfinite()
    :name: appendixes.assertions.assertInfinite.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class InfiniteTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertInfinite(1);
        }
    }

.. parsed-literal::

    $ phpunit InfiniteTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) InfiniteTest::testFailure
    Failed asserting that 1 is infinite.

    /home/sb/InfiniteTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertInstanceOf:

assertInstanceOf()
##################

``assertInstanceOf($expected, $actual[, $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not an instance of ``$expected``.

``assertNotInstanceOf()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertInstanceOf()
    :name: appendixes.assertions.assertInstanceOf.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class InstanceOfTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertInstanceOf(RuntimeException::class, new Exception);
        }
    }

.. parsed-literal::

    $ phpunit InstanceOfTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) InstanceOfTest::testFailure
    Failed asserting that Exception Object (...) is an instance of class "RuntimeException".

    /home/sb/InstanceOfTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertIsArray()
###############

``assertIsArray($actual[, $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``array``.

``assertIsNotArray()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertIsArray()
    :name: appendixes.assertions.assertIsArray.example

    <?php
    use PHPUnit\Framework\TestCase;

    class ArrayTest extends TestCase
    {
        public function testFailure()
        {
            $this->assertIsArray(null);
        }
    }

.. code-block:: bash

    $ phpunit ArrayTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) ArrayTest::testFailure
    Failed asserting that null is of type "array".

    /home/sb/ArrayTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertIsBool()
##############

``assertIsBool($actual[, $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``bool``.

``assertIsNotBool()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertIsBool()
    :name: appendixes.assertions.assertIsBool.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class BoolTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertIsBool(null);
        }
    }

.. code-block:: bash

    $ phpunit BoolTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) BoolTest::testFailure
    Failed asserting that null is of type "bool".

    /home/sb/BoolTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertIsCallable()
##################

``assertIsCallable($actual[, $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``callable``.

``assertIsNotCallable()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertIsCallable()
    :name: appendixes.assertions.assertIsCallable.example

    <?php
    use PHPUnit\Framework\TestCase;

    class CallableTest extends TestCase
    {
        public function testFailure()
        {
            $this->assertIsCallable(null);
        }
    }

.. code-block:: bash

    $ phpunit CallableTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) CallableTest::testFailure
    Failed asserting that null is of type "callable".

    /home/sb/CallableTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertIsFloat()
###############

``assertIsFloat($actual[, $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``float``.

``assertIsNotFloat()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertIsFloat()
    :name: appendixes.assertions.assertIsFloat.example

    <?php
    use PHPUnit\Framework\TestCase;

    class FloatTest extends TestCase
    {
        public function testFailure()
        {
            $this->assertIsFloat(null);
        }
    }

.. code-block:: bash

    $ phpunit FloatTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) FloatTest::testFailure
    Failed asserting that null is of type "float".

    /home/sb/FloatTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertIsInt()
#############

``assertIsInt($actual[, $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``int``.

``assertIsNotInt()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertIsInt()
    :name: appendixes.assertions.assertIsInt.example

    <?php
    use PHPUnit\Framework\TestCase;

    class IntTest extends TestCase
    {
        public function testFailure()
        {
            $this->assertIsInt(null);
        }
    }

.. code-block:: bash

    $ phpunit IntTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) IntTest::testFailure
    Failed asserting that null is of type "int".

    /home/sb/IntTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertIsIterable()
##################

``assertIsIterable($actual[, $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``iterable``.

``assertIsNotIterable()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertIsIterable()
    :name: appendixes.assertions.assertIsIterable.example

    <?php
    use PHPUnit\Framework\TestCase;

    class IterableTest extends TestCase
    {
        public function testFailure()
        {
            $this->assertIsIterable(null);
        }
    }

.. code-block:: bash

    $ phpunit IterableTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) IterableTest::testFailure
    Failed asserting that null is of type "iterable".

    /home/sb/IterableTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertIsNumeric()
#################

``assertIsNumeric($actual[, $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``numeric``.

``assertIsNotNumeric()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertIsNumeric()
    :name: appendixes.assertions.assertIsNumeric.example

    <?php
    use PHPUnit\Framework\TestCase;

    class NumericTest extends TestCase
    {
        public function testFailure()
        {
            $this->assertIsNumeric(null);
        }
    }

.. code-block:: bash

    $ phpunit NumericTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) NumericTest::testFailure
    Failed asserting that null is of type "numeric".

    /home/sb/NumericTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertIsObject()
################

``assertIsObject($actual[, $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``object``.

``assertIsNotObject()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertIsObject()
    :name: appendixes.assertions.assertIsObject.example

    <?php
    use PHPUnit\Framework\TestCase;

    class ObjectTest extends TestCase
    {
        public function testFailure()
        {
            $this->assertIsObject(null);
        }
    }

.. code-block:: bash

    $ phpunit ObjectTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) ObjectTest::testFailure
    Failed asserting that null is of type "object".

    /home/sb/ObjectTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertIsResource()
##################

``assertIsResource($actual[, $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``resource``.

``assertIsNotResource()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertIsResource()
    :name: appendixes.assertions.assertIsResource.example

    <?php
    use PHPUnit\Framework\TestCase;

    class ResourceTest extends TestCase
    {
        public function testFailure()
        {
            $this->assertIsResource(null);
        }
    }

.. code-block:: bash

    $ phpunit ResourceTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) ResourceTest::testFailure
    Failed asserting that null is of type "resource".

    /home/sb/ResourceTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertIsScalar()
################

``assertIsScalar($actual[, $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``scalar``.

``assertIsNotScalar()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertIsScalar()
    :name: appendixes.assertions.assertIsScalar.example

    <?php
    use PHPUnit\Framework\TestCase;

    class ScalarTest extends TestCase
    {
        public function testFailure()
        {
            $this->assertIsScalar(null);
        }
    }

.. code-block:: bash

    $ phpunit ScalarTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) ScalarTest::testFailure
    Failed asserting that null is of type "scalar".

    /home/sb/ScalarTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

assertIsString()
################

``assertIsString($actual[, $message = ''])``

Reports an error identified by ``$message`` if ``$actual`` is not of type ``string``.

``assertIsNotString()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertIsString()
    :name: appendixes.assertions.assertIsString.example

    <?php
    use PHPUnit\Framework\TestCase;

    class StringTest extends TestCase
    {
        public function testFailure()
        {
            $this->assertIsString(null);
        }
    }

.. parsed-literal::

    $ phpunit StringTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) StringTest::testFailure
    Failed asserting that null is of type "string".

    /home/sb/StringTest.php:8

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertIsReadable:

assertIsReadable()
##################

``assertIsReadable(string $filename[, string $message = ''])``

Reports an error identified by ``$message`` if the file or directory specified by ``$filename`` is not readable.

``assertIsNotReadable()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertIsReadable()
    :name: appendixes.assertions.assertIsReadable.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class IsReadableTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertIsReadable('/path/to/unreadable');
        }
    }

.. parsed-literal::

    $ phpunit IsReadableTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) IsReadableTest::testFailure
    Failed asserting that "/path/to/unreadable" is readable.

    /home/sb/IsReadableTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertIsWritable:

assertIsWritable()
##################

``assertIsWritable(string $filename[, string $message = ''])``

Reports an error identified by ``$message`` if the file or directory specified by ``$filename`` is not writable.

``assertIsNotWritable()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertIsWritable()
    :name: appendixes.assertions.assertIsWritable.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class IsWritableTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertIsWritable('/path/to/unwritable');
        }
    }

.. parsed-literal::

    $ phpunit IsWritableTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) IsWritableTest::testFailure
    Failed asserting that "/path/to/unwritable" is writable.

    /home/sb/IsWritableTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertJsonFileEqualsJsonFile:

assertJsonFileEqualsJsonFile()
##############################

``assertJsonFileEqualsJsonFile(mixed $expectedFile, mixed $actualFile[, string $message = ''])``

Reports an error identified by ``$message`` if the value of ``$actualFile`` does not match the value of
``$expectedFile``.

.. code-block:: php
    :caption: Usage of assertJsonFileEqualsJsonFile()
    :name: appendixes.assertions.assertJsonFileEqualsJsonFile.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class JsonFileEqualsJsonFileTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertJsonFileEqualsJsonFile(
              'path/to/fixture/file', 'path/to/actual/file');
        }
    }

.. parsed-literal::

    $ phpunit JsonFileEqualsJsonFileTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) JsonFileEqualsJsonFile::testFailure
    Failed asserting that '{"Mascot":"Tux"}' matches JSON string "["Mascott", "Tux", "OS", "Linux"]".

    /home/sb/JsonFileEqualsJsonFileTest.php:5

    FAILURES!
    Tests: 1, Assertions: 3, Failures: 1.

.. _appendixes.assertions.assertJsonStringEqualsJsonFile:

assertJsonStringEqualsJsonFile()
################################

``assertJsonStringEqualsJsonFile(mixed $expectedFile, mixed $actualJson[, string $message = ''])``

Reports an error identified by ``$message`` if the value of ``$actualJson`` does not match the value of
``$expectedFile``.

.. code-block:: php
    :caption: Usage of assertJsonStringEqualsJsonFile()
    :name: appendixes.assertions.assertJsonStringEqualsJsonFile.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class JsonStringEqualsJsonFileTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertJsonStringEqualsJsonFile(
                'path/to/fixture/file', json_encode(['Mascot' => 'ux'])
            );
        }
    }

.. parsed-literal::

    $ phpunit JsonStringEqualsJsonFileTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) JsonStringEqualsJsonFile::testFailure
    Failed asserting that '{"Mascot":"ux"}' matches JSON string "{"Mascott":"Tux"}".

    /home/sb/JsonStringEqualsJsonFileTest.php:5

    FAILURES!
    Tests: 1, Assertions: 3, Failures: 1.

.. _appendixes.assertions.assertJsonStringEqualsJsonString:

assertJsonStringEqualsJsonString()
##################################

``assertJsonStringEqualsJsonString(mixed $expectedJson, mixed $actualJson[, string $message = ''])``

Reports an error identified by ``$message`` if the value of ``$actualJson`` does not match the value of
``$expectedJson``.

.. code-block:: php
    :caption: Usage of assertJsonStringEqualsJsonString()
    :name: appendixes.assertions.assertJsonStringEqualsJsonString.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class JsonStringEqualsJsonStringTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertJsonStringEqualsJsonString(
                json_encode(['Mascot' => 'Tux']),
                json_encode(['Mascot' => 'ux'])
            );
        }
    }

.. parsed-literal::

    $ phpunit JsonStringEqualsJsonStringTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) JsonStringEqualsJsonStringTest::testFailure
    Failed asserting that two objects are equal.
    --- Expected
    +++ Actual
    @@ @@
     stdClass Object (
     -    'Mascot' => 'Tux'
     +    'Mascot' => 'ux'
    )

    /home/sb/JsonStringEqualsJsonStringTest.php:5

    FAILURES!
    Tests: 1, Assertions: 3, Failures: 1.

.. _appendixes.assertions.assertLessThan:

assertLessThan()
################

``assertLessThan(mixed $expected, mixed $actual[, string $message = ''])``

Reports an error identified by ``$message`` if the value of ``$actual`` is not less than the value of ``$expected``.

.. code-block:: php
    :caption: Usage of assertLessThan()
    :name: appendixes.assertions.assertLessThan.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class LessThanTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertLessThan(1, 2);
        }
    }

.. parsed-literal::

    $ phpunit LessThanTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) LessThanTest::testFailure
    Failed asserting that 2 is less than 1.

    /home/sb/LessThanTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertLessThanOrEqual:

assertLessThanOrEqual()
#######################

``assertLessThanOrEqual(mixed $expected, mixed $actual[, string $message = ''])``

Reports an error identified by ``$message`` if the value of ``$actual`` is not less than or equal to the value of ``$expected``.

.. code-block:: php
    :caption: Usage of assertLessThanOrEqual()
    :name: appendixes.assertions.assertLessThanOrEqual.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class LessThanOrEqualTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertLessThanOrEqual(1, 2);
        }
    }

.. parsed-literal::

    $ phpunit LessThanOrEqualTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.25Mb

    There was 1 failure:

    1) LessThanOrEqualTest::testFailure
    Failed asserting that 2 is equal to 1 or is less than 1.

    /home/sb/LessThanOrEqualTest.php:6

    FAILURES!
    Tests: 1, Assertions: 2, Failures: 1.

.. _appendixes.assertions.assertNan:

assertNan()
###########

``assertNan(mixed $variable[, string $message = ''])``

Reports an error identified by ``$message`` if ``$variable`` is not ``NAN``.

.. code-block:: php
    :caption: Usage of assertNan()
    :name: appendixes.assertions.assertNan.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class NanTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertNan(1);
        }
    }

.. parsed-literal::

    $ phpunit NanTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) NanTest::testFailure
    Failed asserting that 1 is nan.

    /home/sb/NanTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertNull:

assertNull()
############

``assertNull(mixed $variable[, string $message = ''])``

Reports an error identified by ``$message`` if ``$variable`` is not ``null``.

``assertNotNull()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertNull()
    :name: appendixes.assertions.assertNull.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class NullTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertNull('foo');
        }
    }

.. parsed-literal::

    $ phpunit NotNullTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) NullTest::testFailure
    Failed asserting that 'foo' is null.

    /home/sb/NotNullTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertObjectHasAttribute:

assertObjectHasAttribute()
##########################

``assertObjectHasAttribute(string $attributeName, object $object[, string $message = ''])``

Reports an error identified by ``$message`` if ``$object->attributeName`` does not exist.

``assertObjectNotHasAttribute()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertObjectHasAttribute()
    :name: appendixes.assertions.assertObjectHasAttribute.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class ObjectHasAttributeTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertObjectHasAttribute('foo', new stdClass);
        }
    }

.. parsed-literal::

    $ phpunit ObjectHasAttributeTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) ObjectHasAttributeTest::testFailure
    Failed asserting that object of class "stdClass" has attribute "foo".

    /home/sb/ObjectHasAttributeTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertMatchesRegularExpression:

assertMatchesRegularExpression()
################################

``assertMatchesRegularExpression(string $pattern, string $string[, string $message = ''])``

Reports an error identified by ``$message`` if ``$string`` does not match the regular expression ``$pattern``.

``assertDoesNotMatchRegularExpression()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertMatchesRegularExpression()
    :name: appendixes.assertions.assertMatchesRegularExpression.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class RegExpTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertMatchesRegularExpression('/foo/', 'bar');
        }
    }

.. parsed-literal::

    $ phpunit RegExpTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) RegExpTest::testFailure
    Failed asserting that 'bar' matches PCRE pattern "/foo/".

    /home/sb/RegExpTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertStringMatchesFormat:

assertStringMatchesFormat()
###########################

``assertStringMatchesFormat(string $format, string $string[, string $message = ''])``

Reports an error identified by ``$message`` if the ``$string`` does not match the ``$format`` string.

``assertStringNotMatchesFormat()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertStringMatchesFormat()
    :name: appendixes.assertions.assertStringMatchesFormat.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class StringMatchesFormatTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertStringMatchesFormat('%i', 'foo');
        }
    }

.. parsed-literal::

    $ phpunit StringMatchesFormatTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) StringMatchesFormatTest::testFailure
    Failed asserting that 'foo' matches PCRE pattern "/^[+-]?\d+$/s".

    /home/sb/StringMatchesFormatTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

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

assertStringMatchesFormatFile()
###############################

``assertStringMatchesFormatFile(string $formatFile, string $string[, string $message = ''])``

Reports an error identified by ``$message`` if the ``$string`` does not match the contents of the ``$formatFile``.

``assertStringNotMatchesFormatFile()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertStringMatchesFormatFile()
    :name: appendixes.assertions.assertStringMatchesFormatFile.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class StringMatchesFormatFileTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertStringMatchesFormatFile('/path/to/expected.txt', 'foo');
        }
    }

.. parsed-literal::

    $ phpunit StringMatchesFormatFileTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) StringMatchesFormatFileTest::testFailure
    Failed asserting that 'foo' matches PCRE pattern "/^[+-]?\d+
    $/s".

    /home/sb/StringMatchesFormatFileTest.php:6

    FAILURES!
    Tests: 1, Assertions: 2, Failures: 1.

.. _appendixes.assertions.assertSame:

assertSame()
############

``assertSame(mixed $expected, mixed $actual[, string $message = ''])``

Reports an error identified by ``$message`` if the two variables ``$expected`` and ``$actual`` do not have the same type and value.

``assertNotSame()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertSame()
    :name: appendixes.assertions.assertSame.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class SameTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertSame('2204', 2204);
        }
    }

.. parsed-literal::

    $ phpunit SameTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) SameTest::testFailure
    Failed asserting that 2204 is identical to '2204'.

    /home/sb/SameTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

``assertSame(object $expected, object $actual[, string $message = ''])``

Reports an error identified by ``$message`` if the two variables ``$expected`` and ``$actual`` do not reference the same object.

.. code-block:: php
    :caption: Usage of assertSame() with objects
    :name: appendixes.assertions.assertSame.example2

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class SameTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertSame(new stdClass, new stdClass);
        }
    }

.. parsed-literal::

    $ phpunit SameTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 4.75Mb

    There was 1 failure:

    1) SameTest::testFailure
    Failed asserting that two variables reference the same object.

    /home/sb/SameTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertStringEndsWith:

assertStringEndsWith()
######################

``assertStringEndsWith(string $suffix, string $string[, string $message = ''])``

Reports an error identified by ``$message`` if the ``$string`` does not end with ``$suffix``.

``assertStringEndsNotWith()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertStringEndsWith()
    :name: appendixes.assertions.assertStringEndsWith.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class StringEndsWithTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertStringEndsWith('suffix', 'foo');
        }
    }

.. parsed-literal::

    $ phpunit StringEndsWithTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 1 second, Memory: 5.00Mb

    There was 1 failure:

    1) StringEndsWithTest::testFailure
    Failed asserting that 'foo' ends with "suffix".

    /home/sb/StringEndsWithTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertStringEqualsFile:

assertStringEqualsFile()
########################

``assertStringEqualsFile(string $expectedFile, string $actualString[, string $message = ''])``

Reports an error identified by ``$message`` if the file specified by ``$expectedFile`` does not have ``$actualString`` as its contents.

``assertStringNotEqualsFile()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertStringEqualsFile()
    :name: appendixes.assertions.assertStringEqualsFile.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class StringEqualsFileTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertStringEqualsFile('/home/sb/expected', 'actual');
        }
    }

.. parsed-literal::

    $ phpunit StringEqualsFileTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.25Mb

    There was 1 failure:

    1) StringEqualsFileTest::testFailure
    Failed asserting that two strings are equal.
    --- Expected
    +++ Actual
    @@ @@
    -'expected
    -'
    +'actual'

    /home/sb/StringEqualsFileTest.php:6

    FAILURES!
    Tests: 1, Assertions: 2, Failures: 1.

.. _appendixes.assertions.assertStringStartsWith:

assertStringStartsWith()
########################

``assertStringStartsWith(string $prefix, string $string[, string $message = ''])``

Reports an error identified by ``$message`` if the ``$string`` does not start with ``$prefix``.

``assertStringStartsNotWith()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertStringStartsWith()
    :name: appendixes.assertions.assertStringStartsWith.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class StringStartsWithTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertStringStartsWith('prefix', 'foo');
        }
    }

.. parsed-literal::

    $ phpunit StringStartsWithTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) StringStartsWithTest::testFailure
    Failed asserting that 'foo' starts with "prefix".

    /home/sb/StringStartsWithTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertThat:

assertThat()
############

More complex assertions can be formulated using the
``PHPUnit\Framework\Constraint`` classes. They can be
evaluated using the ``assertThat()`` method.
:numref:`appendixes.assertions.assertThat.example` shows how the
``logicalNot()`` and ``equalTo()``
constraints can be used to express the same assertion as
``assertNotEquals()``.

``assertThat(mixed $value, PHPUnit\Framework\Constraint $constraint[, $message = ''])``

Reports an error identified by ``$message`` if the ``$value`` does not match the ``$constraint``.

.. code-block:: php
    :caption: Usage of assertThat()
    :name: appendixes.assertions.assertThat.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class BiscuitTest extends TestCase
    {
        public function testEquals(): void
        {
            $theBiscuit = new Biscuit('Ginger');
            $myBiscuit  = new Biscuit('Ginger');

            $this->assertThat(
              $theBiscuit,
              $this->logicalNot(
                $this->equalTo($myBiscuit)
              )
            );
        }
    }

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
    * - ``PHPUnit\Framework\Constraint\ClassHasAttribute classHasAttribute(string $attributeName)``
      - Constraint that asserts that the class has a given attribute.
    * - ``PHPUnit\Framework\Constraint\ClassHasStaticAttribute classHasStaticAttribute(string $attributeName)``
      - Constraint that asserts that the class has a given static attribute.
    * - ``PHPUnit\Framework\Constraint\ObjectHasAttribute objectHasAttribute(string $attributeName)``
      - Constraint that asserts that the object has a given attribute.
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

.. _appendixes.assertions.assertTrue:

assertTrue()
############

``assertTrue(bool $condition[, string $message = ''])``

Reports an error identified by ``$message`` if ``$condition`` is ``false``.

``assertNotTrue()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertTrue()
    :name: appendixes.assertions.assertTrue.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class TrueTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertTrue(false);
        }
    }

.. parsed-literal::

    $ phpunit TrueTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) TrueTest::testFailure
    Failed asserting that false is true.

    /home/sb/TrueTest.php:6

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.

.. _appendixes.assertions.assertXmlFileEqualsXmlFile:

assertXmlFileEqualsXmlFile()
############################

``assertXmlFileEqualsXmlFile(string $expectedFile, string $actualFile[, string $message = ''])``

Reports an error identified by ``$message`` if the XML document in ``$actualFile`` is not equal to the XML document in ``$expectedFile``.

``assertXmlFileNotEqualsXmlFile()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertXmlFileEqualsXmlFile()
    :name: appendixes.assertions.assertXmlFileEqualsXmlFile.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class XmlFileEqualsXmlFileTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertXmlFileEqualsXmlFile(
              '/home/sb/expected.xml', '/home/sb/actual.xml');
        }
    }

.. parsed-literal::

    $ phpunit XmlFileEqualsXmlFileTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.25Mb

    There was 1 failure:

    1) XmlFileEqualsXmlFileTest::testFailure
    Failed asserting that two DOM documents are equal.
    --- Expected
    +++ Actual
    @@ @@
     <?xml version="1.0"?>
     <foo>
    -  <bar/>
    +  <baz/>
     </foo>

    /home/sb/XmlFileEqualsXmlFileTest.php:7

    FAILURES!
    Tests: 1, Assertions: 3, Failures: 1.

.. _appendixes.assertions.assertXmlStringEqualsXmlFile:

assertXmlStringEqualsXmlFile()
##############################

``assertXmlStringEqualsXmlFile(string $expectedFile, string $actualXml[, string $message = ''])``

Reports an error identified by ``$message`` if the XML document in ``$actualXml`` is not equal to the XML document in ``$expectedFile``.

``assertXmlStringNotEqualsXmlFile()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertXmlStringEqualsXmlFile()
    :name: appendixes.assertions.assertXmlStringEqualsXmlFile.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class XmlStringEqualsXmlFileTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertXmlStringEqualsXmlFile(
              '/home/sb/expected.xml', '<foo><baz/></foo>');
        }
    }

.. parsed-literal::

    $ phpunit XmlStringEqualsXmlFileTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.25Mb

    There was 1 failure:

    1) XmlStringEqualsXmlFileTest::testFailure
    Failed asserting that two DOM documents are equal.
    --- Expected
    +++ Actual
    @@ @@
     <?xml version="1.0"?>
     <foo>
    -  <bar/>
    +  <baz/>
     </foo>

    /home/sb/XmlStringEqualsXmlFileTest.php:7

    FAILURES!
    Tests: 1, Assertions: 2, Failures: 1.

.. _appendixes.assertions.assertXmlStringEqualsXmlString:

assertXmlStringEqualsXmlString()
################################

``assertXmlStringEqualsXmlString(string $expectedXml, string $actualXml[, string $message = ''])``

Reports an error identified by ``$message`` if the XML document in ``$actualXml`` is not equal to the XML document in ``$expectedXml``.

``assertXmlStringNotEqualsXmlString()`` is the inverse of this assertion and takes the same arguments.

.. code-block:: php
    :caption: Usage of assertXmlStringEqualsXmlString()
    :name: appendixes.assertions.assertXmlStringEqualsXmlString.example

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class XmlStringEqualsXmlStringTest extends TestCase
    {
        public function testFailure(): void
        {
            $this->assertXmlStringEqualsXmlString(
              '<foo><bar/></foo>', '<foo><baz/></foo>');
        }
    }

.. parsed-literal::

    $ phpunit XmlStringEqualsXmlStringTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    F

    Time: 0 seconds, Memory: 5.00Mb

    There was 1 failure:

    1) XmlStringEqualsXmlStringTest::testFailure
    Failed asserting that two DOM documents are equal.
    --- Expected
    +++ Actual
    @@ @@
     <?xml version="1.0"?>
     <foo>
    -  <bar/>
    +  <baz/>
     </foo>

    /home/sb/XmlStringEqualsXmlStringTest.php:7

    FAILURES!
    Tests: 1, Assertions: 1, Failures: 1.


