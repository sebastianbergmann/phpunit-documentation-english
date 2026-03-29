

.. _risky-tests:

***********
Risky Tests
***********

PHPUnit can help you identify risky tests, for instance lying tests that give you
a false sense of security.

Tests that are considered risky do not contribute to :ref:`code coverage <code-coverage>`.


.. _risky-tests.useless-tests:

Useless Tests
=============

By default, PHPUnit is strict about tests that do not test anything: tests that do not
perform assertions and do not configure expectations on mock objects.

This check can be disabled by using the ``--do-not-report-useless-tests``
option on the :ref:`command-line <appendixes.cli-options.execution>` or by setting
``beStrictAboutTestsThatDoNotTestAnything="false"`` in
PHPUnit's :ref:`XML configuration file <appendixes.configuration>`.

Conversely, a test that is attributed with ``PHPUnit\Framework\Attributes\DoesNotPerformAssertions`` but still performs assertions will also be considered risky.
This check is always active and cannot be disabled.


.. _risky-tests.unsealed-mock-objects:

Unsealed Mock Objects
=====================

By setting ``requireSealedMockObjects="true"`` in PHPUnit's :ref:`XML configuration file <appendixes.configuration.phpunit.requireSealedMockObjects>`, you can configure PHPUnit to consider a test risky when it does not seal the mock objects it creates (see :ref:`test-doubles.mock-objects.requiring-sealed-mock-objects`).


.. _risky-tests.unintentionally-covered-code:

Unintentionally Covered Code
============================

PHPUnit can be strict about unintentionally covered code. This check
can be enabled by using the ``--strict-coverage`` option on
the :ref:`command-line <appendixes.cli-options.execution>` or by setting
``beStrictAboutCoverageMetadata="true"`` in PHPUnit's
:ref:`XML configuration file <appendixes.configuration>`.

A test that has :ref:`code coverage targets <code-coverage.targeting-units-of-code>` (for example ``PHPUnit\Framework\Attributes\CoversClass``) and that executes code which is not specified using a ``Covers*`` or ``Uses*`` attribute will be considered risky when this check is enabled.

This check is not performed for tests attributed with ``#[Medium]`` or ``#[Large]``.
Medium and large tests typically exercise more code than small, focused unit tests and are therefore more likely to execute code that is not explicitly listed as covered or used.

Furthermore, by setting ``requireCoverageMetadata="true"`` in PHPUnit's :ref:`XML configuration file <appendixes.configuration>`, a test that does not define code coverage
metadata (such as ``CoversClass``, ``CoversMethod``, ``CoversFunction``, ``CoversNothing``, etc.) will be considered risky.
Unlike the strict coverage check described above, this check is performed regardless of the test's size (``#[Small]``, ``#[Medium]``, or ``#[Large]``).


.. _risky-tests.output-during-test-execution:

Output During Test Execution
============================

PHPUnit can be strict about output during tests. This check can be enabled
by using the ``--disallow-test-output`` option on the
:ref:`command-line <appendixes.cli-options.execution>` or by setting
``beStrictAboutOutputDuringTests="true"`` in PHPUnit's
:ref:`XML configuration file <appendixes.configuration>`.

A test that emits output, for instance by invoking ``print`` in
either the test code or the tested code, will be considered risky when this
check is enabled.

Additionally, when this check is enabled, a test will be considered risky if test code or tested code opens output buffers (using ``ob_start()``) but does not close them before the test finishes.
Similarly, a test will be considered risky if it closes output buffers that it did not open itself.


.. _risky-tests.test-execution-timeout:

Test Execution Timeout
======================

PHPUnit can enforce a time limit for the execution of a test when the ``pcntl`` extension
is available. The enforcing of this time limit can be enabled by using the
``--enforce-time-limit`` option on the :ref:`command-line <appendixes.cli-options.execution>`
or by setting ``enforceTimeLimit="true"`` in PHPUnit's :ref:`XML configuration file <appendixes.configuration>`.

A test that is attributed with ``PHPUnit\Framework\Attributes\Large``
(or annotated with ``@large``) will be considered risky when it takes
longer than 60 seconds to run. This timeout is configurable via the
``timeoutForLargeTests`` attribute in the
:ref:`XML configuration file <appendixes.configuration>`.

A test that is attributed with ``PHPUnit\Framework\Attributes\Medium``
(or annotated with ``@medium``) will be considered risky when it takes
longer than 10 seconds to run. This timeout is configurable via the
``timeoutForMediumTests`` attribute in the
:ref:`XML configuration file <appendixes.configuration>`.

A test that is attributed with ``PHPUnit\Framework\Attributes\Small``
(or annotated with ``@small``) will be considered risky when it takes
longer than 1 second to run. This timeout is configurable via the
``timeoutForSmallTests`` attribute in the
:ref:`XML configuration file <appendixes.configuration>`.


.. _risky-tests.global-state-manipulation:

Global State Manipulation
=========================

PHPUnit can be strict about tests that manipulate global state. This check
can be enabled by using the ``--strict-global-state``
option on the :ref:`command-line <appendixes.cli-options.execution>` or by setting
``beStrictAboutChangesToGlobalState="true"`` in PHPUnit's
:ref:`XML configuration file <appendixes.configuration>`.


.. _risky-tests.test-size-dependencies:

Test Size Dependencies
======================

A test that depends on a test that is larger than itself will be considered risky.
For example, a test attributed with ``PHPUnit\Framework\Attributes\Small`` that depends (via ``PHPUnit\Framework\Attributes\Depends`` or ``PHPUnit\Framework\Attributes\DependsExternal``) on a test attributed with ``PHPUnit\Framework\Attributes\Medium`` or ``PHPUnit\Framework\Attributes\Large`` will be considered risky.
This check is always active and cannot be disabled.


