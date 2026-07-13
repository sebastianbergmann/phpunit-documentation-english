

.. _extending-phpunit:

*****************
Extending PHPUnit
*****************

.. _extending-phpunit.enhancing-concrete-test-cases:

Enhancing concrete test cases
=============================

You can extend PHPUnit by enhancing concrete test cases with methods that add functionality.

For example, you may use an assertion in a concrete test case to assert that a value created by the system under test matches a regular expression.

.. literalinclude:: examples/extending-phpunit/OrderIdGeneratorTest.php
   :caption: A concrete test case using a default assertion
   :language: php

You can enhance this concrete test case by extracting a domain-specific assertion.

.. literalinclude:: examples/extending-phpunit/OrderIdGeneratorWithDomainSpecificAssertionTest.php
   :caption: A concrete test case using a domain-specific assertion
   :language: php

.. _extending-phpunit.extracting-abstract-test-cases:

Extracting abstract test cases
==============================

You can extend PHPUnit by extracting abstract test cases to share functionality with other concrete test cases via vertical inheritance.

For example, you may want to pull the domain-specific assertion from above into an abstract test case.

.. literalinclude:: examples/extending-phpunit/AbstractTestCase.php
   :caption: An abstract test case with a domain-specific assertion
   :language: php

You can then enhance a concrete test case by extending the abstract test case.

.. literalinclude:: examples/extending-phpunit/OrderIdGeneratorExtendingAbstractTestCaseTest.php
   :caption: A concrete test case extending an abstract test case with a domain-specific assertion
   :language: php

.. _extending-phpunit.extracting-traits:

Extracting traits
=================

You can extend PHPUnit by extracting traits to share functionality with concrete test cases via horizontal inheritance.

For example, you may want to pull the domain-specific assertion from above into a trait.

.. literalinclude:: examples/extending-phpunit/AssertionTrait.php
   :caption: A trait with a domain-specific assertion
   :language: php

You can then enhance a concrete test case by using the trait.

.. literalinclude:: examples/extending-phpunit/OrderIdGeneratorUsingAssertionTraitTest.php
   :caption: A concrete test case using a trait with a domain-specific assertion
   :language: php


.. _extending-phpunit.implementing-custom-constraints:

Implementing custom constraints
================================

You can extend PHPUnit by implementing a custom constraint.
A custom constraint is a class that extends the ``PHPUnit\Framework\Constraint\Constraint`` class.

A custom constraint must implement two methods:

The ``matches(mixed $other): bool`` method contains the evaluation logic.
It returns ``true`` when the constraint is met and ``false`` otherwise.

The ``toString(): string`` method returns a string representation of the constraint.
This string is used in failure messages when an assertion using this constraint fails.

For example, you may want to implement a constraint that checks whether a string is a valid order ID.

.. literalinclude:: examples/extending-phpunit/IsValidOrderId.php
   :caption: A custom constraint that checks whether a string is a valid order ID
   :language: php

You can use a custom constraint with ``assertThat()``.

.. literalinclude:: examples/extending-phpunit/IsValidOrderIdTest.php
   :caption: A test using a custom constraint with assertThat()
   :language: php

You can optionally override these methods on the ``PHPUnit\Framework\Constraint\Constraint`` class for more control over failure messages:

The ``failureDescription(mixed $other): string`` method returns the description of the failure when the constraint is not met.
By default, this method combines the string representation of the evaluated value with the string returned by ``toString()``.

The ``additionalFailureDescription(mixed $other): string`` method can be used to provide additional details, such as a diff, in the failure message.

You can find a list of all built-in constraints in the :ref:`appendix <appendixes.assertions.constraints>`.


.. _extending-phpunit.implementing-custom-assertions:

Implementing custom assertions
===============================

You can combine a custom constraint with a trait or abstract test case (as shown :ref:`above <extending-phpunit.extracting-traits>`) to create a reusable custom assertion.

For example, you can wrap the ``IsValidOrderId`` constraint from above in a trait that provides an ``assertStringIsOrderId()`` method.

.. literalinclude:: examples/extending-phpunit/CustomAssertionTrait.php
   :caption: A trait wrapping a custom constraint into a custom assertion method
   :language: php

You can then use this trait in a concrete test case.

.. literalinclude:: examples/extending-phpunit/OrderIdGeneratorUsingCustomAssertionTraitTest.php
   :caption: A concrete test case using a custom assertion backed by a custom constraint
   :language: php

