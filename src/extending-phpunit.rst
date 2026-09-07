

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

The ``replaceProgressOutput()`` method can be used to disable the test runner's default progress output while it runs the tests.

The ``replaceResultOutput()`` method can be used to disable the test runner's default result output after it finished running the tests.

The ``replaceOutput()`` method combines the effects of ``replaceProgressOutput()`` and ``replaceResultOutput()`` (see above).

The ``requireCodeCoverageCollection()`` method can be used to activate the collection of code coverage information.

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
    PHPUnit Started (PHPUnit 12.5.34 using PHP 8.5.10 (cli) on Darwin)
    Test Runner Configured
    Event Facade Sealed
    Test Suite Loaded (2 tests)
    Test Runner Started
    Test Suite Sorted
    Test Runner Execution Started (2 tests)
    Test Suite Started (ExampleTest, 2 tests)
    Test Preparation Started (ExampleTest::testOne)
    Test Prepared (ExampleTest::testOne)
    Test Passed (ExampleTest::testOne)
    Test Finished (ExampleTest::testOne)
    Test Preparation Started (ExampleTest::testTwo)
    Test Prepared (ExampleTest::testTwo)
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
    [00:00:00.000009375 / 00:00:00.000009375] [26671776 bytes] PHPUnit Started (PHPUnit 12.5.34 using PHP 8.5.10 (cli) on Darwin)
    [00:00:00.000231875 / 00:00:00.000222500] [26671776 bytes] Test Runner Configured
    [00:00:00.000344292 / 00:00:00.000112417] [26671776 bytes] Event Facade Sealed
    [00:00:00.000605500 / 00:00:00.000261208] [26671776 bytes] Test Suite Loaded (2 tests)
    [00:00:00.000628875 / 00:00:00.000023375] [26671776 bytes] Test Runner Started
    [00:00:00.000643083 / 00:00:00.000014208] [26671776 bytes] Test Suite Sorted
    [00:00:00.000655000 / 00:00:00.000011917] [26671776 bytes] Test Runner Execution Started (2 tests)
    [00:00:00.000666750 / 00:00:00.000011750] [26671776 bytes] Test Suite Started (ExampleTest, 2 tests)
    [00:00:00.000716083 / 00:00:00.000049333] [26671776 bytes] Test Preparation Started (ExampleTest::testOne)
    [00:00:00.000746167 / 00:00:00.000030084] [26267176 bytes] Test Prepared (ExampleTest::testOne)
    [00:00:00.000933000 / 00:00:00.000186833] [26288088 bytes] Test Passed (ExampleTest::testOne)
    [00:00:00.000974000 / 00:00:00.000041000] [26288088 bytes] Test Finished (ExampleTest::testOne)
    [00:00:00.000991625 / 00:00:00.000017625] [26288088 bytes] Test Preparation Started (ExampleTest::testTwo)
    [00:00:00.001014792 / 00:00:00.000023167] [26267984 bytes] Test Prepared (ExampleTest::testTwo)
    [00:00:00.001171292 / 00:00:00.000156500] [26308432 bytes] Test Failed (ExampleTest::testTwo)
                                                               Failed asserting that two strings are identical.
    [00:00:00.001201083 / 00:00:00.000029791] [26308432 bytes] Test Finished (ExampleTest::testTwo)
    [00:00:00.001213375 / 00:00:00.000012292] [26308432 bytes] Test Suite Finished (ExampleTest, 2 tests)
    [00:00:00.001223625 / 00:00:00.000010250] [26308432 bytes] Test Runner Execution Finished
    [00:00:00.001231333 / 00:00:00.000007708] [26308432 bytes] Test Runner Finished
    [00:00:00.001253292 / 00:00:00.000021959] [26308432 bytes] PHPUnit Finished (Shell Exit Code: 1)

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
