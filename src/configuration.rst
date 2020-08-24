

.. _appendixes.configuration:

==========================
The XML Configuration File
==========================

.. _appendixes.configuration.phpunit:

PHPUnit
#######

The attributes of the ``<phpunit>`` element can
be used to configure PHPUnit's core functionality.

.. parsed-literal::

    <phpunit
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/\ |version|/phpunit.xsd"
             backupGlobals="true"
             backupStaticAttributes="false"
             <!--bootstrap="/path/to/bootstrap.php"-->
             cacheResult="false"
             cacheTokens="false"
             colors="false"
             convertErrorsToExceptions="true"
             convertNoticesToExceptions="true"
             convertWarningsToExceptions="true"
             forceCoversAnnotation="false"
             printerClass="PHPUnit\TextUI\ResultPrinter"
             <!--printerFile="/path/to/ResultPrinter.php"-->
             processIsolation="false"
             stopOnError="false"
             stopOnFailure="false"
             stopOnIncomplete="false"
             stopOnSkipped="false"
             stopOnRisky="false"
             testSuiteLoaderClass="PHPUnit\Runner\StandardTestSuiteLoader"
             <!--testSuiteLoaderFile="/path/to/StandardTestSuiteLoader.php"-->
             timeoutForSmallTests="1"
             timeoutForMediumTests="10"
             timeoutForLargeTests="60"
             verbose="false">
      <!-- ... -->
    </phpunit>

The XML configuration above corresponds to the default behaviour of the
TextUI test runner documented in :ref:`textui.clioptions`.

Additional options that are not available as command-line options are:

``convertErrorsToExceptions``

    By default, PHPUnit will install an error handler that converts
    the following errors to exceptions:

    - ``E_WARNING``

    - ``E_NOTICE``

    - ``E_USER_ERROR``

    - ``E_USER_WARNING``

    - ``E_USER_NOTICE``

    - ``E_STRICT``

    - ``E_RECOVERABLE_ERROR``

    - ``E_DEPRECATED``

    - ``E_USER_DEPRECATED``

    Set ``convertErrorsToExceptions`` to
    ``false`` to disable this feature.

``convertNoticesToExceptions``

    When set to ``false``, the error handler installed
    by ``convertErrorsToExceptions`` will not convert
    ``E_NOTICE``, ``E_USER_NOTICE``, or
    ``E_STRICT`` errors to exceptions.

``convertWarningsToExceptions``

    When set to ``false``, the error handler installed
    by ``convertErrorsToExceptions`` will not convert
    ``E_WARNING`` or ``E_USER_WARNING``
    errors to exceptions.

``forceCoversAnnotation``

    A test will be marked as risky (see :ref:`risky-tests.unintentionally-covered-code`)
    when it does not have a :ref:`@covers <appendixes.annotations.covers>` annotation.

``timeoutForLargeTests``

    If time limits based on test size are enforced then this attribute
    sets the timeout for all tests marked as ``@large``.
    If a test does not complete within its configured timeout, it will
    fail.

``timeoutForMediumTests``

    If time limits based on test size are enforced then this attribute
    sets the timeout for all tests marked as ``@medium``.
    If a test does not complete within its configured timeout, it will
    fail.

``timeoutForSmallTests``

    If time limits based on test size are enforced then this attribute
    sets the timeout for all tests not marked as
    ``@medium`` or ``@large``. If a test
    does not complete within its configured timeout, it will fail.

.. _appendixes.configuration.testsuites:

Test Suites
###########

The ``<testsuites>`` element and its
one or more ``<testsuite>`` children can be
used to compose a test suite out of test suites and test cases.

