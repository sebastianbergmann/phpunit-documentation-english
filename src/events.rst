

.. _appendixes.events:

******
Events
******

Application
===========

``PHPUnit\Event\Application\Started``

    The PHPUnit CLI application was started

``PHPUnit\Event\Application\Finished``

    The PHPUnit CLI application has finished

TestRunner
===========

``PHPUnit\Event\TestRunner\EventFacadeSealed``

    The event facade was sealed; no more event subscribers can be registered

``PHPUnit\Event\TestRunner\Configured``

    The test runner was configured

``PHPUnit\Event\TestRunner\BootstrapFinished``

    The test runner finished executing the configured bootstrap script

``PHPUnit\Event\TestRunner\ExtensionBootstrapped``

    The test runner bootstrapped an extension

``PHPUnit\Event\TestRunner\Started``

    The test runner started running

``PHPUnit\Event\TestRunner\ExecutionStarted``

    The test runner started executing tests

``PHPUnit\Event\TestRunner\ChildProcessStarted``

    The test runner started a PHP child process

``PHPUnit\Event\TestRunner\ChildProcessErrored``

    A PHP child process started by the test runner errored

``PHPUnit\Event\TestRunner\ChildProcessFinished``

    A PHP child process started by the test runner finished

The ``ChildProcessStarted``, ``ChildProcessErrored``, and ``ChildProcessFinished`` events
carry the reason why a child process was used. Their ``reason()`` method returns a case of
the ``PHPUnit\Event\TestRunner\ChildProcessReason`` enumeration:

``ChildProcessReason::TestRequiringProcessIsolation``

    The child process runs a test that is run in a separate process

``ChildProcessReason::PhptTest``

    The child process runs the ``FILE`` section of a PHPT test

``ChildProcessReason::PhptSkipIfSection``

    The child process runs the ``SKIPIF`` section of a PHPT test

``ChildProcessReason::PhptCleanSection``

    The child process runs the ``CLEAN`` section of a PHPT test

``PHPUnit\Event\TestRunner\PhpunitDeprecationTriggered``

    PHPUnit itself reports a deprecation about the test runner

``PHPUnit\Event\TestRunner\PhpunitNoticeTriggered``

    PHPUnit itself reports a notice about the test runner

``PHPUnit\Event\TestRunner\PhpunitWarningTriggered``

    PHPUnit itself reports a warning about the test runner

``PHPUnit\Event\TestRunner\PhpDeprecationTriggered``

    A PHP deprecation (``E_DEPRECATED``) was triggered outside of a test

``PHPUnit\Event\TestRunner\DeprecationTriggered``

    A deprecation (``E_USER_DEPRECATED``) was triggered outside of a test

``PHPUnit\Event\TestRunner\ErrorTriggered``

    An error was triggered outside of a test

``PHPUnit\Event\TestRunner\PhpNoticeTriggered``

    A PHP notice (``E_NOTICE``) was triggered outside of a test

``PHPUnit\Event\TestRunner\NoticeTriggered``

    A notice (``E_USER_NOTICE``) was triggered outside of a test

``PHPUnit\Event\TestRunner\PhpWarningTriggered``

    A PHP warning (``E_WARNING``) was triggered outside of a test

``PHPUnit\Event\TestRunner\WarningTriggered``

    A warning (``E_USER_WARNING``) was triggered outside of a test

``PHPUnit\Event\TestRunner\TimeLimitExceeded``

    The time limit for the test run configured with ``--timeout`` was exceeded; ``timeLimit()``
    returns the limit in seconds. No further tests are started after this event

``PHPUnit\Event\TestRunner\ExecutionAborted``

    The test runner aborted the execution of tests

``PHPUnit\Event\TestRunner\ExecutionFinished``

    The test runner finished executing tests

``PHPUnit\Event\TestRunner\StaticAnalysisForCodeCoverageStarted``

    The static code analysis required for code coverage reporting started

``PHPUnit\Event\TestRunner\StaticAnalysisForCodeCoverageFinished``

    The static code analysis required for code coverage reporting finished

``PHPUnit\Event\TestRunner\GarbageCollectionDisabled``

    The test runner disabled PHP's garbage collector

``PHPUnit\Event\TestRunner\GarbageCollectionEnabled``

    The test runner enabled PHP's garbage collector

``PHPUnit\Event\TestRunner\GarbageCollectionTriggered``

    The test runner triggered PHP's garbage collector

