

.. _parallel-test-execution:

***********************
Parallel Test Execution
***********************

By default, PHPUnit runs the tests of a test suite one after another, in a single PHP process.
The time this takes grows with the test suite, and a computer with several CPU cores spends most of it with all but one of them idle.

PHPUnit can run the tests of a test suite in parallel instead.
It then starts several PHP processes and distributes the test classes among them, so that tests of different test classes are run at the same time.

This chapter uses the following terms:

Main process
    The PHP process that is started when you invoke PHPUnit. It loads the test suite, decides which process runs which tests, and reports the results.

Worker process
    A PHP process that is started by the main process to run tests. A worker process, or worker for short, runs the tests of one test class after another until no test class is left.


.. _parallel-test-execution.running:

Running tests in parallel
=========================

The tests in the ``tests`` directory shown below stand in for a test suite with slow tests: each of the six tests in three test classes takes half a second.

.. literalinclude:: examples/parallel-test-execution/slow-tests/tests/CatalogTest.php
   :caption: One of three test classes with slow tests
   :language: php

Run one after another, the six tests take three seconds:

.. literalinclude:: examples/parallel-test-execution/slow-tests/sequential.out

The ``--parallel`` CLI option runs the tests in parallel.
Its value is the number of worker processes to use:

.. literalinclude:: examples/parallel-test-execution/slow-tests/parallel.out

With three workers, each of the three test classes is run by a worker of its own, and the test run takes little more than the second that the two tests of one test class take.
The ``Parallel`` line of the output shows the number of workers that are used.

Use ``auto`` instead of a number to let PHPUnit use one worker for each CPU core that is available:

.. parsed-literal::

    $ ./tools/phpunit --parallel auto

How much faster a test suite runs in parallel depends on the test suite.
Starting the workers takes time, each of them loads the bootstrap script and the test classes, and a test class is never divided among workers.
A test suite that takes a second or two to run sequentially gains little, and a test suite whose time is dominated by a single test class cannot run faster than this one test class does.

A few details are worth knowing:

- Tests are only run in parallel when more than one worker is used. With ``--parallel 1``, and with ``--parallel auto`` when only one CPU core is available, the tests are run sequentially.
- Parallel test execution is requested on the command line. There is no setting for it in the XML configuration file.
- A value that is neither a positive integer nor ``auto`` is ignored: PHPUnit emits a warning and runs the tests sequentially.
- ``--parallel`` requires a 64-bit build of PHP.

.. admonition:: Tests must be independent of each other

   Running tests in parallel changes which tests share a PHP process and which tests are run at the same time.
   Tests that pass when they are run one after another can fail when they are run in parallel, for instance because they use the same database or the same file.
   :ref:`parallel-test-execution.writing-tests` explains what to look out for.


.. _parallel-test-execution.distribution:

How tests are distributed
=========================

The test class is the unit of distribution.
All tests of a test class are run by the same worker, one after another, in the order in which a sequential run would run them.
Tests of the same test class are never run at the same time.

This means that everything that connects the tests of a test class works as it does in a sequential run:

- The methods that are called before the first and after the last test of a test class, such as ``setUpBeforeClass()`` and ``tearDownAfterClass()``, are called once, in the worker that runs the test class. A fixture that they set up is available to all tests of the test class (see :ref:`fixtures.sharing-fixture`).
- A test can depend on another test of the same test class and consume the value that it returns (see :ref:`writing-tests-for-phpunit.test-dependencies`).
- The data sets of a data provider, the repetitions and retries of a test (see :ref:`flaky-tests`), and the examples of a property (see :ref:`property-based-testing`) are run by the worker that runs their test class.

The number that is passed to ``--parallel`` is the number of test classes that are run by workers at the same time.
Tests that are run in the main process (see :ref:`parallel-test-execution.main-process`) are run in addition to this.

**Test suites**

When the XML configuration file configures more than one test suite using ``<testsuite>`` elements, these test suites are run one after another.
Only tests that belong to the same test suite are run at the same time.
The workers are started once and are used for all test suites.

**Order of execution**

The test execution order that is configured, for instance using ``--order-by`` or ``--random-order``, is applied to the test suite before its test classes are distributed (see :ref:`textui.test-execution-order`).
It determines the order of the tests within a test class and the order in which results are reported.

