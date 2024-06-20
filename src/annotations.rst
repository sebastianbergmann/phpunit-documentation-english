

.. _appendixes.annotations:

***********
Annotations
***********

An annotation is a special form of syntactic metadata that can be added to
the source code of some programming languages. Until PHP 8, PHP had no dedicated
language feature for attaching metadata to units of code. The usage of tags such as
``@annotation arguments`` in a documentation block has been established in the PHP
community to annotate source code since the time of PHP 4. Documentation blocks can
be accessed through the Reflection API's ``getDocComment()`` method on the function,
class, method, and attribute level. Applications such as PHPUnit can use this
information at runtime to access annotations in documentation blocks.

.. admonition:: Note

   A doc comment in PHP must start with ``/**`` and end with
   ``*/``. Annotations in any other style of comment will be
   ignored.

Prior to PHPUnit 10, annotations in special PHP comments, so-called "DocBlocks" or "doc-comments",
were the only means of attaching metadata to code units. These annotations are documented in this
appendix.

PHPUnit will first look for metadata in :ref:`attributes <appendixes.attributes>` before it looks
for annotations in comments. When metadata is found in attributes, metadata in comments is ignored.
Support for metadata in comments is closed for further development: bugs will be fixed, but no new
functionality will be implemented based on annotations.

.. admonition:: Note

   Do not use annotations in comments in new test code that you write.
   Use :ref:`attributes <appendixes.attributes>` instead.


.. _appendixes.annotations.author:

@author
=======

The ``@author`` annotation is an alias for the
``@group`` annotation (see :ref:`appendixes.annotations.group`) and allows to filter tests based
on their authors.

.. _appendixes.annotations.after:

@after
======

The ``@after`` annotation can be used to specify methods
that should be called after each test method in a test case class.

.. code-block:: php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class MyTest extends TestCase
    {
        /**
         * @after
         */
        public function tearDownSomeFixtures(): void
        {
            // ...
        }

        /**
         * @after
         */
        public function tearDownSomeOtherFixtures(): void
        {
            // ...
        }
    }

.. _appendixes.annotations.afterClass:

@afterClass
===========

The ``@afterClass`` annotation can be used to specify
static methods that should be called after all test methods in a test
class have been run to clean up shared fixtures.

.. code-block:: php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class MyTest extends TestCase
    {
        /**
         * @afterClass
         */
        public static function tearDownSomeSharedFixtures(): void
        {
            // ...
        }

        /**
         * @afterClass
         */
        public static function tearDownSomeOtherSharedFixtures(): void
        {
            // ...
        }
    }

.. _appendixes.annotations.backupGlobals:

@backupGlobals
==============

PHPUnit can optionally backup all global and super-global variables before each test and restore this backup after each test.

The ``@backupGlobals enabled`` annotation can be used on the class level to enable this operation for all tests of a test case class:

.. code-block:: php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    /**
     * @backupGlobals enabled
     */
    final class MyTest extends TestCase
    {
        // ...
    }

The ``@backupGlobals`` annotation can also be used on the
test method level. This allows for a fine-grained configuration of the
backup and restore operations:

.. code-block:: php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    /**
     * @backupGlobals enabled
     */
    final class MyTest extends TestCase
    {
        public function testThatInteractsWithGlobalVariables()
        {
            // ...
        }

        /**
         * @backupGlobals disabled
         */
        public function testThatDoesNotInteractWithGlobalVariables(): void
        {
            // ...
        }
    }

.. _appendixes.annotations.backupStaticAttributes:

@backupStaticAttributes
=======================

PHPUnit can optionally backup all static attributes in all declared classes before each test and restore this backup after each test.

The ``@backupStaticAttributes enabled`` annotation can be used on the class level to enable this operation for all tests of a test case class:

.. code-block:: php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    /**
     * @backupStaticAttributes enabled
     */
    final class MyTest extends TestCase
    {
        // ...
    }

