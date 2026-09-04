

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

``PHPUnit\Event\TestRunner\ExtensionLoadedFromPhar``

    The test runner loaded an extension from a PHP Archive (PHAR)

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

``PHPUnit\Event\TestRunner\DeprecationTriggered``

    PHPUnit itself reports a deprecation about the test runner. This event will be renamed to ``PHPUnit\Event\TestRunner\PhpunitDeprecationTriggered`` in PHPUnit 14.

``PHPUnit\Event\TestRunner\NoticeTriggered``

    PHPUnit itself reports a notice about the test runner. This event will be renamed to ``PHPUnit\Event\TestRunner\PhpunitNoticeTriggered`` in PHPUnit 14.

``PHPUnit\Event\TestRunner\WarningTriggered``

    PHPUnit itself reports a warning about the test runner. This event will be renamed to ``PHPUnit\Event\TestRunner\PhpunitWarningTriggered`` in PHPUnit 14.

``PHPUnit\Event\TestRunner\PhpDeprecationTriggered``

    A PHP deprecation (``E_DEPRECATED``) was triggered outside of a test

``PHPUnit\Event\TestRunner\Issue\DeprecationTriggered``

    A deprecation (``E_USER_DEPRECATED``) was triggered outside of a test. This event will be moved to ``PHPUnit\Event\TestRunner\DeprecationTriggered`` in PHPUnit 14.

``PHPUnit\Event\TestRunner\ErrorTriggered``

    An error was triggered outside of a test

``PHPUnit\Event\TestRunner\PhpNoticeTriggered``

    A PHP notice (``E_NOTICE``) was triggered outside of a test

``PHPUnit\Event\TestRunner\Issue\NoticeTriggered``

    A notice (``E_USER_NOTICE``) was triggered outside of a test. This event will be moved to ``PHPUnit\Event\TestRunner\NoticeTriggered`` in PHPUnit 14.

``PHPUnit\Event\TestRunner\PhpWarningTriggered``

    A PHP warning (``E_WARNING``) was triggered outside of a test

``PHPUnit\Event\TestRunner\Issue\WarningTriggered``

    A warning (``E_USER_WARNING``) was triggered outside of a test. This event will be moved to ``PHPUnit\Event\TestRunner\WarningTriggered`` in PHPUnit 14.

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

    The preparation of a test for execution was started

``PHPUnit\Event\Test\PreparationErrored``

    The preparation of a test errored

``PHPUnit\Event\Test\PreparationFailed``

    The preparation of a test failed

``PHPUnit\Event\Test\Prepared``

    The test was successfully prepared for execution

``PHPUnit\Event\Test\DataProviderMethodCalled``

    A data provider method was called for a test method

``PHPUnit\Event\Test\DataProviderMethodFinished``

    All data provider methods were called for a test method

``PHPUnit\Event\Test\BeforeFirstTestMethodCalled``

    A "before first test" method was called for a test case class

``PHPUnit\Event\Test\BeforeFirstTestMethodErrored``

    A "before first test" method errored for a test case class

``PHPUnit\Event\Test\BeforeFirstTestMethodFailed``

    A "before first test" method failed for a test case class

``PHPUnit\Event\Test\BeforeFirstTestMethodFinished``

    All "before first test" methods were called for a test case class

``PHPUnit\Event\Test\BeforeTestMethodCalled``

    A "before test" method was called for a test method

``PHPUnit\Event\Test\BeforeTestMethodErrored``

    A "before test" method errored for a test method

``PHPUnit\Event\Test\BeforeTestMethodFailed``

    A "before test" method failed for a test method

``PHPUnit\Event\Test\BeforeTestMethodFinished``

    All "before test" methods were called for a test method

``PHPUnit\Event\Test\PreConditionCalled``

    A "precondition" method was called for a test method

``PHPUnit\Event\Test\PreConditionErrored``

    A "precondition" method errored for a test method

``PHPUnit\Event\Test\PreConditionFailed``

    A "precondition" method failed for a test method

``PHPUnit\Event\Test\PreConditionFinished``

    All "precondition" methods were called for a test method

``PHPUnit\Event\Test\TestPrepared``

    A test was prepared for execution

``PHPUnit\Event\Test\ComparatorRegistered``

    A test registered a custom ``Comparator`` for ``assertEquals()``

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

    The execution of a test method finished

``PHPUnit\Event\Test\PostConditionCalled``

    A "postcondition" method was called for a test method

``PHPUnit\Event\Test\PostConditionErrored``

    A "postcondition" method errored for a test method

``PHPUnit\Event\Test\PostConditionFailed``

    A "postcondition" method failed for a test method

``PHPUnit\Event\Test\PostConditionFinished``

    All "postcondition" methods were called for a test method

``PHPUnit\Event\Test\AfterTestMethodCalled``

    An "after test" method was called for a test method

``PHPUnit\Event\Test\AfterTestMethodErrored``

    An "after test" method errored for a test method

``PHPUnit\Event\Test\AfterTestMethodFailed``

    An "after test" method failed for a test method

``PHPUnit\Event\Test\AfterTestMethodFinished``

    All "after test" methods were called for a test method

``PHPUnit\Event\Test\AfterLastTestMethodCalled``

    An "after last test" method was called for a test case class

``PHPUnit\Event\Test\AfterLastTestMethodErrored``

    An "after last test" method errored for a test case class

``PHPUnit\Event\Test\AfterLastTestMethodFailed``

    An "after last test" method failed for a test case class

``PHPUnit\Event\Test\AfterLastTestMethodFinished``

    All "after last test" methods were called for a test case class

``PHPUnit\Event\Test\AdditionalInformationProvided``

    A test method provided additional information
