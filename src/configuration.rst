

.. _appendixes.configuration:

*************
Configuration
*************

PHPUnit builds the effective configuration for a test suite run by combining three layers:

1. **Built-in defaults** that PHPUnit ships with.
2. **XML configuration** loaded from ``phpunit.xml`` (or the file passed to ``--configuration``), applied on top of the defaults.
3. **CLI options** passed on the command line, applied on top of the XML configuration.

Each layer overrides the previous one for the settings it touches. Settings that a layer does not specify are inherited from the layer below. For example, ``beStrictAboutCoverageMetadata`` defaults to ``false``; setting ``beStrictAboutCoverageMetadata="true"`` in ``phpunit.xml`` turns it on for all runs; passing ``--strict-coverage`` turns it on for a single run even when the XML does not enable it.

The tables in this appendix cross-reference the CLI options (see :ref:`appendixes.cli-options`) and the XML configuration attributes and elements (see :ref:`appendixes.xml-configuration-file`) that participate in this override model, along with their built-in defaults. Options that do not participate (CLI-only or XML-only) are listed at the end.


.. _appendixes.configuration.configuration:

Configuration
=============

.. list-table::
    :header-rows: 1
    :widths: 35 40 25

    * - CLI option
      - XML setting
      - Default
    * - ``--bootstrap <file>``
      - :ref:`bootstrap <appendixes.xml-configuration-file.phpunit.bootstrap>` attribute on ``<phpunit>``
      - —
    * - ``--cache-directory <dir>``
      - :ref:`cacheDirectory <appendixes.xml-configuration-file.phpunit.cacheDirectory>` attribute on ``<phpunit>``
      - —
    * - ``--cache-result`` / ``--do-not-cache-result``
      - :ref:`cacheResult <appendixes.xml-configuration-file.phpunit.cacheResult>` attribute on ``<phpunit>``
      - ``true``
    * - ``--extension <class>``
      - :ref:`\<bootstrap\> <appendixes.xml-configuration-file.extensions.bootstrap>` under ``<extensions>``
      - (additive)
    * - ``--include-path <path>``
      - :ref:`\<includePath\> <appendixes.xml-configuration-file.php.includePath>` under ``<php>``
      - (additive)
    * - ``-d <key[=value]>``
      - :ref:`\<ini\> <appendixes.xml-configuration-file.php.ini>` under ``<php>``
      - (additive)
    * - ``--use-baseline <file>``
      - :ref:`baseline <appendixes.xml-configuration-file.source.baseline>` attribute on ``<source>``
      - —


.. _appendixes.configuration.selection:

Selection
=========

.. list-table::
    :header-rows: 1
    :widths: 35 40 25

    * - CLI option
      - XML setting
      - Default
    * - ``--testsuite <name>``
      - :ref:`defaultTestSuite <appendixes.xml-configuration-file.phpunit.defaultTestSuite>` attribute on ``<phpunit>``
      - —
    * - ``--group <name>``
      - :ref:`\<group\> <appendixes.xml-configuration-file.groups>` under ``<groups><include>``
      - —
    * - ``--exclude-group <name>``
      - :ref:`\<group\> <appendixes.xml-configuration-file.groups>` under ``<groups><exclude>``
      - —


.. _appendixes.configuration.execution:

Execution
=========