It does not determine when a test class is started.
PHPUnit starts with the test classes that it expects to take longest, because this keeps all workers busy until the end of the test run.
The expectation is based on how long the tests took in a previous test run, which is recorded in the :ref:`test run history <appendixes.xml-configuration-file.phpunit.recordTestRunHistory>`.
For tests that have no recorded duration, the size that is declared using the :ref:`Small <appendixes.attributes.Small>`, ``Medium``, or ``Large`` attribute is used.
When nothing is known about any test, the test classes are started in the order of the test suite.

**Reporting**

Results are reported in the order in which a sequential run would report them, regardless of the order in which the workers finish.
A test class that is finished early waits until the results of all test classes before it have been reported.

Consequently, the output of a parallel run matches the output of a sequential run of the same test suite.
The same is true for the reports and logs that PHPUnit writes, for instance in JUnit XML format (see :ref:`textui.logging`), and for TestDox output (see :ref:`testdox`).
The only visible differences are the ``Parallel`` line and the rhythm of the progress output: the progress of the test class whose turn it is to be reported is shown while it runs, the progress of a test class that had to wait is shown at once.


.. _parallel-test-execution.main-process:

Tests that are not run by a worker
==================================

Some tests cannot be run by a worker, or must not be.
PHPUnit runs them in the main process, at the position at which a sequential run would run them.
Their results are reported like all others.


.. _parallel-test-execution.main-process.process-isolation:

Tests that are run in separate processes
----------------------------------------

A test class is run in the main process when one of its tests is to be run in a separate PHP process, because of the :ref:`RunInSeparateProcess <appendixes.attributes.RunInSeparateProcess>` or :ref:`RunTestsInSeparateProcesses <appendixes.attributes.RunTestsInSeparateProcesses>` attribute, the ``--process-isolation`` CLI option, or the :ref:`processIsolation <appendixes.xml-configuration-file.phpunit.processIsolation>` setting of the XML configuration file.
The main process starts the separate processes for these tests, as it does in a sequential run, while the workers run other test classes.

When process isolation is requested for all tests, using ``--process-isolation`` or ``processIsolation="true"``, no test is run by a worker.


.. _parallel-test-execution.main-process.dependencies:

Tests that depend on tests of another test class
------------------------------------------------

A test that depends on a test of another test class, for instance using the :ref:`DependsExternal <appendixes.attributes.DependsExternal>` attribute, needs to know whether that test passed, and it may consume the value that the test returned.
PHPUnit therefore runs both test classes in the main process: the test class that has the dependency and the test class with the test that is depended upon.

.. literalinclude:: examples/parallel-test-execution/dependencies/tests/LoginTest.php
   :caption: A test that depends on a test of another test class
   :language: php

Because this is easily overlooked, and because it makes a test run slower than it could be, PHPUnit emits a test runner notice for each of the two test classes.
Use the ``--display-phpunit-notices`` CLI option to see the details:

.. literalinclude:: examples/parallel-test-execution/dependencies/parallel.out

``PasswordTest``, the third test class in this example, is not involved in the dependency and is run by a worker.

When a test depends on a test class as a whole, using the :ref:`DependsOnClass <appendixes.attributes.DependsOnClass>` attribute, no value is consumed.
Only the test class that has the dependency is then run in the main process.


.. _parallel-test-execution.main-process.do-not-run-in-parallel:

Tests that must not be run in parallel
--------------------------------------

The :ref:`DoNotRunInParallel <appendixes.attributes.DoNotRunInParallel>` attribute excludes a test class from parallel test execution:

.. literalinclude:: examples/parallel-test-execution/do-not-run-in-parallel/tests/SchemaMigrationTest.php
   :caption: A test class that is excluded from parallel test execution
   :language: php

A test class with this attribute is run in the main process, and it is run alone: PHPUnit waits until the workers have finished the test classes they are running and starts no other test until the last test of the test class has been run.

The attribute can be used on a test class and on a test method.
Used on a test method, it has the same effect as on the test class of that method, because the tests of a test class are not divided.
The attribute is inherited from parent classes.

