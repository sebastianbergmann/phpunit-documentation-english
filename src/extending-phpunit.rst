

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
``assertTrue()`` method is just a wrapper around the
``isTrue()`` and ``assertThat()`` methods:
``isTrue()`` creates a matcher object that is passed on to
``assertThat()`` for evaluation.

.. code-block:: php
    :caption: The assertTrue() and isTrue() methods of the PHPUnit\Framework\Assert class
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
    }?>

:numref:`extending-phpunit.examples.IsTrue.php` shows how
``PHPUnit\Framework\Constraint\IsTrue`` extends the
abstract base class for matcher objects (or constraints),
``PHPUnit\Framework\Constraint``.

.. code-block:: php
    :caption: The PHPUnit\Framework\Constraint\IsTrue class
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

.. _extending-phpunit.PHPUnit_Framework_TestListener:

Implement PHPUnit\\Framework\\TestListener
##########################################

:numref:`extending-phpunit.examples.SimpleTestListener.php`
shows a simple implementation of the ``PHPUnit\Framework\TestListener``
interface.

.. code-block:: php
    :caption: A simple test listener
    :name: extending-phpunit.examples.SimpleTestListener.php

    <?php
    use PHPUnit\Framework\TestCase;
    use PHPUnit\Framework\TestListener;

    class SimpleTestListener implements TestListener
    {
        public function addError(PHPUnit\Framework\Test $test, Exception $e, $time)
        {
            printf("Error while running test '%s'.\n", $test->getName());
        }

        public function addFailure(PHPUnit\Framework\Test $test, PHPUnit\Framework\AssertionFailedError $e, $time)
        {
            printf("Test '%s' failed.\n", $test->getName());
        }

        public function addIncompleteTest(PHPUnit\Framework\Test $test, Exception $e, $time)
        {
            printf("Test '%s' is incomplete.\n", $test->getName());
        }

        public function addRiskyTest(PHPUnit\Framework\Test $test, Exception $e, $time)
        {
            printf("Test '%s' is deemed risky.\n", $test->getName());
        }

        public function addSkippedTest(PHPUnit\Framework\Test $test, Exception $e, $time)
        {
            printf("Test '%s' has been skipped.\n", $test->getName());
        }

        public function startTest(PHPUnit\Framework\Test $test)
        {
            printf("Test '%s' started.\n", $test->getName());
        }

        public function endTest(PHPUnit\Framework\Test $test, $time)
        {
            printf("Test '%s' ended.\n", $test->getName());
        }

        public function startTestSuite(PHPUnit\Framework\TestSuite $suite)
        {
            printf("TestSuite '%s' started.\n", $suite->getName());
        }

        public function endTestSuite(PHPUnit\Framework\TestSuite $suite)
        {
            printf("TestSuite '%s' ended.\n", $suite->getName());
        }
    }
    ?>

:numref:`extending-phpunit.examples.BaseTestListener.php`
shows how to subclass the ``PHPUnit\Framework\BaseTestListener``
abstract class, which lets you specify only the interface methods that
are interesting for your use case, while providing empty implementations
for all the others.

.. code-block:: php
    :caption: Using base test listener
    :name: extending-phpunit.examples.BaseTestListener.php

    <?php
    use PHPUnit\Framework\BaseTestListener;

    class ShortTestListener extends BaseTestListener
    {
        public function endTest(PHPUnit\Framework\Test $test, $time)
        {
            printf("Test '%s' ended.\n", $test->getName());
        }
    }
    ?>

In :ref:`appendixes.configuration.test-listeners` you can see
how to configure PHPUnit to attach your test listener to the test
execution.

.. _extending-phpunit.PHPUnit_Extensions_TestDecorator:

Subclass PHPUnit_Extensions_TestDecorator
#########################################

You can wrap test cases or test suites in a subclass of
``PHPUnit_Extensions_TestDecorator`` and use the
Decorator design pattern to perform some actions before and after the
test runs.