The ``@backupStaticAttributes`` annotation can also be used on the
test method level. This allows for a fine-grained configuration of the
backup and restore operations:

.. code-block:: php

    use PHPUnit\Framework\TestCase;

    /**
     * @backupStaticAttributes enabled
     */
    class MyTest extends TestCase
    {
        public function testThatInteractsWithStaticAttributes(): void
        {
            // ...
        }

        /**
         * @backupStaticAttributes disabled
         */
        public function testThatDoesNotInteractWithStaticAttributes(): void
        {
            // ...
        }
    }

.. admonition:: Note

   ``@backupStaticAttributes`` is limited by PHP internals
   and may cause unintended static values to persist and leak into
   subsequent tests in some circumstances.

   See :ref:`fixtures.global-state` for details.

.. _appendixes.annotations.before:

@before
=======

The ``@before`` annotation can be used to specify methods
that should be called before each test method in a test case class.

.. code-block:: php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class MyTest extends TestCase
    {
        /**
         * @before
         */
        public function setupSomeFixtures(): void
        {
            // ...
        }

        /**
         * @before
         */
        public function setupSomeOtherFixtures(): void
        {
            // ...
        }
    }

.. _appendixes.annotations.beforeClass:

@beforeClass
============

The ``@beforeClass`` annotation can be used to specify
static methods that should be called before any test methods in a test
class are run to set up shared fixtures.

.. code-block:: php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class MyTest extends TestCase
    {
        /**
         * @beforeClass
         */
        public static function setUpSomeSharedFixtures(): void
        {
            // ...
        }

        /**
         * @beforeClass
         */
        public static function setUpSomeOtherSharedFixtures(): void
        {
            // ...
        }
    }

.. _appendixes.annotations.codeCoverageIgnore:

@codeCoverageIgnore*
====================

The ``@codeCoverageIgnore``,
``@codeCoverageIgnoreStart`` and
``@codeCoverageIgnoreEnd`` annotations can be used
to exclude lines of code from the coverage analysis.

For usage see :ref:`code-coverage.ignoring-code-blocks`.

.. admonition:: Note

   Please note that while annotations for test code have been deprecated and support
   for them will be removed in PHPUnit 12, the ``@codeCoverageIgnore``,
   ``@codeCoverageIgnoreStart``, and ``@codeCoverageIgnoreEnd`` annotations have not
   been deprecated and support for them will not be removed.

.. _appendixes.annotations.covers:

@covers
=======

The ``@covers`` annotation can be used in the test code to
specify which parts of the code it is supposed to test:

.. code-block:: php

    /**
     * @covers \BankAccount
     */
    public function testBalanceIsInitiallyZero(): void
    {
        $this->assertSame(0, $this->ba->getBalance());
    }

If provided, this effectively filters the code coverage report
to include executed code from the referenced code parts only.
This will make sure that code is only marked as covered if there
are dedicated tests for it, but not if it used indirectly by the
tests for a different class, thus avoiding false positives for code
coverage.

This annotation can be added to the docblock of the test class or the individual
test methods. The recommended way is to add the annotation to the docblock
of the test class, not to the docblock of the test methods.

When the ``requireCoverageMetadata`` configuration option in the
:ref:`configuration file <appendixes.configuration>` is set to ``true``,
every test method needs to have an associated ``@covers`` annotation
(either on the test class or the individual test method).

:numref:`appendixes.annotations.covers` shows
the syntax of the ``@covers`` annotation.
The section :ref:`code-coverage.targeting-units-of-code`
provides longer examples for using the annotation.

Please note that this annotation requires a fully-qualified class name (FQCN).
To make this more obvious to the reader, it is recommended to use a leading
backslash (even if this not required for the annotation to work correctly).

``@covers ClassName`` (recommended)

    Specifies that the annotated test class covers all methods of a given class.

``@covers ClassName::methodName`` (not recommended)

    Specifies that the annotated test class covers the specified method.

