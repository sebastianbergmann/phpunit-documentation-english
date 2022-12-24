

.. _event-system:

************
Event System
************

...

.. _event-system.events:

Events
======

...

.. _event-system.debugging-phpunit:

Debugging PHPUnit
=================

The test runner's ``--log-events-text`` CLI option can be used to write a plain text representation
for each event to a stream. In the example shown below, we use ``--no-output`` to disable both the
default progress output as well as the default result output. Then we use ``--log-events-text php://stdout``
to write event information to standard output:

.. code-block::
    :caption: todo
    :name: event-system.debugging-phpunit.examples.logging-events

    phpunit --no-output --log-events-text php://stdout
    Test Runner Started (PHPUnit 10.0.0 using PHP 8.2.0 (cli) on Linux)
    Test Runner Configured
    Test Suite Loaded (1 test)
    Test Suite Sorted
    Event Facade Sealed
    Test Runner Execution Started (1 test)
    Test Suite Started (1 test)
    Test Suite Started (default, 1 test)
    Test Suite Started (ExampleTest, 1 test)
    Test Preparation Started (ExampleTest::testOne)
    Test Prepared (ExampleTest::testOne)
    Assertion Failed (Constraint: is true, Value: false)
    Test Failed (ExampleTest::testOne)
    Failed asserting that false is true.
    Test Finished (ExampleTest::testOne)
    Test Suite Finished (ExampleTest, 1 test)
    Test Suite Finished (default, 1 test)
    Test Suite Finished (1 test)
    Test Runner Execution Finished
    Test Runner Finished

Alternatively, the ``--log-events-verbose-text`` CLI option can be used to include information
about resource consumption (time since the test runner was started, time since the previous event,
and memory usage):

.. code-block::
    :caption: todo
    :name: event-system.debugging-phpunit.examples.logging-events-verbose

    phpunit --no-output --log-events-verbose-text php://stdout
    [00:00:00.000035031 / 00:00:00.000004880] [4194304 bytes] Test Runner Started (PHPUnit 10.0.0 using PHP 8.2.0 (cli) on Linux)
    [00:00:00.030921054 / 00:00:00.030886023] [6291456 bytes] Test Runner Configured
    [00:00:00.038802684 / 00:00:00.007881630] [6291456 bytes] Test Suite Loaded (1 test)
    [00:00:00.040860588 / 00:00:00.002057904] [6291456 bytes] Test Suite Sorted
    [00:00:00.042708258 / 00:00:00.001847670] [6291456 bytes] Event Facade Sealed
    [00:00:00.043031136 / 00:00:00.000322878] [6291456 bytes] Test Runner Execution Started (1 test)
    [00:00:00.043277400 / 00:00:00.000246264] [6291456 bytes] Test Suite Started (1 test)
    [00:00:00.043402904 / 00:00:00.000125504] [6291456 bytes] Test Suite Started (default, 1 test)
    [00:00:00.044738027 / 00:00:00.001335123] [6291456 bytes] Test Suite Started (ExampleTest, 1 test)
    [00:00:00.046169639 / 00:00:00.001431612] [6291456 bytes] Test Preparation Started (ExampleTest::testOne)
    [00:00:00.046592057 / 00:00:00.000422418] [6291456 bytes] Test Prepared (ExampleTest::testOne)
    [00:00:00.047769887 / 00:00:00.001177830] [6291456 bytes] Assertion Failed (Constraint: is true, Value: false)
    [00:00:00.051970142 / 00:00:00.004200255] [6291456 bytes] Test Failed (ExampleTest::testOne)
                                                              Failed asserting that false is true.
    [00:00:00.053355327 / 00:00:00.001385185] [6291456 bytes] Test Finished (ExampleTest::testOne)
    [00:00:00.053494019 / 00:00:00.000138692] [6291456 bytes] Test Suite Finished (ExampleTest, 1 test)
    [00:00:00.053557577 / 00:00:00.000063558] [6291456 bytes] Test Suite Finished (default, 1 test)
    [00:00:00.053604485 / 00:00:00.000046908] [6291456 bytes] Test Suite Finished (1 test)
    [00:00:00.053998986 / 00:00:00.000394501] [6291456 bytes] Test Runner Execution Finished
    [00:00:00.054820440 / 00:00:00.000821454] [6291456 bytes] Test Runner Finished

.. _event-system.subscribing-to-events:

Subscribing to Events
=====================

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
    :name: event-system.subscribing-to-events.examples.ExecutionFinishedSubscriber.php

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
    :name: event-system.subscribing-to-events.examples.phpunit.xml

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
