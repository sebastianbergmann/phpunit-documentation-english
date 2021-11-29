

.. _appendixes.configuration:

==========================
The XML Configuration File
==========================

.. _appendixes.configuration.phpunit:

The ``<phpunit>`` Element
#########################

.. _appendixes.configuration.phpunit.backupGlobals:

The ``backupGlobals`` Attribute
-------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

PHPUnit can optionally backup all global and super-global variables before each test and restore this backup after each test.

This attribute configures this operation for all tests. This configuration can be overridden using the ``@backupGlobals`` annotation on the test case class and test method level.

.. _appendixes.configuration.phpunit.backupStaticAttributes:

The ``backupStaticAttributes`` Attribute
----------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

PHPUnit can optionally backup all static attributes in all declared classes before each test and restore this backup after each test.

This attribute configures this operation for all tests. This configuration can be overridden using the ``@backupStaticAttributes`` annotation on the test case class and test method level.

.. _appendixes.configuration.phpunit.bootstrap:

The ``bootstrap`` Attribute
---------------------------

This attribute configures the bootstrap script that is loaded before the tests are executed. This script usually only registers the autoloader callback that is used to load the code under test.

.. _appendixes.configuration.phpunit.cacheResult:

The ``cacheResult`` Attribute
-----------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

This attribute configures the caching of test results. This caching is required for certain other features to work.

.. _appendixes.configuration.phpunit.cacheDirectory:

The ``cacheDirectory`` Attribute
--------------------------------

This attribute configures the directory in which PHPUnit caches information such as test results (see above), for instance.

.. _appendixes.configuration.phpunit.colors:

The ``colors`` Attribute
------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether colors are used in PHPUnit's output.

Setting this attribute to ``true`` is equivalent to using the ``--colors=auto`` CLI option.

Setting this attribute to ``false`` is equivalent to using the ``--colors=never`` CLI option.

.. _appendixes.configuration.phpunit.columns:

The ``columns`` Attribute
-------------------------

Possible values: integer or string ``max`` (default: ``80``)

This attribute configures the number of columns to use for progress output.

If ``max`` is defined as value, the number of columns will be maximum of the current terminal.

.. _appendixes.configuration.phpunit.convertDeprecationsToExceptions:

The ``convertDeprecationsToExceptions`` Attribute
-------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

This attribute configures whether ``E_DEPRECATED`` and ``E_USER_DEPRECATED`` events triggered by the code under test are converted to an exception (and mark the test as error).

.. _appendixes.configuration.phpunit.convertErrorsToExceptions:

The ``convertErrorsToExceptions`` Attribute
-------------------------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

This attribute configures whether ``E_ERROR`` and ``E_USER_ERROR`` events triggered by the code under test are converted to an exception (and mark the test as error).

.. _appendixes.configuration.phpunit.convertNoticesToExceptions:

The ``convertNoticesToExceptions`` Attribute
--------------------------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

This attribute configures whether ``E_STRICT``, ``E_NOTICE``, and ``E_USER_NOTICE`` events triggered by the code under test are converted to an exception (and mark the test as error).

.. _appendixes.configuration.phpunit.convertWarningsToExceptions:

The ``convertWarningsToExceptions`` Attribute
---------------------------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

This attribute configures whether ``E_WARNING`` and ``E_USER_WARNING`` events triggered by the code under test are converted to an exception (and mark the test as error).

.. _appendixes.configuration.phpunit.requireCoverageMetadata:

The ``requireCoverageMetadata`` Attribute
-----------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether a test will be marked as risky (see :ref:`risky-tests.unintentionally-covered-code`) when it does indicate the code it intends to cover using an attribute in code or an annotation in a code comment.

.. _appendixes.configuration.phpunit.printerClass:

The ``printerClass`` Attribute
------------------------------

Default: ``PHPUnit\TextUI\DefaultResultPrinter``

This attribute configures the name of a class that implements the ``PHPUnit\TextUI\ResultPrinter`` interface. An object of this class is used to print progress and test results.

.. _appendixes.configuration.phpunit.printerFile:

The ``printerFile`` Attribute
-----------------------------

This attribute can be used to configure the path to the sourcecode file that declares the class configured with ``printerClass`` in case that class cannot be autoloaded.

.. _appendixes.configuration.phpunit.processIsolation:

The ``processIsolation`` Attribute
----------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether each test should be run in a separate PHP process for increased isolation.

.. _appendixes.configuration.phpunit.stopOnError:

The ``stopOnError`` Attribute
-----------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after the first test finished with status "error".

.. _appendixes.configuration.phpunit.stopOnFailure:

The ``stopOnFailure`` Attribute
-------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after the first test finished with status "failure".

.. _appendixes.configuration.phpunit.stopOnIncomplete:

The ``stopOnIncomplete`` Attribute
----------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after the first test finished with status "incomplete".

.. _appendixes.configuration.phpunit.stopOnRisky:

The ``stopOnRisky`` Attribute
-----------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after the first test finished with status "risky".

.. _appendixes.configuration.phpunit.stopOnSkipped:

The ``stopOnSkipped`` Attribute
-------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after the first test finished with status "skipped".

.. _appendixes.configuration.phpunit.stopOnWarning:

The ``stopOnWarning`` Attribute
-------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after the first test finished with status "warning".

.. _appendixes.configuration.phpunit.stopOnDefect:

The ``stopOnDefect`` Attribute
------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the test suite execution should be stopped after the first test finished with a status "error", "failure", "risky" or "warning".

.. _appendixes.configuration.phpunit.failOnIncomplete:

The ``failOnIncomplete`` Attribute
----------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that were marked as incomplete.

.. _appendixes.configuration.phpunit.failOnRisky:

The ``failOnRisky`` Attribute
-----------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that were marked as risky.

.. _appendixes.configuration.phpunit.failOnSkipped:

The ``failOnSkipped`` Attribute
-------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that were marked as skipped.

.. _appendixes.configuration.phpunit.failOnWarning:

The ``failOnWarning`` Attribute
-------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that had warnings.

.. _appendixes.configuration.phpunit.beStrictAboutChangesToGlobalState:

The ``beStrictAboutChangesToGlobalState`` Attribute
---------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether PHPUnit should mark a test as risky when global state is manipulated by the code under test (or the test code).

.. _appendixes.configuration.phpunit.beStrictAboutOutputDuringTests:

The ``beStrictAboutOutputDuringTests`` Attribute
------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether PHPUnit should mark a test as risky when the code under test (or the test code) prints output.

.. _appendixes.configuration.phpunit.beStrictAboutResourceUsageDuringSmallTests:

The ``beStrictAboutResourceUsageDuringSmallTests`` Attribute
------------------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether PHPUnit should mark a test that is annotated with ``@small`` as risky when it invokes a PHP built-in function or method that operates on ``resource`` variables.

.. _appendixes.configuration.phpunit.beStrictAboutTestsThatDoNotTestAnything:

The ``beStrictAboutTestsThatDoNotTestAnything`` Attribute
---------------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

This attribute configures whether PHPUnit should mark a test as risky when no assertions are performed (expectations are also considered).

.. _appendixes.configuration.phpunit.beStrictAboutTodoAnnotatedTests:

The ``beStrictAboutTodoAnnotatedTests`` Attribute
-------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether PHPUnit should mark a test as risky when it is annotated with ``@todo``.

.. _appendixes.configuration.phpunit.beStrictAboutCoverageMetadata:

The ``beStrictAboutCoverageMetadata`` Attribute
-----------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether PHPUnit should mark a test as risky when it executes code that is not specified to be covered or used using an attribute in code or an annotation in a code comment.

.. _appendixes.configuration.phpunit.enforceTimeLimit:

The ``enforceTimeLimit`` Attribute
----------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether time limits should be enforced.

.. _appendixes.configuration.phpunit.defaultTimeLimit:

The ``defaultTimeLimit`` Attribute
----------------------------------

Possible values: integer (default: ``0``)

This attribute configures the default time limit (in seconds).

.. _appendixes.configuration.phpunit.timeoutForSmallTests:

The ``timeoutForSmallTests`` Attribute
--------------------------------------

Possible values: integer (default: ``1``)

This attribute configures the time limit for tests annotated with ``@small`` (in seconds).

.. _appendixes.configuration.phpunit.timeoutForMediumTests:

The ``timeoutForMediumTests`` Attribute
---------------------------------------

Possible values: integer (default: ``10``)

This attribute configures the time limit for tests annotated with ``@medium`` (in seconds).

