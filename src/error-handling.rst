

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
    │   └── SourceClass.php
    ├── tests
    │   └── SourceClassTest.php
    └── vendor
        ├── autoload.php
        └── VendorClass.php

    4 directories, 5 files

.. literalinclude:: examples/error-handling/SourceClassTest.php
   :caption: tests/SourceClassTest.php
   :language: php

.. literalinclude:: examples/error-handling/SourceClass.php
   :caption: src/SourceClass.php
   :language: php

.. literalinclude:: examples/error-handling/VendorClass.php
   :caption: vendor/VendorClass.php
   :language: php

.. literalinclude:: examples/error-handling/default.xml
   :caption: phpunit.xml
   :language: xml


PHPUnit's test runner prints ``D``, ``N``, and ``W``, respectively, for tests that execute code which triggers an issue
(``D`` for deprecations, ``N`` for notices, and ``W`` for warnings).

Shown below is the default output PHPUnit's test runner prints for the example shown above:

.. parsed-literal::

    $ ./tools/phpunit
    PHPUnit 10.1.3 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.10
    Configuration: /path/to/example/phpunit.xml

    D                                                                   1 / 1 (100%)

    Time: 00:00.007, Memory: 4.00 MB

    OK, but there were issues!
    Tests: 1, Assertions: 1, Deprecations: 1.

Detailed information, for instance which issue was triggered where, is only printed when ``--display-deprecations``,
``--display-notices``, or ``--display-warnings`` is used:

.. parsed-literal::

    $ ./tools/phpunit --display-deprecations
    PHPUnit 10.1.3 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.10
    Configuration: /path/to/example/phpunit.xml

    D                                                                   1 / 1 (100%)

    Time: 00:00.006, Memory: 4.00 MB

    1 test triggered 4 deprecations:

    1) example\SourceClassTest::testSomething
    * deprecation
      /path/to/example/src/SourceClass.php:10

    * deprecation
      /path/to/example/vendor/VendorClass.php:8

    * deprecation
      /path/to/example/src/SourceClass.php:10

    * deprecation
      /path/to/example/vendor/VendorClass.php:8

    /path/to/example/tests/SourceClassTest.php:8

    OK, but some tests have issues!
    Tests: 1, Assertions: 1, Deprecations: 1.


Limiting issues to "your code"
==============================

The reporting of issues can be limited to "your code", excluding third-party code from directories such as ``vendor``,
for example. You can configure what you consider "your code" in PHPUnit's XML configuration file
(see :ref:`appendixes.configuration.source`):

.. literalinclude:: examples/error-handling/your-code.xml
   :caption: phpunit.xml
   :language: xml

Here is what the output of PHPUnit's test runner will look like after we configured (see above) it to restrict the
reporting of issues to our own code:

.. parsed-literal::

    $ ./tools/phpunit --display-deprecations
    PHPUnit 10.1.3 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.10
    Configuration: /path/to/example/phpunit.xml

    D                                                                   1 / 1 (100%)

    Time: 00:00.007, Memory: 4.00 MB

    1 test triggered 2 deprecations:

    1) example\SourceClassTest::testSomething
    * deprecation
      /path/to/example/src/SourceClass.php:10

    * deprecation
      /path/to/example/src/SourceClass.php:10

    /path/to/example/tests/SourceClassTest.php:8

    OK, but there were issues!
    Tests: 1, Assertions: 1, Deprecations: 1.

As you can see in the output shown above, deprecations triggered in third-party code located in the
``vendor`` directory are not reported anymore.
