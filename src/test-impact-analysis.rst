

.. _test-impact-analysis:

********************
Test Impact Analysis
********************

Most changes to the code of a project can only affect some of its tests.
Running every test after every change tells you nothing new about the tests that the change cannot affect, and the larger the test suite is, the longer you wait for that.

Test impact analysis runs only the tests that can be affected by what changed.
PHPUnit does this in two steps: while tests are run, it records what each test depends on, and later it uses what was recorded to run only the tests that depend on something that changed.

PHPUnit only leaves out a test when what was recorded shows that the change cannot affect it.
A test that PHPUnit has no reliable information about is run, and when a change cannot be accounted for by what was recorded, every test is run.

This chapter uses the following terms:

Test impact data
    What PHPUnit knows about what each test depends on: the files of the code under test that the test executed, the files that the test itself is made of, and the files that the test declares to depend on.

Recording
    Writing test impact data while tests are run. This chapter also uses this term for the test impact data that was written.

First-party code
    The files that are configured as the project's source code using the :ref:`\<source\> <appendixes.xml-configuration-file.source>` element of the XML configuration file.


.. _test-impact-analysis.recording:

Recording test impact data
==========================

PHPUnit records test impact data when all of the following are true:

- Recording is enabled, either with the ``recordTestImpactData`` attribute of the ``<phpunit>`` element of the XML configuration file or with the ``--record-test-impact-data`` CLI option.
- A :ref:`cache directory <appendixes.xml-configuration-file.phpunit.cacheDirectory>` is configured. This is where the recording is kept.
- First-party code is configured using the :ref:`\<source\> <appendixes.xml-configuration-file.source>` element. Only first-party code is observed.
- A code coverage driver, PCOV or Xdebug, is available (see :ref:`code-coverage`). PHPUnit uses it to observe which files a test executes. No code coverage report has to be requested.

The examples in this chapter use a small project with three classes in the ``src`` directory, three test classes in the ``tests`` directory, and this XML configuration file:

.. literalinclude:: examples/test-impact-analysis/project/phpunit.xml
   :caption: An XML configuration file that enables the recording of test impact data
   :language: xml

A test run that records test impact data looks like any other test run:

.. literalinclude:: examples/test-impact-analysis/record.out

At the end of the test run, the test impact data is written to the file ``test-impact-data`` in the cache directory.

Because the code coverage driver is active while the tests are run, a test run that records test impact data takes longer than one that does not, just like a test run that collects code coverage.


.. _test-impact-analysis.recording.what-is-recorded:

What is recorded
----------------

For each test, PHPUnit records the files the test depends on, each together with a hash of its contents:

- The files of first-party code of which the test executed at least one line.
- The files that the test class is made of: the file that declares the test class, the files of its parent classes and traits, and the file of a class that provides data for the test (see :ref:`appendixes.attributes.DataProviderExternal`).
- The files and directories that the test declares or registers as fixtures (see :ref:`test-impact-analysis.dependencies`).
- The files of first-party code that are executed by the data provider of the test, and by the methods that are called before the first test and after the last test of its test class.
- For a test that is run in a separate process, the files of first-party code that this process loaded, whether or not a line of them was executed. The same applies to the test of a test run that runs a single test.

Files of first-party code that are executed outside of any test, for instance by the bootstrap script or while the test files are loaded, are recorded once for the test run and not for each test.
Every test depends on them.

Nothing is recorded for

- a test that is skipped or marked incomplete, and
- a test that is not a test method, for instance a :ref:`PHPT test <appendixes.phpt>`.

Such a test is run whenever PHPUnit runs only the tests that are affected by a change.

A test run that runs only some of the tests adds what it records to the recording that already exists.
What was recorded for the tests that were not run is kept.
What was recorded for a test that no longer exists is removed by the next test run that runs every test, that is, a test run in which no tests are selected or filtered and which is not stopped early.


.. _test-impact-analysis.recording.code-coverage:

Recording and code coverage
---------------------------

Test impact data can be recorded by a test run that also generates code coverage reports.
The reports are not affected by the recording.

