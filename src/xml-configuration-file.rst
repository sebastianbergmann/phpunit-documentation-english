

.. _appendixes.xml-configuration-file:

**********************
XML Configuration File
**********************

.. _appendixes.xml-configuration-file.phpunit:

The ``<phpunit>`` Element
=========================

.. _appendixes.xml-configuration-file.phpunit.backupGlobals:

The ``backupGlobals`` Attribute
-------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

PHPUnit can optionally backup all global and super-global variables before each test and restore this backup after each test.

This attribute configures this operation for all tests. This configuration can be overridden using the ``BackupGlobals`` attribute on the test case class and test method level.

.. _appendixes.xml-configuration-file.phpunit.backupStaticProperties:

The ``backupStaticProperties`` Attribute
----------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

PHPUnit can optionally backup all static properties in all declared classes before each test and restore this backup after each test.

This attribute configures this operation for all tests. This configuration can be overridden using the ``BackupStaticProperties`` attribute on the test case class and test method level.

.. _appendixes.xml-configuration-file.phpunit.bootstrap:

The ``bootstrap`` Attribute
---------------------------

This attribute configures the bootstrap script that is loaded before the tests are executed. This script usually only registers the autoloader callback that is used to load the code under test.

.. _appendixes.xml-configuration-file.phpunit.cacheDirectory:

The ``cacheDirectory`` Attribute
--------------------------------

This attribute configures the directory in which PHPUnit stores data between test suite runs.
The following data is stored in this directory:

- ``test-results``: Results of the previous test suite run (used for reordering tests based on previous defects or duration, for instance; see below)
- ``code-coverage``: Results of static analysis of tested code and test code (only written when code coverage reporting is requested; significantly improves performance of code coverage analysis on subsequent runs)

.. _appendixes.xml-configuration-file.phpunit.cacheResult:

The ``cacheResult`` Attribute
-----------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

This attribute configures the storing of test results in the ``test-results`` cache file. This is required for ordering tests by defects, duration, or size with the ``executionOrder`` attribute (see :ref:`appendixes.xml-configuration-file.phpunit.executionOrder`).

Use ``--do-not-cache-result`` on the command line or set this attribute to ``false`` to prevent the writing of the ``test-results`` cache file.

.. _appendixes.xml-configuration-file.phpunit.colors:

The ``colors`` Attribute
------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether colors are used in PHPUnit's output.

Setting this attribute to ``true`` is equivalent to using the ``--colors=auto`` CLI option.

Setting this attribute to ``false`` is equivalent to using the ``--colors=never`` CLI option.

.. _appendixes.xml-configuration-file.phpunit.columns:

The ``columns`` Attribute
-------------------------

Possible values: integer or string ``max`` (default: ``80``)

This attribute configures the number of columns to use for progress output.

If ``max`` is defined as value, the number of columns will be maximum of the current terminal.

.. _appendixes.xml-configuration-file.phpunit.controlGarbageCollector:

The ``controlGarbageCollector`` Attribute
-----------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

When the PHP runtime automatically performs `garbage collection <https://www.php.net/manual/en/features.gc.php>`_
then this may happen in the middle of the preparation (fixture setup) of a test or in the middle of the execution of a test.
This can have a negative impact on test execution performance.

Configuring ``controlGarbageCollector="true"`` has the following effects:

* Deactivate automatic garbage collection using ``gc_disable()`` before the first test is run
* Trigger garbage collection using ``gc_collect_cycles()`` before the first test is run
* Trigger garbage collection using ``gc_collect_cycles()`` after each n-th test
* Trigger garbage collection after using ``gc_collect_cycles()`` after the last test was run
* Activate automatic garbage collection using ``gc_enable()`` after the last test was run

The number of tests to execute before garbage collection is triggered is controlled by
``numberOfTestsBeforeGarbageCollection`` (see below).

.. _appendixes.xml-configuration-file.phpunit.numberOfTestsBeforeGarbageCollection:

The ``numberOfTestsBeforeGarbageCollection`` Attribute
------------------------------------------------------

Possible values: integer (default: ``100``)

Configures the number of tests to execute before garbage collection is triggered (see above).

.. _appendixes.xml-configuration-file.phpunit.requireCoverageMetadata:

The ``requireCoverageMetadata`` Attribute
-----------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether a test will be marked as risky (see :ref:`risky-tests.unintentionally-covered-code`) when it does not indicate the code it intends to cover using an attribute.

.. _appendixes.xml-configuration-file.phpunit.requireSealedMockObjects:

The ``requireSealedMockObjects`` Attribute
------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether a test will be marked as risky (see :ref:`risky-tests.unintentionally-covered-code`) when it does not seal the mock objects it creates (see :ref:`test-doubles.mock-objects.requiring-sealed-mock-objects`).

.. _appendixes.xml-configuration-file.phpunit.processIsolation:

The ``processIsolation`` Attribute
----------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether each test should be run in a separate PHP process for increased isolation.

.. _appendixes.xml-configuration-file.phpunit.stopOnDefect:

The ``stopOnDefect`` Attribute
------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after the first error, failure, warning, or risky test.

.. _appendixes.xml-configuration-file.phpunit.stopOnError:

The ``stopOnError`` Attribute
-----------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after the first error.

.. _appendixes.xml-configuration-file.phpunit.stopOnFailure:

The ``stopOnFailure`` Attribute
-------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after the first failure.

.. _appendixes.xml-configuration-file.phpunit.stopOnWarning:

The ``stopOnWarning`` Attribute
-------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after the first test warning.

.. _appendixes.xml-configuration-file.phpunit.stopOnRisky:

The ``stopOnRisky`` Attribute
-----------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after the first risky test.

.. _appendixes.xml-configuration-file.phpunit.stopOnDeprecation:

The ``stopOnDeprecation`` Attribute
-----------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after first test
that triggered a deprecation (``E_DEPRECATED``, ``E_USER_DEPRECATED``, or PHPUnit deprecation).

.. _appendixes.xml-configuration-file.phpunit.stopOnNotice:

The ``stopOnNotice`` Attribute
------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after first test
that triggered a notice (``E_STRICT``, ``E_NOTICE``, or ``E_USER_NOTICE``).

.. _appendixes.xml-configuration-file.phpunit.stopOnSkipped:

The ``stopOnSkipped`` Attribute
-------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after first skipped test.

.. _appendixes.xml-configuration-file.phpunit.stopOnIncomplete:

The ``stopOnIncomplete`` Attribute
----------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after first incomplete test.

.. _appendixes.xml-configuration-file.phpunit.failOnAllIssues:

The ``failOnAllIssues`` Attribute
---------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when an issue is triggered.

Configuring ``failOnAllIssues="true"`` is equivalent to configuring
``failOnDeprecation="true"``, ``failOnEmptyTestSuite="true"``,
``failOnIncomplete="true"``, ``failOnNotice="true"``,
``failOnPhpunitDeprecation="true"``, ``failOnPhpunitNotice="true"``,
``failOnPhpunitWarning="true"``, ``failOnRisky="true"``,
``failOnSkipped="true"``, and ``failOnWarning="true"``.

