

.. _error-handling:

**************
Error Handling
**************

PHPUnit's test runner registers an `error handler <https://www.php.net/manual/en/function.set-error-handler.php>`_ and processes
``E_DEPRECATED``, ``E_USER_DEPRECATED``, ``E_NOTICE``, ``E_USER_NOTICE``, ``E_STRICT``, ``E_WARNING``, and ``E_USER_WARNING``
errors. We will use the term "issues" to refer to ``E_DEPRECATED``, ``E_USER_DEPRECATED``, ``E_NOTICE``, ``E_USER_NOTICE``,
``E_STRICT``, ``E_WARNING``, and ``E_USER_WARNING`` errors for the remainder of this chapter.

The error handler is only active while a test is running and only processes issues triggered by test code or code that is
called from test code. It ignores issues triggered by PHPUnit's own code as well as code from PHPUnit's dependencies.

.. admonition:: Other error handlers

   When PHPUnit's test runner becomes aware (after it called ``set_error_handler()`` to register its error handler)
   that another error handler was registered then it immediately unregisters its error handler so that the
   previously registered error handler remains active.

The error handler emits events that are, for instance, subscribed to and used by the default progress and result printers
as well as loggers.

Here is the code that we will use for the examples in the remainder of this chapter:

.. parsed-literal::

    .
    ├── phpunit.xml
    ├── src
    │   └── FirstPartyClass.php
    ├── tests
    │   └── FirstPartyClassTest.php
    └── vendor
        ├── autoload.php
        └── ThirdPartyClass.php

    4 directories, 5 files

.. literalinclude:: examples/error-handling/deprecation/tests/FirstPartyClassTest.php
   :caption: tests/FirstPartyClassTest.php
   :language: php

.. literalinclude:: examples/error-handling/deprecation/src/FirstPartyClass.php
   :caption: src/FirstPartyClass.php
   :language: php

.. literalinclude:: examples/error-handling/deprecation/vendor/ThirdPartyClass.php
   :caption: vendor/ThirdPartyClass.php
   :language: php

.. literalinclude:: examples/error-handling/deprecation/default.xml
   :caption: phpunit.xml
   :language: xml


PHPUnit's test runner prints ``D``, ``N``, and ``W``, respectively, for tests that execute code which triggers an issue
(``D`` for deprecations, ``N`` for notices, and ``W`` for warnings).

Shown below is the default output PHPUnit's test runner prints for the example shown above:

.. parsed-literal::

    $ ./tools/phpunit
    PHPUnit 11.1.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.3.4
    Configuration: /path/to/example/phpunit.xml

    DD                                                                  1 / 1 (100%)

    Time: 00:00.002, Memory: 8.00 MB

    OK, but there were issues!
    Tests: 2, Assertions: 2, Deprecations: 2.

Detailed information, for instance which issue was triggered where, is only printed when ``--display-deprecations``,
``--display-notices``, or ``--display-warnings`` is used:

.. parsed-literal::

    $ ./tools/phpunit --display-deprecations
    PHPUnit 11.1.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.3.4
    Configuration: /path/to/example/phpunit.xml

    DD                                                                  1 / 1 (100%)

    Time: 00:00.002, Memory: 8.00 MB

    2 tests triggered 2 deprecations:

    1) /path/to/vendor/ThirdPartyClass.php:10
    deprecation in third-party code

    Triggered by:

    * example\FirstPartyClassTest::testOne
      /path/to/tests/FirstPartyClassTest.php:17

    * example\FirstPartyClassTest::testTwo
      /path/to/tests/FirstPartyClassTest.php:22

    2) /path/to/src/FirstPartyClass.php:13
    deprecation in first-party code

    Triggered by:

    * example\FirstPartyClassTest::testOne
      /path/to/tests/FirstPartyClassTest.php:17

    * example\FirstPartyClassTest::testTwo
      /path/to/tests/FirstPartyClassTest.php:22

    OK, but there were issues!
    Tests: 2, Assertions: 2, Deprecations: 2.

Limiting issues to "your code"
==============================

The reporting of issues can be limited to "your code", excluding third-party code from directories such as ``vendor``,
for example. You can configure what you consider "your code" in PHPUnit's XML configuration file
(see :ref:`appendixes.configuration.source`):

.. literalinclude:: examples/error-handling/deprecation/your-code.xml
   :caption: phpunit.xml
   :language: xml

Here is what the output of PHPUnit's test runner will look like after we configured (see above) it to restrict the
reporting of issues to our own code:

.. parsed-literal::

    $ ./tools/phpunit --display-deprecations
    PHPUnit 11.1.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.3.4
    Configuration: /path/to/example/phpunit.xml

    DD                                                                  1 / 1 (100%)

    Time: 00:00.002, Memory: 8.00 MB

    2 tests triggered 2 deprecations:

    1) /path/to/vendor/ThirdPartyClass.php:10
    deprecation in third-party code

    Triggered by:

    * example\FirstPartyClassTest::testOne
      /path/to/tests/FirstPartyClassTest.php:17

    * example\FirstPartyClassTest::testTwo
      /path/to/tests/FirstPartyClassTest.php:22

    2) /path/to/src/FirstPartyClass.php:13
    deprecation in first-party code

    Triggered by:

    * example\FirstPartyClassTest::testOne
      /path/to/tests/FirstPartyClassTest.php:17

    OK, but there were issues!
    Tests: 2, Assertions: 2, Deprecations: 2.