.. _appendixes.configuration.phpunit.timeoutForLargeTests:

The ``timeoutForLargeTests`` Attribute
--------------------------------------

Possible values: integer (default: ``60``)

This attribute configures the time limit for tests annotated with ``@large`` (in seconds).

.. _appendixes.configuration.phpunit.testSuiteLoaderClass:

The ``testSuiteLoaderClass`` Attribute
--------------------------------------

Default: ``PHPUnit\Runner\StandardTestSuiteLoader``

This attribute configures the name of a class that implements the ``PHPUnit\Runner\TestSuiteLoader`` interface. An object of this class is used to load the test suite.

.. _appendixes.configuration.phpunit.testSuiteLoaderFile:

The ``testSuiteLoaderFile`` Attribute
-------------------------------------

This attribute can be used to configure the path to the sourcecode file that declares the class configured with ``testSuiteLoaderClass`` in case that class cannot be autoloaded.

.. _appendixes.configuration.phpunit.defaultTestSuite:

The ``defaultTestSuite`` Attribute
----------------------------------

This attribute configures the name of the default test suite.

.. _appendixes.configuration.phpunit.verbose:

The ``verbose`` Attribute
-------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether more verbose output should be printed.

.. _appendixes.configuration.phpunit.stderr:

The ``stderr`` Attribute
------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether PHPUnit should print its output to ``stderr`` instead of ``stdout``.

.. _appendixes.configuration.phpunit.reverseDefectList:

The ``reverseDefectList`` Attribute
-----------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether tests that are not successful should be printed in reverse order.

.. _appendixes.configuration.phpunit.registerMockObjectsFromTestArgumentsRecursively:

The ``registerMockObjectsFromTestArgumentsRecursively`` Attribute
-----------------------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether arrays and object graphs that are passed from one test to another using the ``@depends`` annotation should be recursively scanned for mock objects.

.. _appendixes.configuration.phpunit.extensionsDirectory:

The ``extensionsDirectory`` Attribute
-------------------------------------

When ``phpunit.phar`` is used then this attribute may be used to configure a directory from which all ``*.phar`` files will be loaded as extensions for the PHPUnit test runner.

.. _appendixes.configuration.phpunit.executionOrder:

The ``executionOrder`` Attribute
--------------------------------

Possible values: ``default``, ``defects``, ``depends``, ``no-depends``, ``duration``, ``random``, ``reverse``, ``size``

Using multiple values is possible. These need to be separated by ``,``.

This attribute configures the order in which tests are executed.

.. _appendixes.configuration.phpunit.resolveDependencies:

The ``resolveDependencies`` Attribute
-------------------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

This attribute configures whether dependencies between tests (expressed using the ``@depends`` annotation) should be resolved.

.. _appendixes.configuration.phpunit.testdox:

The ``testdox`` Attribute
-------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the output should be printed in TestDox format.

The ``noInteraction`` Attribute
-------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether progress should be animated when TestDox format is used, for instance.

.. _appendixes.configuration.testsuites:

The ``<testsuites>`` Element
############################

Parent element: ``<phpunit>``

This element is the root for one or more ``<testsuite>`` elements that are used to configure the tests that are to be executed.

.. _appendixes.configuration.testsuites.testsuite:

The ``<testsuite>`` Element
---------------------------

Parent element: ``<testsuites>``

A ``<testsuite>`` element must have a ``name`` attribute and may have one or more ``<directory>`` and/or ``<file>`` child elements that configure directories and/or files, respectively, that should be searched for tests.

.. code-block:: xml

    <testsuites>
      <testsuite name="unit">
        <directory>tests/unit</directory>
      </testsuite>

      <testsuite name="integration">
        <directory>tests/integration</directory>
      </testsuite>

      <testsuite name="edge-to-edge">
        <directory>tests/edge-to-edge</directory>
      </testsuite>
    </testsuites>

Using the ``phpVersion`` and ``phpVersionOperator`` attributes, a required PHP version can be specified:

.. code-block:: xml

    <testsuites>
      <testsuite name="unit">
        <directory phpVersion="8.0.0" phpVersionOperator=">=">tests/unit</directory>
      </testsuite>
    </testsuites>

In the example above, the tests from the ``tests/unit`` directory are only added to the test suite if the PHP version is at least 8.0.0. The ``phpVersionOperator`` attribute is optional and defaults to ``>=``.

