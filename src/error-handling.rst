

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
   previously registered error handler remains active. Consequently, the features described in this chapter are not
   available when you use your own error handler.

.. admonition:: Your own error handler should follow best practices

   Your own error handler should ignore errors emitted by code it is not responsible for, for instance PHPUnit's code.

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

Detailed information, for instance which issue was triggered where, is only printed when ``--display-deprecations``, ``--display-phpunit-deprecations``, ``--display-phpunit-notices``, ``--display-errors``, ``--display-notices``, ``--display-warnings``, or ``--display-all-issues`` is used:

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

A common problem is that dependencies in ``vendor`` trigger deprecation warnings, notices, or warnings that clutter your test output.
The reporting of issues can be limited to "your code" so that you only see issues that originate from code you are responsible for.

First, you need to configure what you consider "your code" using the ``<source>`` element in your XML configuration file (see :ref:`appendixes.configuration.source`).
Then you can use the following attributes on the ``<source>`` element to filter issues:

* ``ignoreIndirectDeprecations="true"`` ignores ``E_DEPRECATED`` and ``E_USER_DEPRECATED`` triggered by third-party code (e.g. code in ``vendor``)
* ``restrictNotices="true"`` ignores ``E_NOTICE``, ``E_USER_NOTICE``, and ``E_STRICT`` triggered by third-party code
* ``restrictWarnings="true"`` ignores ``E_WARNING`` and ``E_USER_WARNING`` triggered by third-party code

Here is a configuration that only reports issues from your own code:

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

This can be used together with the ``#[IgnoreDeprecations]`` attribute to not let the test fail.

.. admonition:: Testing deprecated functionality

   When you deprecate functionality in your code, you want to keep tests for the deprecated code until it is actually removed.
   Use the ``#[IgnoreDeprecations]`` attribute together with ``expectUserDeprecationMessage()`` on tests that directly exercise deprecated functionality.
   This ensures the deprecated code still works as expected, the expected deprecation message is verified, and the test is not reported as having triggered a deprecation.


.. _error-handling.issue-trigger-resolvers:

Custom Issue Trigger Resolvers
==============================

While the ``<deprecationTrigger>`` element (see :ref:`appendixes.configuration.source.deprecationTrigger`) allows you to
configure functions and methods that act as wrappers around ``trigger_error()``, some frameworks require more
sophisticated logic to determine the correct caller and callee for issue classification. For these cases, PHPUnit
supports custom issue trigger resolvers.

A custom issue trigger resolver is a class that implements the ``PHPUnit\Runner\IssueTriggerResolver\Resolver`` interface:

.. code-block:: php

    <?php declare(strict_types=1);
    namespace PHPUnit\Runner\IssueTriggerResolver;

    interface Resolver
    {
        /**
         * Return null to defer to the next resolver in the chain.
         *
         * @param list<array{file?: string, line?: int, class?: class-string, function?: string, type?: string, args?: list<mixed>}> $trace
         */
        public function resolve(array $trace, string $message): ?Resolution;
    }

The ``resolve()`` method receives the filtered stack trace and the error message. It must return either:

- A ``PHPUnit\Runner\IssueTriggerResolver\Resolution`` object that specifies the callee and caller file paths
- ``null`` to defer to the next resolver in the chain

The ``Resolution`` class is constructed with two nullable string arguments: the callee file path and the caller file path:

.. code-block:: php

    new Resolution(
        $trace[1]['file'] ?? null, // callee (where the issue originated)
        $trace[2]['file'] ?? null, // caller (what called the code that triggered the issue)
    );

PHPUnit uses these file paths to classify the issue as ``self``, ``direct``, ``indirect``, or ``test``
by checking whether each file belongs to first-party code, third-party code, test code, or PHPUnit itself.

Implementing a custom resolver
------------------------------

Consider a framework that wraps ``trigger_error()`` in its own method:

.. code-block:: php

    namespace Vendor;

    final class Framework
    {
        public function trigger(): void
        {
            @trigger_error('framework deprecation', E_USER_DEPRECATED);
        }
    }

Without a custom resolver, PHPUnit's default resolver uses ``$trace[0]`` as the callee (the file containing
``trigger_error()``) and ``$trace[1]`` as the caller. When the framework method is in the call stack, this
means the framework file is the callee and the first-party code calling it is the caller, which may not
accurately reflect the intended classification.

A custom resolver can inspect the stack trace and adjust the caller/callee accordingly:

.. code-block:: php

    <?php declare(strict_types=1);
    namespace Vendor;

    use PHPUnit\Runner\IssueTriggerResolver\Resolution;
    use PHPUnit\Runner\IssueTriggerResolver\Resolver;

    final class FrameworkResolver implements Resolver
    {
        public function resolve(array $trace, string $message): ?Resolution
        {
            if (isset($trace[0]['file']) && str_contains($trace[0]['file'], 'Framework.php')) {
                return new Resolution(
                    $trace[1]['file'] ?? null,
                    $trace[2]['file'] ?? null,
                );
            }

            return null;
        }
    }

This resolver checks whether the first frame in the stack trace is from the framework. If so, it shifts
the caller/callee by one frame to skip the framework's wrapper. If the condition does not match, it returns
``null`` to let the next resolver (or the default resolver) handle the issue.

Registering a custom resolver
------------------------------

Custom resolvers are registered in PHPUnit's XML configuration file using the
``<issueTriggerResolvers>`` element inside ``<source>`` (see :ref:`appendixes.configuration.source.issueTriggerResolvers`):

.. code-block:: xml

    <source>
        <include>
            <directory>src</directory>
        </include>

        <issueTriggerResolvers>
            <issueTriggerResolver className="Vendor\FrameworkResolver"/>
        </issueTriggerResolvers>
    </source>

Multiple resolvers can be registered and are called in the order they are listed. The first resolver that
returns a ``Resolution`` object wins. If all custom resolvers return ``null``, PHPUnit falls back to its
default resolver.


Disabling PHPUnit's error handler
=================================

When you want to test your own `error handler <https://www.php.net/manual/en/function.set-error-handler.php>`_
or want to test that unit of code under test triggers an expected issue, for instance, the error handler
registered by PHPUnit's test runner will interfere with what you want to achieve.

The ``#[WithoutErrorHandler]`` attribute can be used in such a case to disable PHPUnit's error handler for
a test method.