``@covers ::functionName`` (recommended)

    Specifies that the annotated test class covers the specified global function.

.. _appendixes.annotations.coversDefaultClass:

@coversDefaultClass
===================

The ``@coversDefaultClass`` annotation can be used to
specify a default namespace or class name. That way long names don't need to be
repeated for every ``@covers`` annotation. See
:numref:`appendixes.annotations.examples.CoversDefaultClassTest.php`.

Please note that this annotation requires a fully-qualified class name (FQCN).
To make this more obvious to the reader, it is recommended to use a leading
backslash (even if this not required for the annotation to work correctly).

.. code-block:: php
    :caption: Using @coversDefaultClass to shorten annotations
    :name: appendixes.annotations.examples.CoversDefaultClassTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    /**
     * @coversDefaultClass \Foo\CoveredClass
     */
    final class CoversDefaultClassTest extends TestCase
    {
        /**
         * @covers ::publicMethod
         */
        public function testSomething(): void
        {
            $o = new Foo\CoveredClass;
            $o->publicMethod();
        }
    }

.. _appendixes.annotations.coversNothing:

@coversNothing
==============

The ``@coversNothing`` annotation can be used in the
test code to specify that no code coverage information will be
recorded for the annotated test case.

This can be used for integration testing. See
:ref:`code-coverage.targeting-units-of-code.examples.GuestbookIntegrationTest.php`
for an example.

The annotation can be used on the class and the method level and
will override any ``@covers`` tags.

.. _appendixes.annotations.dataProvider:

@dataProvider
=============

A test method can accept arbitrary arguments. These arguments are to be
provided by one or more data provider methods (``provider()`` in
:ref:`writing-tests-for-phpunit.data-providers.examples.NumericDataSetsTest.php`).
The data provider method to be used is specified using the
``@dataProvider`` annotation.

See :ref:`writing-tests-for-phpunit.data-providers` for more
details.

.. _appendixes.annotations.depends:

@depends
========

PHPUnit supports the declaration of explicit dependencies between test
methods. Such dependencies do not define the order in which the test
methods are to be executed but they allow the returning of an instance of
the test fixture by a producer and passing it to the dependent consumers.
:ref:`writing-tests-for-phpunit.examples.StackTest.php` shows
how to use the ``@depends`` annotation to express
dependencies between test methods.

See :ref:`writing-tests-for-phpunit.test-dependencies` for more
details.

.. _appendixes.annotations.doesNotPerformAssertions:

@doesNotPerformAssertions
=========================

Prevents a test that performs no assertions from being considered risky.

.. _appendixes.annotations.group:

@group
======

A test can be tagged as belonging to one or more groups using the
``@group`` annotation like this

.. code-block:: php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class MyTest extends TestCase
    {
        /**
         * @group specification
         */
        public function testSomething(): void
        {
        }

        /**
         * @group regression
         * @group bug2204
         */
        public function testSomethingElse(): void
        {
        }
    }

The ``@group`` annotation can also be provided for the test
class. It is then "inherited" to all test methods of that test class.

Tests can be selected for execution based on groups using the
``--group`` and ``--exclude-group`` options
of the command-line test runner or using the respective directives of the
XML configuration file.

.. _appendixes.annotations.large:

@large
======

The ``@large`` annotation is an alias for
``@group large``.

If the ``PHP_Invoker`` package is installed and strict
mode is enabled, a large test will fail if it takes longer than 60
seconds to execute. This timeout is configurable via the
``timeoutForLargeTests`` attribute in the XML
configuration file.

.. _appendixes.annotations.medium:

@medium
=======

The ``@medium`` annotation is an alias for
``@group medium``. A medium test must not depend on a test
marked as ``@large``.

If the ``PHP_Invoker`` package is installed and strict
mode is enabled, a medium test will fail if it takes longer than 10
seconds to execute. This timeout is configurable via the
``timeoutForMediumTests`` attribute in the XML
configuration file.

