

.. _risky-tests:

===========
Risky Tests
===========

PHPUnit can perform the additional checks documented below while it executes
the tests.

.. _risky-tests.useless-tests:

Useless Tests
#############

PHPUnit is by default strict about tests that do not test anything. This check
can be disabled by using the ``--dont-report-useless-tests``
option on the commandline or by setting
``beStrictAboutTestsThatDoNotTestAnything="false"`` in
PHPUnit's XML configuration file.

A test that does not perform an assertion will be marked as risky
when this check is enabled. Expectations on mock objects or annotations
such as @expectedException count as an assertion.

.. _risky-tests.unintentionally-covered-code:

Unintentionally Covered Code
############################

PHPUnit can be strict about unintentionally covered code. This check
can be enabled by using the ``--strict-coverage`` option on
the commandline or by setting
``beStrictAboutCoversAnnotation="true"`` in PHPUnit's
XML configuration file.

A test that is annotated with @covers and executes code that
is not listed using a @covers or @uses
annotation will be marked as risky when this check is enabled.

.. _risky-tests.output-during-test-execution:

Output During Test Execution
############################

PHPUnit can be strict about output during tests. This check can be enabled
by using the ``--disallow-test-output`` option on the
commandline or by setting
``beStrictAboutOutputDuringTests="true"`` in PHPUnit's XML
configuration file.

A test that emits output, for instance by invoking print in
either the test code or the tested code, will be marked as risky when this
check is enabled.

.. _risky-tests.test-execution-timeout:

Test Execution Timeout
######################

A time limit can be enforced for the execution of a test if the
``PHP_Invoker`` package is installed and the
``pcntl`` extension is available. The enforcing of this
time limit can be enabled by using the
``--enforce-time-limit`` option on the commandline or by
setting ``beStrictAboutTestSize="true"`` in PHPUnit's XML
configuration file.

A test annotated with ``@large`` will fail if it takes
longer than 60 seconds to execute. This timeout is configurable via the
``timeoutForLargeTests`` attribute in the XML
configuration file.

A test annotated with ``@medium`` will fail if it takes
longer than 10 seconds to execute. This timeout is configurable via the
``timeoutForMediumTests`` attribute in the XML
configuration file.

A test that is not annotated with ``@medium`` or
``@large`` will be treated as if it were annotated with
``@small``. A small test will fail if it takes longer than
1 second to execute. This timeout is configurable via the
``timeoutForSmallTests`` attribute in the XML configuration
file.

.. _risky-tests.global-state-manipulation:

Global State Manipulation
#########################

PHPUnit can be strict about tests that manipulate global state. This check
can be enabled by using the ``--strict-global-state``
option on the commandline or by setting
``beStrictAboutChangesToGlobalState="true"`` in PHPUnit's
XML configuration file.