.. list-table::
    :header-rows: 1
    :widths: 35 40 25

    * - CLI option
      - XML setting
      - Default
    * - ``--process-isolation``
      - :ref:`processIsolation <appendixes.xml-configuration-file.phpunit.processIsolation>` attribute on ``<phpunit>``
      - ``false``
    * - ``--globals-backup``
      - :ref:`backupGlobals <appendixes.xml-configuration-file.phpunit.backupGlobals>` attribute on ``<phpunit>``
      - ``false``
    * - ``--static-backup``
      - :ref:`backupStaticProperties <appendixes.xml-configuration-file.phpunit.backupStaticProperties>` attribute on ``<phpunit>``
      - ``false``
    * - ``--strict-coverage``
      - :ref:`beStrictAboutCoverageMetadata <appendixes.xml-configuration-file.phpunit.beStrictAboutCoverageMetadata>` attribute on ``<phpunit>``
      - ``false``
    * - ``--require-coverage-contribution``
      - :ref:`requireCoverageContribution <appendixes.xml-configuration-file.phpunit.requireCoverageContribution>` attribute on ``<phpunit>``
      - ``false``
    * - ``--strict-global-state``
      - :ref:`beStrictAboutChangesToGlobalState <appendixes.xml-configuration-file.phpunit.beStrictAboutChangesToGlobalState>` attribute on ``<phpunit>``
      - ``false``
    * - ``--disallow-test-output``
      - :ref:`beStrictAboutOutputDuringTests <appendixes.xml-configuration-file.phpunit.beStrictAboutOutputDuringTests>` attribute on ``<phpunit>``
      - ``false``
    * - ``--do-not-report-useless-tests``
      - :ref:`beStrictAboutTestsThatDoNotTestAnything <appendixes.xml-configuration-file.phpunit.beStrictAboutTestsThatDoNotTestAnything>` attribute on ``<phpunit>`` (inverted)
      - ``true``
    * - ``--enforce-time-limit``
      - :ref:`enforceTimeLimit <appendixes.xml-configuration-file.phpunit.enforceTimeLimit>` attribute on ``<phpunit>``
      - ``false``
    * - ``--default-time-limit <sec>``
      - :ref:`defaultTimeLimit <appendixes.xml-configuration-file.phpunit.defaultTimeLimit>` attribute on ``<phpunit>``
      - ``0``
    * - ``--order-by <order>``
      - :ref:`executionOrder <appendixes.xml-configuration-file.phpunit.executionOrder>` attribute on ``<phpunit>``
      - ``default``
    * - ``--random-order``
      - :ref:`executionOrder <appendixes.xml-configuration-file.phpunit.executionOrder>` ``= "random"``
      - ``default``
    * - ``--reverse-order``
      - :ref:`executionOrder <appendixes.xml-configuration-file.phpunit.executionOrder>` ``= "reverse"``
      - ``default``
    * - ``--resolve-dependencies`` / ``--ignore-dependencies``
      - :ref:`resolveDependencies <appendixes.xml-configuration-file.phpunit.resolveDependencies>` attribute on ``<phpunit>``
      - ``true``


.. _appendixes.configuration.stop-on:

Stopping on Issues
==================

.. list-table::
    :header-rows: 1
    :widths: 35 40 25

    * - CLI option
      - XML setting
      - Default
    * - ``--stop-on-defect``
      - :ref:`stopOnDefect <appendixes.xml-configuration-file.phpunit.stopOnDefect>` attribute on ``<phpunit>``
      - ``false``
    * - ``--stop-on-error``
      - :ref:`stopOnError <appendixes.xml-configuration-file.phpunit.stopOnError>` attribute on ``<phpunit>``
      - ``false``
    * - ``--stop-on-failure``
      - :ref:`stopOnFailure <appendixes.xml-configuration-file.phpunit.stopOnFailure>` attribute on ``<phpunit>``
      - ``false``
    * - ``--stop-on-warning``
      - :ref:`stopOnWarning <appendixes.xml-configuration-file.phpunit.stopOnWarning>` attribute on ``<phpunit>``
      - ``false``
    * - ``--stop-on-risky``
      - :ref:`stopOnRisky <appendixes.xml-configuration-file.phpunit.stopOnRisky>` attribute on ``<phpunit>``
      - ``false``
    * - ``--stop-on-deprecation``
      - :ref:`stopOnDeprecation <appendixes.xml-configuration-file.phpunit.stopOnDeprecation>` attribute on ``<phpunit>``
      - ``false``
    * - ``--stop-on-notice``
      - :ref:`stopOnNotice <appendixes.xml-configuration-file.phpunit.stopOnNotice>` attribute on ``<phpunit>``
      - ``false``
    * - ``--stop-on-skipped``
      - :ref:`stopOnSkipped <appendixes.xml-configuration-file.phpunit.stopOnSkipped>` attribute on ``<phpunit>``
      - ``false``
    * - ``--stop-on-incomplete``
      - :ref:`stopOnIncomplete <appendixes.xml-configuration-file.phpunit.stopOnIncomplete>` attribute on ``<phpunit>``
      - ``false``