PHPUnit ships with one concrete test decorator:
``PHPUnit_Extensions_RepeatedTest``. It is used to run a
test repeatedly and only count it as a success if all iterations are
successful.

:numref:`extending-phpunit.examples.RepeatedTest.php`
shows a cut-down version of the ``PHPUnit_Extensions_RepeatedTest``
test decorator that illustrates how to write your own test decorators.

.. code-block:: php
    :caption: The RepeatedTest Decorator
    :name: extending-phpunit.examples.RepeatedTest.php

    <?php
    use PHPUnit\Framework\TestCase;

    require_once 'PHPUnit/Extensions/TestDecorator.php';

    class PHPUnit_Extensions_RepeatedTest extends PHPUnit_Extensions_TestDecorator
    {
        private $timesRepeat = 1;

        public function __construct(PHPUnit\Framework\Test $test, $timesRepeat = 1)
        {
            parent::__construct($test);

            if (is_integer($timesRepeat) &&
                $timesRepeat >= 0) {
                $this->timesRepeat = $timesRepeat;
            }
        }

        public function count()
        {
            return $this->timesRepeat * $this->test->count();
        }

        public function run(PHPUnit\Framework\TestResult $result = null)
        {
            if ($result === null) {
                $result = $this->createResult();
            }

            for ($i = 0; $i < $this->timesRepeat && !$result->shouldStop(); $i++) {
                $this->test->run($result);
            }

            return $result;
        }
    }
    ?>

.. _extending-phpunit.PHPUnit_Framework_Test:

Implement PHPUnit\Framework\Test
################################

The ``PHPUnit\Framework\Test`` interface is narrow and
easy to implement. You can write an implementation of
``PHPUnit\Framework\Test`` that is simpler than
``PHPUnit\Framework\TestCase`` and that runs
*data-driven tests*, for instance.

:numref:`extending-phpunit.examples.DataDrivenTest.php`
shows a data-driven test case class that compares values from a file
with Comma-Separated Values (CSV). Each line of such a file looks like
``foo;bar``, where the first value is the one we expect
and the second value is the actual one.

.. code-block:: php
    :caption: A data-driven test
    :name: extending-phpunit.examples.DataDrivenTest.php

    <?php
    use PHPUnit\Framework\TestCase;

    class DataDrivenTest implements PHPUnit\Framework\Test
    {
        private $lines;

        public function __construct($dataFile)
        {
            $this->lines = file($dataFile);
        }

        public function count()
        {
            return 1;
        }

        public function run(PHPUnit\Framework\TestResult $result = null)
        {
            if ($result === null) {
                $result = new PHPUnit\Framework\TestResult;
            }

            foreach ($this->lines as $line) {
                $result->startTest($this);
                PHP_Timer::start();
                $stopTime = null;

                list($expected, $actual) = explode(';', $line);

                try {
                    PHPUnit\Framework\Assert::assertEquals(
                      trim($expected), trim($actual)
                    );
                }

                catch (PHPUnit\Framework\AssertionFailedError $e) {
                    $stopTime = PHP_Timer::stop();
                    $result->addFailure($this, $e, $stopTime);
                }

                catch (Exception $e) {
                    $stopTime = PHP_Timer::stop();
                    $result->addError($this, $e, $stopTime);
                }

                if ($stopTime === null) {
                    $stopTime = PHP_Timer::stop();
                }

                $result->endTest($this, $stopTime);
            }

            return $result;
        }
    }

    $test = new DataDrivenTest('data_file.csv');
    $result = PHPUnit\TextUI\TestRunner::run($test);
    ?>

.. code-block:: bash

    PHPUnit 7.0.0 by Sebastian Bergmann and contributors.

    .F

    Time: 0 seconds

    There was 1 failure:

    1) DataDrivenTest
    Failed asserting that two strings are equal.
    expected string <bar>
    difference      <  x>
    got string      <baz>
    /home/sb/DataDrivenTest.php:32
    /home/sb/DataDrivenTest.php:53

    FAILURES!
    Tests: 2, Failures: 1.