A test run that is told not to collect code coverage with the ``--no-coverage`` CLI option does not record test impact data, even when recording is enabled in the XML configuration file.
The ``--no-coverage`` and ``--record-test-impact-data`` CLI options cannot be used together.


.. _test-impact-analysis.recording.not-possible:

When test impact data cannot be recorded
----------------------------------------

When recording is enabled but one of its requirements is not met, PHPUnit runs the tests, does not record test impact data, and emits a test runner warning that names the reason:

- ``Cannot record test impact data because no cache directory is configured``
- ``No filter is configured, test impact data will not be recorded`` when no first-party code is configured
- ``No code coverage driver available that supports line coverage, test impact data will not be recorded``, or a message about the configuration of Xdebug that ends in the same way

By default, a test runner warning causes PHPUnit to exit with a shell exit code that indicates failure (see :ref:`textui.exit-codes`).
Use the ``--do-not-record-test-impact-data`` CLI option for a test run on a machine that has no code coverage driver when recording is enabled in the XML configuration file.


.. _test-impact-analysis.selecting:

Running only the tests that are affected
========================================

The ``--only-impacted`` CLI option runs only the tests that can be affected by what changed since they were recorded.
A file changed when its contents are not what they were when a test that depends on it was recorded.
PHPUnit finds this out by comparing hashes of the contents of the files, it does not ask a version control system.

When nothing changed, no test is run:

.. literalinclude:: examples/test-impact-analysis/nothing-changed.out

The ``Impact`` line shows how many of the tests can be affected by what changed and how many tests are not run.
The ``Recorded`` line shows when the recording that this is based on was last written.

After the implementation of the ``Vat`` class in ``src/Vat.php`` was changed, the four tests that depend on this file are run.
The two tests of ``SlugTest`` are not run:

.. literalinclude:: examples/test-impact-analysis/source-file-changed.out

Recording is enabled in the XML configuration file of the example, so this test run also records the four tests that it runs.
The next test run with ``--only-impacted`` does not run them again unless a file that they depend on changes once more.
When recording is not enabled for such a test run, the recording stays as it is, and ``src/Vat.php`` is considered changed until a test run records the tests that depend on it.

Running only the tests that are affected requires a cache directory and the :ref:`test run history <appendixes.xml-configuration-file.phpunit.recordTestRunHistory>`.
PHPUnit exits with an error message, for instance ``Cannot run only the tests that are affected by what changed because the test run history is not recorded``, when one of them is missing.
A code coverage driver is not required.

A test run for which no test can be affected by what changed is not considered a test run with an empty test suite: the ``--fail-on-empty-test-suite`` CLI option and the ``failOnEmptyTestSuite`` attribute of the XML configuration file do not cause it to fail.


.. _test-impact-analysis.selecting.naming-what-changed:

Naming what changed
-------------------

Instead of letting PHPUnit find out what changed, you can name the files and directories that changed.
PHPUnit then runs the tests that are recorded as depending on one of them, without comparing the contents of files.

The ``--impacted-by`` CLI option names a path that changed:

.. literalinclude:: examples/test-impact-analysis/impacted-by.out

The ``--impacted-by-file`` CLI option reads the paths that changed from a file, one path per line.
Use ``-`` to read them from standard input, for instance to pass on what a version control system reports:

.. parsed-literal::

    $ git diff --name-only main -- src tests | ./tools/phpunit --impacted-by-file -

A few details are worth knowing:

- ``--impacted-by`` can be used multiple times and can be combined with ``--impacted-by-file``.
- A path can be a file or a directory. A directory stands for every file that was recorded beneath it.
- A relative path is resolved against the current working directory, not against the directory of the XML configuration file.
- A path does not have to exist: a file that was deleted is a change like any other.
- A list of paths that is empty says that nothing changed, and no test is run.
- ``--impacted-by`` and ``--impacted-by-file`` cannot be used together with ``--only-impacted``.

.. admonition:: Only name paths that PHPUnit knows about

   A path that is not part of the recording is a change that PHPUnit cannot account for, and every test is run because of it.
   This is the case for a file that is neither first-party code, nor a file a test depends on, nor a :ref:`watched file <test-impact-analysis.dependencies.watched-files>`, for instance a ``README.md`` file.
   It is also the case for a test file that was added.
   When you pass on what a version control system reports, restrict it to the directories that matter, as the example shown above does, or use ``--only-impacted``.


