

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

.. _appendixes.configuration.phpunit.cacheResultFile:

The ``cacheResultFile`` Attribute
---------------------------------

This attribute configures the file in which the test result cache (see above) is stored.

.. _appendixes.configuration.phpunit.cacheTokens:

The ``cacheTokens`` Attribute
-----------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures the in-memory cache of the token streams that are used for code coverage analysis.

When more than one code coverage report is generated in a single run, enabling this cache will increase memory usage and may reduce the time to generate the reports.

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

.. _appendixes.configuration.phpunit.disableCodeCoverageIgnore:

The ``disableCodeCoverageIgnore`` Attribute
-------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the ``@codeCoverageIgnore*`` annotations should be ignored.

.. _appendixes.configuration.phpunit.forceCoversAnnotation:

The ``forceCoversAnnotation`` Attribute
---------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether a test will be marked as risky (see :ref:`risky-tests.unintentionally-covered-code`) when it does not have a :ref:`@covers <appendixes.annotations.covers>` annotation.

.. _appendixes.configuration.phpunit.printerClass:

The ``printerClass`` Attribute
------------------------------

Default: ``PHPUnit\TextUI\ResultPrinter``

This attribute configures the name of a class that either is ``PHPUnit\TextUI\ResultPrinter`` or that extends ``PHPUnit\TextUI\ResultPrinter``. An object of this class is used to print progress and test results.

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

.. _appendixes.configuration.phpunit.failOnRisky:

The ``failOnRisky`` Attribute
-----------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether the PHPUnit test runner should exit with a shell exit code that indicates failure when all tests are successful but there are tests that were marked as risky.

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

.. _appendixes.configuration.phpunit.beStrictAboutCoversAnnotation:

The ``beStrictAboutCoversAnnotation`` Attribute
-----------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether PHPUnit should mark a test as risky when it executes code that is not specified using ``@covers`` or ``@uses``.

.. _appendixes.configuration.phpunit.ignoreDeprecatedCodeUnitsFromCodeCoverage:

The ``ignoreDeprecatedCodeUnitsFromCodeCoverage`` Attribute
-----------------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``false``)

This attribute configures whether code units annotated with ``@deprecated`` should be ignored from code coverage.

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

Possible values: ``default``, ``defects``, ``depends``, ``no-depends``, ``duration``, ``random``, ``reverse``

Using multiple values is possible. These need to be separated by ``,``.

This attribute configures the order in which tests are executed.

.. _appendixes.configuration.phpunit.resolveDependencies:

The ``resolveDependencies`` Attribute
-----------------------------------------------------------------

Possible values: ``true`` or ``false`` (default: ``true``)

This attribute configures whether dependencies between tests (expressed using the ``@depends`` annotation) should be resolved.

.. _appendixes.configuration.phpunit.testsuites:

The ``<testsuites>`` Element
############################

Parent element: ``<phpunit>``

This element is the root for one or more ``<testsuite>`` elements that are used to configure the tests that are to be executed.

.. _appendixes.configuration.phpunit.testsuites.testsuite:

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

.. _appendixes.configuration.phpunit.groups:

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

.. _appendixes.configuration.phpunit.testdoxGroups:

The ``<testdoxGroups>`` Element
###############################

Parent element: ``<phpunit>``

... TBD ...

.. _appendixes.configuration.phpunit.filter:

The ``<filter>`` Element
########################

Parent element: ``<phpunit>``

The ``<filter/whitelist>`` element and its children can be used to configure the whitelist for the code coverage reporting:

.. code-block:: xml

    <filter>
      <whitelist processUncoveredFilesFromWhitelist="true">
        <directory suffix=".php">src</directory>
        <exclude>
          <file>src/autoload.php</file>
        </exclude>
      </whitelist>
    </filter>

.. _appendixes.configuration.phpunit.listeners:

The ``<listeners>`` Element
###########################

