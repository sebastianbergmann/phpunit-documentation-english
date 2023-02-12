

.. _extending-phpunit:

*****************
Extending PHPUnit
*****************

.. _extending-phpunit.extending-the-test-runner:

Extending the Test Runner
=========================

...

.. code-block:: php
    :caption: todo
    :name: event-system.subscribing-to-events.examples.Extension.php

    <?php declare(strict_types=1);
    namespace Vendor\ExampleExtensionForPhpunit;

    use PHPUnit\Runner\Extension\Extension as PhpunitExtension;
    use PHPUnit\Runner\Extension\Facade as EventFacade;
    use PHPUnit\Runner\Extension\ParameterCollection;
    use PHPUnit\TextUI\Configuration\Configuration;

    final class Extension implements PhpunitExtension
    {
        public function bootstrap(Configuration $configuration, EventFacade $facade, ParameterCollection $parameters): void
        {
            $message = 'the-default-message';

            if ($parameters->has('message')) {
                $message = $parameters->get('message');
            }

            $facade->registerSubscriber(
                new ExecutionFinishedSubscriber(
                    $message
                )
            );
        }
    }

...

.. code-block:: php
    :caption: todo
    :name: extending-phpunit.event-system.extending-the-test-runner.examples.ExecutionFinishedSubscriber.php

    <?php declare(strict_types=1);
    namespace Vendor\ExampleExtensionForPhpunit;

    use const PHP_EOL;
    use PHPUnit\Event\TestRunner\ExecutionFinished;
    use PHPUnit\Event\TestRunner\ExecutionFinishedSubscriber as ExecutionFinishedSubscriberInterface;

    final class ExecutionFinishedSubscriber implements ExecutionFinishedSubscriberInterface
    {
        private readonly string $message;

        public function __construct(string $message)
        {
            $this->message = $message;
        }

        public function notify(ExecutionFinished $event): void
        {
            print __METHOD__ . PHP_EOL . $this->message . PHP_EOL;
        }
    }

...

.. code-block:: xml
    :caption: todo
    :name: extending-phpunit.event-system.extending-the-test-runner.examples.phpunit.xml

    <?xml version="1.0" encoding="UTF-8"?>
    <phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/10.0/phpunit.xsd">
        <!-- ... -->

        <extensions>
            <bootstrap class="Vendor\ExampleExtensionForPhpunit\Extension">
                <parameter name="message" value="the-message"/>
            </bootstrap>
        </extensions>

        <!-- ... -->
    </phpunit>

...

.. _extending-phpunit.event-system.event-system:

PHPUnit's Event System
----------------------

...

.. _extending-phpunit.event-system.event-system.events:

Events
^^^^^^

``PHPUnit\Event\Application\Started``

    The PHPUnit CLI application was started

``PHPUnit\Event\TestRunner\Configured``

    The test runner was configured

``PHPUnit\Event\TestRunner\BootstrapFinished``

    The test runner finished executing the configured bootstrap script

``PHPUnit\Event\TestRunner\ExtensionLoadedFromPhar``

    The test runner loaded an extension from a PHP Archive (PHAR)

``PHPUnit\Event\TestRunner\ExtensionBootstrapped``

    The test runner bootstrapped an extension

``PHPUnit\Event\TestSuite\Loaded``

    The test suite was loaded

``PHPUnit\Event\TestRunner\EventFacadeSealed``

    The event facade was sealed (new event subscribers can no longer be registered)

``PHPUnit\Event\TestSuite\Filtered``

    The test suite was filtered

``PHPUnit\Event\TestSuite\Sorted``

    The test suite was sorted

``PHPUnit\Event\TestRunner\ExecutionStarted``

    The test runner started executing tests

``PHPUnit\Event\TestSuite\Skipped``

    The execution of a test suite was skipped

``PHPUnit\Event\TestSuite\Started``

    The execution of a test suite was started

``PHPUnit\Event\Test\PreparationStarted``

    The preparation of a test for execution was started

``PHPUnit\Event\Test\BeforeFirstTestMethodCalled``

    A "before first test" method was called for a test case class

``PHPUnit\Event\Test\BeforeFirstTestMethodErrored``

    A "before first test" method errored for a test case class

``PHPUnit\Event\Test\BeforeFirstTestMethodFinished``

    All "before first test" methods were called for a test case class

``PHPUnit\Event\Test\BeforeTestMethodCalled``

    A "before test" method was called for a test method

``PHPUnit\Event\Test\BeforeTestMethodFinished``

    All "before test" methods were called for a test method

``PHPUnit\Event\Test\PreConditionCalled``

    A "precondition" method was called for a test method

``PHPUnit\Event\Test\PreConditionFinished``

    All "precondition" methods were called for a test method

``PHPUnit\Event\Test\TestPrepared``

    A test was prepared for execution

``PHPUnit\Event\Test\ComparatorRegistered``

    A test registered a custom ``Comparator`` for ``assertEquals()``

``PHPUnit\Event\Test\AssertionSucceeded``

    A test successfully asserted something

