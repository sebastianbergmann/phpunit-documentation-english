

.. _risky-tests:

***********
Risky Tests
***********

PHPUnit can perform the additional checks documented below while it executes
the tests.

.. _risky-tests.useless-tests:

Useless Tests
=============

PHPUnit is by default strict about tests that do not test anything. This check
can be disabled by using the ``--dont-report-useless-tests``
option on the :ref:`command line <textui.command-line-options>` or by setting
``beStrictAboutTestsThatDoNotTestAnything="false"`` in
PHPUnit's :ref:`configuration file <appendixes.configuration>`.

A test that does not perform an assertion will be marked as risky
when this check is enabled. Expectations on mock objects
count as an assertion.

.. _risky-tests.unintentionally-covered-code:

Unintentionally Covered Code
============================

PHPUnit can be strict about unintentionally covered code. This check
can be enabled by using the ``--strict-coverage`` option on
the :ref:`command line <textui.command-line-options>` or by setting
``beStrictAboutCoversAnnotation="true"`` in PHPUnit's
:ref:`configuration file <appendixes.configuration>`.

A test that is annotated with
:ref:`@covers <appendixes.annotations.covers>` and executes code that
is not listed using a :ref:`@covers <appendixes.annotations.covers>`
or :ref:`@uses <appendixes.annotations.uses>`
annotation will be marked as risky when this check is enabled.

Furthermore, by setting ``forceCoversAnnotation="true"`` in PHPUnit's
:ref:`configuration file <appendixes.configuration>`, a test can be marked as
risky when it does not have a :ref:`@covers <appendixes.annotations.covers>`
annotation.

.. _risky-tests.output-during-test-execution:

Output During Test Execution
============================

PHPUnit can be strict about output during tests. This check can be enabled
by using the ``--disallow-test-output`` option on the
:ref:`command line <textui.command-line-options>` or by setting
``beStrictAboutOutputDuringTests="true"`` in PHPUnit's
:ref:`configuration file <appendixes.configuration>`.

A test that emits output, for instance by invoking print in
either the test code or the tested code, will be marked as risky when this
check is enabled.

.. _risky-tests.test-execution-timeout:

Test Execution Timeout
======================

A time limit can be enforced for the execution of a test if the
`PHP_Invoker <https://packagist.org/packages/phpunit/php-invoker>`_ package
is installed and the ``pcntl`` extension is available. The enforcing of this
time limit can be enabled by using the ``--enforce-time-limit`` option
on the :ref:`command line <textui.command-line-options>` or by setting
``enforceTimeLimit="true"`` in PHPUnit's
:ref:`configuration file <appendixes.configuration>`.

A test annotated with ``@large`` will be marked as risky if it takes
longer than 60 seconds to execute. This timeout is configurable via the
``timeoutForLargeTests`` attribute in the
:ref:`configuration file <appendixes.configuration>`.

A test annotated with ``@medium`` will be marked as risky if it takes
longer than 10 seconds to execute. This timeout is configurable via the
``timeoutForMediumTests`` attribute in the
configuration :ref:`configuration file <appendixes.configuration>`.

A test annotated with ``@small`` will be marked as risky if it takes
longer than 1 second to execute. This timeout is configurable via the
``timeoutForSmallTests`` attribute in the
:ref:`configuration file <appendixes.configuration>`.

.. admonition:: Note

   Tests need to be explicitly annotated by either ``@small``,
   ``@medium``, or ``@large`` to enable run time limits.

   To exit the test run with a non-zero exit code when tests overrun
   their time-limit, the ``--fail-on-risky`` option on the
   :ref:`command line <textui.command-line-options>` or the ``failOnRisky="true"``
   setting in PHPUnit's :ref:`configuration file <appendixes.configuration>`
   needs to be enabled.

.. _risky-tests.global-state-manipulation:

Global State Manipulation
=========================

PHPUnit can be strict about tests that manipulate global state. This check
can be enabled by using the ``--strict-global-state``
option on the :ref:`command line <textui.command-line-options>` or by setting
``beStrictAboutChangesToGlobalState="true"`` in PHPUnit's
:ref:`configuration file <appendixes.configuration>`.


