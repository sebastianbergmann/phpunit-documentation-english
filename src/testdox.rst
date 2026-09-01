


.. _testdox:

*******
TestDox
*******

TestDox is a feature that allows tests to serve not only as executable verification but also as
executable specification and executable documentation.

During test-driven development (TDD), tests are written before the production code. In this
context, the tests serve as an executable specification: they describe the expected behaviour
of the code that is yet to be written. Once the production code has been written, the tests
continue to serve as executable documentation: they describe the actual behaviour of the code.

TestDox renders test method names as human-readable sentences. When used together with
the ``--testdox`` CLI option, it provides a behaviour-driven view of your test suite
that reads like documentation.


.. _testdox.default-behaviour:

Default Behaviour
=================

By default, PHPUnit uses the naming conventions of test classes and test methods to generate
human-readable documentation.

.. _testdox.default-behaviour.class-names:

Class Names
-----------

A test class name is prettified by removing the ``Test`` suffix, then splitting the camelCase name into words. For example, ``GreeterTest`` becomes ``Greeter``.

When the test class is in a namespace, the unqualified class name is used as the title with
the fully qualified class name (without the ``Test`` suffix) in parentheses. For example,
``App\Tests\GreeterTest`` becomes ``Greeter (App\Tests\Greeter)``.

.. _testdox.default-behaviour.method-names:

Method Names
------------

A test method name is prettified by removing the ``test`` prefix (or ``test_`` for snake_case
method names), then converting the remainder to a sentence:

- **camelCase**: ``testGreetsWithName`` becomes ``Greets with name``
- **snake_case**: ``test_greets_with_name`` becomes ``Greets with name``
- **Numbers**: ``testValueIsGreaterThan0`` becomes ``Value is greater than 0``

Consider the following test class:

.. literalinclude:: examples/testdox/src/Greeter.php
   :caption: A class named ``Greeter`` (declared in ``src/Greeter.php``)
   :name: testdox.examples.Greeter.php
   :language: php

.. literalinclude:: examples/testdox/GreeterTest.php
   :caption: A test class named ``GreeterTest`` (declared in ``tests/GreeterTest.php``)
   :name: testdox.examples.GreeterTest.php
   :language: php

Running this test with the ``--testdox`` option yields the output shown below:

.. parsed-literal::

    $ ./tools/phpunit --no-progress --testdox tests/GreeterTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.5.3

    Time: 00:00.077, Memory: 10.00 MB

    Greeter
     ✔ Greets with name
     ✔ Greets with good morning before noon
     ✔ Greets with good afternoon after noon
     ✔ Greets with good evening after 6 pm

    OK (4 tests, 4 assertions)

.. _testdox.default-behaviour.data-providers:

Data Providers
--------------

When a test method uses a :ref:`data provider <writing-tests-for-phpunit.data-providers>`,
TestDox appends the data set information to the prettified method name:

- For indexed data sets: ``with data set #0``
- For named data sets: ``with "name"``


.. _testdox.ordering:

Ordering
========

The TestDox report is not a log of the test run. The output of ``--debug`` as well as the
Open Test Reporting (XML) logfile are event logs: they report what happened, in the order
it happened. TestDox is different: it is meant to read like documentation of the code
under test. It is therefore sorted for readability and stable, no matter in which order
the tests were actually run.

Three sorting rules are applied:

1. Test classes are sorted against each other by their prettified class name, using
   case-insensitive natural ordering.

2. Within a test class, the test methods are grouped by the class that declares them.
   Those groups are ordered parent class first, subclass last.

3. Within each of these groups, the test methods are sorted by the line number at which
   they are declared in the source code.

The order in which the tests were executed plays no role. Neither does
:ref:`Depends <writing-tests-for-phpunit.test-dependencies>`, nor
``--order-by``, nor ``--random-order-seed``.

Consider the following test class:

.. code-block:: php
    :caption: A test class with a test dependency
    :name: testdox.examples.SimpleTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\Depends;
    use PHPUnit\Framework\TestCase;

    final class SimpleTest extends TestCase
    {
        #[Depends('testCharlie')]
        public function testAlpha(): void
        {
            $this->assertTrue(true);
        }

        public function testBravo(): void
        {
            $this->assertTrue(true);
        }

        public function testCharlie(): void
        {
            $this->assertTrue(true);
        }
    }

