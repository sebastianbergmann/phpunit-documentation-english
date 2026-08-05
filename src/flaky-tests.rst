.. _flaky-tests:

***********
Flaky Tests
***********

A test is *flaky* when it sometimes passes and sometimes fails without any
change to the code under test or to the test itself. Flakiness usually has one
of two root causes:

- The code under test, or the test itself, depends on something that is not
  fully under its control: the wall clock, the order in which concurrent
  operations complete, a remote service, the network, the file system, or the
  scheduling of operating system processes.

- The code under test manages state (connections, caches, file handles, or
  other resources) that is set up correctly once but leaks or becomes corrupt
  over repeated use.

PHPUnit provides two complementary features for dealing with flaky tests:

- :ref:`Repeating <flaky-tests.repeating-tests>` a test runs it multiple times
  and stops at the first failure. This helps you *find* flakiness and
  stress-test stateful code.

- :ref:`Retrying <flaky-tests.retrying-tests>` a test runs it multiple times
  and stops at the first success. This helps you *tolerate* flakiness that you
  cannot eliminate, while keeping it visible.

The two features have opposite semantics: repeating stops at the first
failure, retrying stops at the first success. They cannot be applied to the
same test at the same time.

.. admonition:: Eligibility

   Both features apply only to test methods that

   - declare an explicit ``void`` return type, and
   - do not depend on another test (do not use the ``#[Depends*]`` attributes).

   A test method that does not meet these requirements is run exactly once.
   When the ``--repeat`` or ``--retry`` command-line option is used, ineligible
   test methods are silently run once. When the ``#[Repeat]`` or ``#[Retry]``
   attribute is used on an ineligible test method, PHPUnit emits a warning.

   These requirements concern test methods. :ref:`PHPT tests <flaky-tests.phpt>`
   are always eligible.


.. _flaky-tests.repeating-tests:

Repeating Tests
===============

Repeating a test runs it several times in a row and stops as soon as it fails.
This is useful for two purposes:

- **Finding flaky tests.** A test that fails only intermittently is more likely
  to fail at least once when it is run many times.

- **Stress-testing stateful code.** Code that behaves correctly on the first
  invocation may leak or corrupt state over repeated invocations. Running the
  same test many times within a single PHPUnit run surfaces such problems.

Each repetition is reported individually, using its repetition number, in the
progress output, the result output, and the events emitted by PHPUnit.


.. _flaky-tests.repeating-tests.cli:

Repeating Tests Using the Command Line
--------------------------------------

The ``--repeat <N>`` command-line option runs each eligible test method ``N``
times:

.. parsed-literal::

    $ phpunit --repeat 3 tests/ExampleTest.php

By default, the remaining repetitions of a test method are skipped as soon as
one repetition fails.


.. _flaky-tests.repeating-tests.attribute:

Repeating Tests Using the ``#[Repeat]`` Attribute
-------------------------------------------------

The ``#[Repeat]`` attribute repeats an individual test method. This expresses
the intent to repeat the test in the code, where it can be reviewed together
with the test itself, rather than relying on a command-line option.

.. code-block:: php
    :caption: Using the ``#[Repeat]`` attribute

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\Repeat;
    use PHPUnit\Framework\TestCase;

    final class ExampleTest extends TestCase
    {
        #[Repeat(100)]
        public function testSomething(): void
        {
            // ...
        }
    }

The test method shown above is run ``100`` times. As with the command-line
option, the remaining repetitions are skipped as soon as one repetition fails.

A method-level ``#[Repeat]`` attribute takes precedence over the ``--repeat``
command-line option.


.. _flaky-tests.repeating-tests.failure-threshold:

Failure Threshold
-----------------

By default, the remaining repetitions of a test method are skipped as soon as
the first repetition fails. Sometimes the *pattern* of failures is itself
valuable diagnostic information, and you want to keep repeating the test until
several failures have accumulated.

The optional second argument of the ``#[Repeat]`` attribute, ``$failureThreshold``,
controls how many repetitions may fail before the remaining repetitions are
skipped. It defaults to ``1``.

.. code-block:: php
    :caption: Using the ``#[Repeat]`` attribute with a failure threshold

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\Repeat;
    use PHPUnit\Framework\TestCase;

    final class ExampleTest extends TestCase
    {
        #[Repeat(100, 5)]
        public function testSomething(): void
        {
            // ...
        }
    }

The test method shown above is repeated until either ``100`` repetitions have
run or ``5`` repetitions have failed, whichever comes first. Any repetitions
beyond that point are skipped.


.. _flaky-tests.retrying-tests:

Retrying Tests
==============

Retrying a test runs it again when it fails or errors, and stops as soon as it
succeeds. The first attempt whose status is neither a failure nor an error
decides the test's result.

Retrying is appropriate for tests whose flakiness you cannot eliminate because
it originates outside your control, for example a test that exercises an
unreliable remote system, or that is sensitive to timing or to the scheduling
of operating system processes. Rather than relying on a retry mechanism in your
continuous integration system, you express the decision to tolerate the
flakiness of a specific test in your code, where it can be reviewed.

.. admonition:: Flakiness stays visible

   Retrying does not hide flakiness. A test that passed only after one or more
   failed attempts is listed in the test result summary of every run, together
   with the number of failed attempts:

   .. parsed-literal::

       There was 1 retried test:

       1) ExampleTest::testSomething
       1 failed attempt

   This keeps the cost of the flakiness visible so that it is not forgotten.

