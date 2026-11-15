

.. _appendixes.capability-statement:

********************
Capability Statement
********************

This appendix states what PHPUnit's test runner does to the environment in which it is run:
which code it executes, which processes it starts, which network connections it opens, and
which files it writes.

It is written for those who have to decide whether the ``phpunit`` command may be run
automatically and without supervision, for instance in a continuous integration pipeline or by a
coding agent.

.. admonition:: PHPUnit is not a sandbox

   This statement covers PHPUnit itself. The bootstrap scripts, extensions, and tests that
   PHPUnit is asked to load are arbitrary PHP code. That code can do everything the PHP process
   is allowed to do, and PHPUnit does not restrict it. Isolating the process, for instance its
   access to the file system and to the network, is the responsibility of the environment in
   which PHPUnit is run.


.. _appendixes.capability-statement.code:

Code That Is Executed
=====================

PHPUnit executes code from the following sources and from no others:

Composer's autoloader
   When PHPUnit is :ref:`installed using Composer <installation>`, the ``vendor/autoload.php``
   script of the project is loaded. This also loads the files that the project and its
   dependencies have configured to be loaded with the autoloader. This happens for every
   invocation of ``phpunit``, whatever the options.

Bootstrap scripts
   The script configured with the ``--bootstrap`` option or the
   :ref:`bootstrap <appendixes.xml-configuration-file.phpunit.bootstrap>` attribute of the
   ``<phpunit>`` element, and the scripts configured with the ``bootstrap`` attribute of
   :ref:`<testsuite> <appendixes.xml-configuration-file.testsuites.testsuite>` elements.

Extensions for the test runner
   The classes configured with the ``--extension`` option or the
   :ref:`\<extensions\> <appendixes.xml-configuration-file.extensions>` element, and all
   ``*.phar`` files in the directory configured with the
   :ref:`extensionsDirectory <appendixes.xml-configuration-file.phpunit.extensionsDirectory>`
   attribute. The ``--no-extensions`` option prevents both.

Test files
   The files that are searched for tests, as selected on the command-line or in the
   :ref:`\<testsuites\> <appendixes.xml-configuration-file.testsuites>` element. A test file is
   loaded, and its data providers are called, when the test suite is built. This happens before
   the first test is run, and it also happens when tests are only listed, for instance with
   ``--list-tests``.

PHPT tests
   The ``--FILE--``, ``--SKIPIF--``, and ``--CLEAN--`` sections of a
   :ref:`PHPT test <appendixes.phpt>`, each in a PHP process of its own.

The :ref:`\<php\> <appendixes.xml-configuration-file.php>` element of the XML configuration file
and the ``-d`` and ``--include-path`` options do not execute code. They do change the
configuration of PHP, its ``include_path``, constants, global variables, and environment
variables, and thereby the conditions under which the code listed above is executed.

No code from the sources listed above, with the exception of Composer's autoloader, is executed
when PHPUnit is invoked with ``--help``, ``--version``, ``--atleast-version``,
``--check-version``, ``--check-php-configuration``, ``--generate-configuration``,
``--migrate-configuration``, or ``--validate-configuration``.


.. _appendixes.capability-statement.processes:

Processes That Are Started
==========================

PHPUnit starts the following processes and no others:

The PHP binary
   The PHP binary that PHPUnit itself is run with is started for each test that is run in a
   separate process (see ``--process-isolation`` in :ref:`appendixes.cli-options.execution`,
   :ref:`appendixes.attributes.RunInSeparateProcess`, and
   :ref:`appendixes.attributes.RunTestsInSeparateProcesses`) and for each section of a
   :ref:`PHPT test <appendixes.phpt>` that is executed. Before the first of these
   processes is started, the PHP binary is started to find out which settings and extensions
   have to be passed on to them.

``git``
   Git is invoked to read the remote, branch, commit, and status of the working directory when
   the ``--include-git-information`` option is used together with ``--log-otr``. It is also
   invoked with ``describe`` to determine PHPUnit's version number when PHPUnit is run from a
   Git checkout of its own repository rather than from a release.

``dot``
   The ``dot`` command of `Graphviz <https://graphviz.org/>`_ is invoked to render control flow
   graphs when a code coverage report in HTML format is generated and path coverage data has
   been collected (see ``--path-coverage`` in :ref:`appendixes.cli-options.code-coverage`).

``stty``
   On Windows ``mode CON``. It is invoked to find out the width of the terminal for ``--help``
   and when ``max`` is configured for the number of
   :ref:`columns <appendixes.xml-configuration-file.phpunit.columns>`.


.. _appendixes.capability-statement.network:

Network Access
==============

PHPUnit does not access the network when it runs tests. There are two cases in which PHPUnit
itself opens a network connection:

``--check-version``
   PHPUnit requests the number of the latest version from ``https://phar.phpunit.de/`` and then
   exits without running tests.