.. _appendixes.configuration.fail-on:

Failing on Issues
=================

.. list-table::
    :header-rows: 1
    :widths: 35 40 25

    * - CLI option
      - XML setting
      - Default
    * - ``--fail-on-all-issues``
      - :ref:`failOnAllIssues <appendixes.xml-configuration-file.phpunit.failOnAllIssues>` attribute on ``<phpunit>``
      - ``false``
    * - ``--fail-on-empty-test-suite`` / ``--do-not-fail-on-empty-test-suite``
      - :ref:`failOnEmptyTestSuite <appendixes.xml-configuration-file.phpunit.failOnEmptyTestSuite>` attribute on ``<phpunit>``
      - ``false``
    * - ``--fail-on-warning`` / ``--do-not-fail-on-warning``
      - :ref:`failOnWarning <appendixes.xml-configuration-file.phpunit.failOnWarning>` attribute on ``<phpunit>``
      - ``false``
    * - ``--fail-on-risky`` / ``--do-not-fail-on-risky``
      - :ref:`failOnRisky <appendixes.xml-configuration-file.phpunit.failOnRisky>` attribute on ``<phpunit>``
      - ``false``
    * - ``--fail-on-deprecation`` / ``--do-not-fail-on-deprecation``
      - :ref:`failOnDeprecation <appendixes.xml-configuration-file.phpunit.failOnDeprecation>` attribute on ``<phpunit>``
      - ``false``
    * - ``--fail-on-phpunit-deprecation`` / ``--do-not-fail-on-phpunit-deprecation``
      - :ref:`failOnPhpunitDeprecation <appendixes.xml-configuration-file.phpunit.failOnPhpunitDeprecation>` attribute on ``<phpunit>``
      - ``false``
    * - ``--fail-on-phpunit-notice`` / ``--do-not-fail-on-phpunit-notice``
      - :ref:`failOnPhpunitNotice <appendixes.xml-configuration-file.phpunit.failOnPhpunitNotice>` attribute on ``<phpunit>``
      - ``false``
    * - ``--fail-on-phpunit-warning`` / ``--do-not-fail-on-phpunit-warning``
      - ``failOnPhpunitWarning`` attribute on ``<phpunit>``
      - ``true``
    * - ``--fail-on-notice`` / ``--do-not-fail-on-notice``
      - :ref:`failOnNotice <appendixes.xml-configuration-file.phpunit.failOnNotice>` attribute on ``<phpunit>``
      - ``false``
    * - ``--fail-on-skipped`` / ``--do-not-fail-on-skipped``
      - :ref:`failOnSkipped <appendixes.xml-configuration-file.phpunit.failOnSkipped>` attribute on ``<phpunit>``
      - ``false``
    * - ``--fail-on-incomplete`` / ``--do-not-fail-on-incomplete``
      - :ref:`failOnIncomplete <appendixes.xml-configuration-file.phpunit.failOnIncomplete>` attribute on ``<phpunit>``
      - ``false``

``failOnAllIssues="true"`` takes precedence over the fine-grained ``failOn*``
attributes: configuring a fine-grained ``failOn*`` attribute to ``false`` does
not disable failing on the respective issue type (see
:ref:`appendixes.xml-configuration-file.phpunit.failOnAllIssues`).

The ``--do-not-fail-on-*`` CLI options have no XML configuration file
counterpart. They take precedence over the ``failOn*`` attributes listed
above, including ``failOnAllIssues``, and are the only way to not fail on
a specific issue type when ``failOnAllIssues="true"`` is configured.


.. _appendixes.configuration.reporting:

Reporting
=========