.. _test-impact-analysis.selecting.which-tests:

Which tests are run
-------------------

When PHPUnit runs only the tests that are affected, a test is run for one of the following reasons:

It depends on something that changed
    The test executed, or declared that it depends on, a file that changed.
    This includes the files that the test class is made of: a test that was changed is run.

It depends on a test that can be affected
    The test :ref:`depends <writing-tests-for-phpunit.test-dependencies>` on a test that is run for one of the reasons listed here.
    It is given what that test returns, and it is skipped when that test does not pass.

It has never been recorded
    Nothing was recorded for the test.
    It was added after the recording was made, or it was skipped or marked incomplete when it was run.
    The tests of a test file that was added are run for this reason.

It did not pass when it was last run
    According to the test run history, the test did not pass when it was last run.

Another test that is run depends on it
    A test that is run for one of the reasons listed here depends on this test and cannot be run without it.

It cannot be recorded
    The test is not a test method, for instance a PHPT test.


.. _test-impact-analysis.selecting.every-test:

When every test is run
----------------------

PHPUnit runs every test when what was recorded cannot account for what changed.
The ``Impact`` line of the output then names the reason:

.. literalinclude:: examples/test-impact-analysis/source-file-added.out

Every test is run when

- no test impact data has been recorded,
- the recording cannot be used any longer (see :ref:`test-impact-analysis.validity`),
- a file changed that was executed outside of any test, for instance by the bootstrap script,
- a file of first-party code or a :ref:`watched file <test-impact-analysis.dependencies.watched-files>` was added (see :ref:`test-impact-analysis.dependencies.added-files` for how to change this),
- a file of first-party code or a watched file changed that no test is recorded as depending on, or
- a path that is :ref:`named <test-impact-analysis.selecting.naming-what-changed>` as changed is not part of the recording, or no test is recorded as depending on it.

A test run that runs every test, and that records test impact data, records the files of first-party code and the watched files as they are now.
The change that caused every test to be run does therefore not cause every test to be run again.
In the example shown above, the next test run with ``--only-impacted`` does not run any test.


.. _test-impact-analysis.selecting.other-options:

Combining with other options for selecting tests
------------------------------------------------

CLI options such as ``--filter``, ``--group``, and ``--testsuite`` can be used together with ``--only-impacted``, ``--impacted-by``, and ``--impacted-by-file`` (see :ref:`textui.selecting-tests`).
A test is then only run when it can be affected by what changed and is selected by the other options.
How many tests are run is decided after the ``Impact`` line is printed, which is why this line does not show a number of tests that are not run in this case::

    Impact:        2 of 6 tests can be affected by what changed; the tests that are run are filtered as well


.. _test-impact-analysis.explaining:

Understanding why tests are run
===============================

The ``--explain-impacted`` CLI option reports which tests can be affected by what changed, and why each of them is run, without running tests.
It reports what a test run with ``--only-impacted`` would do, or, when it is combined with ``--impacted-by`` or ``--impacted-by-file``, what a test run with these options would do:

.. literalinclude:: examples/test-impact-analysis/explain-impacted.out

The tests are grouped by the reason for which they are run (see :ref:`test-impact-analysis.selecting.which-tests`).
When more than one reason applies to a test, only one of them is reported.
For a test that depends on something that changed, one of the files that changed is shown.

The ``--list-tests-that-depend-on`` CLI option answers the opposite question.
It lists the tests that are recorded as depending on a file, without running tests:

.. literalinclude:: examples/test-impact-analysis/list-tests-that-depend-on.out

When the file changed since some of the tests were recorded, these tests are listed separately, as ``Tests that depend on an earlier version of`` the file.
For a file that was executed outside of any test, PHPUnit reports that every test depends on it.
The file has to exist, and a cache directory has to be configured.

The list shows what was recorded, which is not necessarily every test that a change to the file can affect: a test that has never been run is not part of the recording.


.. _test-impact-analysis.dependencies:

Dependencies that PHPUnit cannot observe
========================================

PHPUnit observes which files of first-party code a test executes.
A test can depend on other files as well: on a file with test data that is read by a data provider, for instance, or on a configuration file that is read by the code under test.
Reading a file is not executing code, so PHPUnit has to be told about such dependencies.


.. _test-impact-analysis.dependencies.uses-fixture:

Declaring fixtures
------------------

The ``#[UsesFixture]`` attribute declares that a test depends on a file or on a directory.

The ``VatTest`` test class of the example project uses a data provider that reads data from a CSV file:

.. literalinclude:: examples/test-impact-analysis/project/tests/VatTest.php
   :caption: A data provider that declares the file it reads
   :language: php

After ``tests/fixtures/vat.csv`` was changed, the tests that use this data provider can be affected, and no other test:

.. literalinclude:: examples/test-impact-analysis/fixture-changed.out

The attribute can be used on a test class, on a test method, and on a data provider method, and it can be used more than once:

- Used on a test class, it applies to the tests of this class and to the tests of the classes that extend it.
- Used on a data provider method, or on the class of a data provider, it applies to the tests that the data provider provides data for.
- A relative path is resolved against the directory of the file in which the attribute is used.
- When the path is a directory, the test depends on every file in this directory and in its subdirectories: it can be affected when one of these files changes, is added, or is removed.
- When the path does not exist, PHPUnit emits a warning such as ``Fixture "fixtures/vat.csv" does not exist, the attribute is ignored`` for each test the attribute applies to.

The attribute only has an effect while test impact data is recorded.


.. _test-impact-analysis.dependencies.register-fixture:

Registering fixtures while a test runs
--------------------------------------

A test that only knows which files it depends on while it runs registers them with the ``registerFixture()`` method:

.. code-block:: php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class InvoiceTemplateTest extends TestCase
    {
        public function testTemplateShowsTotal(): void
        {
            $template = (new TemplateLocator)->locate('invoice');

            $this->registerFixture($template);

            $this->assertStringContainsString(
                'Total: 357',
                (new Renderer)->render($template, ['total' => 357]),
            );
        }
    }

``registerFixture()`` accepts one or more paths of files and directories.
It can be called in a test method as well as in the methods that are called before and after a test (see :ref:`fixtures`).
A relative path is resolved against the current working directory.
When a path does not exist, PHPUnit emits a warning for the test and ignores the path.

What is registered is recorded for the test together with what the test executed.
Calling the method has no effect while test impact data is not recorded.


.. _test-impact-analysis.dependencies.watched-files:

Watched files
-------------

A watched file is a file that is not first-party code and for which PHPUnit nevertheless notices a change, even when no test is recorded as depending on it.
When a watched file changed, the tests that are recorded as depending on it are run.
When no test is recorded as depending on it, every test is run.

The PHP files in the directories of the test suites that are not test files, such as helper classes for tests, are watched without further configuration.

Additional files are watched using the ``<watch>`` element of the :ref:`\<testImpactAnalysis\> <appendixes.xml-configuration-file.testImpactAnalysis>` element of the XML configuration file:

.. code-block:: xml

    <testImpactAnalysis>
        <watch>
            <directory>config</directory>
            <directory suffix=".yaml">resources</directory>
            <file>.env.testing</file>
        </watch>
    </testImpactAnalysis>

The example shown above watches the files with ``.php`` suffix in the ``config`` directory, the files with ``.yaml`` suffix in the ``resources`` directory, and the ``.env.testing`` file.

Watch the files that are read rather than executed and that can change what many tests do, such as configuration files.
Declare a file that only a few tests depend on as a :ref:`fixture <test-impact-analysis.dependencies.uses-fixture>` of these tests instead, so that a change to it does not cause every test to be run.


.. _test-impact-analysis.dependencies.added-files:

Files that are added
--------------------

A file of first-party code that is added is, by default, a change that PHPUnit cannot account for, and every test is run.
PHPUnit cannot know whether code that already exists uses the new file without having been changed itself: a framework that discovers classes by looking at the file system, or by deriving their names from a convention, does this.