``socket://`` targets
   When the target of ``--log-junit``, ``--log-teamcity``, ``--testdox-html``, or
   ``--testdox-text``, or of the corresponding element of the
   :ref:`\<logging\> <appendixes.xml-configuration-file.logging>` element, has the form
   ``socket://<host>:<port>``, then PHPUnit opens a TCP connection to that host and port and
   writes the log to it.

The targets of logs and reports are otherwise opened with PHP's functions for files. PHPUnit does
not verify that such a target is a path in the local file system: a target that names a
`stream wrapper <https://www.php.net/manual/en/wrappers.php>`_ of PHP that accesses the network
is handed to that stream wrapper.


.. _appendixes.capability-statement.files:

Files That Are Written
======================

PHPUnit writes the files listed in this section and no others. It does not change or delete the
files that it reads: the XML configuration file (with the exception of
``--migrate-configuration``), test files, the files of the code under test, and baseline files.


.. _appendixes.capability-statement.files.output:

Output That Is Requested
------------------------

A file or directory is only written to the target that is given for it on the command-line or in
the XML configuration file, and only when it is requested. A file that already exists is
overwritten. For most logs and code coverage reports, directories that are missing are created.

- Logs: ``--log-otr``, ``--log-junit``, ``--log-teamcity``, ``--testdox-html``,
  ``--testdox-text``, ``--log-events-text``, and ``--log-events-verbose-text``
  (see :ref:`textui.logging`) as well as the
  :ref:`\<logging\> <appendixes.xml-configuration-file.logging>` element
- Code coverage reports: ``--coverage-clover``, ``--coverage-cobertura``, ``--coverage-crap4j``,
  ``--coverage-html``, ``--coverage-jsonl``, ``--coverage-openclover``, ``--coverage-php``,
  ``--coverage-text``, and ``--coverage-xml`` (see :ref:`appendixes.cli-options.code-coverage`)
  as well as the :ref:`\<report\> <appendixes.xml-configuration-file.coverage.report>` element.
  ``--coverage-html``, ``--coverage-jsonl``, and ``--coverage-xml`` write a directory of files.
- ``--generate-baseline`` writes a baseline file
- ``--list-tests-xml`` writes a list of tests
- ``--generate-configuration`` writes ``phpunit.xml`` to the current working directory
- ``--migrate-configuration`` copies the XML configuration file to a file with the additional
  suffix ``.bak`` and then overwrites the XML configuration file


.. _appendixes.capability-statement.files.state:

State That Is Kept Between Runs
-------------------------------

Test run history
   The status and duration of each test are written at the end of a run, unless this is
   disabled with ``--do-not-record-test-run-history`` or the
   :ref:`recordTestRunHistory <appendixes.xml-configuration-file.phpunit.recordTestRunHistory>`
   attribute. The file is ``test-run-history`` in the cache directory. When no cache directory
   is configured, it is ``.phpunit.result.cache`` in the directory of the XML configuration
   file or, when no XML configuration file is used, in the directory of the ``phpunit`` script.

Test index
   An index of the test files is written to ``test-index`` in the cache directory when this is
   enabled with ``--cache-test-index`` or the ``cacheTestIndex`` attribute of the ``<phpunit>``
   element. It is not written when no cache directory is configured.

Cache for static analysis
   The results of the static analysis that is performed for code coverage are written when
   code coverage is collected and for ``--warm-coverage-cache``. The directory is
   ``code-coverage`` in the cache directory. When no cache directory is configured, it is
   ``phpunit-code-coverage-cache-<user>`` in the operating system's directory for temporary
   files, where ``<user>`` is the ID of the current user (on Windows
   ``phpunit-code-coverage-cache``). On systems other than Windows, this directory is created
   so that only the current user can access it, and it is not used when another user owns it
   or can write to it.

The :ref:`cache directory <appendixes.xml-configuration-file.phpunit.cacheDirectory>` is created
as soon as it is configured, whether or not something is written to it.


.. _appendixes.capability-statement.files.temporary:

Temporary Files
---------------

PHP processes that PHPUnit starts
   PHPUnit passes code and data to a test that is run in a separate process, and receives the
   result of that test, through files in the operating system's directory for temporary files.
   The code of a :ref:`PHPT test <appendixes.phpt>` that has a ``--STDIN--`` section
   is passed to its process in the same way. The names of these files start with ``phpunit_``.
   PHPUnit deletes them when the process has finished, with one exception: a file with the list
   of the files of the code under test is created once per invocation of ``phpunit`` and is
   deleted when PHPUnit exits.

PHPT tests and code coverage
   While code coverage is collected, PHPUnit creates a directory for each
   :ref:`PHPT test <appendixes.phpt>` in the operating system's directory for temporary files
   for the time that this test is run. The name of this directory is ``phpunit-phpt-`` followed
   by a random string, and only the current user can access it. PHPUnit writes two files to
   this directory and deletes them, and the directory, when the test has finished.

Anonymous temporary files
   PHPUnit also uses temporary files that have no name it could pass on, for instance to
   capture output that is written with ``error_log()``. Such a file is created in the operating
   system's directory for temporary files and is removed automatically when PHPUnit closes it.