.. _appendixes.configuration.coverage:

The ``<coverage>`` Element
##########################

Parent element: ``<phpunit>``

The ``<coverage>`` element and its children can be used to configure code coverage:

.. code-block:: xml

    <coverage includeUncoveredFiles="true"
              processUncoveredFiles="true"
              pathCoverage="false"
              ignoreDeprecatedCodeUnits="true"
              disableCodeCoverageIgnore="true">
        <!-- ... -->
    </coverage>

The ``includeUncoveredFiles`` Attribute
---------------------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

When set to ``true``, all sourcecode files that are configured to be considered for code coverage analysis will be included in the code coverage report(s). This includes sourcecode files that are not executed while the tests are running.

The ``processUncoveredFiles`` Attribute
---------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

When set to ``true``, all sourcecode files that are configured to be considered for code coverage analysis will be processed. This includes sourcecode files that are not executed while the tests are running.

The ``ignoreDeprecatedCodeUnits`` Attribute
-------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether code units annotated with ``@deprecated`` should be ignored from code coverage.

The ``pathCoverage`` Attribute
------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

When set to ``false``, only line coverage data will be collected, processed, and reported.

When set to ``true``, line coverage, branch coverage, and path coverage data will be collected, processed, and reported. This requires a code coverage driver that supports path coverage. Path Coverage is currently only implemented by Xdebug.

The ``disableCodeCoverageIgnore`` Attribute
-------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the ``@codeCoverageIgnore*`` annotations should be ignored.

.. _appendixes.configuration.coverage.include:

The ``<include>`` Element
-------------------------

Parent element: ``<coverage>``

Configures a set of files to be included in code coverage report(s).

.. code-block:: xml

    <include>
        <directory suffix=".php">src</directory>
    </include>

The example shown above instructs PHPUnit to include all sourcecode files with ``.php`` suffix in the ``src`` directory and its sub-directories in the code coverage report(s).


.. _appendixes.configuration.coverage.exclude:

The ``<exclude>`` Element
-------------------------

Parent element: ``<coverage>``

Configures a set of files to be excluded from code coverage report(s).

.. code-block:: xml

    <include>
        <directory suffix=".php">src</directory>
    </include>

    <exclude>
        <directory suffix=".php">src/generated</directory>
        <file>src/autoload.php</file>
    </exclude>

The example shown above instructs PHPUnit to include all sourcecode files with ``.php`` suffix in the ``src`` directory and its sub-directories in the code coverage report but exclude all files with ``.php`` suffix in the ``src/generated`` directory and its sub-directories as well as the ``src/autoload.php`` file from the code coverage report(s).


.. _appendixes.configuration.coverage.directory:

The ``<directory>`` Element
---------------------------

Parent elements: ``<include>``, ``<exclude>``

Configures a directory and its sub-directories for inclusion in or exclusion from code coverage report(s).

The ``prefix`` Attribute
************************

Possible values: string

Configures a prefix-based filter that is applied to the names of files in the directory and its sub-directories.

The ``suffix`` Attribute
************************

Possible values: string (default: ``'.php'``)

Configures a suffix-based filter that is applied to the names of files in the directory and its sub-directories.

The ``phpVersion`` Attribute
****************************

Possible values: string

Configures a filter based on the version of the PHP runtime that is used to run the current PHPUnit process.

The ``phpVersionOperator`` Attribute
************************************

Possible values: ``'<'``, ``'lt'``, ``'<='``, ``'le'``, ``'>'``, ``'gt'``, ``'>='``, ``'ge'``, ``'=='``, ``'='``, ``'eq'``, ``'!='``, ``'<>'``, ``'ne'`` (default: ``'>='``)

Configures the comparison operator to be used with ``version_compare()`` for the filter based on the version of the PHP runtime that is used to run the current PHPUnit process.


.. _appendixes.configuration.coverage.file:

The ``<file>`` Element
----------------------

Parent elements: ``<include>``, ``<exclude>``

Configures a file for inclusion in or exclusion from code coverage report(s).


.. _appendixes.configuration.coverage.report:

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


.. _appendixes.configuration.coverage.report.clover:

The ``<clover>`` Element
************************

Parent element: ``<report>``

Configures a code coverage report in Clover XML format.