The dependency forces ``testAlpha`` to be run last, so the execution order is
``testBravo``, ``testCharlie``, ``testAlpha``. The TestDox output, however, is in
source code order:

.. parsed-literal::

    Simple
     ✔ Alpha
     ✔ Bravo
     ✔ Charlie


.. _testdox.ordering.inheritance-and-traits:

Inheritance and Traits
----------------------

Because of the second sorting rule, test methods that are inherited from an abstract
test case class are shown before the test methods that are declared in the concrete
test class, regardless of when they were run.

Traits are handled differently: for a test method that is imported from a trait,
``ReflectionMethod::getDeclaringClass()`` returns the class that uses the trait, not the
trait itself. All test methods that are imported from traits are therefore put into the
same group as the test methods that are declared in the class itself. The third sorting
rule then sorts them by line number, using line numbers that originate from different
files:

.. code-block:: php
    :caption: A test class that imports test methods from traits
    :name: testdox.examples.MixTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    // TraitA.php: testFromTraitA is declared on line 13
    // TraitB.php: testFromTraitB is declared on line 5

    final class MixTest extends TestCase
    {
        use TraitA;
        use TraitB;

        // this method is declared on line 12
        public function testDeclaredHere(): void
        {
            $this->assertTrue(true);
        }
    }

The TestDox output for the test class shown above is:

.. parsed-literal::

    Mix
     ✔ From trait b
     ✔ Declared here
     ✔ From trait a

The ordering shown above is not random, but it is driven by line numbers that come from
unrelated files. Test methods that are imported from traits are not taken into account
for TestDox sorting.

If you want the TestDox report to read in a particular order then arrange the source code
in that order, as this is the only lever TestDox responds to. Prefer inheritance over
traits when the grouping matters to you: the parent-before-child rule is predictable
whereas cross-file line numbers are not.


.. _testdox.customizing-test-documentation:

Customizing Test Documentation
==============================

The default prettification is often sufficient, but there are cases where you want more
control over the documentation text. PHPUnit provides three attributes for this purpose.


.. _testdox.testdox-attribute:

The ``TestDox`` Attribute
-------------------------

The ``TestDox(string $text)`` attribute can be used at the class level and/or the method
level to specify custom documentation text.

At the class level, it replaces the prettified class name:

.. code-block:: php
    :caption: Using the ``TestDox`` attribute at the class level
    :name: testdox.examples.ClassLevelTestDox.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\TestDox;
    use PHPUnit\Framework\TestCase;

    #[TestDox('Greeting Service')]
    final class GreeterTest extends TestCase
    {
        #[TestDox('Greets a person by their name')]
        public function testGreetsWithName(): void
        {
            // ...
        }
    }

Running this test with TestDox output enabled yields:

.. parsed-literal::

    Greeting Service
     ✔ Greets a person by their name

See :ref:`appendixes.attributes.TestDox` for the attribute reference.


.. _testdox.testdox-attribute.data-provider-placeholders:

Using Placeholders with Data Providers
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

When the ``TestDox`` attribute is used on a test method that uses a
:ref:`data provider <writing-tests-for-phpunit.data-providers>`, you can embed
placeholders that reference the method's parameters. Each placeholder is the parameter
name prefixed with ``$``.

.. code-block:: php
    :caption: Using placeholders in the ``TestDox`` attribute
    :name: testdox.examples.PlaceholderTestDox.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\DataProvider;
    use PHPUnit\Framework\Attributes\TestDox;
    use PHPUnit\Framework\TestCase;

    final class CalculatorTest extends TestCase
    {
        #[DataProvider('additionProvider')]
        #[TestDox('Adding $a to $b results in $expected')]
        public function testAdd(int $expected, int $a, int $b): void
        {
            $this->assertSame($expected, $a + $b);
        }

        public static function additionProvider(): array
        {
            return [
                'zero plus zero' => [0, 0, 0],
                'zero plus one'  => [1, 0, 1],
                'one plus zero'  => [1, 1, 0],
                'one plus one'   => [2, 1, 1],
            ];
        }
    }

Running this test with TestDox output enabled yields:

.. parsed-literal::

    Calculator
     ✔ Adding 0 to 0 results in 0
     ✔ Adding 0 to 1 results in 1
     ✔ Adding 1 to 0 results in 1
     ✔ Adding 1 to 1 results in 2