This approach gives you the best of both worlds: a convenient assertion method for test authors and a well-structured constraint that provides clear failure messages.


.. _extending-phpunit.implementing-custom-comparators:

Implementing custom comparators
================================

A custom comparator controls how ``assertEquals()`` and ``assertNotEquals()`` compare objects of a specific type.

A custom comparator is a class that extends the ``SebastianBergmann\Comparator\Comparator`` class that must implement two methods:

The ``accepts(mixed $expected, mixed $actual): bool`` method returns ``true`` when this comparator can handle the given pair of values.

The ``assertEquals(mixed $expected, mixed $actual, float $delta = 0.0, bool $canonicalize = false, bool $ignoreCase = false): void`` method performs the comparison.
It must throw a ``SebastianBergmann\Comparator\ComparisonFailure`` exception when the values are not equal.

For example, consider a ``Money`` value object.

.. literalinclude:: examples/extending-phpunit/Money.php
   :caption: A Money value object
   :language: php

You may want to implement a comparator that compares ``Money`` objects by their amount and currency.

.. literalinclude:: examples/extending-phpunit/MoneyComparator.php
   :caption: A custom comparator for Money objects
   :language: php

You must register a custom comparator with the ``SebastianBergmann\Comparator\Factory`` before it can be used.
After you are done, you should unregister it again.
The best place to do this is in a before-test method such as ``setUp()`` and an after-test method such as ``tearDown()`` methods of your test case.

.. literalinclude:: examples/extending-phpunit/MoneyComparatorTest.php
   :caption: A test registering and using a custom comparator
   :language: php

Once the custom comparator is registered, it will be used whenever ``assertEquals()`` or ``assertNotEquals()`` is called with two ``Money`` objects.

.. admonition:: Alternative for simple cases

   If your value object has an ``equals()`` method (or a similar method), consider using ``assertObjectEquals()`` instead of implementing a custom comparator.
   See :ref:`assertObjectEquals() <appendixes.assertions.assertObjectEquals>` for details.


.. _extending-phpunit.implementing-custom-object-exporters:

Implementing custom object exporters
====================================

When an assertion fails, PHPUnit exports the values that were involved so that they can be represented as strings in the failure message.
By default, an object is exported with all its properties.
This can make failure messages hard to read when the objects involved are large or contain properties that are irrelevant for understanding the failure.

A custom object exporter controls how objects of a specific type are represented in failure messages.

A custom object exporter is a class that implements the ``SebastianBergmann\Exporter\ObjectExporter`` interface, which declares two methods:

The ``handles(object $object): bool`` method returns ``true`` when this object exporter can export the given object.

The ``export(object $object, Exporter $exporter, int $indentation): string`` method returns the string representation of the given object.
The ``$exporter`` argument can be used to export the values of the object's properties.
The ``$indentation`` argument provides the current level of indentation to support nested output.

For example, you may want to represent the ``Money`` objects from the previous section compactly by their amount and currency.

.. literalinclude:: examples/extending-phpunit/MoneyExporter.php
   :caption: A custom object exporter for Money objects
   :language: php

You can register a custom object exporter using the ``registerObjectExporter()`` method of the ``PHPUnit\Framework\TestCase`` class.
PHPUnit automatically unregisters custom object exporters after the test has finished.

.. literalinclude:: examples/extending-phpunit/MoneyExporterTest.php
   :caption: A test registering and using a custom object exporter
   :language: php

The assertion in the test shown above fails with the message shown below:

.. code-block::

    Failed asserting that an array contains Money (100 EUR).

Without the custom object exporter, the same failure would be described using the default representation:

.. code-block::

    Failed asserting that an array contains Money Object #519 (
        'amount' => 100,
        'currency' => 'EUR',
    ).

Custom object exporters are used wherever PHPUnit exports values: in the failure descriptions of constraints as well as in the diffs shown for comparison failures.


.. _extending-phpunit.customizing-test-method-invocation:

Customizing test method invocation
==================================

You can extend PHPUnit by overriding the ``TestCase::invokeTestMethod()`` method in a test case class.

The ``invokeTestMethod()`` method is responsible for invoking the test method. By default, it simply
calls the test method with the provided arguments:

.. code-block:: php
   :caption: Default implementation of invokeTestMethod()

    protected function invokeTestMethod(string $methodName, array $testArguments): mixed
    {
        return $this->{$methodName}(...$testArguments);
    }

