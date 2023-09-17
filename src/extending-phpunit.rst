

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

You can register one or more PHPUnit extensions from PHARs or from Composer package using the :ref:`extensions <appendixes.configuration.extensions>`, :ref:`bootstrap <appendixes.configuration.extensions.bootstrap>`, and :ref:`parameters <appendixes.configuration.extensions.extension.arguments>` elements of the :ref:`PHPUnit XML configuration file <appendixes.configuration>`.

Registering an extension from a PHAR
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

When you install PHPUnit as a PHAR, it is best to load extensions from a PHAR.

You can use the :ref:`extensionsDirectory <appendixes.configuration.phpunit.extensionsDirectory>` attribute of the :ref:`phpunit <appendixes.configuration.phpunit>` element to configure the directory from which PHPUnit should load extensions as a PHAR.

.. literalinclude:: examples/extending-phpunit/phpunit-phar.xml
   :caption: An XML configuration registering an ExampleExtension with parameters, loaded from an extensions directory
   :language: xml

Registering an extension from a Composer package
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

When you install PHPUnit as a Composer package, it is best to load extensions from Composer packages.

You do not need to configure the :ref:`extensionsDirectory <appendixes.configuration.phpunit.extensionsDirectory>` attribute, as extensions from Composer packages will be available through the autoloading mechanism of Composer.

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