.. _appendixes.annotations.preserveGlobalState:

@preserveGlobalState
====================

When a test is run in a separate process, PHPUnit will
attempt to preserve the global state from the parent process by
serializing all globals in the parent process and unserializing them
in the child process. This can cause problems if the parent process
contains globals that are not serializable. To fix this, you can prevent
PHPUnit from preserving global state with the
``@preserveGlobalState`` annotation.

.. code-block:: php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class MyTest extends TestCase
    {
        /**
         * @runInSeparateProcess
         * @preserveGlobalState disabled
         */
        public function testInSeparateProcess(): void
        {
            // ...
        }
    }

.. _appendixes.annotations.requires:

@requires
=========

The ``@requires`` annotation can be used to skip tests when common
preconditions, like the PHP Version or installed extensions, are not met.

.. rst-class:: table
.. list-table:: Possible @requires usages
    :name: writing-tests-for-phpunit.skipping-tests.skipping-tests-using-requires.tables.api
    :header-rows: 1

    * - Type
      - Possible Values
      - Examples
      - Another example
    * - ``PHP``
      - Any PHP version identifier along with an optional operator
      - @requires PHP 7.1.20
      - @requires PHP >= 7.2
    * - ``PHPUnit``
      - Any PHPUnit version identifier along with an optional operator
      - @requires PHPUnit 7.3.1
      - @requires PHPUnit < 8
    * - ``OS``
      - A regexp matching `PHP_OS <https://www.php.net/manual/en/reserved.constants.php=constant.php-os>`_
      - @requires OS Linux
      - @requires OS WIN32|WINNT
    * - ``OSFAMILY``
      - Any `OS family <https://www.php.net/manual/en/reserved.constants.php=constant.php-os-family>`_
      - @requires OSFAMILY Solaris
      - @requires OSFAMILY Windows
    * - ``function``
      - Any valid parameter to `function_exists <https://www.php.net/function_exists>`_
      - @requires function imap_open
      - @requires function ReflectionMethod::setAccessible
    * - ``extension``
      - Any extension name along with an optional version identifier and optional operator
      - @requires extension mysqli
      - @requires extension redis >= 2.2.0
    * - ``setting``
      - Name and value for a ``php.ini`` configuration setting
      - @requires setting date.timezone Europe/Berlin
      -

The following operators are supported for PHP, PHPUnit, and extension version constraints: ``<``, ``<=``, ``>``, ``>=``, ``=``, ``==``, ``!=``, ``<>``.

Versions are compared using PHP's `version_compare <https://www.php.net/version_compare>`_ function. Among other things, this means that the ``=`` and ``==`` operator can only be used with complete ``X.Y.Z`` version numbers and that just ``X.Y`` will not work.

.. _appendixes.annotations.runTestsInSeparateProcesses:

@runTestsInSeparateProcesses
============================

Indicates that all tests in a test class should be run in a separate
PHP process.

.. code-block:: php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    /**
     * @runTestsInSeparateProcesses
     */
    final class MyTest extends TestCase
    {
        // ...
    }

*Note:* By default, PHPUnit will
attempt to preserve the global state from the parent process by
serializing all globals in the parent process and unserializing them
in the child process. This can cause problems if the parent process
contains globals that are not serializable. See :ref:`appendixes.annotations.preserveGlobalState` for information
on how to fix this.

.. _appendixes.annotations.runInSeparateProcess:

@runInSeparateProcess
=====================

Indicates that a test should be run in a separate PHP process.

.. code-block:: php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class MyTest extends TestCase
    {
        /**
         * @runInSeparateProcess
         */
        public function testInSeparateProcess(): void
        {
            // ...
        }
    }

.. _appendixes.annotations.small:

@small
======

The ``@small`` annotation is an alias for
``@group small``. A small test must not depend on a test
marked as ``@medium`` or ``@large``.