As you can see in the output shown above, deprecations triggered by third-party code located in the
``vendor`` directory are not reported anymore.

The following attributes can be used on the ``<source>`` element to configure how PHPUnit
uses the information what your code is:

* :ref:`appendixes.configuration.source.ignoreSelfDeprecations` setting can be used to ignore deprecations triggered by first-party code in first-party code
* :ref:`appendixes.configuration.source.ignoreDirectDeprecations` setting can be used to ignore deprecations triggered by first-party code in third-party code
* :ref:`appendixes.configuration.source.ignoreIndirectDeprecations` setting can be used to ignore deprecations triggered by third-party code
* :ref:`appendixes.configuration.source.restrictNotices` setting can be used to ignore notices in third-party code
* :ref:`appendixes.configuration.source.restrictWarnings` setting can be used to ignore warnings in third-party code


Ignoring issue suppression
==========================

By default, the error handler registered by PHPUnit's test runner respects the suppression operator (``@``).
This means that issues triggered using ``@trigger_error()``, for example, will not be reported by the
default progress and result printers.

The suppression of issues using the suppression operator (``@``) can be ignored by configuration settings
in PHPUnit's XML configuration file:

* :ref:`appendixes.configuration.source.ignoreSuppressionOfDeprecations` setting can be used to ignore the suppression of ``E_USER_DEPRECATED`` issues
* :ref:`appendixes.configuration.source.ignoreSuppressionOfPhpDeprecations` setting can be used to ignore the suppression of ``E_DEPRECATED`` issues
* :ref:`appendixes.configuration.source.ignoreSuppressionOfNotices` setting can be used to ignore the suppression of ``E_USER_NOTICES`` issues
* :ref:`appendixes.configuration.source.ignoreSuppressionOfPhpNotices` setting can be used to ignore the suppression of ``E_NOTICE`` and ``E_STRICT`` issues
* :ref:`appendixes.configuration.source.ignoreSuppressionOfWarnings` setting can be used to ignore the suppression of ``E_USER_WARNING`` issues
* :ref:`appendixes.configuration.source.ignoreSuppressionOfPhpWarnings` setting can be used to ignore the suppression of ``E_WARNING`` issues


Ignoring previously reported issues
===================================

PHPUnit's test runner supports declaring the currently reported list of issues. Issues that are on this so-called baseline
are no longer reported. This allows you to focus on new issues that are triggered by new or changed code.

When you run your test suite using the ``--generate-baseline`` CLI option then PHPUnit's test runner
will write a list of all issues that are triggered to an XML file:

.. parsed-literal::

    $ phpunit --generate-baseline baseline.xml
    PHPUnit 11.1.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.10
    Configuration: /path/to/example/phpunit.xml

    D                                                                   1 / 1 (100%)

    Time: 00:00.008, Memory: 4.00 MB

    OK, but there were issues!
    Tests: 1, Assertions: 1, Deprecations: 1.

    Baseline written to /path/to/example/baseline.xml.

When you run your test suite using the ``--use-baseline`` CLI option (or if you have configured a baseline
in your XML configuration file for PHPUnit using the :ref:`appendixes.configuration.source.baseline` setting)
then PHPUnit's test runner will use this list of already known issues to ignore them for the current run:

.. parsed-literal::

    $ phpunit --use-baseline baseline.xml
    PHPUnit 11.1.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.10
    Configuration: /path/to/example/phpunit.xml

    .                                                                   1 / 1 (100%)

    Time: 00:00.007, Memory: 4.00 MB

    OK (1 test, 1 assertion)

    2 issues were ignored by baseline.

Expecting Deprecations (``E_USER_DEPRECATED``)
==============================================

The ``expectUserDeprecationMessage()`` method can be used to expect that an ``E_USER_DEPRECATED``
issue with a specified message is triggered.

.. literalinclude:: examples/error-handling/DeprecationExpectationTest.php
   :caption: Usage of expectUserDeprecationMessage()
   :language: php

Running the test shown above yields the output shown below:

.. literalinclude:: examples/error-handling/DeprecationExpectationTest.php.out

Alternatively, the ``$this->expectUserDeprecationMessageMatches()`` can be used to expect that
an ``E_USER_DEPRECATED`` issue is triggered where the deprecation message matches a specified
regular expression.

Disabling PHPUnit's error handler
=================================

When you want to test your own `error handler <https://www.php.net/manual/en/function.set-error-handler.php>`_
or want to test that unit of code under test triggers an expected issue, for instance, the error handler
registered by PHPUnit's test runner will interfere with what you want to achieve.

The ``#[WithoutErrorHandler]`` attribute can be used in such a case to disable PHPUnit's error handler for
a test method.