.. admonition:: Precedence over fine-grained ``failOn*`` settings

   ``failOnAllIssues="true"`` takes precedence over the fine-grained
   ``failOn*`` attributes documented below. When ``failOnAllIssues`` is
   configured to ``true``, configuring a fine-grained ``failOn*`` attribute
   to ``false`` has no effect. For example, configuring
   ``failOnAllIssues="true"`` together with ``failOnDeprecation="false"``
   does not disable failing on deprecations.

   The ``--do-not-fail-on-*`` CLI options are the only way to not fail on
   a specific issue type when ``failOnAllIssues="true"`` is configured.

   So that this conflict does not go unnoticed, PHPUnit emits a test runner
   warning when a fine-grained ``failOn*`` attribute is explicitly configured
   to ``false`` while ``failOnAllIssues`` is enabled, for example
   ``failOnDeprecation="false" has no effect because failOnAllIssues is
   enabled. Use the --do-not-fail-on-deprecation CLI option instead``.

.. admonition:: Backward Compatibility

   Please note that if you configure ``failOnAllIssues`` to ``true``
   then you opt in to failing on additional issues in later versions of PHPUnit
   that will be put under the control of this setting. This is not considered
   to be a break of backward compatibility and rather the expected behaviour
   of this setting.

.. _appendixes.xml-configuration-file.phpunit.failOnEmptyTestSuite:

The ``failOnEmptyTestSuite`` Attribute
--------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when the configured test suite is empty.

.. _appendixes.xml-configuration-file.phpunit.failOnWarning:

The ``failOnWarning`` Attribute
-------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that had warnings.

.. _appendixes.xml-configuration-file.phpunit.failOnRisky:

The ``failOnRisky`` Attribute
-----------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that were marked as risky.

.. _appendixes.xml-configuration-file.phpunit.failOnDeprecation:

The ``failOnDeprecation`` Attribute
-----------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that triggered a deprecation (``E_DEPRECATED`` or ``E_USER_DEPRECATED``).

.. _appendixes.xml-configuration-file.phpunit.failOnSelfDeprecation:

The ``failOnSelfDeprecation`` Attribute
---------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that triggered a deprecation (``E_DEPRECATED`` or ``E_USER_DEPRECATED``) by first-party code in first-party code.

This setting only takes effect when PHPUnit can identify how a deprecation was triggered, which requires the ``<source>`` element (see :ref:`appendixes.xml-configuration-file.source`) to be configured. See :ref:`error-handling.failing-on-deprecations-by-trigger` for details.

.. _appendixes.xml-configuration-file.phpunit.failOnDirectDeprecation:

The ``failOnDirectDeprecation`` Attribute
-----------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that triggered a deprecation (``E_DEPRECATED`` or ``E_USER_DEPRECATED``) by first-party code in third-party code.

This setting only takes effect when PHPUnit can identify how a deprecation was triggered, which requires the ``<source>`` element (see :ref:`appendixes.xml-configuration-file.source`) to be configured. See :ref:`error-handling.failing-on-deprecations-by-trigger` for details.

.. _appendixes.xml-configuration-file.phpunit.failOnIndirectDeprecation:

The ``failOnIndirectDeprecation`` Attribute
-------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that triggered a deprecation (``E_DEPRECATED`` or ``E_USER_DEPRECATED``) by third-party code.

This setting only takes effect when PHPUnit can identify how a deprecation was triggered, which requires the ``<source>`` element (see :ref:`appendixes.xml-configuration-file.source`) to be configured. See :ref:`error-handling.failing-on-deprecations-by-trigger` for details.

.. _appendixes.xml-configuration-file.phpunit.failOnPhpunitDeprecation:

The ``failOnPhpunitDeprecation`` Attribute
------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but PHPUnit deprecations were triggered.

.. _appendixes.xml-configuration-file.phpunit.failOnPhpunitNotice:

The ``failOnPhpunitNotice`` Attribute
------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but PHPUnit notices were triggered.

.. _appendixes.xml-configuration-file.phpunit.failOnPhpunitWarning:

The ``failOnPhpunitWarning`` Attribute
------------------------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but PHPUnit warnings were triggered.

.. _appendixes.xml-configuration-file.phpunit.failOnNotice:

The ``failOnNotice`` Attribute
------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that triggered a notice (``E_STRICT``, ``E_NOTICE``, or ``E_USER_NOTICE``).

.. _appendixes.xml-configuration-file.phpunit.failOnSkipped:

The ``failOnSkipped`` Attribute
-------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that were marked as skipped.

.. _appendixes.xml-configuration-file.phpunit.failOnIncomplete:

The ``failOnIncomplete`` Attribute
----------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that were marked as incomplete.

.. _appendixes.xml-configuration-file.phpunit.beStrictAboutChangesToGlobalState:

The ``beStrictAboutChangesToGlobalState`` Attribute
---------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether PHPUnit should mark a test as risky when global state is manipulated by the code under test (or the test code).

.. _appendixes.xml-configuration-file.phpunit.beStrictAboutOutputDuringTests:

The ``beStrictAboutOutputDuringTests`` Attribute
------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether PHPUnit should mark a test as risky when the code under test (or the test code) prints output.

.. _appendixes.xml-configuration-file.phpunit.beStrictAboutTestsThatDoNotTestAnything:

The ``beStrictAboutTestsThatDoNotTestAnything`` Attribute
---------------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

This attribute configures whether PHPUnit should mark a test as risky when no assertions are performed (expectations are also considered).

.. _appendixes.xml-configuration-file.phpunit.beStrictAboutCoverageMetadata:

The ``beStrictAboutCoverageMetadata`` Attribute
-----------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether PHPUnit should mark a test as risky when it executes code that is not specified to be covered or used using an attribute.

.. _appendixes.xml-configuration-file.phpunit.requireCoverageContribution:

The ``requireCoverageContribution`` Attribute
---------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether PHPUnit should mark a test as risky when it has code coverage targets but does not execute any line of the targeted code.

.. _appendixes.xml-configuration-file.phpunit.enforceTimeLimit:

The ``enforceTimeLimit`` Attribute
----------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether time limits should be enforced.

.. _appendixes.xml-configuration-file.phpunit.defaultTimeLimit:

The ``defaultTimeLimit`` Attribute
----------------------------------

Possible values: integer (default: ``0``)

This attribute configures the default time limit (in seconds).

.. _appendixes.xml-configuration-file.phpunit.diffContext:

The ``diffContext`` Attribute
-----------------------------

Possible values: integer (default: ``3``)

This attribute configures the number of context lines shown around changes in diffs.

.. _appendixes.xml-configuration-file.phpunit.timeoutForSmallTests:

The ``timeoutForSmallTests`` Attribute
--------------------------------------

Possible values: integer (default: ``1``)

This attribute configures the time limit for tests attributed with ``Small`` (in seconds).

.. _appendixes.xml-configuration-file.phpunit.timeoutForMediumTests:

The ``timeoutForMediumTests`` Attribute
---------------------------------------

Possible values: integer (default: ``10``)

This attribute configures the time limit for tests attributed with ``Medium`` (in seconds).

.. _appendixes.xml-configuration-file.phpunit.timeoutForLargeTests:

The ``timeoutForLargeTests`` Attribute
--------------------------------------

Possible values: integer (default: ``60``)

This attribute configures the time limit for tests attributed with ``Large`` (in seconds).