``PHPUnit\Event\TestRunner\Finished``

    The test runner finished running

TestSuite
=========

``PHPUnit\Event\TestSuite\Loaded``

    The test suite was loaded

``PHPUnit\Event\TestSuite\Filtered``

    The test suite was filtered

``PHPUnit\Event\TestSuite\Sorted``

    The test suite was sorted; ``pipeline()`` returns the names of the reordering stages
    that were applied, in the order in which they were applied

``PHPUnit\Event\TestRunner\ExecutionStarted``

    The test runner started executing tests

``PHPUnit\Event\TestSuite\Skipped``

    The execution of a test suite was skipped

``PHPUnit\Event\TestSuite\Started``

    The execution of a test suite was started

``PHPUnit\Event\TestSuite\Finished``

    The execution of a test suite has finished

Test
=========

``PHPUnit\Event\Test\PreparationStarted``

    The preparation of a test for execution was started. This is the first event that is emitted for a test

``PHPUnit\Event\Test\PreparationErrored``

    The preparation of a test errored

``PHPUnit\Event\Test\PreparationFailed``

    The preparation of a test failed

``PHPUnit\Event\Test\Prepared``

    The test was successfully prepared for execution

``PHPUnit\Event\Test\CustomTestMethodInvocationUsed``

    A test method was invoked using a custom implementation of ``TestCase::invokeTestMethod()``

``PHPUnit\Event\Test\DataProviderMethodCalled``

    A data provider method was called for a test method

``PHPUnit\Event\Test\DataProviderMethodFinished``

    All data provider methods were called for a test method

``PHPUnit\Event\Test\BeforeFirstTestMethodCalled``

    A "before first test" method is about to be called for a test case class

``PHPUnit\Event\Test\BeforeFirstTestMethodErrored``

    A "before first test" method errored for a test case class

``PHPUnit\Event\Test\BeforeFirstTestMethodFailed``

    A "before first test" method failed for a test case class

``PHPUnit\Event\Test\BeforeFirstTestMethodFinished``

    All "before first test" methods were called for a test case class

``PHPUnit\Event\Test\BeforeTestMethodCalled``

    A "before test" method is about to be called for a test method

``PHPUnit\Event\Test\BeforeTestMethodErrored``

    A "before test" method errored for a test method

``PHPUnit\Event\Test\BeforeTestMethodFailed``

    A "before test" method failed for a test method

``PHPUnit\Event\Test\BeforeTestMethodFinished``

    All "before test" methods were called for a test method

``PHPUnit\Event\Test\PreConditionCalled``

    A "precondition" method is about to be called for a test method

``PHPUnit\Event\Test\PreConditionErrored``

    A "precondition" method errored for a test method

``PHPUnit\Event\Test\PreConditionFailed``

    A "precondition" method failed for a test method

``PHPUnit\Event\Test\PreConditionFinished``

    All "precondition" methods were called for a test method

``PHPUnit\Event\Test\ComparatorRegistered``

    A test registered a custom ``Comparator`` for ``assertEquals()``

``PHPUnit\Event\Test\MockObjectCreated``

    A test created a mock object

``PHPUnit\Event\Test\MockObjectForIntersectionOfInterfacesCreated``

    A test created a mock object for an intersection of interfaces

``PHPUnit\Event\Test\PartialMockObjectCreated``

    A test created a partial mock object

``PHPUnit\Event\Test\TestStubCreated``

    A test created a test stub

``PHPUnit\Event\Test\TestStubForIntersectionOfInterfacesCreated``

    A test created a test stub for an intersection of interfaces

``PHPUnit\Event\Test\Errored``

    A test errored

``PHPUnit\Event\Test\Failed``

    A test failed

``PHPUnit\Event\Test\AttemptErrored``

    An attempt of a :ref:`retried <flaky-tests.retrying-tests>` test errored and another attempt is made. The events of such an attempt are not emitted, this event is emitted instead of ``PHPUnit\Event\Test\Errored``

``PHPUnit\Event\Test\AttemptFailed``

    An attempt of a :ref:`retried <flaky-tests.retrying-tests>` test failed and another attempt is made. The events of such an attempt are not emitted, this event is emitted instead of ``PHPUnit\Event\Test\Failed``

``PHPUnit\Event\Test\Passed``

    A test passed

``PHPUnit\Event\Test\PrintedUnexpectedOutput``

    A test printed unexpected output

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

