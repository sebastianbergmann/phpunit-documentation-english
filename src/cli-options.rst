

.. _appendixes.cli-options:

***********
CLI Options
***********

This appendix provides a reference overview of all command-line options supported by PHPUnit's test runner.


.. _appendixes.cli-options.configuration:

Configuration
=============

``--bootstrap <file>``
   Specifies a PHP script that is included before any tests are executed. This is typically used to set up autoloading or initialize the environment required by the test suite.

``-c``, ``--configuration <file>``
   Reads configuration from the specified XML file instead of the default ``phpunit.xml``. This allows you to maintain multiple configuration files for different testing scenarios.

``--no-configuration``
   Ignores the default ``phpunit.xml`` configuration file. Useful when you want to run tests without any XML-based configuration being applied.

``--extension <class>``
   Registers a test runner extension by specifying its bootstrap class. The extension will be loaded and activated for the current test run.

``--no-extensions``
   Prevents any test runner extensions from being registered. This disables all extensions, including those configured in the XML configuration file.

``--include-path <path(s)>``
   Prepends PHP's ``include_path`` with the given path or paths. Multiple paths can be specified, separated by the platform's path separator.

``-d <key[=value]>``
   Sets a ``php.ini`` configuration value for the duration of the test run. For boolean settings, the value can be omitted to set it to ``1``.

``--cache-directory <dir>``
   Specifies the directory where PHPUnit stores data between test suite runs. The following data is stored in this directory:

   - ``test-results``: Results of the previous test suite run (used for reordering tests based on previous defects or duration with the ``--order-by`` option, for instance)
   - ``code-coverage``: Results of static analysis of tested code and test code (only written when code coverage reporting is requested; significantly improves performance of code coverage analysis on subsequent runs)

``--generate-configuration``
   Generates a ``phpunit.xml`` configuration file with suggested settings through an interactive wizard. This is a convenient way to bootstrap a new PHPUnit configuration.

``--migrate-configuration``
   Migrates an existing XML configuration file to the current format. This is useful after upgrading PHPUnit to a new major version that introduces configuration changes.

``--generate-baseline <file>``
   Generates a baseline file that records currently existing issues such as deprecations and warnings. This baseline can later be used to suppress known issues so that only new issues are reported.

``--use-baseline <file>``
   Uses a previously generated baseline file to suppress known issues during the test run. Issues recorded in the baseline will not be reported.

``--ignore-baseline``
   Ignores any configured baseline file, causing all issues to be reported regardless of whether they appear in the baseline.


.. _appendixes.cli-options.selection:

Selection
=========

``--all``
   Ignores test selection configured in the XML configuration file and runs all discovered tests. This overrides any ``<testsuites>`` filtering from the configuration.

``--list-suites``
   Lists all available test suites defined in the XML configuration file and then exits. No tests are executed.

``--testsuite <name>``
   Restricts test execution to the specified test suite or suites. Multiple suite names can be separated by commas.

``--exclude-testsuite <name>``
   Excludes the specified test suite or suites from the test run. Multiple suite names can be separated by commas.

``--list-groups``
   Lists all available test groups and then exits. No tests are executed.

``--group <name>``
   Only runs tests belonging to the specified group. This option can be used multiple times to select tests from more than one group.

``--exclude-group <name>``
   Excludes tests belonging to the specified group from the test run. This option can be used multiple times to exclude tests from more than one group.

``--covers <name>``
   Only runs tests that declare they intend to cover the specified class or function. This filters based on ``#[Covers*]`` attributes. This option can be used multiple times.

``--uses <name>``
   Only runs tests that declare they intend to use the specified class or function. This filters based on ``#[Uses*]`` attributes. This option can be used multiple times.

``--requires-php-extension <name>``
   Only runs tests that declare a requirement for the specified PHP extension. Tests without this requirement are skipped.

``--list-test-files``
   Lists all test files that would be executed and then exits. No tests are actually run.

``--list-tests``
   Lists all tests that would be executed and then exits. Each test is shown with its fully qualified name.

``--list-tests-xml <file>``
   Lists all tests that would be executed in XML format and writes the output to the specified file. No tests are actually run.