.. list-table::
    :header-rows: 1
    :widths: 35 40 25

    * - CLI option
      - XML setting
      - Default
    * - ``--colors=<flag>``
      - :ref:`colors <appendixes.xml-configuration-file.phpunit.colors>` attribute on ``<phpunit>``
      - ``false``
    * - ``--columns <n>``
      - :ref:`columns <appendixes.xml-configuration-file.phpunit.columns>` attribute on ``<phpunit>``
      - ``80``
    * - ``--diff-context <n>``
      - :ref:`diffContext <appendixes.xml-configuration-file.phpunit.diffContext>` attribute on ``<phpunit>``
      - ``3``
    * - ``--stderr``
      - :ref:`stderr <appendixes.xml-configuration-file.phpunit.stderr>` attribute on ``<phpunit>``
      - ``false``
    * - ``--reverse-list``
      - :ref:`reverseDefectList <appendixes.xml-configuration-file.phpunit.reverseDefectList>` attribute on ``<phpunit>``
      - ``false``
    * - ``--testdox``
      - :ref:`testdox <appendixes.xml-configuration-file.phpunit.testdox>` attribute on ``<phpunit>``
      - ``false``
    * - ``--testdox-summary``
      - :ref:`testdoxSummary <appendixes.xml-configuration-file.phpunit.testdoxSummary>` attribute on ``<phpunit>``
      - ``false``
    * - ``--display-all-issues``
      - :ref:`displayDetailsOnAllIssues <appendixes.xml-configuration-file.phpunit.displayDetailsOnAllIssues>` attribute on ``<phpunit>``
      - ``false``
    * - ``--display-incomplete``
      - :ref:`displayDetailsOnIncompleteTests <appendixes.xml-configuration-file.phpunit.displayDetailsOnIncompleteTests>` attribute on ``<phpunit>``
      - ``false``
    * - ``--display-skipped``
      - :ref:`displayDetailsOnSkippedTests <appendixes.xml-configuration-file.phpunit.displayDetailsOnSkippedTests>` attribute on ``<phpunit>``
      - ``false``
    * - ``--display-deprecations``
      - :ref:`displayDetailsOnTestsThatTriggerDeprecations <appendixes.xml-configuration-file.phpunit.displayDetailsOnTestsThatTriggerDeprecations>` attribute on ``<phpunit>``
      - ``false``
    * - ``--display-phpunit-deprecations``
      - :ref:`displayDetailsOnPhpunitDeprecations <appendixes.xml-configuration-file.phpunit.displayDetailsOnPhpunitDeprecations>` attribute on ``<phpunit>``
      - ``false``
    * - ``--display-phpunit-notices``
      - :ref:`displayDetailsOnPhpunitNotices <appendixes.xml-configuration-file.phpunit.displayDetailsOnPhpunitNotices>` attribute on ``<phpunit>``
      - ``false``
    * - ``--display-errors``
      - :ref:`displayDetailsOnTestsThatTriggerErrors <appendixes.xml-configuration-file.phpunit.displayDetailsOnTestsThatTriggerErrors>` attribute on ``<phpunit>``
      - ``false``
    * - ``--display-notices``
      - :ref:`displayDetailsOnTestsThatTriggerNotices <appendixes.xml-configuration-file.phpunit.displayDetailsOnTestsThatTriggerNotices>` attribute on ``<phpunit>``
      - ``false``
    * - ``--display-warnings``
      - :ref:`displayDetailsOnTestsThatTriggerWarnings <appendixes.xml-configuration-file.phpunit.displayDetailsOnTestsThatTriggerWarnings>` attribute on ``<phpunit>``
      - ``false``


.. _appendixes.configuration.logging:

Logging
=======

.. list-table::
    :header-rows: 1
    :widths: 35 40 25

    * - CLI option
      - XML setting
      - Default
    * - ``--log-junit <file>``
      - :ref:`\<junit outputFile\> <appendixes.xml-configuration-file.logging.junit>` under ``<logging>``
      - —
    * - ``--log-otr <file>``
      - ``<otr outputFile>`` under ``<logging>``
      - —
    * - ``--include-git-information``
      - ``includeGitInformation`` attribute on ``<logging><otr>``
      - ``false``
    * - ``--log-teamcity <file>``
      - :ref:`\<teamcity outputFile\> <appendixes.xml-configuration-file.logging.teamcity>` under ``<logging>``
      - —
    * - ``--testdox-html <file>``
      - :ref:`\<testdoxHtml outputFile\> <appendixes.xml-configuration-file.logging.testdoxHtml>` under ``<logging>``
      - —
    * - ``--testdox-text <file>``
      - :ref:`\<testdoxText outputFile\> <appendixes.xml-configuration-file.logging.testdoxText>` under ``<logging>``
      - —


