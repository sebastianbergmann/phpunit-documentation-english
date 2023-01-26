

.. _wrapping-the-test-runner:

************************
Wrapping the Test Runner
************************

The ``PHPUnit\TextUI\Application`` class is the entry point for PHPUnit's own CLI test runner.
It is not meant to be (re)used by developers who want to wrap PHPUnit to build something such
as ParaTest.

For the actual running of tests, ``PHPUnit\TextUI\Application`` uses ``PHPUnit\TextUI\TestRunner::run()``.

``PHPUnit\TextUI\TestRunner::run()`` requires a ``PHPUnit\TextUI\Configuration\Configuration``,
a ``PHPUnit\Runner\ResultCache\ResultCache``, and a ``PHPUnit\Framework\TestSuite``.

A ``PHPUnit\TextUI\Configuration\Configuration`` can be built using ``PHPUnit\TextUI\Configuration\Builder::build()``.
You need to pass ``$_SERVER['argv']`` to this method. The method then parses CLI arguments/options and loads an XML
configuration file, if one can be loaded.

A ``PHPUnit\Framework\TestSuite`` can be built from a ``PHPUnit\TextUI\Configuration\Configuration`` using
``PHPUnit\TextUI\Configuration\TestSuiteBuilder::build()``.

While it is marked ``@internal``, ``PHPUnit\TextUI\TestRunner`` is meant to be (re)used by developers who
want to wrap PHPUnit's test runner.
