

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

    <?php
    namespace PHPUnit\Framework;

    use PHPUnit\Framework\TestCase;

    abstract class Assert
    {
        // ...

        /**
         * Asserts that a condition is true.
         *
         * @param  boolean $condition
         * @param  string  $message
         * @throws PHPUnit\Framework\AssertionFailedError
         */
        public static function assertTrue($condition, $message = '')
        {
            self::assertThat($condition, self::isTrue(), $message);
        }

        // ...

        /**
         * Returns a PHPUnit\Framework\Constraint\IsTrue matcher object.
         *
         * @return PHPUnit\Framework\Constraint\IsTrue
         * @since  Method available since Release 3.3.0
         */
        public static function isTrue()
        {
            return new PHPUnit\Framework\Constraint\IsTrue;
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

    <?php
    namespace PHPUnit\Framework\Constraint;

    use PHPUnit\Framework\Constraint;

    class IsTrue extends Constraint
    {
        /**
         * Evaluates the constraint for parameter $other. Returns true if the
         * constraint is met, false otherwise.
         *
         * @param mixed $other Value or object to evaluate.
         * @return bool
         */
        public function matches($other)
        {
            return $other === true;
        }

        /**
         * Returns a string representation of the constraint.
         *
         * @return string
         */
        public function toString()
        {
            return 'is true';
        }
    }?>

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