.. _appendixes.configuration.code-coverage:

Code Coverage
=============

.. list-table::
    :header-rows: 1
    :widths: 35 40 25

    * - CLI option
      - XML setting
      - Default
    * - ``--path-coverage``
      - :ref:`pathCoverage <appendixes.xml-configuration-file.coverage>` attribute on ``<coverage>``
      - ``false``
    * - ``--disable-coverage-ignore``
      - :ref:`disableCodeCoverageIgnore <appendixes.xml-configuration-file.coverage>` attribute on ``<coverage>``
      - ``false``
    * - ``--coverage-clover <file>``
      - :ref:`\<clover outputFile\> <appendixes.xml-configuration-file.coverage.report.clover>` under ``<coverage><report>``
      - —
    * - ``--coverage-openclover <file>``
      - ``<openclover outputFile>`` under ``<coverage><report>``
      - —
    * - ``--coverage-cobertura <file>``
      - :ref:`\<cobertura outputFile\> <appendixes.xml-configuration-file.coverage.report.cobertura>` under ``<coverage><report>``
      - —
    * - ``--coverage-crap4j <file>``
      - :ref:`\<crap4j outputFile\> <appendixes.xml-configuration-file.coverage.report.crap4j>` under ``<coverage><report>``
      - —
    * - ``--coverage-html <dir>``
      - :ref:`\<html outputDirectory\> <appendixes.xml-configuration-file.coverage.report.html>` under ``<coverage><report>``
      - —
    * - ``--coverage-php <file>``
      - :ref:`\<php outputFile\> <appendixes.xml-configuration-file.coverage.report.php>` under ``<coverage><report>``
      - —
    * - ``--coverage-text[=<file>]``
      - :ref:`\<text outputFile\> <appendixes.xml-configuration-file.coverage.report.text>` under ``<coverage><report>``
      - —
    * - ``--only-summary-for-coverage-text``
      - ``showOnlySummary`` attribute on ``<coverage><report><text>``
      - ``false``
    * - ``--show-uncovered-for-coverage-text``
      - ``showUncoveredFiles`` attribute on ``<coverage><report><text>``
      - ``false``
    * - ``--coverage-xml <dir>``
      - :ref:`\<xml outputDirectory\> <appendixes.xml-configuration-file.coverage.report.xml>` under ``<coverage><report>``
      - —
    * - ``--exclude-source-from-xml-coverage``
      - ``includeSource`` attribute on ``<coverage><report><xml>`` (inverted)
      - ``true``


.. _appendixes.configuration.cli-only:

CLI-only Options
================

The following CLI options have no XML counterpart. They either control the current invocation of the test runner, perform one-shot operations, or suppress parts of the XML configuration.

Configuration selection and one-shot operations:

- ``-c``, ``--configuration <file>``
- ``--no-configuration``
- ``--no-extensions``
- ``--no-coverage``
- ``--no-logging``
- ``--generate-configuration``
- ``--migrate-configuration``
- ``--generate-baseline <file>``
- ``--ignore-baseline``
- ``--warm-coverage-cache``

Test selection and filtering:

- ``--all``
- ``--testsuite <name>`` (also overrides the XML ``defaultTestSuite`` attribute; see above)
- ``--exclude-testsuite <name>``
- ``--filter <pattern>``
- ``--exclude-filter <pattern>``
- ``--covers <name>``
- ``--uses <name>``
- ``--requires-php-extension <name>``
- ``--test-suffix <suffix>``

Introspection:

- ``--list-suites``
- ``--list-groups``
- ``--list-tests``
- ``--list-test-files``
- ``--list-tests-xml <file>``