Use this attribute for the few tests that cannot share the machine with other tests, for instance because they change something that all other tests rely on.
Every test class that is excluded makes all workers wait, so that a test suite in which many test classes have this attribute gains little from being run in parallel.
For tests that merely need a resource of their own, see :ref:`parallel-test-execution.writing-tests.worker-identity`.


.. _parallel-test-execution.main-process.phpt:

PHPT tests
----------

:ref:`PHPT tests <appendixes.phpt>` are not run by workers either, as each of them is run in a PHP process of its own anyway.
In a parallel run, the main process runs several PHPT tests at the same time.
PHPT tests and test classes share the limit that is configured using ``--parallel``.

A PHPT test cannot have an attribute.
It declares the tests that it must not be run alongside using a ``--CONFLICTS--`` section (see :ref:`appendixes.phpt.sections.conflicts`).


.. _parallel-test-execution.writing-tests:

Writing tests that can be run in parallel
=========================================

A test that sets up everything it needs, cleans up after itself, and does not rely on what another test left behind can be run in parallel without any change.
The problems that surface when a test suite is run in parallel for the first time are almost always dependencies between tests that went unnoticed as long as the tests were run one after another, in the same order, in the same process.
There are two kinds of them.


.. _parallel-test-execution.writing-tests.state-in-process:

State inside the PHP process
----------------------------

In a sequential run, all tests share one PHP process.
In a parallel run, a test shares its process only with the tests of the test classes that the same worker happens to run, and which test classes these are differs from one test run to the next.

A test that relies on global state that a test of another test class has set up, such as a static property, a registered service, or a changed ``php.ini`` setting, may therefore find that state missing.
Such a test also fails in a sequential run when it is run on its own or when the tests are run in a different order, for instance using ``--random-order``.

The opposite does not change: a worker runs many test classes, and what a test leaves behind in the process is still there for the tests that the worker runs afterwards.
Running tests in parallel does not isolate tests from each other.
Tests that need a process of their own are run in separate processes (see :ref:`parallel-test-execution.main-process.process-isolation`).


.. _parallel-test-execution.writing-tests.worker-identity:

Resources outside the PHP process
---------------------------------

The second kind of dependency is new: tests that are run at the same time, in different processes, and use the same resource outside of these processes.
Two tests that write to the same database table, the same file, the same cache key, or listen on the same network port interfere with each other.

The solution is to give each worker a resource of its own.
For this purpose, a worker makes its identity available to the tests that it runs through two environment variables:

``PHPUNIT_WORKER_ID``
    The number of the worker: ``0`` for the first worker, ``1`` for the second worker, and so on. With ``--parallel 4``, the workers have the numbers ``0`` to ``3``. Use this number to select one resource out of a fixed set, for instance one of four databases that were created in advance.

``PHPUNIT_WORKER_TOKEN``
    A string that is unique for each worker process and for each test run, for instance ``1_5f2b9c0d47a81e36b0c4d9e2f7a13b58``. Use it for resources that must not collide with what a previous test run may have left behind, for instance the name of a temporary directory.

Both are read using ``getenv()``:

.. literalinclude:: examples/parallel-test-execution/worker-identity/tests/FileCacheTest.php
   :caption: Using a directory that is not shared with other workers
   :language: php

The two environment variables are only set in worker processes.
They are not set in a sequential run, and they are not set for the tests that are run in the main process of a parallel run or in a separate process (see :ref:`parallel-test-execution.main-process`).

.. admonition:: Do not fall back to the number of a worker

   A test that finds ``PHPUNIT_WORKER_ID`` not set must not use ``0`` in its place.
   Most tests that are run in the main process of a parallel run are run while the workers run other tests.
   Such a test would share the resource of the first worker.
   Use a value that no worker uses, as the example shown above does.

The bootstrap script can use ``PHPUNIT_WORKER_ID`` as well, for instance to create the database that the tests of the worker use (see :ref:`parallel-test-execution.writing-tests.bootstrap`).

When a resource cannot be provided for each worker, and the tests that use it are in a few test classes, the ``DoNotRunInParallel`` attribute excludes these test classes from parallel test execution (see :ref:`parallel-test-execution.main-process.do-not-run-in-parallel`).


.. _parallel-test-execution.writing-tests.data-providers:

Data providers
--------------