.. _appendixes.xml-configuration-file.phpunit.defaultTestSuite:

The ``defaultTestSuite`` Attribute
----------------------------------

This attribute configures the name of the default test suite.

.. _appendixes.xml-configuration-file.phpunit.stderr:

The ``stderr`` Attribute
------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether PHPUnit should print its output to ``stderr`` instead of ``stdout``.

.. _appendixes.xml-configuration-file.phpunit.reverseDefectList:

The ``reverseDefectList`` Attribute
-----------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether tests that are not successful should be printed in reverse order.

.. _appendixes.xml-configuration-file.phpunit.registerMockObjectsFromTestArgumentsRecursively:

The ``registerMockObjectsFromTestArgumentsRecursively`` Attribute
-----------------------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether arrays and object graphs that are passed from one test to another using the ``Depends*`` attributes should be recursively scanned for mock objects.

.. _appendixes.xml-configuration-file.phpunit.extensionsDirectory:

The ``extensionsDirectory`` Attribute
-------------------------------------

When ``phpunit.phar`` is used then this attribute may be used to configure a directory from which all ``*.phar`` files will be loaded as extensions for the PHPUnit test runner.

.. _appendixes.xml-configuration-file.phpunit.executionOrder:

The ``executionOrder`` Attribute
--------------------------------

Possible values: ``default``, ``defects``, ``depends``, ``no-depends``, ``duration-ascending``, ``duration-descending``, ``random``, ``reverse``, ``size-ascending``, ``size-descending`` (default: ``default``)

Using multiple values is possible. These need to be separated by ``,``.

This attribute configures the order in which tests are executed.

Primary orderings:

- ``default``: ordered in the order in which PHPUnit found the tests (does not use the result cache)
- ``defects``: ordered by defect (errored, failed, warning, incomplete, risky, skipped, unknown, passed), requires enabled :ref:`result cache<appendixes.xml-configuration-file.phpunit.cacheResult>`
- ``duration-ascending``: ordered by duration (fastest test first, slowest test last), requires enabled :ref:`result cache<appendixes.xml-configuration-file.phpunit.cacheResult>`
- ``duration-descending``: ordered by duration (slowest test first, fastest test last), requires enabled :ref:`result cache<appendixes.xml-configuration-file.phpunit.cacheResult>`
- ``random``: ordered randomly
- ``reverse``: ordered as PHPUnit found the tests, then ordered in reverse
- ``size-ascending``: ordered by size (small, medium, large, unknown), also see :ref:`appendixes.attributes.Small`, :ref:`appendixes.attributes.Medium`, and :ref:`appendixes.attributes.Large`
- ``size-descending``: ordered by size in reverse (large, medium, small, unknown)

Dependency modifiers (combined with the primary orderings above via ``,``):

- ``depends``: order by dependency first (tests without dependencies first, dependent tests last), then apply the remaining orderings
- ``no-depends``: do not order by dependency, then apply the remaining orderings

Valid combinations include ``depends,defects``, ``depends,duration-ascending``, ``depends,duration-descending``, ``depends,random``, ``depends,reverse``, ``depends,size-ascending``, ``depends,size-descending``, and the corresponding ``no-depends,*`` variants.

The ``defects`` ordering can also be combined with a secondary ordering that is applied within each defect bucket: ``defects,duration-ascending``, ``defects,duration-descending``, ``defects,random``, ``defects,reverse``, ``defects,size-ascending``, ``defects,size-descending``. These can also be prefixed with ``depends,`` or ``no-depends,``, for example ``depends,defects,duration-ascending``.

.. _appendixes.xml-configuration-file.phpunit.resolveDependencies:

The ``resolveDependencies`` Attribute
-------------------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

This attribute configures whether dependencies between tests (expressed using the ``Depends*`` attributes) should be resolved.

.. _appendixes.xml-configuration-file.phpunit.testdox:

The ``testdox`` Attribute
-------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the output should be printed in TestDox format.

.. _appendixes.xml-configuration-file.phpunit.testdoxSummary:

The ``testdoxSummary`` Attribute
--------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether TestDox output for non-successful tests should be repeated after the regular TestDox output.

.. _appendixes.xml-configuration-file.phpunit.displayDetailsOnAllIssues:

The ``displayDetailsOnAllIssues`` Attribute
-------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether details on all issues should be printed.

.. admonition:: Backward Compatibility

   Please note that if you configure ``displayDetailsOnAllIssues`` to ``true``
   then you opt in to printing additional issues in later versions of PHPUnit
   that will be put under the control of this setting. This is not considered
   to be a break of backward compatibility and rather the expected behaviour
   of this setting.

.. _appendixes.xml-configuration-file.phpunit.displayDetailsOnIncompleteTests:

The ``displayDetailsOnIncompleteTests`` Attribute
-------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether details on incomplete tests should be printed.

.. _appendixes.xml-configuration-file.phpunit.displayDetailsOnSkippedTests:

The ``displayDetailsOnSkippedTests`` Attribute
----------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether details on skipped tests should be printed.

.. _appendixes.xml-configuration-file.phpunit.displayDetailsOnTestsThatTriggerDeprecations:

The ``displayDetailsOnTestsThatTriggerDeprecations`` Attribute
--------------------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether details on tests that triggered deprecations should be printed.

.. _appendixes.xml-configuration-file.phpunit.displayDetailsOnPhpunitDeprecations:

The ``displayDetailsOnPhpunitDeprecations`` Attribute
-----------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether details on PHPUnit deprecations should be printed.

.. _appendixes.xml-configuration-file.phpunit.displayDetailsOnPhpunitNotices:

The ``displayDetailsOnPhpunitNotices`` Attribute
-----------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether details on PHPUnit notices should be printed.

.. _appendixes.xml-configuration-file.phpunit.displayDetailsOnTestsThatTriggerErrors:

The ``displayDetailsOnTestsThatTriggerErrors`` Attribute
--------------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether details on tests that triggered errors should be printed.

.. _appendixes.xml-configuration-file.phpunit.displayDetailsOnTestsThatTriggerNotices:

The ``displayDetailsOnTestsThatTriggerNotices`` Attribute
---------------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether details on tests that triggered notices should be printed.

.. _appendixes.xml-configuration-file.phpunit.displayDetailsOnTestsThatTriggerWarnings:

The ``displayDetailsOnTestsThatTriggerWarnings`` Attribute
----------------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether details on tests that triggered warnings should be printed.

.. _appendixes.xml-configuration-file.phpunit.shortenArraysForExportThreshold:

The ``shortenArraysForExportThreshold`` Attribute
-------------------------------------------------

Possible values: integer (default: ``0``)

This attribute configures whether the export of arrays should be limited to a specified number of elements.

When set to ``0`` (default) then the export of arrays is not limited.

.. _appendixes.xml-configuration-file.phpunit.warnWhenPhpIsNotConfiguredForDevelopment:

The ``warnWhenPhpIsNotConfiguredForDevelopment`` Attribute
----------------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test runner triggers a warning when PHP is not configured for development (see :ref:`installation.configuring-php-for-development`).

When this attribute is set to ``true``, a test runner warning is triggered for each PHP configuration setting that is not configured as recommended.

