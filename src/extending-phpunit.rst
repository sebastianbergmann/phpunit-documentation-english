

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

.. _extending-phpunit.PHPUnit_Framework_TestListener:

Implement PHPUnit\\Framework\\TestListener
##########################################

:numref:`extending-phpunit.examples.SimpleTestListener.php`
shows a simple implementation of the ``PHPUnit\Framework\TestListener``
interface.

.. code-block:: php
    :caption: A simple test listener
    :name: extending-phpunit.examples.SimpleTestListener.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;
    use PHPUnit\Framework\TestListener;

    class SimpleTestListener implements TestListener
    {
        public function addError(PHPUnit\Framework\Test $test, \Throwable $e, float $time): void
        {
            printf("Error while running test '%s'.\n", $test->getName());
        }

        public function addWarning(PHPUnit\Framework\Test $test, PHPUnit\Framework\Warning $e, float $time): void
        {
            printf("Warning while running test '%s'.\n", $test->getName());
        }

        public function addFailure(PHPUnit\Framework\Test $test, PHPUnit\Framework\AssertionFailedError $e, float $time): void
        {
            printf("Test '%s' failed.\n", $test->getName());
        }

        public function addIncompleteTest(PHPUnit\Framework\Test $test, \Throwable $e, float $time): void
        {
            printf("Test '%s' is incomplete.\n", $test->getName());
        }

        public function addRiskyTest(PHPUnit\Framework\Test $test, \Throwable $e, float $time): void
        {
            printf("Test '%s' is deemed risky.\n", $test->getName());
        }

        public function addSkippedTest(PHPUnit\Framework\Test $test, \Throwable $e, float $time): void
        {
            printf("Test '%s' has been skipped.\n", $test->getName());
        }

        public function startTest(PHPUnit\Framework\Test $test): void
        {
            printf("Test '%s' started.\n", $test->getName());
        }

        public function endTest(PHPUnit\Framework\Test $test, float $time): void
        {
            printf("Test '%s' ended.\n", $test->getName());
        }

        public function startTestSuite(PHPUnit\Framework\TestSuite $suite): void
        {
            printf("TestSuite '%s' started.\n", $suite->getName());
        }

        public function endTestSuite(PHPUnit\Framework\TestSuite $suite): void
        {
            printf("TestSuite '%s' ended.\n", $suite->getName());
        }
    }

:numref:`extending-phpunit.examples.ExtendedTestListener.php`
shows how to use the ``PHPUnit\Framework\TestListenerDefaultImplementation``
trait, which lets you specify only the interface methods that
are interesting for your use case, while providing empty implementations
for all the others.

.. code-block:: php
    :caption: Using test listener default implementation trait
    :name: extending-phpunit.examples.ExtendedTestListener.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestListener;
    use PHPUnit\Framework\TestListenerDefaultImplementation;

    class ShortTestListener implements TestListener
    {
        use TestListenerDefaultImplementation;

        public function endTest(PHPUnit\Framework\Test $test, float $time): void
        {
            printf("Test '%s' ended.\n", $test->getName());
        }
    }

In :ref:`appendixes.configuration.test-listeners` you can see
how to configure PHPUnit to attach your test listener to the test
execution.