``PHPUnit\Event\Test\AssertionFailed``

    A test failed to assert something

``PHPUnit\Event\Test\MockObjectCreated``

    A test created a mock object

``PHPUnit\Event\Test\MockObjectForIntersectionOfInterfacesCreated``

    A test created a mock object for an intersection of interfaces

``PHPUnit\Event\Test\MockObjectForTraitCreated``

    A test created a mock object for a trait

``PHPUnit\Event\Test\MockObjectForAbstractClassCreated``

    A test created a mock object for an abstract class

``PHPUnit\Event\Test\MockObjectFromWsdlCreated``

    A test created a mock object from a WSDL file

``PHPUnit\Event\Test\PartialMockObjectCreated``

    A test created a partial mock object

``PHPUnit\Event\Test\TestProxyCreated``

    A test created a test proxy

``PHPUnit\Event\Test\TestStubCreated``

    A test created a test stub

``PHPUnit\Event\Test\TestStubForIntersectionOfInterfacesCreated``

    A test created a test stub for an intersection of interfaces

``PHPUnit\Event\Test\Errored``

    A test errored

``PHPUnit\Event\Test\Failed``

    A test failed

``PHPUnit\Event\Test\Passed``

    A test passed

``PHPUnit\Event\Test\ConsideredRisky``

    A test was considered risky

``PHPUnit\Event\Test\MarkedIncomplete``

    A test was marked incomplete

``PHPUnit\Event\Test\Skipped``

    A test was skipped

``PHPUnit\Event\Test\PhpunitDeprecationTriggered``

    A test triggered a PHPUnit deprecation

``PHPUnit\Event\Test\PhpDeprecationTriggered``

    A test triggered a PHP deprecation

``PHPUnit\Event\Test\DeprecationTriggered``

    A test triggered a deprecation (neither a PHPUnit nor a PHP deprecation)

``PHPUnit\Event\Test\PhpunitErrorTriggered``

    A test triggered a PHPUnit error

``PHPUnit\Event\Test\ErrorTriggered``

    A test triggered an error (not a PHPUnit error)

``PHPUnit\Event\Test\PhpNoticeTriggered``

    A test triggered a PHP notice

``PHPUnit\Event\Test\NoticeTriggered``

    A test triggered a notice (not a PHP notice)

``PHPUnit\Event\Test\PhpunitWarningTriggered``

    A test triggered a PHPUnit warning

``PHPUnit\Event\Test\PhpWarningTriggered``

    A test triggered a PHP warning

``PHPUnit\Event\Test\WarningTriggered``

    A test triggered a warning (neither a PHPUnit nor a PHP warning)

``PHPUnit\Event\Test\Finished``

    The execution of a test method finished

``PHPUnit\Event\Test\PostConditionCalled``

    A "postcondition" method was called for a test method

``PHPUnit\Event\Test\PostConditionFinished``

    All "postcondition" methods were called for a test method

``PHPUnit\Event\Test\AfterTestMethodCalled``

    An "after test" method was called for a test method

``PHPUnit\Event\Test\AfterTestMethodFinished``

    All "after test" methods were called for a test method

``PHPUnit\Event\Test\AfterLastTestMethodCalled``

    An "after last test" method was called for a test case class

``PHPUnit\Event\Test\AfterLastTestMethodFinished``

    All "after last test" methods were called for a test case class

``PHPUnit\Event\TestSuite\Finished``

    The execution of a test suite has finished

``PHPUnit\Event\TestRunner\DeprecationTriggered``

    A deprecation in the test runner was triggered

``PHPUnit\Event\TestRunner\WarningTriggered``

    A warning in the test runner was triggered

``PHPUnit\Event\TestRunner\ExecutionFinished``

    The test runner finished executing tests

``PHPUnit\Event\Application\Finished``

    The PHPUnit CLI application has finished

.. _extending-phpunit.event-system.event-system.debugging-phpunit:

Debugging PHPUnit
^^^^^^^^^^^^^^^^^

The test runner's ``--log-events-text`` CLI option can be used to write a plain text representation
for each event to a stream. In the example shown below, we use ``--no-output`` to disable both the
default progress output as well as the default result output. Then we use ``--log-events-text php://stdout``
to write event information to standard output:

.. code-block::
    :caption: todo
    :name: extending-phpunit.event-system.event-system.debugging-phpunit.examples.logging-events

    phpunit --no-output --log-events-text php://stdout
    PHPUnit Started (PHPUnit 10.0.0 using PHP 8.2.1 (cli) on Linux)
    Test Runner Configured
    Test Suite Loaded (2 tests)
    Event Facade Sealed
    Test Runner Started
    Test Suite Sorted
    Test Runner Execution Started (2 tests)
    Test Suite Started (ExampleTest, 2 tests)
    Test Preparation Started (ExampleTest::testOne)
    Test Prepared (ExampleTest::testOne)
    Assertion Succeeded (Constraint: is true)
    Test Passed (ExampleTest::testOne)
    Test Finished (ExampleTest::testOne)
    Test Preparation Started (ExampleTest::testTwo)
    Test Prepared (ExampleTest::testTwo)
    Assertion Failed (Constraint: is identical to 'foo', Value: 'bar')
    Test Failed (ExampleTest::testTwo)
    Failed asserting that two strings are identical.
    Test Finished (ExampleTest::testTwo)
    Test Suite Finished (ExampleTest, 2 tests)
    Test Runner Execution Finished
    Test Runner Finished
    PHPUnit Finished (Shell Exit Code: 1)