When placeholders are used, data provider information is not appended to the text
because the parameter values are already embedded in the documentation text.

The special placeholder ``$_dataName`` is available and resolves to the name of
the current data set (for example, ``zero plus zero`` or ``one plus one`` in the example
shown above).


.. _testdox.testdox-formatter:

The ``TestDoxFormatter`` Attribute
----------------------------------

The ``TestDoxFormatter(string $methodName)`` attribute can be used on a test method to
specify a static method in the same class that formats the documentation text. This is
useful when the documentation text requires logic that cannot be expressed with simple
placeholders, for example when objects need to be formatted in a specific way.

The formatter method must be ``public`` and ``static``. It receives the same parameters
as the test method (from the data provider) and must return a ``string``.

.. code-block:: php
    :caption: Using the ``TestDoxFormatter`` attribute
    :name: testdox.examples.TestDoxFormatter.php

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
                ],
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

Running this test with TestDox output enabled yields:

.. parsed-literal::

    Example
     ✔ 2025-08-01 is expected to be 2025-08-01

See :ref:`appendixes.attributes.TestDoxFormatter` for the attribute reference.


.. _testdox.testdox-formatter-external:

The ``TestDoxFormatterExternal`` Attribute
------------------------------------------

The ``TestDoxFormatterExternal(string $className, string $methodName)`` attribute works
like ``TestDoxFormatter``, but references a ``public static`` method declared in another class.
This is useful when multiple test classes share the same formatting logic.

See :ref:`appendixes.attributes.TestDoxFormatterExternal` for the attribute reference.


.. _testdox.output:

TestDox Output
==============


.. _testdox.output.console:

Console Output
--------------

The ``--testdox`` CLI option replaces the default result output with TestDox format.
The following symbols are used to indicate the status of each test:

- ✔ Test passed
- ✘ Test failed or errored
- ↩ Test was skipped
- ⚠ Test triggered a warning
- ∅ Test is incomplete

The ``--testdox-summary`` CLI option repeats the TestDox output at the end of the test
run for tests that had errors, failures, or issues. This provides a focused summary of
problematic tests in TestDox format.


.. _testdox.output.text:

Plain Text Output
-----------------

The ``--testdox-text <file>`` CLI option writes the test results in TestDox format as a
plain text file. Successful tests are marked with ``[x]`` and defective tests are marked
with ``[ ]``:

.. parsed-literal::

    Greeter
     [x] Greets with name
     [x] Greets with good morning before noon
     [x] Greets with good afternoon after noon
     [x] Greets with good evening after 6 pm

This can also be configured in the XML configuration file using the ``<testdoxText>`` element
inside the ``<logging>`` element (see :ref:`appendixes.xml-configuration-file.logging.testdoxText`).


.. _testdox.output.html:

HTML Output
-----------

The ``--testdox-html <file>`` CLI option writes the test results in TestDox format as an
HTML file. This produces a browsable document that

- has a header with the number of tests that passed and, when there were any, the number of
  tests that did not pass,
- groups the tests by the test class they belong to, and
- marks successful tests with a ✓ and defective tests with a ✗, and highlights them
  accordingly.

The document is self-contained: it embeds its styling and does not load any external
resources. It adapts to the light or dark color scheme that the browser reports, and it
is legible on small screens.

This can also be configured in the XML configuration file using the ``<testdoxHtml>`` element
inside the ``<logging>`` element (see :ref:`appendixes.xml-configuration-file.logging.testdoxHtml`).


.. _testdox.xml-configuration:

XML Configuration
-----------------

TestDox output can be enabled through the XML configuration file as an alternative to
using CLI options.

The ``testdox`` attribute on the ``<phpunit>`` element enables TestDox console output
(see :ref:`appendixes.xml-configuration-file.phpunit.testdox`).

The ``testdoxSummary`` attribute on the ``<phpunit>`` element enables the TestDox summary
(see :ref:`appendixes.xml-configuration-file.phpunit.testdoxSummary`).

File-based TestDox output is configured using child elements of the ``<logging>`` element:

.. code-block:: xml

    <phpunit testdox="true" testdoxSummary="true">
        <logging>
            <testdoxHtml outputFile="testdox.html"/>
            <testdoxText outputFile="testdox.txt"/>
        </logging>
    </phpunit>