The ``outputFile`` Attribute
++++++++++++++++++++++++++++

Possible values: string

The file to which the Clover XML report is written.

.. _appendixes.configuration.coverage.report.cobertura:

The ``<cobertura>`` Element
***************************

Parent element: ``<report>``

Configures a code coverage report in Cobertura XML format.

The ``outputFile`` Attribute
++++++++++++++++++++++++++++

Possible values: string

The file to which the Cobertura XML report is written.

.. _appendixes.configuration.coverage.report.crap4j:

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


.. _appendixes.configuration.coverage.report.html:

The ``<html>`` Element
**********************

Parent element: ``<report>``

Configures a code coverage report in HTML format.

The ``outputDirectory`` Attribute
+++++++++++++++++++++++++++++++++

The directory to which the HTML report is written.

The ``lowUpperBound`` Attribute
+++++++++++++++++++++++++++++++

Possible values: integer (default: ``50``)

The upper bound of what should be considered "low coverage".

The ``highLowerBound`` Attribute
++++++++++++++++++++++++++++++++

Possible values: integer (default: ``90``)

The lower bound of what should be considered "high coverage".


.. _appendixes.configuration.coverage.report.php:

The ``<php>`` Element
*********************

Parent element: ``<report>``

Configures a code coverage report in PHP format.

The ``outputFile`` Attribute
++++++++++++++++++++++++++++

Possible values: string

The file to which the PHP report is written.


.. _appendixes.configuration.coverage.report.text:

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


.. _appendixes.configuration.coverage.report.xml:

The ``<xml>`` Element
*********************

Parent element: ``<report>``

Configures a code coverage report in PHPUnit XML format.

The ``outputDirectory`` Attribute
+++++++++++++++++++++++++++++++++

Possible values: string

The directory to which the PHPUnit XML report is written.


.. _appendixes.configuration.logging:

The ``<logging>`` Element
#########################

Parent element: ``<phpunit>``

The ``<logging>`` element and its children can be used to configure the logging of the test execution.

.. code-block:: xml

    <logging>
        <junit outputFile="junit.xml"/>
        <teamcity outputFile="teamcity.txt"/>
        <testdoxHtml outputFile="testdox.html"/>
        <testdoxText outputFile="testdox.txt"/>
        <testdoxXml outputFile="testdox.xml"/>
        <text outputFile="logfile.txt"/>
    </logging>


.. _appendixes.configuration.logging.junit:

The ``<junit>`` Element
-----------------------

Parent element: ``<logging>``

Configures a test result logfile in JUnit XML format.

The ``outputFile`` Attribute
****************************

Possible values: string

The file to which the test result logfile in JUnit XML format is written.


.. _appendixes.configuration.logging.teamcity:

The ``<teamcity>`` Element
--------------------------

Parent element: ``<logging>``

Configures a test result logfile in TeamCity format.

The ``outputFile`` Attribute
****************************

Possible values: string

The file to which the test result logfile in TeamCity format is written.


.. _appendixes.configuration.logging.testdoxHtml:

The ``<testdoxHtml>`` Element
-----------------------------

Parent element: ``<logging>``

Configures a test result logfile in TestDox HTML format.

The ``outputFile`` Attribute
****************************

Possible values: string

The file to which the test result logfile in TestDox HTML format is written.


.. _appendixes.configuration.logging.testdoxText:

The ``<testdoxText>`` Element
-----------------------------

Parent element: ``<logging>``

Configures a test result logfile in TestDox text format.

The ``outputFile`` Attribute
****************************

Possible values: string

The file to which the test result logfile in TestDox text format is written.


.. _appendixes.configuration.logging.testdoxXml:

The ``<testdoxXml>`` Element
----------------------------

Parent element: ``<logging>``

Configures a test result logfile in TestDox XML format.

The ``outputFile`` Attribute
****************************

Possible values: string

The file to which the test result logfile in TestDox XML format is written.


.. _appendixes.configuration.logging.text:

The ``<text>`` Element
----------------------

Parent element: ``<logging>``

Configures a test result logfile in text format.

The ``outputFile`` Attribute
****************************

Possible values: string

The file to which the test result logfile in text format is written.


.. _appendixes.configuration.groups:

The ``<groups>`` Element
########################

Parent element: ``<phpunit>``