Where this does not happen, a file that is added can only affect a test through another file that was changed to use it.
That other file is a change that PHPUnit can account for.
The ``<addedFilesAreReachedThroughChanges>`` element of the ``<testImpactAnalysis>`` element configures the files for which this is the case:

.. code-block:: xml

    <testImpactAnalysis>
        <addedFilesAreReachedThroughChanges>
            <include>
                <directory>src</directory>
            </include>

            <exclude>
                <directory>src/Listeners</directory>
            </exclude>
        </addedFilesAreReachedThroughChanges>
    </testImpactAnalysis>

With the configuration shown above, a file that is added to the ``src`` directory does not cause every test to be run, unless it is added to the ``src/Listeners`` directory.
The tests that depend on the files that changed, and the tests that were added, are run.

``--explain-impacted`` lists the files that were added and did not cause every test to be run because of this configuration.

Changing this configuration causes the recording to be discarded (see :ref:`test-impact-analysis.validity`).


.. _test-impact-analysis.coverage-targets:

Deriving test impact data from code coverage targets
====================================================

Instead of observing what the tests execute, PHPUnit can work out what each test depends on from the code coverage targets that the test declares using attributes such as ``#[CoversClass]`` and ``#[UsesClass]`` (see :ref:`code-coverage.targeting-units-of-code`).
This is enabled with the ``deriveTestImpactDataFromCoverageTargets`` attribute of the ``<phpunit>`` element of the XML configuration file or with the ``--derive-test-impact-data-from-coverage-targets`` CLI option.
Enabling it also enables the recording of test impact data.

Test impact data that is derived this way

- does not require a code coverage driver and does not slow the tests down,
- is derived before the tests are run, for every test of the test run, and
- contains, for each test, the files of the units of code that the test declares to cover or to use, the files that the test class is made of, and the fixtures that are declared with ``#[UsesFixture]``.

It is only as complete as the code coverage targets are.
This is why the following applies:

- Code coverage metadata has to be required for all tests, using the :ref:`requireCoverageMetadata <appendixes.xml-configuration-file.phpunit.requireCoverageMetadata>` attribute of the XML configuration file, and this requirement must not be relaxed for tests of a certain size. Otherwise, PHPUnit emits a test runner warning and does not derive test impact data.
- First-party code and a cache directory have to be configured.
- PHPUnit emits a test runner warning unless :ref:`beStrictAboutCoverageMetadata <appendixes.xml-configuration-file.phpunit.beStrictAboutCoverageMetadata>` is enabled. Without this setting, a test that executes code it does not declare is not considered risky while code coverage is collected, and nothing checks that the code coverage targets are complete.
- Nothing is derived for a test that declares to cover nothing, for instance using ``#[CoversNothing]``, and for a test that declares a code coverage target that does not exist or is not first-party code. Such a test is run whenever PHPUnit runs only the tests that are affected by a change.
- Files that a test registers with ``registerFixture()`` are not part of what is derived.

Test impact data that is derived from code coverage targets and test impact data that was observed are never combined.
A test run that derives test impact data discards a recording that was observed, and the other way around.
A test run that uses the one kind does not select tests based on a recording of the other kind and runs every test instead.

The ``--do-not-record-test-impact-data`` CLI option also disables the derivation of test impact data that is enabled in the XML configuration file.


.. _test-impact-analysis.validity:

When a recording is not used
============================

Test impact data describes what the tests depend on under the circumstances under which it was recorded.
When these circumstances change, the recording as a whole can no longer be relied on.
PHPUnit then discards it: every test is run, and a test run that records test impact data starts a new recording.

A recording is discarded when one of the following is not what it was when the recording was made:

- The version of PHPUnit
- The version of PHP
- A setting that can change which code a test executes: the PHP settings, constants, and variables that are configured using the :ref:`\<php\> <appendixes.xml-configuration-file.php>` element, the bootstrap scripts that are configured for test suites, the test runner extensions that are loaded, and the settings for process isolation and for the backup of global variables and static properties
- The contents of a bootstrap script
- What is configured as first-party code using the ``<source>`` element. A file that is added to a directory that is already configured does not change this.
- What is configured using the :ref:`\<addedFilesAreReachedThroughChanges\> <test-impact-analysis.dependencies.added-files>` element
- The contents of the ``composer.lock`` file. PHPUnit looks for this file in the directory of the XML configuration file and, when it is not there, in the directories above it. A change to this file is how PHPUnit notices that third-party code changed.