``PHPUnit\Event\Test\PhpunitNoticeTriggered``

    A test triggered a PHPUnit notice

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

    The test has finished. This is the last event that is emitted for a test, and it is emitted for every test for which ``PHPUnit\Event\Test\PreparationStarted`` was emitted

``PHPUnit\Event\Test\PostConditionCalled``

    A "postcondition" method is about to be called for a test method

``PHPUnit\Event\Test\PostConditionErrored``

    A "postcondition" method errored for a test method

``PHPUnit\Event\Test\PostConditionFailed``

    A "postcondition" method failed for a test method

``PHPUnit\Event\Test\PostConditionFinished``

    All "postcondition" methods were called for a test method

``PHPUnit\Event\Test\AfterTestMethodCalled``

    An "after test" method is about to be called for a test method

``PHPUnit\Event\Test\AfterTestMethodErrored``

    An "after test" method errored for a test method

``PHPUnit\Event\Test\AfterTestMethodFailed``

    An "after test" method failed for a test method

``PHPUnit\Event\Test\AfterTestMethodFinished``

    All "after test" methods were called for a test method

``PHPUnit\Event\Test\AfterLastTestMethodCalled``

    An "after last test" method is about to be called for a test case class

``PHPUnit\Event\Test\AfterLastTestMethodErrored``

    An "after last test" method errored for a test case class

``PHPUnit\Event\Test\AfterLastTestMethodFailed``

    An "after last test" method failed for a test case class

``PHPUnit\Event\Test\AfterLastTestMethodFinished``

    All "after last test" methods were called for a test case class

``PHPUnit\Event\Test\AdditionalInformationProvided``

    A test method provided additional information using the ``provideAdditionalInformation()`` method of ``PHPUnit\Framework\TestCase``

.. _appendixes.events.guarantees:

Guarantees
==========

PHPUnit guarantees that tests, test suites, and hook methods are bracketed by events. An event subscriber that needs to know when one of them begins and ends can rely on the following:

Tests
    Every ``PHPUnit\Event\Test\PreparationStarted`` event is followed by exactly one ``PHPUnit\Event\Test\Finished`` event for the same test, and all other events for that test are emitted between these two events. This also holds for a test whose preparation errored or failed, for a test that was skipped, and for a test that is reported as skipped or errored without being run, for instance because a test it depends on did not pass.

    ``PHPUnit\Event\Test\Prepared`` is only emitted when the preparation of a test succeeded. A subscriber that needs to know whether a test was prepared can rely on this event, but it does not need it to find out when a test ends.

Test suites
    Every ``PHPUnit\Event\TestSuite\Started`` event is followed by exactly one ``PHPUnit\Event\TestSuite\Finished`` event for the same test suite. When a "before first test" method skips the tests of a test case class, ``PHPUnit\Event\TestSuite\Skipped`` is emitted before ``PHPUnit\Event\TestSuite\Finished``.

Hook methods
    The ``*Called`` event for a hook method, for instance ``PHPUnit\Event\Test\BeforeTestMethodCalled``, is emitted before the hook method is invoked. The events that are emitted while a hook method runs are bracketed by its ``*Called`` event and the next ``*Called``, ``*Errored``, ``*Failed``, or ``*Finished`` event of the same kind of hook method. The ``*Finished`` event, for instance ``PHPUnit\Event\Test\BeforeTestMethodFinished``, is emitted after all hook methods of that kind have been invoked.

Child processes
    ``PHPUnit\Event\TestRunner\ChildProcessStarted`` and ``PHPUnit\Event\TestRunner\ChildProcessFinished`` (or ``PHPUnit\Event\TestRunner\ChildProcessErrored``) for a test that is run in a separate process are emitted between the ``PHPUnit\Event\Test\PreparationStarted`` and ``PHPUnit\Event\Test\Finished`` events for that test.

This is, for instance, the sequence of events for a test that is skipped in its ``setUp()`` method. No ``PHPUnit\Event\Test\Prepared`` event is emitted because the preparation of the test did not succeed, but the test is finished nevertheless:

.. code-block::

    Test Preparation Started (ExampleTest::testOne)
    Before Test Method Called (ExampleTest::setUp)
    Before Test Method Finished:
    - ExampleTest::setUp
    Test Skipped (ExampleTest::testOne)
    Test Finished (ExampleTest::testOne)

When a "before first test" method errors or fails, the tests of the test case class are not run and no events are emitted for them.
