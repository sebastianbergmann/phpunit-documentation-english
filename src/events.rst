

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


``PHPUnit\Event\TestRunner\DeprecationTriggered``

    A deprecation in the test runner was triggered

``PHPUnit\Event\TestRunner\WarningTriggered``

    A warning in the test runner was triggered

``PHPUnit\Event\TestRunner\ExecutionFinished``

    The test runner finished executing tests

``PHPUnit\Event\TestRunner\Finished``

    The test runner finished running

TestSuite
=========

``PHPUnit\Event\TestSuite\Loaded``

    The test suite was loaded

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

``PHPUnit\Event\TestSuite\Finished``

    The execution of a test suite has finished

Test
=========

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