.. code-block:: xml

    <testsuites>
      <testsuite name="My Test Suite">
        <directory>/path/to/*Test.php files</directory>
        <file>/path/to/MyTest.php</file>
        <exclude>/path/to/exclude</exclude>
      </testsuite>
    </testsuites>

Using the ``phpVersion`` and
``phpVersionOperator`` attributes, a required PHP version
can be specified. The example below will only add the
:file:`/path/to/\*Test.php` files and
:file:`/path/to/MyTest.php` file if the PHP version is at
least 5.3.0.

.. code-block:: xml

      <testsuites>
        <testsuite name="My Test Suite">
          <directory suffix="Test.php" phpVersion="5.3.0" phpVersionOperator=">=">/path/to/files</directory>
          <file phpVersion="5.3.0" phpVersionOperator=">=">/path/to/MyTest.php</file>
        </testsuite>
      </testsuites>

The ``phpVersionOperator`` attribute is optional and
defaults to ``>=``.

.. _appendixes.configuration.groups:

Groups
######

The ``<groups>`` element and its
``<include>``,
``<exclude>``, and
``<group>`` children can be used to select
groups of tests marked with the ``@group`` annotation
(documented in :ref:`appendixes.annotations.group`)
that should (not) be run.

.. code-block:: xml

    <groups>
      <include>
        <group>name</group>
      </include>
      <exclude>
        <group>name</group>
      </exclude>
    </groups>

The XML configuration above corresponds to invoking the TextUI test runner
with the following options:

-

  ``--group name``

-

  ``--exclude-group name``

.. _appendixes.configuration.whitelisting-files:

Whitelisting Files for Code Coverage
####################################

The ``<filter>`` element and its children can
be used to configure the whitelist for the code coverage reporting.

.. code-block:: xml

    <filter>
      <whitelist processUncoveredFilesFromWhitelist="true">
        <directory suffix=".php">/path/to/files</directory>
        <file>/path/to/file</file>
        <exclude>
          <directory suffix=".php">/path/to/files</directory>
          <file>/path/to/file</file>
        </exclude>
      </whitelist>
    </filter>

.. _appendixes.configuration.logging:

Logging
#######

The ``<logging>`` element and its
``<log>`` children can be used to configure the
logging of the test execution.

.. code-block:: xml

    <logging>
      <log type="coverage-html" target="/tmp/report/html" lowUpperBound="35"
           highLowerBound="70"/>
      <log type="coverage-xml" target="tmp/report/xml"/>
      <log type="coverage-clover" target="/tmp/coverage.clover.xml"/>
      <log type="coverage-crap4j" target="/tmp/coverage.crap4j.xml"/>
      <log type="coverage-php" target="/tmp/coverage.serialized.php"/>
      <log type="coverage-text" target="php://stdout" showUncoveredFiles="false"/>
      <log type="junit" target="/tmp/logfile.xml"/>
      <log type="teamcity" target="/tmp/teamcity.txt"/>
      <log type="testdox-html" target="/tmp/testdox.html"/>
      <log type="testdox-text" target="/tmp/testdox.txt"/>
      <log type="testdox-xml" target="/tmp/testdox.xml"/>
    </logging>

The XML configuration above corresponds to invoking the TextUI test runner
with the following options:

-

  ``--coverage-html /tmp/report/html``

-

  ``--coverage-xml /tmp/report/xml``

-

  ``--coverage-clover /tmp/coverage.clover.xml``

-

  ``--coverage-crap4j /tmp/coverage.crap4j.xml``

-

  ``--coverage-php /tmp/coverage.serialized.php``

-

  ``--coverage-text``

-

  ``> /tmp/logfile.txt``

-

  ``--log-junit /tmp/logfile.xml``

-

  ``--log-teamcity /tmp/teamcity.txt``

-

  ``--testdox-html /tmp/testdox.html``

-

  ``--testdox-text /tmp/testdox.txt``

-

  ``--testdox-xml /tmp/testdox.xml``

The ``lowUpperBound``, ``highLowerBound``,
and ``showUncoveredFiles`` attributes have no equivalent TextUI
test runner option.

-

  ``lowUpperBound``: Maximum coverage percentage to be considered "lowly" covered.

-

  ``highLowerBound``: Minimum coverage percentage to be considered "highly" covered.

-

  ``showUncoveredFiles``: Show all whitelisted files in ``--coverage-text`` output not just the ones with coverage information.

-

  ``showOnlySummary``: Show only the summary in ``--coverage-text`` output.

.. _appendixes.configuration.test-listeners:

Test Listeners
##############

The ``<listeners>`` element and its
``<listener>`` children can be used to attach
additional test listeners to the test execution.

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

Registering TestRunner Extensions
#################################

The ``<extensions>`` element and its ``<extension>`` children
can be used to register custom TestRunner extensions.

:numref:`configuration.examples.RegisterExtension` shows how to register
such an extension.

.. code-block:: xml
    :caption: Registering a TestRunner Extension
    :name: configuration.examples.RegisterExtension

      <?xml version="1.0" encoding="UTF-8"?>
      <phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/7.1/phpunit.xsd">
          <extensions>
              <extension class="Vendor\MyExtension"/>
          </extensions>
      </phpunit>


.. _appendixes.configuration.phpunit.extensions.extension.arguments:

Configuring TestRunner Extensions
---------------------------------

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

.. _appendixes.configuration.php-ini-constants-variables:

Setting PHP INI settings, Constants and Global Variables
########################################################

The ``<php>`` element and its children can be
used to configure PHP settings, constants, and global variables. It can
also be used to prepend the ``include_path``.

.. code-block:: xml

    <php>
      <includePath>.</includePath>
      <ini name="foo" value="bar"/>
      <const name="foo" value="bar"/>
      <var name="foo" value="bar"/>
      <env name="foo" value="bar"/>
      <post name="foo" value="bar"/>
      <get name="foo" value="bar"/>
      <cookie name="foo" value="bar"/>
      <server name="foo" value="bar"/>
      <files name="foo" value="bar"/>
      <request name="foo" value="bar"/>
    </php>

The XML configuration above corresponds to the following PHP code:

.. code-block:: php

    ini_set('foo', 'bar');
    define('foo', 'bar');
    $GLOBALS['foo'] = 'bar';
    $_ENV['foo'] = 'bar';
    $_POST['foo'] = 'bar';
    $_GET['foo'] = 'bar';
    $_COOKIE['foo'] = 'bar';
    $_SERVER['foo'] = 'bar';
    $_FILES['foo'] = 'bar';
    $_REQUEST['foo'] = 'bar';

By default, environment variables are not overwritten if they exist already.
To force overwriting existing variables, use the ``force`` attribute:

.. code-block:: xml

    <php>
      <env name="foo" value="bar" force="true"/>
    </php>