Frameworks that require special execution contexts, such as asynchronous frameworks,
can override this method to wrap the test method invocation with their runtime scheduler.


.. _extending-phpunit.extending-the-test-runner:

Extending the Test Runner
=========================

You can extend PHPUnit by implementing and registering an extension.

Implementing an extension
-------------------------

A PHPUnit extension is a class that implements the ``PHPUnit\Runner\Extension\Extension`` interface.

The extension interface declares a ``bootstrap()`` method that accepts the PHPUnit configuration, the extension facade, and the extension parameter collection.

.. literalinclude:: examples/extending-phpunit/ExampleExtension.php
   :caption: An example extension registering an ExampleSubscriber and an ExampleTracer
   :language: php

The PHPUnit configuration is an instance of ``PHPUnit\TextUI\Configuration\Configuration`` and gives you access to the configuration of PHPUnit after merging configuration options from defaults, the XML configuration file, and command-line options.

You can inspect the configuration object to adjust the behavior of your extension. For example, you may want to extend PHPUnit with an extension that renders output on the console. If that is the case, you may be interested to know whether a user of PHPUnit wants to use colors or prefers a monochrome output.

The parameter collection is an instance of ``PHPUnit\Runner\Extension\ParameterCollection`` and gives you access to extension parameters a user has provided via PHPUnit's XML configuration file. You can use the parameter collection to allow users of the extension to configure the behavior of your extension.

.. Note::

  You must verify and process the values from the parameter collection yourself. PHPUnit has no functionality for verifying or casting the values from the parameter collection to other types.

The extension facade is an instance of ``PHPUnit\Runner\Extension\Facade`` and allows you to register event subscribers and event tracers using the methods ``registerSubscribers()``, ``registerSubscriber()``, and ``registerTracer()``.

The extension facade also provides the following methods for test runner extensions to indicate to the test runner that they intend to replace default functionality or require certain functionality to be activated:

The ``replacesProgressOutput()`` method can be used to disable the test runner's default progress output while it runs the tests.

The ``replacesResultOutput()`` method can be used to disable the test runner's default result output after it finished running the tests.

The ``replacesOutput()`` method combines the effects of ``replacesProgressOutput()`` and ``replacesResultOutput()`` (see above).

The ``requiresCodeCoverageCollection()`` method can be used to activate the collection of code coverage information.

The ``requiresExportOfObjects()`` method can be used to activate the export of objects for events such as ``Test\AssertionSucceeded`` and ``Test\AssertionFailed``, for example.

Implementing an event subscriber
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

An event subscriber is a class that implements an event subscriber interface.

An event subscriber interface declares a single ``notify()`` method that accepts an instance of the corresponding event class.

.. literalinclude:: examples/extending-phpunit/ExampleSubscriber.php
   :caption: An ExampleSubscriber printing a message when PHPUnit emits the ExecutionFinished event
   :language: php

After registering an event subscriber with the extension facade, PHPUnit will notify the event subscriber when emitting an event of the corresponding event class.

.. Note::

  You can not create an event subscriber that implements more than one event subscriber interface at a time.

  If you want to subscribe to more than one event, you need to implement at least one event subscriber for each event you are interested in.

Implementing an event tracer
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

An event tracer is a class that implements the ``PHPUnit\Event\Tracer\Tracer`` interface.

The tracer interface declares a single ``trace()`` method that accepts an event.

.. literalinclude:: examples/extending-phpunit/ExampleTracer.php
   :caption: An ExampleTracer receiving all events
   :language: php

After registering an event tracer with the extension facade, PHPUnit will notify the tracer of every event.

.. Hint::

  Are you unsure whether you should implement an event tracer or multiple event subscribers?

  If you are interested in every event that PHPUnit emits during the execution of the CLI application, you probably want to implement and register an event tracer.

  If you are interested in selected events that PHPUnit emits during the execution of the CLI application, you probably want to implement and register one or more event subscribers.

Understanding events
^^^^^^^^^^^^^^^^^^^^

An event is a class that implements the ``PHPUnit\Event\Event`` interface.

The ``PHPUnit\Event\Event`` interface declares a ``telemetryInfo()`` method that gives you access to telemetry information and an ``asString()`` method that returns a string representation of the event.

Each event may implement additional methods that provide access to information available when PHPUnit registers and emits the event.

You can consume, inspect, and process these events in event subscribers or tracers.