This configuration can be overridden for a single run using the ``--warn-when-php-is-not-configured-for-development`` and ``--do-not-warn-when-php-is-not-configured-for-development`` CLI options.

.. _appendixes.xml-configuration-file.testsuites:

The ``<testsuites>`` Element
============================

Parent element: ``<phpunit>``

This element is the root for one or more ``<testsuite>`` elements that are used to configure the tests that are to be executed.

.. _appendixes.xml-configuration-file.testsuites.testsuite:

The ``<testsuite>`` Element
---------------------------

Parent element: ``<testsuites>``

A ``<testsuite>`` element must have a ``name`` attribute and may have one or more ``<directory>`` and/or ``<file>`` child elements that configure directories and/or files, respectively, that should be searched for tests. Files and directories can be excluded by using ``<exclude>`` child elements.

.. code-block:: xml

    <phpunit bootstrap="vendor/autoload.php">
      <testsuites>
        <testsuite name="unit">
          <directory>tests/unit</directory>
        </testsuite>

        <testsuite name="integration" bootstrap="tests/integration/bootstrap.php">
          <directory>tests/integration</directory>
        </testsuite>

        <testsuite name="smoke">
          <file>tests/smoke/FirstTest.php</file>
          <file>tests/smoke/SecondTest.php</file>
        </testsuite>
      </testsuites>
    </phpunit>

While the ``<directory>`` element adds all test classes found in a directory to the test suite, the ``<file>`` element adds the test class found in a single file. This is useful when only a few selected files from a directory should be part of a test suite.

The ``bootstrap`` Attribute
***************************

Possible values: string

The ``bootstrap`` attribute can be used to configure an additional bootstrap script for a test suite.

With the configuration shown above:

Invoking the PHPUnit test runner with ``phpunit`` loads ``vendor/autoload.php`` and ``tests/integration/bootstrap.php``.

Invoking the PHPUnit test runner with ``phpunit --testsuite unit`` loads only ``vendor/autoload.php``.

Invoking the PHPUnit test runner with ``phpunit --testsuite integration`` loads ``vendor/autoload.php`` and ``tests/integration/bootstrap.php``.

The script configured using the ``bootstrap`` attribute on the ``<phpunit>`` element is always loaded.
Each bootstrap script, even if configured multiple times, is only loaded once.

The ``phpVersion`` and ``phpVersionOperator`` Attributes
********************************************************

Possible values: string

A required PHP version can be specified using the ``phpVersion`` and ``phpVersionOperator`` attributes:

.. code-block:: xml

    <testsuites>
      <testsuite name="unit">
        <directory phpVersion="8.0.0" phpVersionOperator=">=">tests/unit</directory>
      </testsuite>
    </testsuites>

In the example above, the tests from the ``tests/unit`` directory are only added to the test suite if the PHP version is at least 8.0.0. The ``phpVersionOperator`` attribute is optional and defaults to ``>=``.

The ``groups`` Attribute
************************

Possible values: string

The tests that are found using ``<directory>`` and ``<file>`` elements can be added to a comma-separated list of groups:

.. code-block:: xml

    <testsuites>
      <testsuite name="unit">
        <directory groups="foo,bar">tests/foo-bar</directory>
      </testsuite>
    </testsuites>

.. _appendixes.xml-configuration-file.source:

The ``<source>`` Element
========================

Parent element: ``<phpunit>``

Configures the project's source code files. This is used to restrict code coverage analysis and reporting of deprecations, notices, and warnings to your own code, for instance, while excluding code from third-party dependencies.

In the following, we refer to code that is configured using this element as "your code" or "first-party code". We refer to code that is not "your code" as "third-party code".


.. _appendixes.xml-configuration-file.source.include:

The ``<include>`` Element
-------------------------

Parent element: ``<source>``

Configures a set of files to be included in the list of the project's source code files.

.. code-block:: xml

    <include>
        <directory suffix=".php">src</directory>
    </include>

The example shown above instructs PHPUnit to include all source code files with ``.php`` suffix in the ``src`` directory and its sub-directories.


.. _appendixes.xml-configuration-file.source.exclude:

The ``<exclude>`` Element
-------------------------

Parent element: ``<source>``

Configures a set of files to be excluded from the list of the project's source code files.

.. code-block:: xml

    <include>
        <directory suffix=".php">src</directory>
    </include>

    <exclude>
        <directory suffix=".php">src/generated</directory>
        <file>src/autoload.php</file>
    </exclude>

The example shown above instructs PHPUnit to include all source code files with ``.php`` suffix in the ``src`` directory and its sub-directories, but to exclude all files with ``.php`` suffix in the ``src/generated`` directory and its sub-directories as well as the ``src/autoload.php`` file.


.. _appendixes.xml-configuration-file.source.directory:

The ``<directory>`` Element
---------------------------

Parent elements: ``<include>``, ``<exclude>``

Configures a directory and its sub-directories for inclusion in or exclusion from the list of the project's source code files.

The ``prefix`` Attribute
************************

Possible values: string

Configures a prefix-based filter that is applied to the names of files in the directory and its sub-directories.

The ``suffix`` Attribute
************************

Possible values: string (default: ``'.php'``)

Configures a suffix-based filter that is applied to the names of files in the directory and its sub-directories.

The ``includeInCodeCoverage`` Attribute
***************************************

Possible values: ``true`` or ``false`` (default: ``true``)

Only meaningful when the ``<directory>`` element is a child of ``<include>``.

When set to ``false``, the files in the directory and its sub-directories are part of the project's source code files (for example, for the purpose of reporting deprecations triggered in them) but are not considered for code coverage analysis.


.. _appendixes.xml-configuration-file.source.file:

The ``<file>`` Element
----------------------

Parent elements: ``<include>``, ``<exclude>``

Configures a file for inclusion in or exclusion from the list of the project's source code files.

The ``includeInCodeCoverage`` Attribute
***************************************

Possible values: ``true`` or ``false`` (default: ``true``)

Only meaningful when the ``<file>`` element is a child of ``<include>``.

When set to ``false``, the file is part of the project's source code files (for example, for the purpose of reporting deprecations triggered in it) but is not considered for code coverage analysis.


.. _appendixes.xml-configuration-file.source.deprecationTrigger:

The ``<deprecationTrigger>`` Element
------------------------------------

Parent element: ``<source>``

Some libraries use a wrapper around PHP's ``trigger_error()`` function such as ``symfony/deprecation-contracts``
or ``doctrine/deprecations``. Using such a wrapper adds an additional stack frame that needs to be considered
when reporting of the location where a deprecation was triggered.

The ``<deprecationTrigger>`` element, together with its child elements ``<function>`` and ``<method>``
can be used to configure functions or methods, respectively, as deprecation triggers.

The ``ignoreUndefinedTriggers`` Attribute
*****************************************

Possible values: ``true`` or ``false`` (default: ``false``)

When set to ``true``, functions and methods configured as deprecation triggers that are not defined at the time the configuration is loaded are silently ignored instead of causing a test runner warning.

This is useful, for instance, when a deprecation trigger is provided by a library that is only installed as a development dependency in some projects.

.. _appendixes.xml-configuration-file.source.deprecationTrigger.function:

The ``<function>`` Element
**************************

Parent element: ``<deprecationTrigger>``