Parent element: ``<phpunit>``

The ``<listeners>`` element and its ``<listener>`` children can be used to attach additional test listeners to the test execution.

.. _appendixes.configuration.phpunit.listeners.listener:

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

.. _appendixes.configuration.phpunit.extensions:

The ``<extensions>`` Element
############################

Parent element: ``<phpunit>``

The ``<extensions>`` element and its ``<extension>`` children can be used to register test runner extensions.

.. _appendixes.configuration.phpunit.extensions.extension:

The ``<extension>`` Element
---------------------------

Parent element: ``<extensions>``

.. code-block:: xml

    <extensions>
        <extension class="Vendor\MyExtension"/>
    </extensions>

.. _appendixes.configuration.phpunit.extensions.extension.arguments:

The ``<arguments>`` Element
~~~~~~~~~~~~~~~~~~~~~~~~~~~

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

.. _appendixes.configuration.phpunit.logging:

The ``<logging>`` Element
#########################

Parent element: ``<phpunit>``

The ``<logging>`` element and its ``<log>`` children can be used to configure the logging of the test execution.

.. _appendixes.configuration.phpunit.logging.log:

The ``<log>`` Element
---------------------

Parent element: ``<logging>``

.. code-block:: xml

    <logging>
      <log type="coverage-html" target="/tmp/report" lowUpperBound="35" highLowerBound="70"/>
      <log type="coverage-clover" target="/tmp/coverage.xml"/>
      <log type="coverage-php" target="/tmp/coverage.serialized"/>
      <log type="coverage-text" target="php://stdout" showUncoveredFiles="false"/>
      <log type="junit" target="/tmp/logfile.xml"/>
      <log type="testdox-html" target="/tmp/testdox.html"/>
      <log type="testdox-text" target="/tmp/testdox.txt"/>
    </logging>

The XML configuration above corresponds to invoking the TextUI test runner with the following options:

-

  ``--coverage-html /tmp/report``

-

  ``--coverage-clover /tmp/coverage.xml``

-

  ``--coverage-php /tmp/coverage.serialized``

-

  ``--coverage-text``

-

  ``> /tmp/logfile.txt``

-

  ``--log-junit /tmp/logfile.xml``

-

  ``--testdox-html /tmp/testdox.html``

-

  ``--testdox-text /tmp/testdox.txt``

The ``lowUpperBound``, ``highLowerBound``, and ``showUncoveredFiles`` attributes have no equivalent TextUI test runner option.

-

  ``lowUpperBound``: Maximum coverage percentage to be considered "lowly" covered.

-

  ``highLowerBound``: Minimum coverage percentage to be considered "highly" covered.

-

  ``showUncoveredFiles``: Show all whitelisted files in ``--coverage-text`` output not just the ones with coverage information.

-

  ``showOnlySummary``: Show only the summary in ``--coverage-text`` output.

.. _appendixes.configuration.phpunit.php:

The ``<php>`` Element
#####################

Parent element: ``<phpunit>``

The ``<php>`` element and its children can be used to configure PHP settings, constants, and global variables. It can also be used to prepend the ``include_path``.

.. _appendixes.configuration.phpunit.php.includePath:

The ``<includePath>`` Element
-----------------------------

Parent element: ``<php>``

This element can be used to prepend a path to the ``include_path``.

.. _appendixes.configuration.phpunit.php.ini:

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

.. _appendixes.configuration.phpunit.php.const:

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

.. _appendixes.configuration.phpunit.php.var:

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

.. _appendixes.configuration.phpunit.php.env:

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

.. _appendixes.configuration.phpunit.php.get:

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

.. _appendixes.configuration.phpunit.php.post:

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

.. _appendixes.configuration.phpunit.php.cookie:

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

.. _appendixes.configuration.phpunit.php.server:

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

.. _appendixes.configuration.phpunit.php.files:

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

.. _appendixes.configuration.phpunit.php.request:

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

