

.. _extending-phpunit:

=================
Extending PHPUnit
=================

PHPUnit can be extended in various ways to make the writing of tests
easier and customize the feedback you get from running tests. Here are
common starting points to extend PHPUnit.

.. _extending-phpunit.PHPUnit_Framework_TestCase:

Subclass PHPUnit\\Framework\\TestCase
#####################################

Write custom assertions and utility methods in an abstract subclass of
``PHPUnit\Framework\TestCase`` and derive your test case
classes from that class. This is one of the easiest ways to extend
PHPUnit.

.. _extending-phpunit.custom-assertions:

Write custom assertions
#######################

When writing custom assertions it is the best practice to follow how
PHPUnit's own assertions are implemented. As you can see in
:numref:`extending-phpunit.examples.Assert.php`, the
``assertTrue()`` method is a wrapper around the
``isTrue()`` and ``assertThat()`` methods:
``isTrue()`` creates a matcher object that is passed on to
``assertThat()`` for evaluation.

.. code-block:: php
    :caption: The assertTrue() and isTrue() methods of the PHPUnit\\Framework\\Assert class
    :name: extending-phpunit.examples.Assert.php

    <?php declare(strict_types=1);
    namespace PHPUnit\Framework;

    use PHPUnit\Framework\Constraint\IsTrue;

    abstract class Assert
    {
        // ...

        public static function assertTrue($condition, string $message = ''): void
        {
            static::assertThat($condition, static::isTrue(), $message);
        }

        // ...

        public static function isTrue(): IsTrue
        {
            return new IsTrue;
        }

        // ...
    }

:numref:`extending-phpunit.examples.IsTrue.php` shows how
``PHPUnit\Framework\Constraint\IsTrue`` extends the
abstract base class for matcher objects (or constraints),
``PHPUnit\Framework\Constraint``.

.. code-block:: php
    :caption: The PHPUnit\\Framework\Constraint\\IsTrue class
    :name: extending-phpunit.examples.IsTrue.php

    <?php declare(strict_types=1);
    namespace PHPUnit\Framework\Constraint;

    use PHPUnit\Framework\Constraint;

    final class IsTrue extends Constraint
    {
        public function toString(): string
        {
            return 'is true';
        }

        protected function matches($other): bool
        {
            return $other === true;
        }
    }

The effort of implementing the ``assertTrue()`` and
``isTrue()`` methods as well as the
``PHPUnit\Framework\Constraint\IsTrue`` class yields the
benefit that ``assertThat()`` automatically takes care of
evaluating the assertion and bookkeeping tasks such as counting it for
statistics. Furthermore, the ``isTrue()`` method can be
used as a matcher when configuring mock objects.

.. _extending-phpunit.TestRunner:

Extending the TestRunner
########################

PHPUnit's test runner can be extended by registering objects that implement
one or more of the following interfaces:

- ``AfterIncompleteTestHook``
- ``AfterLastTestHook``
- ``AfterRiskyTestHook``
- ``AfterSkippedTestHook``
- ``AfterSuccessfulTestHook``
- ``AfterTestErrorHook``
- ``AfterTestFailureHook``
- ``AfterTestWarningHook``
- ``AfterTestHook``
- ``BeforeFirstTestHook``
- ``BeforeTestHook``

Each "hook", meaning each of the interfaces listed above, represents an event
that can occur while the tests are being executed.

See :ref:`appendixes.configuration.extensions` for details on how
to register extensions in PHPUnit's XML configuration.

:numref:`extending-phpunit.examples.TestRunnerExtension` shows an example
for an extension implementing ``BeforeFirstTestHook`` and ``AfterLastTestHook``:

.. code-block:: php
    :caption: TestRunner Extension Example
    :name: extending-phpunit.examples.TestRunnerExtension

    <?php declare(strict_types=1);
    namespace Vendor;

    use PHPUnit\Runner\BeforeFirstTestHook;
    use PHPUnit\Runner\AfterLastTestHook;

    final class MyExtension implements BeforeFirstTestHook, AfterLastTestHook
    {
        public function executeBeforeFirstTest(): void
        {
            // called before the first test is being run
        }

        public function executeAfterLastTest(): void
        {
            // called after the last test has been run
        }
    }

Configuring extensions
----------------------

You can configure PHPUnit extensions, assuming the extension accepts
configuration values.

:numref:`extending-phpunit.examples.TestRunnerConfigurableExtension` shows an
example how to make an extension configurable, by adding an ``__constructor()``
definition to the extension class:

.. code-block:: php
    :caption: TestRunner Extension with constructor
    :name: extending-phpunit.examples.TestRunnerConfigurableExtension

    <?php declare(strict_types=1);
    namespace Vendor;

    use PHPUnit\Runner\BeforeFirstTestHook;
    use PHPUnit\Runner\AfterLastTestHook;

    final class MyConfigurableExtension implements BeforeFirstTestHook, AfterLastTestHook
    {
        protected $config_value_1 = '';

        protected $config_value_2 = 0;

        public function __construct(string $value1 = '', int $value2 = 0)
        {
            $this->config_value_1 = $config_1;
            $this->config_value_2 = $config_2;
        }

        public function executeBeforeFirstTest(): void
        {
            if (strlen($this->config_value_1) {
                echo 'Testing with configuration value: ' . $this->config_value_1;
            }
        }

        public function executeAfterLastTest(): void
        {
            if ($this->config_value_2 > 10) {
                echo 'Second config value is OK!';
            }
        }
    }

To input configuration to the extension via XML, the XML configuration file's
``extensions`` section needs to be updated to have configuration values, as
shown in
:numref:`extending-phpunit.examples.TestRunnerConfigurableExtensionConfig`:

.. code-block:: xml
    :caption: TestRunner Extension configuration
    :name: extending-phpunit.examples.TestRunnerConfigurableExtensionConfig

    <extensions>
        <extension class="Vendor\MyUnconfigurableExtension" />
        <extension class="Vendor\MyConfigurableExtension">
            <arguments>
                <string>Hello world!</string>
                <int>15</int>
            </arguments>
        </extension>
    </extensions>

See :ref:`appendixes.configuration.extensions.extension.arguments` for
details on how to use the ``arguments`` configuration.

Remember: all configuration is optional, so make sure your extension either has
sane defaults in place, or that it disables itself in case configuration is
missing.