``--filter <pattern>``
   Selects tests whose name matches ``<pattern>``. In addition to full PCRE regular expressions, a shortcut syntax is accepted for selecting individual data sets (``testFoo#3``, ``testFoo#1-3``, ``testFoo@named set``). See :ref:`textui.selecting-tests.filter` for the full syntax.

``--exclude-filter <pattern>``
   The inverse of ``--filter``; accepts the same pattern syntax and removes matching tests from the run.

``--test-suffix <suffix>``
   Restricts test file discovery to files with the specified suffix. The defaults are ``Test.php`` and ``.phpt``. This option can be used multiple times.


.. _appendixes.cli-options.execution:

Execution
=========

``--process-isolation``
   Runs each test in a separate PHP process. This ensures complete isolation between tests but significantly increases execution time.

``--globals-backup``
   Backs up and restores the ``$GLOBALS`` superglobal before and after each test. This prevents tests from inadvertently affecting each other through global state.

``--static-backup``
   Backs up and restores static properties of all classes before and after each test. This prevents tests from affecting each other through shared static state.

``--strict-coverage``
   Enforces strict mode for code coverage metadata. Tests that execute code not declared in their ``#[Covers*]`` or ``#[Uses*]`` metadata will be marked as risky.

``--strict-global-state``
   Enforces strict checking of global state changes. Tests that modify global state (such as global variables, super-globals, or static properties) will be marked as risky.

``--disallow-test-output``
   Marks tests that produce output (via ``echo``, ``print``, etc.) as risky. This helps ensure tests only communicate results through assertions.

``--enforce-time-limit``
   Enforces time limits on test execution based on test size. Small tests get 1 second, medium tests get 10 seconds, and large tests get 60 seconds by default.

``--default-time-limit <sec>``
   Sets the timeout in seconds for tests that have no declared size. This is used in conjunction with ``--enforce-time-limit``.

``--do-not-report-useless-tests``
   Suppresses reporting of useless tests. A test is considered useless if it does not perform any assertions.

``--stop-on-defect``
   Stops test execution after the first error, failure, warning, or risky test. This is a shorthand for enabling all specific stop-on options.

``--stop-on-error``
   Stops test execution after the first test that results in an error.

``--stop-on-failure``
   Stops test execution after the first test that results in a failure.

``--stop-on-warning``
   Stops test execution after the first test that triggers a warning.

``--stop-on-risky``
   Stops test execution after the first test that is considered risky.

``--stop-on-deprecation``
   Stops test execution after the first test that triggers a deprecation.

``--stop-on-notice``
   Stops test execution after the first test that triggers a notice.

``--stop-on-skipped``
   Stops test execution after the first skipped test.

``--stop-on-incomplete``
   Stops test execution after the first incomplete test.

``--fail-on-empty-test-suite``
   Causes PHPUnit to return a failure exit code when no tests were executed. This is useful in CI pipelines to detect misconfigured test suites.

``--fail-on-warning``
   Causes PHPUnit to return a failure exit code when any test triggers a warning.

``--fail-on-risky``
   Causes PHPUnit to return a failure exit code when any test is considered risky.

``--fail-on-deprecation``
   Causes PHPUnit to return a failure exit code when any test triggers a deprecation.

``--fail-on-phpunit-deprecation``
   Causes PHPUnit to return a failure exit code when a PHPUnit deprecation is triggered. PHPUnit deprecations relate to deprecated usage of PHPUnit's own API.

``--fail-on-phpunit-notice``
   Causes PHPUnit to return a failure exit code when a PHPUnit notice is triggered. PHPUnit notices are informational messages about PHPUnit-specific issues.

``--fail-on-phpunit-warning``
   Causes PHPUnit to return a failure exit code when a PHPUnit warning is triggered.

``--fail-on-notice``
   Causes PHPUnit to return a failure exit code when any test triggers a notice.

``--fail-on-skipped``
   Causes PHPUnit to return a failure exit code when any test is skipped.

``--fail-on-incomplete``
   Causes PHPUnit to return a failure exit code when any test is marked incomplete.

``--fail-on-all-issues``
   Causes PHPUnit to return a failure exit code when any issue is triggered. This is a shorthand that enables all ``--fail-on-*`` options at once.

``--do-not-fail-on-empty-test-suite``
   Disables the signaling of failure when no tests were run. This overrides the corresponding XML configuration setting.

``--do-not-fail-on-warning``
   Disables the signaling of failure when a warning is triggered. This overrides the corresponding XML configuration setting.

``--do-not-fail-on-risky``
   Disables the signaling of failure when a test is considered risky. This overrides the corresponding XML configuration setting.

``--do-not-fail-on-deprecation``
   Disables the signaling of failure when a deprecation is triggered. This overrides the corresponding XML configuration setting.

``--do-not-fail-on-phpunit-deprecation``
   Disables the signaling of failure when a PHPUnit deprecation is triggered. This overrides the corresponding XML configuration setting.

``--do-not-fail-on-phpunit-notice``
   Disables the signaling of failure when a PHPUnit notice is triggered. This overrides the corresponding XML configuration setting.

``--do-not-fail-on-phpunit-warning``
   Disables the signaling of failure when a PHPUnit warning is triggered. This overrides the corresponding XML configuration setting.

``--do-not-fail-on-notice``
   Disables the signaling of failure when a notice is triggered. This overrides the corresponding XML configuration setting.

``--do-not-fail-on-skipped``
   Disables the signaling of failure when a test is skipped. This overrides the corresponding XML configuration setting.

``--do-not-fail-on-incomplete``
   Disables the signaling of failure when a test is marked incomplete. This overrides the corresponding XML configuration setting.

``--cache-result``
   Enables writing of test results to the ``test-results`` cache file. The result cache is used by the ``--order-by defects`` and ``--order-by duration`` options to reorder tests based on results of previous test suite runs.

``--do-not-cache-result``
   Disables writing of test results to the ``test-results`` cache file. This overrides the corresponding XML configuration setting.

``--order-by <order>``
   Controls the order in which tests are executed. Supported values are ``default``, ``defects``, ``depends``, ``duration``, ``no-depends``, ``random``, ``reverse``, and ``size``. Multiple values can be combined with a comma.

``--resolve-dependencies``
   Alias for ``--order-by depends``.

``--ignore-dependencies``
   Alias for ``--order-by no-depends``.

``--random-order``
   Alias for ``--order-by random``.

``--random-order-seed <N>``
   Sets the seed for the random number generator when using ``--order-by random``. Using the same seed produces the same test order, which is useful for reproducing failures.

``--reverse-order``
   Alias for ``--order-by reverse``.


.. _appendixes.cli-options.reporting:

Reporting
=========

``--colors=<flag>``
   Controls the use of colors in terminal output. Accepted values are ``never``, ``auto`` (uses colors when the terminal supports it), and ``always``.

``--columns <n>``
   Sets the number of columns to use for progress output. This controls the width of the progress bar displayed during test execution.

``--columns max``
   Uses the maximum number of columns available in the terminal for progress output.

``--stderr``
   Writes output to STDERR instead of STDOUT. This can be useful when STDOUT is being redirected or captured.

``--no-progress``
   Disables the progress section of the default output (see :ref:`textui.output.progress`). Alternative output formats selected via ``--testdox``, ``--teamcity``, or ``--debug`` are not affected by this option.

``--no-results``
   Disables the test results and summary sections of the default output (see :ref:`textui.output.test-results` and :ref:`textui.output.summary`). Alternative output formats selected via ``--testdox``, ``--teamcity``, or ``--debug`` are not affected by this option.

``--no-output``
   Disables the default output of PHPUnit (runtime information, progress, test results, and summary). Alternative output formats selected via ``--testdox``, ``--teamcity``, or ``--debug`` are not affected by this option and will still produce their own output.

``--display-incomplete``
   Displays details for incomplete tests in the test results. By default, only a count of incomplete tests is shown.

``--display-skipped``
   Displays details for skipped tests in the test results. By default, only a count of skipped tests is shown.

``--display-deprecations``
   Displays details for deprecations triggered by tests. This shows the deprecation messages along with their source location.

``--display-phpunit-deprecations``
   Displays details for PHPUnit deprecations in the test results.

``--display-phpunit-notices``
   Displays details for PHPUnit notices in the test results. These are informational messages from PHPUnit about potential configuration or usage issues.

``--display-errors``
   Displays details for errors triggered by tests. This includes PHP errors such as ``E_ERROR`` that occurred during test execution.

``--display-notices``
   Displays details for notices triggered by tests. This shows PHP notice messages along with their source location.

``--display-warnings``
   Displays details for warnings triggered by tests. This shows PHP warning messages along with their source location.

``--display-all-issues``
   Displays details for all types of issues. This is a shorthand that enables all ``--display-*`` options at once.

.. admonition:: Backward Compatibility

   Please note that if you use ``--display-all-issues`` then you opt in to
   printing additional issues in later versions of PHPUnit that will be put
   under the control of this CLI option. This is not considered to be a break of
   backward compatibility and rather the expected behaviour of this CLI option.

``--reverse-list``
   Prints defects in reverse order. This shows the most recently encountered defects first, which can be useful for large test suites.

``--teamcity``
   Replaces the default progress and result output with TeamCity format. This is used for integration with JetBrains TeamCity and other CI tools that support the TeamCity service message protocol.

``--testdox``
   Replaces the default result output with TestDox format. TestDox renders test method names as human-readable sentences, providing a behavior-driven view of the test suite. To write TestDox output to a file instead, use ``--testdox-html`` or ``--testdox-text`` (see :ref:`appendixes.cli-options.logging`).

``--testdox-summary``
   Repeats the TestDox output for tests that had errors, failures, or issues at the end of the test run. This provides a focused summary of problematic tests in TestDox format. Requires ``--testdox``.

``--debug``
   Replaces the default progress and result output with detailed debugging information. This shows event-by-event details of the test execution process.

``--with-telemetry``
   Includes telemetry information such as timing and memory usage in the debugging output. This option is used in conjunction with ``--debug``.


.. _appendixes.cli-options.logging:

Logging
=======

``--log-junit <file>``
   Writes the test results in JUnit XML format to the specified file. This format is widely supported by CI/CD tools for test result reporting.

``--log-otr <file>``
   Writes the test results in Open Test Reporting XML format to the specified file. This is a newer, standardized format for test result reporting.

``--include-git-information``
   Includes Git information (such as branch and commit hash) in the Open Test Reporting XML logfile. This helps associate test results with specific code versions.

``--log-teamcity <file>``
   Writes the test results in TeamCity format to the specified file. This is useful when you need TeamCity-formatted output in a log file while using a different console output format.

``--testdox-html <file>``
   Writes the test results in TestDox format as an HTML file. This produces a browsable, human-readable document of test behaviors. This option is intended for writing a report to a file; for TestDox-formatted console output use ``--testdox`` instead.

``--testdox-text <file>``
   Writes the test results in TestDox format as a plain text file. This produces a simple text document of test behaviors. This option is intended for writing a report to a file; for TestDox-formatted console output use ``--testdox`` instead.

``--log-events-text <file>``
   Streams all test runner events as plain text to the specified file. This provides a comprehensive log of every event that occurs during the test run.

``--log-events-verbose-text <file>``
   Streams all test runner events as plain text with extended information to the specified file. This includes additional details compared to ``--log-events-text``.

``--no-logging``
   Ignores all logging configured in the XML configuration file. No log files will be written regardless of XML configuration settings.


.. _appendixes.cli-options.code-coverage:

Code Coverage
=============

``--coverage-clover <file>``
   Writes the code coverage report in Clover XML format to the specified file. This format is supported by many CI tools and code quality platforms.

``--coverage-openclover <file>``
   Writes the code coverage report in OpenClover XML format to the specified file. OpenClover is the open-source continuation of the Clover format.

``--coverage-cobertura <file>``
   Writes the code coverage report in Cobertura XML format to the specified file. This format is commonly used by CI tools such as GitLab CI and Jenkins.

``--coverage-crap4j <file>``
   Writes the code coverage report in Crap4J XML format to the specified file. Crap4J combines code coverage with cyclomatic complexity to identify risky code.

``--coverage-html <dir>``
   Writes the code coverage report in HTML format to the specified directory. This produces a browsable report with line-by-line coverage highlighting.

``--coverage-php <file>``
   Writes serialized code coverage data to the specified file. This data can later be merged with other coverage data or processed programmatically.

``--coverage-text[=<file>]``
   Writes the code coverage report in plain text format. If a file is specified, the report is written there; otherwise it is written to standard output.

``--only-summary-for-coverage-text``
   When generating a text coverage report, only shows the summary without per-class details. This produces a more concise coverage overview.

``--show-uncovered-for-coverage-text``
   When generating a text coverage report, includes files that have no coverage at all. By default, completely uncovered files are omitted from the text report.

``--coverage-xml <dir>``
   Writes the code coverage report in XML format to the specified directory. This generates one XML file per source file along with an index file.

``--exclude-source-from-xml-coverage``
   Excludes the ``<source>`` element from the XML coverage report. This reduces the size of the generated XML files by omitting source code.

``--warm-coverage-cache``
   Performs static analysis of tested code and test code ahead of time and stores the result in the ``code-coverage`` cache directory. Without this option, the static analysis that is needed for code coverage reporting is performed on the fly during the test suite run and the result is then stored in the cache.

``--coverage-filter <dir>``
   Adds the specified directory to the code coverage filter. Only files within filtered directories will be included in the coverage report.

``--path-coverage``
   Enables path coverage reporting in addition to line coverage. Path coverage tracks which execution paths through the code have been tested, providing deeper insight than line coverage alone.

``--disable-coverage-ignore``
   Disables the ``@codeCoverageIgnore`` metadata. When this option is used, code annotated with coverage-ignore metadata will be included in the coverage report.

``--no-coverage``
   Ignores all code coverage reporting configured in the XML configuration file. No coverage data will be collected or reported.


.. _appendixes.cli-options.miscellaneous:

Miscellaneous
=============

``-h``, ``--help``
   Prints the usage information showing all available command-line options and then exits.

``--version``
   Prints the PHPUnit version string and then exits.

``--atleast-version <min>``
   Checks whether the installed PHPUnit version is at least the specified minimum version. Exits with code 0 if the condition is met, or 1 otherwise.

``--check-version``
   Checks whether the installed PHPUnit version is the latest available release. This requires an internet connection to query the latest version information.

``--check-php-configuration``
   Checks whether the current PHP configuration follows best practices for running PHPUnit. Reports any ``php.ini`` settings that may cause issues during test execution.