The reason is reported, for instance by ``--only-impacted``::

    Impact:        every test is run: a bootstrap script changed since the test impact data was recorded

Settings that do not change which code a test executes, such as those for the output, for logging, for code coverage reports, for stopping the test run early, and for the order in which tests are run, do not cause a recording to be discarded.


.. _test-impact-analysis.sharing:

Using a recording in another checkout
=====================================

The recording names files relative to the directory of the XML configuration file, or relative to the current working directory when no XML configuration file is used.
A recording that was made in one checkout of a project can therefore be used in another checkout of the same project, for instance on another machine or in another job of a continuous integration pipeline, by copying the cache directory.

This makes the following workflow possible:

#. A test run that runs every test records test impact data, for instance for each commit on the main branch. The cache directory is stored.
#. A test run for a change that is based on this commit restores the cache directory and runs only the tests that are affected, using ``--only-impacted`` or by :ref:`naming what changed <test-impact-analysis.selecting.naming-what-changed>`.

Keep the following in mind:

- With ``--only-impacted``, what changed is everything that differs from what was recorded. The ``Recorded`` line of the output shows when this was.
- The version of PHP, the version of PHPUnit, and the contents of ``composer.lock`` have to be the same where the recording is made and where it is used (see :ref:`test-impact-analysis.validity`).
- Recording requires a code coverage driver, using a recording does not. Disable recording with ``--do-not-record-test-impact-data`` where no code coverage driver is available.
- Test runs that use the same cache directory at the same time do not overwrite what the others recorded.
- Run every test at some point before a change is released, for instance when it is merged (see :ref:`test-impact-analysis.limitations`).


.. _test-impact-analysis.other-features:

Test impact analysis and other features of PHPUnit
==================================================

Parallel test execution
    Test impact data is recorded by the worker processes and combined by the main process when tests are run in :ref:`parallel <parallel-test-execution>`.
    When only the tests that are affected are run, only these tests are distributed among the worker processes.

Tests that are run in separate processes
    What a test that is run in a separate process executes is recorded like that of any other test.
    In addition, the files of first-party code that its process loaded are recorded for it (see :ref:`test-impact-analysis.recording.what-is-recorded`).

Repeating and retrying tests
    The repetitions of a test that is repeated, and the attempts of a test that is retried, are recorded as one test (see :ref:`flaky-tests`).

Property-based testing
    What the examples of a :ref:`property <property-based-testing>` execute is recorded for the property.
    Examples that are run while a counterexample is shrunk are not recorded.
    When only the tests that are affected are run and none of them is a property, the random seed that is used for properties is not shown.

Data providers
    Each data set of a test that uses a data provider is a test of its own.
    A data set that is added has never been recorded and is run.

Dependencies between tests
    Tests that depend on other tests are run together with the tests they depend on (see :ref:`test-impact-analysis.selecting.which-tests`).

PHPT tests
    PHPT tests are not recorded and are always run.

Test run history
    Running only the tests that are affected requires the test run history, which is how PHPUnit knows the tests that did not pass when they were last run.


.. _test-impact-analysis.limitations:

Limitations
===========

- Dependencies are recorded per file. A change to a file can affect every test that depends on this file, no matter which part of the file was changed.
- A test is only recorded as depending on a file of first-party code when it executes a line of that file. Code that a test uses without executing a line of the file that declares it, for instance an interface or a constant of a class, is not observed, unless the test is run in a separate process.
- Dependencies that are neither first-party code that is executed, nor declared as fixtures, nor watched are not known to PHPUnit. Examples are environment variables, the contents of a database, and services that are accessed over the network.
- A change to third-party code is only noticed when the ``composer.lock`` file changes.
- Recording what the tests execute requires a code coverage driver and slows the tests down.
- Running only the tests that are affected does not replace running every test. It shortens the time until you learn about a problem with a change, and it relies on a recording that describes the past.