The ``<groups>`` element and its ``<include>``, ``<exclude>``, and ``<group>`` children can be used to select groups of tests marked with the ``@group`` annotation (documented in :ref:`appendixes.annotations.group`) that should (not) be run:

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

.. _appendixes.configuration.testdoxGroups:

The ``<testdoxGroups>`` Element
###############################

Parent element: ``<phpunit>``

... TBD ...

.. _appendixes.configuration.listeners:

The ``<listeners>`` Element
###########################

Parent element: ``<phpunit>``

The ``<listeners>`` element and its ``<listener>`` children can be used to attach additional test listeners to the test execution.

.. _appendixes.configuration.listeners.listener:

The ``<listener>`` Element
--------------------------

Parent element: ``<listeners>``

.. code-block:: xml

    <listeners>
      <listener class="MyListener" file="/optional/path/to/MyListener.php">
        <arguments>
          <array>
            <element key="0">
              <string>Sebastian</string>
            </element>
          </array>
          <integer>22</integer>
          <string>April</string>
          <double>19.78</double>
          <null/>
          <object class="stdClass"/>
        </arguments>
      </listener>
    </listeners>

The XML configuration above corresponds to attaching the
``$listener`` object (see below) to the test execution:

.. code-block:: php

    $listener = new MyListener(
        ['Sebastian'],
        22,
        'April',
        19.78,
        null,
        new stdClass
    );

.. admonition:: Note

    Please note that the ``PHPUnit\Framework\TestListener`` interface is
    deprecated and will be removed in the future. TestRunner extensions
    should be used instead of test listeners.

.. _appendixes.configuration.extensions:

The ``<extensions>`` Element
############################

Parent element: ``<phpunit>``

The ``<extensions>`` element and its ``<extension>`` children can be used to register test runner extensions.

.. _appendixes.configuration.extensions.extension:

The ``<extension>`` Element
---------------------------

Parent element: ``<extensions>``

.. code-block:: xml

    <extensions>
        <extension class="Vendor\MyExtension"/>
    </extensions>

.. _appendixes.configuration.extensions.extension.arguments:

The ``<arguments>`` Element
***************************

Parent element: ``<extension>``

The ``<arguments>`` element can be used to configure a single ``<extension>``.

Accepts a list of elements of types, which are then used to configure individual
extensions. The arguments are passed to the extension class' ``__constructor``
method in the order they are defined in the configuration.

Available types:

- ``<boolean>``
- ``<integer>``
- ``<string>``
- ``<double>`` (float)
- ``<array>``
- ``<object>``

.. code-block:: xml

    <extension class="Vendor\MyExtension">
        <arguments>
            <integer>1</integer>
            <integer>2</integer>
            <integer>3</integer>
            <string>hello world</string>
            <boolean>true</boolean>
            <double>1.23</double>
            <array>
                <element index="0">
                    <string>value1</string>
                </element>
                <element index="1">
                    <string>value2</string>
                </element>
            </array>
            <object class="Vendor\MyPhpClass">
                <string>constructor arg 1</string>
                <string>constructor arg 2</string>
            </object>
        </arguments>
    </extension>


.. _appendixes.configuration.php:

The ``<php>`` Element
#####################

Parent element: ``<phpunit>``

The ``<php>`` element and its children can be used to configure PHP settings, constants, and global variables. It can also be used to prepend the ``include_path``.

.. _appendixes.configuration.php.includePath:

The ``<includePath>`` Element
-----------------------------

Parent element: ``<php>``

This element can be used to prepend a path to the ``include_path``.

.. _appendixes.configuration.php.ini:

The ``<ini>`` Element
---------------------

Parent element: ``<php>``

This element can be used to set a PHP configuration setting.

.. code-block:: xml

    <php>
      <ini name="foo" value="bar"/>
    </php>

The XML configuration above corresponds to the following PHP code:

.. code-block:: php

    ini_set('foo', 'bar');

.. _appendixes.configuration.php.const:

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

.. _appendixes.configuration.php.var:

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

.. _appendixes.configuration.php.env:

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

.. _appendixes.configuration.php.get:

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

.. _appendixes.configuration.php.post:

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

.. _appendixes.configuration.php.cookie:

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

.. _appendixes.configuration.php.server:

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

.. _appendixes.configuration.php.files:

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

.. _appendixes.configuration.php.request:

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