The main process invokes the data providers when it loads the test suite, as it does in a sequential run.
The data sets themselves are not sent to the workers, because data sets can contain values, such as closures or resources, that cannot be sent from one process to another.
The worker that runs a test class invokes the data providers of its tests again and uses the data sets that the main process selected, which it identifies by their names (see :ref:`writing-tests-for-phpunit.data-providers.reference.names-of-data-sets`).

A data provider must therefore provide the same data sets, under the same names, each time it is invoked.
This is the case for a data provider that returns fixed values.
It is not the case for a data provider that derives the names of its data sets from something that differs between processes or changes over time, such as random values, the current time, or the contents of a directory that the tests modify.

When a worker does not find a data set that the main process selected, the tests of the test method are reported as errors:

.. code-block:: text

    The data provider for ExampleTest::testOne did not provide data set "example" when the worker process invoked it; a data provider must provide the same data sets, under the same names, in every process

Keep in mind that the code of a data provider is executed once in the main process and once in each worker that runs tests of its test class.


.. _parallel-test-execution.writing-tests.bootstrap:

Bootstrap scripts
-----------------

The bootstrap script that is configured using the ``--bootstrap`` CLI option or the :ref:`bootstrap <appendixes.xml-configuration-file.phpunit.bootstrap>` attribute of the XML configuration file is loaded in the main process and in each worker process when it starts.
The same is true for the bootstrap scripts that are configured for test suites.

A bootstrap script that only registers an autoloader or defines constants needs no change.
A bootstrap script that does more than that, for instance one that creates a database schema or starts a service, does so once in each process.
Such a script can use ``PHPUNIT_WORKER_ID`` to find out whether it is loaded in a worker process, and in which one:

.. code-block:: php
    :caption: A bootstrap script that prepares a database for each worker

    <?php declare(strict_types=1);
    require __DIR__ . '/../vendor/autoload.php';

    $workerId = getenv('PHPUNIT_WORKER_ID');

    if ($workerId !== false) {
        // This is a worker process
        createDatabase('application_test_' . $workerId);
    }


.. _parallel-test-execution.writing-tests.standard-input:

Standard input
--------------

The standard input of a worker process is closed.
A test that is run by a worker cannot read from ``STDIN``.


.. _parallel-test-execution.workers:

Worker processes
================

The worker processes are started when the main process begins to run the tests, and they are used until the test run ends.
No worker is started when no test can be run by one, for instance when all tests are PHPT tests.

A worker is started with the PHP binary that runs the main process.
It is configured like the main process:

- It inherits the environment variables of the main process.
- The ``php.ini`` settings that were passed to the PHP binary on the command line, using its ``-d`` option, are passed on to the worker.
- The settings that are configured in the ``<php>`` element of the XML configuration file are applied (see :ref:`appendixes.xml-configuration-file.php`).
- The bootstrap script is loaded (see :ref:`parallel-test-execution.writing-tests.bootstrap`).
- Test runner extensions that are implemented for this are bootstrapped (see :ref:`extending-phpunit.extending-the-test-runner.child-processes`).

Xdebug slows down the execution of PHP code considerably.
When Xdebug is loaded, it is therefore disabled in the workers unless it is needed: because code coverage is collected, because a debugger is used, because ``xdebug.mode`` was configured explicitly, or because a test requires it.


.. _parallel-test-execution.workers.recycling:

Replacing workers
-----------------

A worker runs many test classes, and whatever their tests leave behind in the process accumulates: the values of static properties, caches, objects that are not freed.
When this becomes a problem, for instance because the workers run out of memory towards the end of a long test run, PHPUnit can replace a worker with a new process after it has run a number of test classes.

This is configured using the ``--recycle-workers-after`` CLI option or the :ref:`numberOfTestClassesBeforeWorkerRecycling <appendixes.xml-configuration-file.phpunit.numberOfTestClassesBeforeWorkerRecycling>` attribute of the XML configuration file:

.. parsed-literal::

    $ ./tools/phpunit --parallel 4 --recycle-workers-after 50

Workers are not replaced by default.
Replacing a worker is not free: the new process loads the bootstrap script and the test classes again.

The new process takes the place of the worker that it replaces.
It has the same ``PHPUNIT_WORKER_ID`` and a new ``PHPUNIT_WORKER_TOKEN``.