You can find a list of all events PHPUnit currently emits in the :ref:`appendix <appendixes.events>`.

.. Note::

  PHPUnit currently does not support registering custom events.

.. _extending-phpunit.extending-the-test-runner.a-complete-example-custom-printer:

A complete example: custom printer
----------------------------------

PHPUnit's default output shows a progress bar of dots and letters while tests run, followed by a detailed result summary.
An extension can suppress that output entirely and substitute its own by calling ``replaceOutput()`` on the extension facade during bootstrap.
This section walks through a complete example to explain every piece that is needed.

.. _extending-phpunit.extending-the-test-runner.a-complete-example-custom-printerwhat-we-are-building:

What we are building
^^^^^^^^^^^^^^^^^^^^

The example extension prints one line per test in the following format::

    ExampleTest::testPasses ... passed (0.001s)
    ExampleTest::testFails ... failed (0.002s)
    ExampleTest::testIsSkipped ... skipped (0.001s)
    ExampleTest::testIsIncomplete ... incomplete (0.001s)

It is intentionally minimal so that the focus stays on the extension infrastructure rather than on formatting details.

.. _extending-phpunit.extending-the-test-runner.a-complete-example-custom-printer.structure:

Structure of the extension
^^^^^^^^^^^^^^^^^^^^^^^^^^

The extension is made up of four kinds of objects:

* **Extension** — the entry point that PHPUnit bootstraps
* **Printer** — contains the output logic, writing to ``STDOUT``
* **Subscribers** — one per test event, each forwarding the relevant data to
  the ``Printer``
* **Configuration** — the ``phpunit.xml`` file that registers the extension

.. _extending-phpunit.extending-the-test-runner.a-complete-example-custom-printer.extension:

Step 1: Implement the extension entry point
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Every PHPUnit extension must contain a class that implements ``PHPUnit\Runner\Extension\Extension``:

.. code-block:: php

    <?php declare(strict_types=1);
    namespace PHPUnit\ExtensionExample\Printer;

    use PHPUnit\Runner\Extension\Extension as ExtensionInterface;
    use PHPUnit\Runner\Extension\Facade;
    use PHPUnit\Runner\Extension\ParameterCollection;
    use PHPUnit\TextUI\Configuration\Configuration;

    final class Extension implements ExtensionInterface
    {
        public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
        {
            $facade->replaceOutput();

            $printer = new Printer;

            $facade->registerSubscribers(
                new Subscriber\TestPreparationStartedSubscriber($printer),
                new Subscriber\TestPassedSubscriber($printer),
                new Subscriber\TestFailedSubscriber($printer),
                new Subscriber\TestErroredSubscriber($printer),
                new Subscriber\TestSkippedSubscriber($printer),
                new Subscriber\TestMarkedIncompleteSubscriber($printer),
                new Subscriber\TestFinishedSubscriber($printer),
            );
        }
    }

The interface requires a single method, ``bootstrap()``, which PHPUnit calls once after it has loaded all extensions but before it runs any tests.
The method receives three arguments:

``$configuration``
    The fully resolved ``Configuration`` object that reflects the settings from ``phpunit.xml`` and the command line.
    The extension can inspect it to adapt its behaviour, but it is read-only.

``$facade``
    The ``Facade`` object through which the extension interacts with PHPUnit.
    Calling ``replaceOutput()`` on it tells PHPUnit to suppress its own progress and result output so the extension can produce its own.
    ``registerSubscribers()`` connects the extension's subscriber objects to PHPUnit's event system.

``$parameters``
    An immutable key/value map populated from the ``<parameter>`` child elements that can appear inside the ``<bootstrap>`` element in ``phpunit.xml``.
    The example extension does not use parameters.

.. note::

    Calling ``$facade->replaceOutput()`` suppresses both the progress output and the result output that PHPUnit would otherwise print.
    If you only want to replace one of the two, call ``$facade->replaceProgressOutput()`` or ``$facade->replaceResultOutput()`` instead.

.. _extending-phpunit.extending-the-test-runner.a-complete-example-custom-printer.printer:

Step 2: Implement the printer
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The ``Printer`` class is responsible for all output from our extension.
It is a plain PHP class with no PHPUnit interface requirements; subscribers call its methods directly:

.. code-block:: php

    <?php declare(strict_types=1);
    namespace PHPUnit\ExtensionExample\Printer;

    use function fwrite;
    use function sprintf;
    use PHPUnit\Event\Telemetry\HRTime;

    final class Printer
    {
        private ?HRTime $startTime;
        private string $outcome;

        public function testPreparationStarted(string $testId, HRTime $startTime): void
        {
            $this->startTime = $startTime;
            $this->outcome   = 'unknown';

            fwrite(
                STDOUT,
                sprintf(
                    '%s ... ',
                    $testId,
                ),
            );
        }

        public function testOutcome(string $outcome): void
        {
            $this->outcome = $outcome;
        }

        public function testFinished(HRTime $endTime): void
        {
            fwrite(
                STDOUT,
                sprintf(
                    '%s (%.3fs)' . PHP_EOL,
                    $this->outcome,
                    $endTime->duration($this->startTime)->asFloat(),
                ),
            );

            $this->startTime = null;
            $this->outcome   = 'unknown';
        }
    }

The ``Printer`` maintains two pieces of state between the start and the end of each test:

* ``$startTime`` — an ``HRTime`` instance captured when the test begins, used to compute the elapsed time when the test finishes.
* ``$outcome`` — a string set by whichever outcome subscriber fires (``passed``, ``failed``, ``errored``, ``skipped``, or ``incomplete``).

When ``testPreparationStarted()`` is called, the printer writes the test identifier followed by `` ... `` and leaves the cursor on the same line.
When ``testFinished()`` is called, it appends the outcome and elapsed time and moves to the next line.  Between those two calls, exactly one outcome method will have been called to record the result.

``HRTime::duration()`` returns a ``Duration`` object; ``Duration::asFloat()`` converts it to a number of seconds as a ``float``, which ``sprintf`` formats to three decimal places.

.. _extending-phpunit.extending-the-test-runner.a-complete-example-custom-printer.subscribers:

Step 3: Implement the subscribers
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

A subscriber is a class that implements one of PHPUnit's event subscriber interfaces.
Each interface corresponds to one event and has a single method, ``notify()``, that PHPUnit calls when the event fires.
The subscriber receives the event object and can read data from it.

Every subscriber in this extension follows the same pattern: it holds a reference to the shared ``Printer`` instance and delegates to one of the printer's methods.

.. _extending-phpunit.extending-the-test-runner.a-complete-example-custom-printer.subscribers.preparation:

