

.. _incomplete-and-skipped-tests:

============================
Incomplete and Skipped Tests
============================

.. _incomplete-and-skipped-tests.incomplete-tests:

Incomplete Tests
################

When you are working on a new test case class, you might want to begin
by writing empty test methods such as:

.. code-block:: php

    public function testSomething(): void
    {
    }

to keep track of the tests that you have to write. The
problem with empty test methods is that they are interpreted as a
success by the PHPUnit framework. This misinterpretation leads to the
test reports being useless -- you cannot see whether a test is actually
successful or just not yet implemented. Calling
``$this->fail()`` in the unimplemented test method
does not help either, since then the test will be interpreted as a
failure. This would be just as wrong as interpreting an unimplemented
test as a success.

If we think of a successful test as a green light and a test failure
as a red light, we need an additional yellow light to mark a test
as being incomplete or not yet implemented.
``PHPUnit\Framework\IncompleteTest`` is a marker
interface for marking an exception that is raised by a test method as
the result of the test being incomplete or currently not implemented.
``PHPUnit\Framework\IncompleteTestError`` is the
standard implementation of this interface.

:numref:`incomplete-and-skipped-tests.incomplete-tests.examples.SampleTest.php`
shows a test case class, ``SampleTest``, that contains one test
method, ``testSomething()``. By calling the convenience
method ``markTestIncomplete()`` (which automatically
raises an ``PHPUnit\Framework\IncompleteTestError``
exception) in the test method, we mark the test as being incomplete.

.. code-block:: php
    :caption: Marking a test as incomplete
    :name: incomplete-and-skipped-tests.incomplete-tests.examples.SampleTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class SampleTest extends TestCase
    {
        public function testSomething(): void
        {
            // Optional: Test anything here, if you want.
            $this->assertTrue(true, 'This should already work.');

            // Stop here and mark this test as incomplete.
            $this->markTestIncomplete(
              'This test has not been implemented yet.'
            );
        }
    }

An incomplete test is denoted by an ``I`` in the output
of the PHPUnit command-line test runner, as shown in the following
example:

.. parsed-literal::

    $ phpunit --verbose SampleTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    I

    Time: 0 seconds, Memory: 3.95Mb

    There was 1 incomplete test:

    1) SampleTest::testSomething
    This test has not been implemented yet.

    /home/sb/SampleTest.php:12
    OK, but incomplete or skipped tests!
    Tests: 1, Assertions: 1, Incomplete: 1.

:numref:`incomplete-and-skipped-tests.incomplete-tests.tables.api`
shows the API for marking tests as incomplete.

.. rst-class:: table
.. list-table:: API for Incomplete Tests
    :name: incomplete-and-skipped-tests.incomplete-tests.tables.api
    :header-rows: 1

    * - Method
      - Meaning
    * - ``void markTestIncomplete()``
      - Marks the current test as incomplete.
    * - ``void markTestIncomplete(string $message)``
      - Marks the current test as incomplete using ``$message`` as an explanatory message.

.. _incomplete-and-skipped-tests.skipping-tests:

Skipping Tests
##############

Not all tests can be run in every environment. Consider, for instance,
a database abstraction layer that has several drivers for the different
database systems it supports. The tests for the MySQL driver can
only be run if a MySQL server is available.

:numref:`incomplete-and-skipped-tests.skipping-tests.examples.DatabaseTest.php`
shows a test case class, ``DatabaseTest``, that contains one test
method, ``testConnection()``. In the test case class'
``setUp()`` template method we check whether the MySQLi
extension is available and use the ``markTestSkipped()``
method to skip the test if it is not.

.. code-block:: php
    :caption: Skipping a test
    :name: incomplete-and-skipped-tests.skipping-tests.examples.DatabaseTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class DatabaseTest extends TestCase
    {
        protected function setUp(): void
        {
            if (!extension_loaded('mysqli')) {
                $this->markTestSkipped(
                  'The MySQLi extension is not available.'
                );
            }
        }

        public function testConnection(): void
        {
            // ...
        }
    }

A test that has been skipped is denoted by an ``S`` in
the output of the PHPUnit command-line test runner, as shown in the
following example:

.. parsed-literal::

    $ phpunit --verbose DatabaseTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    S

    Time: 0 seconds, Memory: 3.95Mb

    There was 1 skipped test:

    1) DatabaseTest::testConnection
    The MySQLi extension is not available.

    /home/sb/DatabaseTest.php:9
    OK, but incomplete or skipped tests!
    Tests: 1, Assertions: 0, Skipped: 1.

:numref:`incomplete-and-skipped-tests.skipped-tests.tables.api`
shows the API for skipping tests.

.. rst-class:: table
.. list-table:: API for Skipping Tests
    :name: incomplete-and-skipped-tests.skipped-tests.tables.api
    :header-rows: 1

    * - Method
      - Meaning
    * - ``void markTestSkipped()``
      - Marks the current test as skipped.
    * - ``void markTestSkipped(string $message)``
      - Marks the current test as skipped using ``$message`` as an explanatory message.

.. _incomplete-and-skipped-tests.skipping-tests-using-requires:

Skipping Tests using @requires
##############################

In addition to the above methods it is also possible to use the
``@requires`` annotation to express common preconditions for a test case.

A test can have multiple ``@requires`` annotations, in which case all requirements
need to be met for the test to run.

.. rst-class:: table
.. list-table:: Possible @requires usages
    :name: incomplete-and-skipped-tests.requires.tables.api
    :header-rows: 1

    * - Type
      - Possible Values
      - Examples
      - Another example
    * - ``PHP``
      - Any PHP version identifier along with an optional operator
      - @requires PHP 7.1.20
      - @requires PHP >= 7.2
    * - ``PHPUnit``
      - Any PHPUnit version identifier along with an optional operator
      - @requires PHPUnit 7.3.1
      - @requires PHPUnit < 8
    * - ``OS``
      - A regexp matching `PHP_OS <https://www.php.net/manual/en/reserved.constants.php#constant.php-os>`_
      - @requires OS Linux
      - @requires OS WIN32|WINNT
    * - ``OSFAMILY``
      - Any `OS family <https://www.php.net/manual/en/reserved.constants.php#constant.php-os-family>`_
      - @requires OSFAMILY Solaris
      - @requires OSFAMILY Windows
    * - ``function``
      - Any valid parameter to `function_exists <https://www.php.net/function_exists>`_
      - @requires function imap_open
      - @requires function ReflectionMethod::setAccessible
    * - ``extension``
      - Any extension name along with an optional version identifier and optional operator
      - @requires extension mysqli
      - @requires extension redis >= 2.2.0

The following operators are supported for PHP, PHPUnit, and extension version constraints: ``<``, ``<=``, ``>``, ``>=``, ``=``, ``==``, ``!=``, ``<>``.

Versions are compared using PHP's `version_compare <https://www.php.net/version_compare>`_ function. Among other things, this means that the ``=`` and ``==`` operator can only be used with complete ``X.Y.Z`` version numbers and that just ``X.Y`` will not work.

.. code-block:: php
    :caption: Skipping test cases using @requires
    :name: incomplete-and-skipped-tests.skipping-tests.examples.DatabaseClassSkippingTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    /**
     * @requires extension mysqli
     */
    final class DatabaseTest extends TestCase
    {
        /**
         * @requires PHP >= 5.3
         */
        public function testConnection(): void
        {
            // Test requires the mysqli extension and PHP >= 5.3
        }

        // ... All other tests require the mysqli extension
    }

If you are using syntax that doesn't compile with a certain PHP Version look into the xml
configuration for version dependent includes in :ref:`appendixes.configuration.testsuites`