If the ``PHP_Invoker`` package is installed and strict
mode is enabled, a small test will fail if it takes longer than 1
second to execute. This timeout is configurable via the
``timeoutForSmallTests`` attribute in the XML
configuration file.

.. admonition:: Note

   Tests need to be explicitly annotated by either ``@small``,
   ``@medium``, or ``@large`` to enable run time limits.

.. _appendixes.annotations.test:

@test
=====

As an alternative to prefixing your test method names with
``test``, you can use the ``@test``
annotation in a method's DocBlock to mark it as a test method.

.. code-block:: php

    /**
     * @test
     */
    public function initialBalanceShouldBe0(): void
    {
        $this->assertSame(0, $this->ba->getBalance());
    }

.. _appendixes.annotations.testdox:

@testdox
========

Specifies an alternative description used when generating the agile
documentation sentences.

The ``@testdox`` annotation can be applied to both test classes and test methods.

.. code-block:: php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    /**
     * @testdox A bank account
     */
    final class BankAccountTest extends TestCase
    {
        /**
         * @testdox has an initial balance of zero
         */
        public function balanceIsInitiallyZero(): void
        {
            $this->assertSame(0, $this->ba->getBalance());
        }
    }

.. admonition:: Note

   Prior to PHPUnit 7.0 (due to a bug in the annotation parsing), using
   the ``@testdox`` annotation also activated the behaviour
   of the ``@test`` annotation.

When using the ``@testdox`` annotation at method level with a ``@dataProvider`` you may use the method parameters as placeholders in your alternative description.
``$_dataName`` is available in addition to use the actual name of the current data. That would be ``data set 1`` up to 4 in below example.

.. code-block:: php

    /**
     * @dataProvider additionProvider
     * @testdox Adding $a to $b results in $expected
     */
    public function testAdd($a, $b, $expected)
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

.. _appendixes.annotations.testWith:

@testWith
=========

Instead of implementing a method for use with ``@dataProvider``,
you can define a data set using the ``@testWith`` annotation.

A data set consists of one or many elements. To define a data set
with multiple elements, define each element in a separate line.
Each element of the data set must be an array defined in JSON.

See :ref:`writing-tests-for-phpunit.data-providers` to learn
more about passing a set of data to a test.

.. code-block:: php

    /**
     * @testWith ["test", 4]
     *           ["longer-string", 13]
     */
    public function testStringLength(string $input, int $expectedLength): void
    {
        $this->assertSame($expectedLength, strlen($input));
    }

An object representation in JSON will be converted into an associative array.

.. code-block:: php

    /**
     * @testWith [{"day": "monday", "conditions": "sunny"}, ["day", "conditions"]]
     */
    public function testArrayKeys(array $array, array $keys): void
    {
        $this->assertSame($keys, array_keys($array));
    }

.. _appendixes.annotations.ticket:

@ticket
=======

The ``@ticket`` annotation is an alias for the
``@group`` annotation (see :ref:`appendixes.annotations.group`) and allows to filter tests based
on their ticket ID.

.. _appendixes.annotations.uses:

@uses
=====

The ``@uses`` annotation specifies code which will be
executed by a test, but is not intended to be covered by the test. A good
example is a value object which is necessary for testing a unit of code.

.. code-block:: php

    /**
     * @covers \BankAccount
     * @uses   \Money
     */
    public function testMoneyCanBeDepositedInAccount(): void
    {
        // ...
    }

:numref:`code-coverage.targeting-units-of-code.examples.InvoiceTest.php`
shows another example.

In addition to being helpful for persons reading the code,
this annotation is useful in strict coverage mode
where unintentionally covered code will cause a test to fail.
See :ref:`risky-tests.unintentionally-covered-code` for more
information regarding strict coverage mode.

Please note that this annotation requires a fully-qualified class name (FQCN).
To make this more obvious to the reader, it is recommended to use a leading
backslash (even if this is not required for the annotation to work correctly).