TestPreparationStartedSubscriber
"""""""""""""""""""""""""""""""""

.. code-block:: php

    <?php declare(strict_types=1);
    namespace PHPUnit\ExtensionExample\Printer\Subscriber;

    use PHPUnit\Event\Test\PreparationStarted;
    use PHPUnit\Event\Test\PreparationStartedSubscriber;
    use PHPUnit\ExtensionExample\Printer\Printer;

    final readonly class TestPreparationStartedSubscriber implements PreparationStartedSubscriber
    {
        public function __construct(private Printer $printer) {}

        public function notify(PreparationStarted $event): void
        {
            $this->printer->testPreparationStarted(
                $event->test()->id(),
                $event->telemetryInfo()->time()
            );
        }
    }

This subscriber fires just before PHPUnit sets up a test (before ``setUp()`` runs).
It reads the test identifier from ``$event->test()->id()`` — which returns a string such as ``ExampleTest::testPasses`` — and the current high-resolution time from ``$event->telemetryInfo()->time()``.

.. _extending-phpunit.extending-the-test-runner.a-complete-example-custom-printer.subscribers.outcome:

Outcome Subscribers
"""""""""""""""""""

Four outcome events can fire after a test completes, one for each possible result.
Each is handled by a dedicated subscriber that calls ``Printer::testOutcome()`` with the corresponding label.

**TestPassedSubscriber** — fires when all assertions pass:

.. code-block:: php

    <?php declare(strict_types=1);
    namespace PHPUnit\ExtensionExample\Printer\Subscriber;

    use PHPUnit\Event\Test\Passed;
    use PHPUnit\Event\Test\PassedSubscriber;
    use PHPUnit\ExtensionExample\Printer\Printer;

    final readonly class TestPassedSubscriber implements PassedSubscriber
    {
        public function __construct(private Printer $printer) {}

        public function notify(Passed $event): void
        {
            $this->printer->testOutcome('passed');
        }
    }

**TestFailedSubscriber** — fires when an assertion fails:

.. code-block:: php

    <?php declare(strict_types=1);
    namespace PHPUnit\ExtensionExample\Printer\Subscriber;

    use PHPUnit\Event\Test\Failed;
    use PHPUnit\Event\Test\FailedSubscriber;
    use PHPUnit\ExtensionExample\Printer\Printer;

    final readonly class TestFailedSubscriber implements FailedSubscriber
    {
        public function __construct(private Printer $printer) {}

        public function notify(Failed $event): void
        {
            $this->printer->testOutcome('failed');
        }
    }

**TestErroredSubscriber** — fires when the test throws an unexpected exception or a PHP error that is not an assertion failure:

.. code-block:: php

    <?php declare(strict_types=1);
    namespace PHPUnit\ExtensionExample\Printer\Subscriber;

    use PHPUnit\Event\Test\Errored;
    use PHPUnit\Event\Test\ErroredSubscriber;
    use PHPUnit\ExtensionExample\Printer\Printer;

    final readonly class TestErroredSubscriber implements ErroredSubscriber
    {
        public function __construct(private Printer $printer) {}

        public function notify(Errored $event): void
        {
            $this->printer->testOutcome('errored');
        }
    }

**TestSkippedSubscriber** — fires when the test calls ``$this->markTestSkipped()``:

.. code-block:: php

    <?php declare(strict_types=1);
    namespace PHPUnit\ExtensionExample\Printer\Subscriber;

    use PHPUnit\Event\Test\Skipped;
    use PHPUnit\Event\Test\SkippedSubscriber;
    use PHPUnit\ExtensionExample\Printer\Printer;

    final readonly class TestSkippedSubscriber implements SkippedSubscriber
    {
        public function __construct(private Printer $printer) {}

        public function notify(Skipped $event): void
        {
            $this->printer->testOutcome('skipped');
        }
    }

**TestMarkedIncompleteSubscriber** — fires when the test calls ``$this->markTestIncomplete()``:

.. code-block:: php

    <?php declare(strict_types=1);
    namespace PHPUnit\ExtensionExample\Printer\Subscriber;

    use PHPUnit\Event\Test\MarkedIncomplete;
    use PHPUnit\Event\Test\MarkedIncompleteSubscriber;
    use PHPUnit\ExtensionExample\Printer\Printer;

    final readonly class TestMarkedIncompleteSubscriber implements MarkedIncompleteSubscriber
    {
        public function __construct(private Printer $printer) {}

        public function notify(MarkedIncomplete $event): void
        {
            $this->printer->testOutcome('incomplete');
        }
    }

.. _extending-phpunit.extending-the-test-runner.a-complete-example-custom-printer.subscribers.finished:

TestFinishedSubscriber
""""""""""""""""""""""

.. code-block:: php

    <?php declare(strict_types=1);
    namespace PHPUnit\ExtensionExample\Printer\Subscriber;

    use PHPUnit\Event\Test\Finished;
    use PHPUnit\Event\Test\FinishedSubscriber;
    use PHPUnit\ExtensionExample\Printer\Printer;

    final readonly class TestFinishedSubscriber implements FinishedSubscriber
    {
        public function __construct(private Printer $printer) {}

        public function notify(Finished $event): void
        {
            $this->printer->testFinished(
                $event->telemetryInfo()->time(),
            );
        }
    }

This subscriber fires after ``tearDown()`` has completed.
It passes the current high-resolution time to ``Printer::testFinished()``, which uses it together with the start time saved earlier to compute the test duration.

.. note::

    The sequence of events for a single test is always:

    1. ``Test\PreparationStarted``
    2. Exactly one outcome event: ``Test\Passed``, ``Test\Failed``, ``Test\Errored``, ``Test\Skipped``, or ``Test\MarkedIncomplete``
    3. ``Test\Finished``

    Because outcome events fire before ``Test\Finished``, the ``Printer`` always knows the outcome when it writes the completed line.

.. _extending-phpunit.extending-the-test-runner.a-complete-example-custom-printer.configuration:

Step 4: Register the Extension in phpunit.xml
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

PHPUnit discovers extensions through the ``<extensions>`` element in ``phpunit.xml``:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:noNamespaceSchemaLocation="../phpunit.xsd"
             bootstrap="autoload.php">
        <extensions>
            <bootstrap class="PHPUnit\ExtensionExample\Printer\Extension"/>
        </extensions>

        <testsuites>
            <testsuite name="default">
                <directory>tests</directory>
            </testsuite>
        </testsuites>
    </phpunit>