.. code-block:: xml

    <deprecationTrigger>
         <function>trigger_deprecation</function>
     </deprecationTrigger>

The example configuration shown above configures the global function ``trigger_deprecation()`` as a deprecation trigger.

.. _appendixes.xml-configuration-file.source.deprecationTrigger.method:

The ``<method>`` Element
************************

Parent element: ``<deprecationTrigger>``

.. code-block:: xml

    <deprecationTrigger>
         <method>DeprecationTrigger::triggerDeprecation</method>
     </deprecationTrigger>

The example configuration shown above configures the ``public`` ``static`` method ``triggerDeprecation()``
of the ``DeprecationTrigger`` class as a deprecation trigger.

.. _appendixes.xml-configuration-file.source.issueTriggerResolvers:

The ``<issueTriggerResolvers>`` Element
---------------------------------------

Parent element: ``<source>``

The ``<issueTriggerResolvers>`` element can be used to register custom issue trigger resolver classes.
An issue trigger resolver allows you to override how PHPUnit determines the caller and callee files
when classifying issues (deprecations, notices, warnings) as ``self``, ``direct``, ``indirect``, or ``test``.

This is useful when a framework or library wraps ``trigger_error()`` in a way that the
``<deprecationTrigger>`` element cannot handle, for instance when the wrapper is a method that requires
inspecting the error message or the stack trace arguments to determine the correct caller and callee.

.. _appendixes.xml-configuration-file.source.issueTriggerResolvers.issueTriggerResolver:

The ``<issueTriggerResolver>`` Element
**************************************

Parent element: ``<issueTriggerResolvers>``

.. code-block:: xml

    <issueTriggerResolvers>
        <issueTriggerResolver className="Vendor\FrameworkResolver"/>
    </issueTriggerResolvers>

The ``className`` attribute must be the fully qualified class name of a class that implements the
``PHPUnit\Runner\IssueTriggerResolver\Resolver`` interface.

Multiple resolvers can be registered:

.. code-block:: xml

    <issueTriggerResolvers>
        <issueTriggerResolver className="Vendor\FirstResolver"/>
        <issueTriggerResolver className="Vendor\SecondResolver"/>
    </issueTriggerResolvers>

Resolvers are called in the order they are listed. Each resolver can either return a
``PHPUnit\Runner\IssueTriggerResolver\Resolution`` object (to override the caller/callee determination)
or return ``null`` to defer to the next resolver in the chain. If no custom resolver handles the issue,
PHPUnit's default resolver is used.

At startup, PHPUnit validates each configured resolver:

- If the class does not exist, a test runner warning is emitted
- If the class does not implement ``PHPUnit\Runner\IssueTriggerResolver\Resolver``, a test runner warning is emitted

Invalid entries are skipped after the warning is emitted and do not prevent the test suite from running.

See :ref:`error-handling.issue-trigger-resolvers` for more information about implementing custom issue trigger resolvers.

.. _appendixes.xml-configuration-file.source.deprecationFilters:

The ``<deprecationFilters>`` Element
------------------------------------

Parent element: ``<source>``

The ``<deprecationFilters>`` element can be used to register custom deprecation filter classes.
A deprecation filter allows you to suppress individual deprecations based on custom logic, for instance
by inspecting the deprecation message, the file and line where it was triggered, or how the deprecation
was triggered (``self``, ``direct``, or ``indirect``).

.. _appendixes.xml-configuration-file.source.deprecationFilters.deprecationFilter:

The ``<deprecationFilter>`` Element
***********************************

Parent element: ``<deprecationFilters>``

.. code-block:: xml

    <deprecationFilters>
        <deprecationFilter className="App\Tests\MyDeprecationFilter"/>
    </deprecationFilters>

The ``className`` attribute must be the fully qualified class name of a class that implements the
``PHPUnit\Runner\DeprecationFilter`` interface and that can be instantiated without arguments.

Multiple filters can be registered:

.. code-block:: xml

    <deprecationFilters>
        <deprecationFilter className="App\Tests\FirstDeprecationFilter"/>
        <deprecationFilter className="App\Tests\SecondDeprecationFilter"/>
    </deprecationFilters>

A deprecation is ignored as soon as one of the registered filters returns ``true`` for it.

At startup, PHPUnit validates each configured filter:

- If the class does not exist, a test runner warning is emitted
- If the class does not implement ``PHPUnit\Runner\DeprecationFilter``, a test runner warning is emitted

Invalid entries are skipped after the warning is emitted and do not prevent the test suite from running.

See :ref:`error-handling.deprecation-filters` for more information about implementing custom deprecation filters.

.. _appendixes.xml-configuration-file.source.identifyIssueTrigger:

The ``<identifyIssueTrigger>`` Attribute
----------------------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

Configures whether PHPUnit identifies how an issue was triggered: by first-party code in first-party code (``self``), by first-party code in third-party code (``direct``), or by third-party code (``indirect``).

When this attribute is set to ``false``, all issues are treated as if it was unknown whether they were triggered by first-party code or third-party code. Settings that depend on trigger identification, such as ``ignoreSelfDeprecations``, ``ignoreDirectDeprecations``, ``ignoreIndirectDeprecations``, ``failOnSelfDeprecation``, ``failOnDirectDeprecation``, and ``failOnIndirectDeprecation``, then have no effect.

See :ref:`error-handling.failing-on-deprecations-by-trigger` for details.


.. _appendixes.xml-configuration-file.source.ignoreSelfDeprecations:

The ``<ignoreSelfDeprecations>`` Attribute
------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

Ignore deprecations (``E_DEPRECATED`` and ``E_USER_DEPRECATED``) triggered by first-party code in first-party code.


.. _appendixes.xml-configuration-file.source.ignoreDirectDeprecations:

The ``<ignoreDirectDeprecations>`` Attribute
--------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

Ignore deprecations (``E_DEPRECATED`` and ``E_USER_DEPRECATED``) triggered by first-party code in third-party code.


.. _appendixes.xml-configuration-file.source.ignoreIndirectDeprecations:

The ``<ignoreIndirectDeprecations>`` Attribute
----------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``, suggested: ``true``)

Ignore deprecations (``E_DEPRECATED`` and ``E_USER_DEPRECATED``) triggered by third-party code.


.. _appendixes.xml-configuration-file.source.restrictNotices:

The ``<restrictNotices>`` Attribute
-----------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

Restricts the reporting of ``E_STRICT``, ``E_NOTICE``, and ``E_USER_NOTICE``
`errors <https://www.php.net/manual/en/errorfunc.constants.php>`_ to the
list of the project's source code files.


.. _appendixes.xml-configuration-file.source.restrictWarnings:

The ``<restrictWarnings>`` Attribute
------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

Restricts the reporting of ``E_WARNING`` and ``E_USER_WARNING``
`errors <https://www.php.net/manual/en/errorfunc.constants.php>`_ to the
list of the project's source code files.


.. _appendixes.xml-configuration-file.source.baseline:

The ``<baseline>`` Attribute
----------------------------

Possible values: string

The baseline file to be used when running the test suite.


.. _appendixes.xml-configuration-file.source.ignoreSuppressionOfDeprecations:

The ``<ignoreSuppressionOfDeprecations>`` Attribute
---------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