Only failures and errors trigger another attempt. If an attempt is skipped,
marked incomplete, or considered risky, retrying stops immediately and that
outcome becomes the test's result. Issues such as deprecations that are
triggered during an attempt that is later superseded by a successful attempt
are discarded.


.. _flaky-tests.retrying-tests.cli:

Retrying Tests Using the Command Line
-------------------------------------

The ``--retry <N>`` command-line option attempts each eligible test method up
to ``N`` times, stopping at the first success:

.. parsed-literal::

    $ phpunit --retry 3 tests/ExampleTest.php

The ``--repeat`` and ``--retry`` command-line options are mutually exclusive.


.. _flaky-tests.retrying-tests.attribute:

Retrying Tests Using the ``#[Retry]`` Attribute
-----------------------------------------------

The ``#[Retry]`` attribute retries an individual test method. This expresses
the decision to tolerate the flakiness of a specific test in the code, where it
can be reviewed, instead of applying retrying globally.

.. code-block:: php
    :caption: Using the ``#[Retry]`` attribute

    <?php declare(strict_types=1);
    use PHPUnit\Framework\Attributes\Retry;
    use PHPUnit\Framework\TestCase;

    final class ExampleTest extends TestCase
    {
        #[Retry(3)]
        public function testSomething(): void
        {
            // ...
        }
    }

The test method shown above is attempted up to ``3`` times. The first attempt
that does not fail or error decides the test's result.

A method-level ``#[Retry]`` attribute takes precedence over the ``--repeat`` and
``--retry`` command-line options.


.. _flaky-tests.combining:

Combining Repeating and Retrying
================================

Repeating and retrying have opposite semantics and cannot be applied to the
same test method at the same time.

When a test method is annotated with both ``#[Repeat]`` and ``#[Retry]``,
PHPUnit emits a warning and ignores the ``#[Retry]`` attribute; the test method
is repeated.

The precedence rules between the attributes and the command-line options are:

- A method-level ``#[Repeat]`` attribute takes precedence over the ``--repeat``
  and ``--retry`` command-line options.

- A method-level ``#[Retry]`` attribute takes precedence over the ``--repeat``
  and ``--retry`` command-line options.

- The ``--repeat`` and ``--retry`` command-line options are mutually exclusive.


.. _flaky-tests.opting-out:

Opting Individual Tests Out
===========================

Because the attributes take precedence over the command-line options, an
attribute that asks for a single run opts a test method out of the
corresponding command-line option:

- ``#[Repeat(1)]`` runs the test method exactly once, even when ``--repeat``
  is used.

- ``#[Retry(1)]`` attempts the test method exactly once, even when ``--retry``
  is used.

This is useful for a test that must not be run more than once, for instance
because it is expensive or because it depends on state that only exists on the
first run.

An attribute that asks for a single run only opts out of its own
command-line option: ``#[Repeat(1)]`` does not prevent ``--retry`` from
retrying the test method, and ``#[Retry(1)]`` does not prevent ``--repeat``
from repeating it.

An argument that is not a positive integer has the same effect as the value
``1``, and PHPUnit emits a warning that names the test method and the invalid
value:

- ``#[Repeat]`` with a number of repetitions, or a failure threshold, that is
  not a positive integer

- ``#[Retry]`` with a maximum number of attempts that is not a positive
  integer

Because such a test method is run only once, its eligibility for repeating or
retrying does not matter and no warning about eligibility is emitted for it.


.. _flaky-tests.data-providers:

Data Providers
==============

Repeating and retrying both work together with data providers:
each data set is repeated or retried individually.

All repetitions or attempts for a data set receive the same argument values from the data provider.
The arguments are not cloned between repetitions or attempts:
when a data provider provides an object and the test mutates it, subsequent repetitions or attempts of that data set observe the mutated object, not the object as it was originally provided.

The exception is a test that is run in a separate PHP process.
Each repetition or attempt then receives its own copy of the arguments, because the arguments are serialized when they are passed to the child process.

A test that is repeated or retried should therefore not rely on mutating argument objects that are provided by a data provider.
State that the test modifies should be created in the test method itself or in ``setUp()``.


.. _flaky-tests.phpt:

PHPT Tests
==========

Repeating and retrying also work for :ref:`PHPT tests <appendixes.phpt>`.

Because attributes cannot be used in a PHPT file, repeating and retrying a PHPT
test can only be requested using the ``--repeat`` and ``--retry`` command-line
options. These options apply to every PHPT test of the test suite that is run:
the eligibility requirements that apply to test methods do not apply to PHPT
tests.

.. parsed-literal::

    $ phpunit --repeat 3 tests/example.phpt

Each repetition or attempt of a PHPT test is reported individually, using its
repetition or attempt number, just like a repeated or retried test method.
The remaining repetitions of a PHPT test are skipped as soon as one repetition
fails: the failure threshold that can be configured using the ``#[Repeat]``
attribute has no equivalent for PHPT tests.