Alternatively, the ``--log-events-verbose-text`` CLI option can be used to include information
about resource consumption (time since the test runner was started, time since the previous event,
and memory usage):

.. code-block::
    :caption: todo
    :name: extending-phpunit.event-system.event-system.debugging-phpunit.examples.logging-events-verbose

    phpunit --no-output --log-events-verbose-text php://stdout
    [00:00:00.000046482 / 00:00:00.000006987] [4194304 bytes] PHPUnit Started (PHPUnit 10.0.0 using PHP 8.2.1 (cli) on Linux)
    [00:00:00.048195557 / 00:00:00.048149075] [4194304 bytes] Test Runner Configured
    [00:00:00.067646038 / 00:00:00.019450481] [6291456 bytes] Test Suite Loaded (2 tests)
    [00:00:00.075942220 / 00:00:00.008296182] [6291456 bytes] Event Facade Sealed
    [00:00:00.076452360 / 00:00:00.000510140] [6291456 bytes] Test Runner Started
    [00:00:00.084421682 / 00:00:00.007969322] [6291456 bytes] Test Suite Sorted
    [00:00:00.084664485 / 00:00:00.000242803] [6291456 bytes] Test Runner Execution Started (2 tests)
    [00:00:00.085240320 / 00:00:00.000575835] [6291456 bytes] Test Suite Started (ExampleTest, 2 tests)
    [00:00:00.086992385 / 00:00:00.001752065] [6291456 bytes] Test Preparation Started (ExampleTest::testOne)
    [00:00:00.087443560 / 00:00:00.000451175] [6291456 bytes] Test Prepared (ExampleTest::testOne)
    [00:00:00.088237489 / 00:00:00.000793929] [6291456 bytes] Assertion Succeeded (Constraint: is true)
    [00:00:00.089076305 / 00:00:00.000838816] [6291456 bytes] Test Passed (ExampleTest::testOne)
    [00:00:00.091027624 / 00:00:00.001951319] [6291456 bytes] Test Finished (ExampleTest::testOne)
    [00:00:00.091110095 / 00:00:00.000082471] [6291456 bytes] Test Preparation Started (ExampleTest::testTwo)
    [00:00:00.091158739 / 00:00:00.000048644] [6291456 bytes] Test Prepared (ExampleTest::testTwo)
    [00:00:00.091991799 / 00:00:00.000833060] [6291456 bytes] Assertion Failed (Constraint: is identical to 'foo', Value: 'bar')
    [00:00:00.099242925 / 00:00:00.007251126] [8388608 bytes] Test Failed (ExampleTest::testTwo)
                                                              Failed asserting that two strings are identical.
    [00:00:00.099386498 / 00:00:00.000143573] [8388608 bytes] Test Finished (ExampleTest::testTwo)
    [00:00:00.099437634 / 00:00:00.000051136] [8388608 bytes] Test Suite Finished (ExampleTest, 2 tests)
    [00:00:00.103014760 / 00:00:00.003577126] [8388608 bytes] Test Runner Execution Finished
    [00:00:00.103207309 / 00:00:00.000192549] [8388608 bytes] Test Runner Finished
    [00:00:00.105879902 / 00:00:00.002672593] [8388608 bytes] PHPUnit Finished (Shell Exit Code: 1)

.. _extending-phpunit.wrapping-the-test-runner:

Wrapping the Test Runner
========================

The ``PHPUnit\TextUI\Application`` class is the entry point for PHPUnit's own CLI test runner.
It is not meant to be (re)used by developers who want to wrap PHPUnit to build something such
as ParaTest.

For the actual running of tests, ``PHPUnit\TextUI\Application`` uses ``PHPUnit\TextUI\TestRunner::run()``.

``PHPUnit\TextUI\TestRunner::run()`` requires a ``PHPUnit\TextUI\Configuration\Configuration``,
a ``PHPUnit\Runner\ResultCache\ResultCache``, and a ``PHPUnit\Framework\TestSuite``.

A ``PHPUnit\TextUI\Configuration\Configuration`` can be built using ``PHPUnit\TextUI\Configuration\Builder::build()``.
You need to pass ``$_SERVER['argv']`` to this method. The method then parses CLI arguments/options and loads an XML
configuration file, if one can be loaded.

A ``PHPUnit\Framework\TestSuite`` can be built from a ``PHPUnit\TextUI\Configuration\Configuration`` using
``PHPUnit\TextUI\Configuration\TestSuiteBuilder::build()``.

While it is marked ``@internal``, ``PHPUnit\TextUI\TestRunner`` is meant to be (re)used by developers who
want to wrap PHPUnit's test runner.