Alternative output and event streaming:

- ``--debug``
- ``--with-telemetry``
- ``--teamcity``
- ``--log-events-text <file>``
- ``--log-events-verbose-text <file>``
- ``--no-output``
- ``--no-progress``
- ``--no-results``

Code coverage:

- ``--coverage-filter <dir>``

Miscellaneous:

- ``--random-order-seed <N>``
- ``-h``, ``--help``
- ``--version``
- ``--atleast-version <min>``
- ``--check-version``
- ``--check-php-configuration``


.. _appendixes.configuration.xml-only:

XML-only Settings
=================

The following XML configuration settings have no CLI counterpart and can only be changed by editing the configuration file.

On the ``<phpunit>`` element:

- :ref:`controlGarbageCollector <appendixes.xml-configuration-file.phpunit.controlGarbageCollector>` (default: ``false``)
- :ref:`numberOfTestsBeforeGarbageCollection <appendixes.xml-configuration-file.phpunit.numberOfTestsBeforeGarbageCollection>` (default: ``100``)
- :ref:`requireCoverageMetadata <appendixes.xml-configuration-file.phpunit.requireCoverageMetadata>` (default: ``false``)
- :ref:`timeoutForSmallTests <appendixes.xml-configuration-file.phpunit.timeoutForSmallTests>` (default: ``1``)
- :ref:`timeoutForMediumTests <appendixes.xml-configuration-file.phpunit.timeoutForMediumTests>` (default: ``10``)
- :ref:`timeoutForLargeTests <appendixes.xml-configuration-file.phpunit.timeoutForLargeTests>` (default: ``60``)
- :ref:`extensionsDirectory <appendixes.xml-configuration-file.phpunit.extensionsDirectory>`
- :ref:`shortenArraysForExportThreshold <appendixes.xml-configuration-file.phpunit.shortenArraysForExportThreshold>`

Test suite definition:

- The :ref:`\<testsuites\> <appendixes.xml-configuration-file.testsuites>` element and its children

Source code configuration:

- The :ref:`\<source\> <appendixes.xml-configuration-file.source>` element and all of its attributes and children

Code coverage:

- :ref:`includeUncoveredFiles <appendixes.xml-configuration-file.coverage>` attribute on ``<coverage>`` (default: ``true``)
- :ref:`ignoreDeprecatedCodeUnits <appendixes.xml-configuration-file.coverage>` attribute on ``<coverage>`` (default: ``false``)
- :ref:`\<html\> <appendixes.xml-configuration-file.coverage.report.html>` color and bound attributes (``lowUpperBound``, ``highLowerBound``, ``colorSuccessLow``, ``colorSuccessMedium``, ``colorSuccessHigh``, ``colorSuccessBar``, ``colorWarning``, ``colorWarningBar``, ``colorDanger``, ``colorDangerBar``, ``colorBreadcrumbs``, their ``*Dark`` variants, and ``customCssFile``)
- ``threshold`` attribute on ``<coverage><report><crap4j>`` (default: ``50``)

PHP environment:

- :ref:`\<const\> <appendixes.xml-configuration-file.php.const>` under ``<php>``
- :ref:`\<var\> <appendixes.xml-configuration-file.php.var>` under ``<php>``
- :ref:`\<env\> <appendixes.xml-configuration-file.php.env>` under ``<php>``
- :ref:`\<get\> <appendixes.xml-configuration-file.php.get>` under ``<php>``
- :ref:`\<post\> <appendixes.xml-configuration-file.php.post>` under ``<php>``
- :ref:`\<cookie\> <appendixes.xml-configuration-file.php.cookie>` under ``<php>``
- :ref:`\<server\> <appendixes.xml-configuration-file.php.server>` under ``<php>``
- :ref:`\<files\> <appendixes.xml-configuration-file.php.files>` under ``<php>``
- :ref:`\<request\> <appendixes.xml-configuration-file.php.request>` under ``<php>``

Extensions:

- :ref:`\<parameter\> <appendixes.xml-configuration-file.extensions.extension.arguments>` elements under ``<extensions><bootstrap>``