Ignore the suppression (using the ``@`` operator) of ``E_USER_DEPRECATED``
`errors <https://www.php.net/manual/en/errorfunc.constants.php>`_.


.. _appendixes.xml-configuration-file.source.ignoreSuppressionOfPhpDeprecations:

The ``<ignoreSuppressionOfPhpDeprecations>`` Attribute
------------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

Ignore the suppression (using the ``@`` operator) of ``E_DEPRECATED``
`errors <https://www.php.net/manual/en/errorfunc.constants.php>`_.


.. _appendixes.xml-configuration-file.source.ignoreSuppressionOfErrors:

The ``<ignoreSuppressionOfErrors>`` Attribute
---------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

Ignore the suppression (using the ``@`` operator) of ``E_USER_ERROR``
`errors <https://www.php.net/manual/en/errorfunc.constants.php>`_.


.. _appendixes.xml-configuration-file.source.ignoreSuppressionOfNotices:

The ``<ignoreSuppressionOfNotices>`` Attribute
----------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

Ignore the suppression (using the ``@`` operator) of ``E_USER_NOTICE``
`errors <https://www.php.net/manual/en/errorfunc.constants.php>`_.


.. _appendixes.xml-configuration-file.source.ignoreSuppressionOfPhpNotices:

The ``<ignoreSuppressionOfPhpNotices>`` Attribute
-------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

Ignore the suppression (using the ``@`` operator) of ``E_STRICT`` and ``E_NOTICE``
`errors <https://www.php.net/manual/en/errorfunc.constants.php>`_.


.. _appendixes.xml-configuration-file.source.ignoreSuppressionOfWarnings:

The ``<ignoreSuppressionOfWarnings>`` Attribute
-----------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

Ignore the suppression (using the ``@`` operator) of ``E_USER_WARNING``
`errors <https://www.php.net/manual/en/errorfunc.constants.php>`_.


.. _appendixes.xml-configuration-file.source.ignoreSuppressionOfPhpWarnings:

The ``<ignoreSuppressionOfPhpWarnings>`` Attribute
--------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

Ignore the suppression (using the ``@`` operator) of ``E_WARNING``
`errors <https://www.php.net/manual/en/errorfunc.constants.php>`_.


.. _appendixes.xml-configuration-file.coverage:

The ``<coverage>`` Element
==========================

Parent element: ``<phpunit>``

The ``<coverage>`` element and its children can be used to configure code coverage:

.. code-block:: xml

    <coverage includeUncoveredFiles="true"
              pathCoverage="false"
              ignoreDeprecatedCodeUnits="true"
              disableCodeCoverageIgnore="true">
        <!-- ... -->
    </coverage>

The ``includeUncoveredFiles`` Attribute
---------------------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

When set to ``true``, all source code files that are configured to be considered for code coverage analysis will be included in the code coverage report(s). This includes source code files that are not executed while the tests are running.

The ``ignoreDeprecatedCodeUnits`` Attribute
-------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether code units annotated with ``@deprecated`` should be ignored from code coverage.

The ``pathCoverage`` Attribute
------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

When set to ``false``, only line coverage data will be collected, processed, and reported.

When set to ``true``, line coverage, branch coverage, and path coverage data will be collected, processed, and reported. This requires a code coverage driver that supports path coverage. Path Coverage is currently only implemented by Xdebug.

.. _appendixes.xml-configuration-file.coverage.driver:

The ``driver`` Attribute
------------------------

By default, PHPUnit automatically selects a code coverage driver from the drivers that are available in the current runtime environment (for instance, Xdebug or PCOV).

The ``driver`` attribute can be used to configure a custom code coverage driver class that PHPUnit should use instead of selecting one automatically:

.. code-block:: xml

    <coverage driver="My\Custom\Driver">
        <!-- ... -->
    </coverage>

The configured class must

* exist and be autoloadable,
* extend ``SebastianBergmann\CodeCoverage\Driver\Driver``, and
* be instantiable (not be ``abstract``).

When PHPUnit instantiates the configured driver class, it inspects the constructor of the class:

* When the constructor has at least one required parameter, PHPUnit passes the code coverage ``SebastianBergmann\CodeCoverage\Filter`` object as the first argument.
* Otherwise, the class is instantiated without arguments.

After the driver has been instantiated, PHPUnit configures its granularity according to the ``pathCoverage`` attribute.

Here is an example of a custom code coverage driver whose constructor receives the ``Filter`` object:

.. code-block:: php

    <?php declare(strict_types=1);
    namespace My\Custom;

    use SebastianBergmann\CodeCoverage\Data\RawCodeCoverageData;
    use SebastianBergmann\CodeCoverage\Driver\Driver as CodeCoverageDriver;
    use SebastianBergmann\CodeCoverage\Filter;

    final class Driver extends CodeCoverageDriver
    {
        private readonly Filter $filter;

        public function __construct(Filter $filter)
        {
            $this->filter = $filter;
        }

        public function name(): string
        {
            return 'My Custom Driver';
        }

        public function version(): string
        {
            return '1.0.0';
        }

        public function start(): void
        {
            // ...
        }

        public function stop(): RawCodeCoverageData
        {
            // ...
        }
    }

.. note::

   ``RawCodeCoverageData`` is an internal implementation detail that is not covered by the backward compatibility promise for ``phpunit/php-code-coverage``. This might change in the future, but for the time being, implementors of custom coverage drivers must realize that they are dealing with library internals.

The ``disableCodeCoverageIgnore`` Attribute
-------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether metadata to ignore code should be ignored.

.. _appendixes.xml-configuration-file.coverage.report:

The ``<report>`` Element
------------------------

Parent element: ``<coverage>``

Configures the code coverage reports to be generated.

.. code-block:: xml

    <report>
        <clover outputFile="clover.xml"/>
        <cobertura outputFile="cobertura.xml"/>
        <crap4j outputFile="crap4j.xml" threshold="50"/>
        <html outputDirectory="html-coverage" lowUpperBound="50" highLowerBound="90"/>
        <php outputFile="coverage.php"/>
        <text outputFile="coverage.txt" showUncoveredFiles="false" showOnlySummary="true"/>
        <xml outputDirectory="xml-coverage"/>
    </report>


.. _appendixes.xml-configuration-file.coverage.report.clover:

The ``<clover>`` Element
************************

Parent element: ``<report>``

Configures a code coverage report in Clover XML format.

The ``outputFile`` Attribute
++++++++++++++++++++++++++++

Possible values: string

The file to which the Clover XML report is written.

.. _appendixes.xml-configuration-file.coverage.report.cobertura:

The ``<cobertura>`` Element
***************************

Parent element: ``<report>``

Configures a code coverage report in Cobertura XML format.

The ``outputFile`` Attribute
++++++++++++++++++++++++++++

Possible values: string

The file to which the Cobertura XML report is written.

.. _appendixes.xml-configuration-file.coverage.report.crap4j:

The ``<crap4j>`` Element
************************

Parent element: ``<report>``

Configures a code coverage report in Crap4J XML format.

The ``outputFile`` Attribute
++++++++++++++++++++++++++++

Possible values: string

The file to which the Crap4J XML report is written.

The ``threshold`` Attribute
+++++++++++++++++++++++++++

Possible values: integer (default: ``50``)


.. _appendixes.xml-configuration-file.coverage.report.html:

The ``<html>`` Element
**********************

Parent element: ``<report>``

Configures a code coverage report in HTML format.

The ``outputDirectory`` Attribute
+++++++++++++++++++++++++++++++++

Possible values: string

The directory to which the HTML report is written.

When this attribute is not set, the ``--coverage-html`` :ref:`command-line option <appendixes.cli-options.code-coverage>` must be used to specify the output directory.

The ``classView`` Attribute
+++++++++++++++++++++++++++

Possible values: ``true`` or ``false`` (default: ``true``)

Whether the class view is rendered. The class view shows code coverage information aggregated by classes, traits, and functions.

The ``fileView`` Attribute
++++++++++++++++++++++++++

Possible values: ``true`` or ``false`` (default: ``true``)

Whether the file view is rendered. The file view shows code coverage information organized by directories and files, including line-by-line coverage highlighting.

The class view and the file view cannot both be disabled. When both ``classView`` and ``fileView`` are set to ``false``, PHPUnit emits a warning and renders both views.

The ``lowUpperBound`` Attribute
+++++++++++++++++++++++++++++++

Possible values: integer (default: ``50``)

The upper bound of what should be considered "low coverage".

The ``highLowerBound`` Attribute
++++++++++++++++++++++++++++++++

Possible values: integer (default: ``90``)

The lower bound of what should be considered "high coverage".

The ``colorSuccessHigh`` Attribute
++++++++++++++++++++++++++++++++++