The ``class`` attribute of ``<bootstrap>`` must be the fully qualified class name of the class that implements ``PHPUnit\Runner\Extension\Extension``.
PHPUnit instantiates the class and calls its ``bootstrap()`` method.

Optional parameters can be passed to an extension by adding ``<parameter>`` elements inside ``<bootstrap>``:

.. code-block:: xml

    <bootstrap class="PHPUnit\ExtensionExample\Printer\Extension">
        <parameter name="colorize" value="true"/>
    </bootstrap>

The extension can then read the parameter value in ``bootstrap()`` using ``$parameters->get('colorize')``.
Always call ``$parameters->has()`` first to check whether the parameter was supplied.

.. _extending-phpunit.extending-the-test-runner.a-complete-example-custom-printer.autoloading:

How it all fits together
^^^^^^^^^^^^^^^^^^^^^^^^

When PHPUnit runs with the example extension active, the following sequence of events takes place for each test:

1. PHPUnit emits ``Test\PreparationStarted``.
   ``TestPreparationStartedSubscriber`` calls ``Printer::testPreparationStarted()``, which saves the start time and writes ``ExampleTest::testPasses ... `` to ``STDOUT`` without a trailing newline.

2. The test method executes.
   Depending on what the method does, PHPUnit emits one outcome event.
   The corresponding subscriber calls ``Printer::testOutcome()`` to record the result label.

3. PHPUnit emits ``Test\Finished``.
``TestFinishedSubscriber`` calls ``Printer::testFinished()``, which appends the label and the elapsed time to the line already started in step 1, then resets the printer's state.

Because ``$facade->replaceOutput()`` was called during bootstrap, PHPUnit prints nothing of its own.
The extension has full control over what appears on the terminal.

Sharing an extension
--------------------

You can share a PHPUnit extension as a PHAR or a Composer package.

Sharing an extension as a PHAR
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

When users of your extension prefer to install PHPUnit as a PHAR, it is best to make your extension also available as a PHAR.

To make your extension loadable as a PHAR, you need to include a `PHAR Manifest <https://github.com/phar-io/manifest>`_.

.. literalinclude:: examples/extending-phpunit/manifest.xml
   :caption: An example manifest.xml
   :language: xml

Sharing an extension as a Composer package
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

When users of your extension prefer to install PHPUnit as a Composer package, it is best to make your extension available as a Composer package.

Registering an extension
------------------------

You can register one or more PHPUnit extensions from PHARs or from Composer package using the :ref:`extensions <appendixes.xml-configuration-file.extensions>`, :ref:`bootstrap <appendixes.xml-configuration-file.extensions.bootstrap>`, and :ref:`parameters <appendixes.xml-configuration-file.extensions.extension.arguments>` elements of the :ref:`PHPUnit XML configuration file <appendixes.xml-configuration-file>`.

Registering an extension from a PHAR
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

When you install PHPUnit as a PHAR, it is best to load extensions from a PHAR.

You can use the :ref:`extensionsDirectory <appendixes.xml-configuration-file.phpunit.extensionsDirectory>` attribute of the :ref:`phpunit <appendixes.xml-configuration-file.phpunit>` element to configure the directory from which PHPUnit should load extensions as a PHAR.

.. literalinclude:: examples/extending-phpunit/phpunit-phar.xml
   :caption: An XML configuration registering an ExampleExtension with parameters, loaded from an extensions directory
   :language: xml

Registering an extension from a Composer package
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

When you install PHPUnit as a Composer package, it is best to load extensions from Composer packages.

You do not need to configure the :ref:`extensionsDirectory <appendixes.xml-configuration-file.phpunit.extensionsDirectory>` attribute, as extensions from Composer packages will be available through the autoloading mechanism of Composer.

.. literalinclude:: examples/extending-phpunit/phpunit-composer.xml
   :caption: An XML configuration registering an ExampleExtension with parameters
   :language: xml

.. _extending-phpunit.event-system.event-system.debugging-phpunit:

Debugging PHPUnit
-----------------

The test runner's ``--log-events-text`` CLI option can be used to write a plain text representation
for each event to a stream. In the example shown below, we use ``--no-output`` to disable both the
default progress output as well as the default result output. Then we use ``--log-events-text php://stdout``
to write event information to standard output:

.. code-block::
    :caption: Output of "phpunit --no-output --log-events-text php://stdout" command
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
    :caption: Output of "phpunit --no-output --log-events-verbose-text php://stdout" command
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