.. _parallel-test-execution.workers.crashes:

When a worker ends unexpectedly
-------------------------------

A worker process ends unexpectedly when the code that it runs calls ``exit()``, triggers a fatal error, or crashes PHP.
The second test of the test class shown below ends the process:

.. literalinclude:: examples/parallel-test-execution/crash/tests/ImportTest.php
   :caption: A test that ends the PHP process
   :language: php

.. literalinclude:: examples/parallel-test-execution/crash/parallel.out

The results that the worker reported before it ended are kept: the first test of ``ImportTest`` is reported as successful.
The test that was running, and the tests of the test class that could not be run anymore, are reported as errors.
The message names the test class and is followed by what is known about the cause.

The test run continues.
A new worker process is started in place of the one that ended and runs the test classes that are left.

When a worker ends before a result of the test class that it was running has been reported, PHPUnit runs this test class again, once, in a new worker process.
All of its tests are then run again, including those that were run before the worker ended, and so are the methods that are called before the first and after the last test of the test class.


.. _parallel-test-execution.other-features:

Parallel test execution and other features of PHPUnit
=====================================================

Stopping early
    The ``--stop-on-*`` CLI options and the corresponding settings of the XML configuration file stop a parallel run as they stop a sequential run (see :ref:`textui.stopping-early`).
    The results that are reported are those that a sequential run would report up to this point.
    A worker that is running a test class at this moment finishes the test that it is running, starts no further test, and calls the methods that are called after the last test of the test class.
    The results of such a test class are not reported.

Limiting the duration of the test run
    The ``--timeout`` CLI option limits the duration of a parallel run as well (see :ref:`textui.time-limit`).
    When the limit is exceeded, the tests that the workers are running are aborted.

Code coverage
    Code coverage is collected in the workers and combined by the main process.
    The code coverage reports of a parallel run are generated as they are for a sequential run, no additional step is required to merge anything (see :ref:`code-coverage`).
    PHPT tests are run one after another while code coverage is collected.

Repeating and retrying tests
    Tests are repeated and retried by the worker that runs their test class (see :ref:`flaky-tests`).

Property-based testing
    A property is checked by the worker that runs its test class.
    The examples are generated from the random seed of the test run and are the same as in a sequential run with the same seed (see :ref:`property-based-testing.reproducing-failures`).

Random numbers
    The random seed that is shown in the output applies to the test run as a whole, and a parallel run is reproduced like a sequential run, using ``--random-seed`` (see :ref:`textui.random-numbers`).

Issues
    Deprecations, notices, and warnings that are triggered in a worker are reported by the main process.
    The settings that control how issues are handled, such as the ``<source>`` element of the XML configuration file and the baseline, apply to the workers (see :ref:`error-handling`).

Output of tests
    What a test prints is handled as in a sequential run.
    What is written to standard output or standard error outside of a test, for instance in a method that is called before the first test of a test class, is printed by the main process at the position at which a sequential run would print it.

Test run history
    The test run history is recorded by the main process and covers the tests that were run by workers.

Test impact analysis
    Test impact data is recorded in the workers and combined by the main process.
    When only the tests that are affected by a change are run, only these tests are distributed among the workers (see :ref:`test-impact-analysis`).

Test runner extensions
    A test runner extension is bootstrapped in the main process and receives the events of all tests, including those that were run by workers.
    An extension that has to act in the process in which a test is run needs to be implemented for this (see :ref:`extending-phpunit.extending-the-test-runner.child-processes`).


.. _parallel-test-execution.limitations:

Limitations
===========

- The tests of a test class are not run in parallel. A test suite that consists of few, large test classes gains less than a test suite with many small test classes.
- Parallel test execution cannot be enabled in the XML configuration file.
- Tests that are run in separate processes, tests that depend on tests of another test class, and the tests of the test classes they depend on are not run by workers (see :ref:`parallel-test-execution.main-process`).
- A data provider is invoked more than once and has to provide the same data sets, under the same names, each time (see :ref:`parallel-test-execution.writing-tests.data-providers`).
- Tests that are run by a worker cannot read from standard input.
- Running tests in parallel does not isolate tests that are run by the same worker from each other.