Possible values: string (default: ``#8cb4d5``)

The color used to indicate that a line of code is covered by small (and larger) tests, for instance.

The ``colorSuccessMedium`` Attribute
++++++++++++++++++++++++++++++++++++

Possible values: string (default: ``#b3d1e8``)

The color used to indicate that a line of code is covered by medium (and large) tests, for instance.

The ``colorSuccessLow`` Attribute
+++++++++++++++++++++++++++++++++

Possible values: string (default: ``#d6e6f2``)

The color used to indicate that a line of code is covered by large tests, for instance.

The ``colorSuccessBar`` Attribute
+++++++++++++++++++++++++++++++++

Possible values: string (default: ``#1a73b4``)

The color used for the "success" portion of coverage progress bars.

The ``colorWarning`` Attribute
++++++++++++++++++++++++++++++

Possible values: string (default: ``#fdf0d5``)

The color used to indicate that a line of code cannot be covered, for instance.

The ``colorWarningBar`` Attribute
+++++++++++++++++++++++++++++++++

Possible values: string (default: ``#e5a100``)

The color used for the "warning" portion of coverage progress bars.

The ``colorDanger`` Attribute
++++++++++++++++++++++++++++++

Possible values: string (default: ``#fad4c0``)

The color used to indicate that a line of code can be covered but is not covered, for instance.

The ``colorDangerBar`` Attribute
++++++++++++++++++++++++++++++++

Possible values: string (default: ``#d45500``)

The color used for the "danger" portion of coverage progress bars.

The ``colorBreadcrumbs`` Attribute
++++++++++++++++++++++++++++++++++

Possible values: string (default: ``var(--bs-gray-200)``)

The color used for the breadcrumb navigation in the HTML report.

The ``colorSuccessHighDark`` Attribute
++++++++++++++++++++++++++++++++++++++

Possible values: string (default: ``#2a4a6b``)

The dark-mode variant of ``colorSuccessHigh``.

The ``colorSuccessMediumDark`` Attribute
++++++++++++++++++++++++++++++++++++++++

Possible values: string (default: ``#2d4f6e``)

The dark-mode variant of ``colorSuccessMedium``.

The ``colorSuccessLowDark`` Attribute
+++++++++++++++++++++++++++++++++++++

Possible values: string (default: ``#1e3550``)

The dark-mode variant of ``colorSuccessLow``.

The ``colorSuccessBarDark`` Attribute
+++++++++++++++++++++++++++++++++++++

Possible values: string (default: ``#1560a0``)

The dark-mode variant of ``colorSuccessBar``.

The ``colorWarningDark`` Attribute
++++++++++++++++++++++++++++++++++

Possible values: string (default: ``#3d3010``)

The dark-mode variant of ``colorWarning``.

The ``colorWarningBarDark`` Attribute
+++++++++++++++++++++++++++++++++++++

Possible values: string (default: ``#b88a00``)

The dark-mode variant of ``colorWarningBar``.

The ``colorDangerDark`` Attribute
+++++++++++++++++++++++++++++++++

Possible values: string (default: ``#4a2a10``)

The dark-mode variant of ``colorDanger``.

The ``colorDangerBarDark`` Attribute
++++++++++++++++++++++++++++++++++++

Possible values: string (default: ``#b54400``)

The dark-mode variant of ``colorDangerBar``.

The ``colorBreadcrumbsDark`` Attribute
++++++++++++++++++++++++++++++++++++++

Possible values: string (default: ``var(--bs-gray-800)``)

The dark-mode variant of ``colorBreadcrumbs``.

The ``customCssFile`` Attribute
+++++++++++++++++++++++++++++++

Possible values: string

The path to a custom CSS file.

.. _appendixes.xml-configuration-file.coverage.report.php:

The ``<php>`` Element
*********************

Parent element: ``<report>``

Configures a code coverage report in PHP format.

The ``outputFile`` Attribute
++++++++++++++++++++++++++++

Possible values: string

The file to which the PHP report is written.


.. _appendixes.xml-configuration-file.coverage.report.text:

The ``<text>`` Element
**********************

Parent element: ``<report>``

Configures a code coverage report in text format.

The ``outputFile`` Attribute
++++++++++++++++++++++++++++

Possible values: string

The file to which the text report is written.

The ``showUncoveredFiles`` Attribute
++++++++++++++++++++++++++++++++++++

Possible values: ``true`` or ``false`` (default: ``false``)

The ``showOnlySummary`` Attribute
+++++++++++++++++++++++++++++++++

Possible values: ``true`` or ``false`` (default: ``false``)


.. _appendixes.xml-configuration-file.coverage.report.xml:

The ``<xml>`` Element
*********************

Parent element: ``<report>``

Configures a code coverage report in PHPUnit XML format.

The ``outputDirectory`` Attribute
+++++++++++++++++++++++++++++++++

Possible values: string

The directory to which the PHPUnit XML report is written.


.. _appendixes.xml-configuration-file.logging:

The ``<logging>`` Element
=========================

Parent element: ``<phpunit>``

The ``<logging>`` element and its children can be used to configure the logging of the test execution.

.. code-block:: xml

    <logging>
        <junit outputFile="junit.xml"/>
        <teamcity outputFile="teamcity.txt"/>
        <testdoxHtml outputFile="testdox.html"/>
        <testdoxText outputFile="testdox.txt"/>
    </logging>


.. _appendixes.xml-configuration-file.logging.junit:

The ``<junit>`` Element
-----------------------

Parent element: ``<logging>``

Configures a test result logfile in JUnit XML format.

The ``outputFile`` Attribute
****************************

Possible values: string

The file to which the test result logfile in JUnit XML format is written.


.. _appendixes.xml-configuration-file.logging.teamcity:

The ``<teamcity>`` Element
--------------------------

Parent element: ``<logging>``

Configures a test result logfile in TeamCity format.

The ``outputFile`` Attribute
****************************

Possible values: string

The file to which the test result logfile in TeamCity format is written.


.. _appendixes.xml-configuration-file.logging.testdoxHtml:

The ``<testdoxHtml>`` Element
-----------------------------

Parent element: ``<logging>``

Configures a test result logfile in TestDox HTML format.

The ``outputFile`` Attribute
****************************

Possible values: string

The file to which the test result logfile in TestDox HTML format is written.


.. _appendixes.xml-configuration-file.logging.testdoxText:

The ``<testdoxText>`` Element
-----------------------------

Parent element: ``<logging>``

Configures a test result logfile in TestDox text format.

The ``outputFile`` Attribute
****************************

Possible values: string

The file to which the test result logfile in TestDox text format is written.


.. _appendixes.xml-configuration-file.groups:

The ``<groups>`` Element
========================

Parent element: ``<phpunit>``

The ``<groups>`` element and its ``<include>``, ``<exclude>``, and ``<group>`` children can be used to select groups of tests marked with the ``Group`` attribute (documented in :ref:`appendixes.attributes.Group`) that should (not) be run:

.. code-block:: xml

    <groups>
      <include>
        <group>name</group>
      </include>
      <exclude>
        <group>name</group>
      </exclude>
    </groups>

The example shown above is equivalent to invoking the PHPUnit test runner with ``--group name --exclude-group name``.

.. _appendixes.xml-configuration-file.extensions:

The ``<extensions>`` Element
============================

Parent element: ``<phpunit>``

The ``<extensions>`` element and its ``<bootstrap>`` children can be used to register test runner extensions.

.. _appendixes.xml-configuration-file.extensions.bootstrap:

The ``<bootstrap>`` Element
---------------------------

Parent element: ``<extensions>``

.. code-block:: xml

    <extensions>
        <bootstrap class="Vendor\ExampleExtensionForPhpunit\Extension"/>
    </extensions>

.. _appendixes.xml-configuration-file.extensions.extension.arguments:

The ``<parameter>`` Element
***************************

Parent element: ``<bootstrap>``

The ``<parameter>`` element can be used to configure parameters that are passed
to the extension for bootstrapping.

.. code-block:: xml

    <extensions>
        <bootstrap class="Vendor\ExampleExtensionForPhpunit\Extension">
            <parameter name="message" value="the-message"/>
        </bootstrap>
    </extensions>

.. _appendixes.xml-configuration-file.php:

The ``<php>`` Element
=====================

Parent element: ``<phpunit>``

The ``<php>`` element and its children can be used to configure PHP settings, constants, and global variables. It can also be used to prepend the ``include_path``.

.. _appendixes.xml-configuration-file.php.includePath:

The ``<includePath>`` Element
-----------------------------

Parent element: ``<php>``

This element can be used to prepend a path to the ``include_path``.

.. _appendixes.xml-configuration-file.php.ini:

The ``<ini>`` Element
---------------------

Parent element: ``<php>``

This element can be used to set a PHP configuration setting using ``ini_set()``.

.. code-block:: xml

    <php>
      <ini name="foo" value="bar"/>
    </php>

The XML configuration above corresponds to the following PHP code:

.. code-block:: php

    ini_set('foo', 'bar');

.. admonition:: Note

   Because the ``<ini>`` element uses ``ini_set()`` internally, it can only be used for PHP configuration
   settings that can be changed at runtime. PHP configuration settings that can only be set in a
   configuration file (``php.ini``, for instance) or on the command line (using PHP's ``-d`` option) cannot
   be set using the ``<ini>`` element.

.. _appendixes.xml-configuration-file.php.const:

The ``<const>`` Element
-----------------------

Parent element: ``<php>``

This element can be used to set a global constant.

.. code-block:: xml

    <php>
      <const name="foo" value="bar"/>
    </php>

The XML configuration above corresponds to the following PHP code:

.. code-block:: php

    define('foo', 'bar');

.. _appendixes.xml-configuration-file.php.var:

The ``<var>`` Element
---------------------

Parent element: ``<php>``

This element can be used to set a global variable.

.. code-block:: xml

    <php>
      <var name="foo" value="bar"/>
    </php>

The XML configuration above corresponds to the following PHP code:

.. code-block:: php

    $GLOBALS['foo'] = 'bar';

.. _appendixes.xml-configuration-file.php.env:

The ``<env>`` Element
---------------------

Parent element: ``<php>``

This element can be used to set a value in the super-global array ``$_ENV``.

.. code-block:: xml

    <php>
      <env name="foo" value="bar"/>
    </php>

The XML configuration above corresponds to the following PHP code:

.. code-block:: php

    $_ENV['foo'] = 'bar';

By default, environment variables are not overwritten if they exist already.
To force overwriting existing variables, use the ``force`` attribute:

.. code-block:: xml

    <php>
      <env name="foo" value="bar" force="true"/>
    </php>

.. _appendixes.xml-configuration-file.php.get:

The ``<get>`` Element
---------------------

Parent element: ``<php>``

This element can be used to set a value in the super-global array ``$_GET``.

.. code-block:: xml

    <php>
      <get name="foo" value="bar"/>
    </php>

The XML configuration above corresponds to the following PHP code:

.. code-block:: php

    $_GET['foo'] = 'bar';

.. _appendixes.xml-configuration-file.php.post:

The ``<post>`` Element
----------------------

Parent element: ``<php>``

This element can be used to set a value in the super-global array ``$_POST``.

.. code-block:: xml

    <php>
      <post name="foo" value="bar"/>
    </php>

The XML configuration above corresponds to the following PHP code:

.. code-block:: php

    $_POST['foo'] = 'bar';

.. _appendixes.xml-configuration-file.php.cookie:

The ``<cookie>`` Element
------------------------

Parent element: ``<php>``

This element can be used to set a value in the super-global array ``$_COOKIE``.

.. code-block:: xml

    <php>
      <cookie name="foo" value="bar"/>
    </php>

The XML configuration above corresponds to the following PHP code:

.. code-block:: php

    $_COOKIE['foo'] = 'bar';

.. _appendixes.xml-configuration-file.php.server:

The ``<server>`` Element
------------------------

Parent element: ``<php>``

This element can be used to set a value in the super-global array ``$_SERVER``.

.. code-block:: xml

    <php>
      <server name="foo" value="bar"/>
    </php>

The XML configuration above corresponds to the following PHP code:

.. code-block:: php

    $_SERVER['foo'] = 'bar';

.. _appendixes.xml-configuration-file.php.files:

The ``<files>`` Element
-----------------------

Parent element: ``<php>``

This element can be used to set a value in the super-global array ``$_FILES``.

.. code-block:: xml

    <php>
      <files name="foo" value="bar"/>
    </php>

The XML configuration above corresponds to the following PHP code:

.. code-block:: php

    $_FILES['foo'] = 'bar';

.. _appendixes.xml-configuration-file.php.request:

The ``<request>`` Element
-------------------------

Parent element: ``<php>``

This element can be used to set a value in the super-global array ``$_REQUEST``.

.. code-block:: xml

    <php>
      <request name="foo" value="bar"/>
    </php>

The XML configuration above corresponds to the following PHP code:

.. code-block:: php

    $_REQUEST['foo'] = 'bar';

