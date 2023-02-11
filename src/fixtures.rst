

.. _fixtures:

********
Fixtures
********

A test usually follows the "Arrange, Act, Assert" structure: **arranging** all necessary
preconditions and inputs (the so-called *test fixture*), **acting** on the object under
test, and **asserting** that the expected results have occurred.

.. admonition:: Arrange, Expect, Act

   When you expect an action to raise an exception or when you verify the communication
   between collaborating objects using mock objects then the test usually follows the
   "Arrange, Expect, Act" structure.

Sometimes the *test fixture* is made up of a single object, sometimes it is a more complex
object graph, for instance. The amount of code needed to set it up will grow accordingly.
The actual content of the test gets lost in the noise of setting up the test fixture. This
problem gets even worse when you write several tests with similar test fixtures.

PHPUnit supports the reuse of setup code between tests. Before a test method is run, a
template method called ``setUp()`` is invoked: this is where you can create your test
fixture. Once the test method has finished running, whether it succeeded or failed,
another template method called ``tearDown()`` is invoked: this is where you can clean
up the objects against which you tested.

.. code-block:: php
    :caption: Example of a test class that uses setUp() and tearDown()
    :name: fixtures.examples.ExampleTest.php

    <?php declare(strict_types=1);
    namespace example;

    use PHPUnit\Framework\TestCase;

    final class ExampleTest extends TestCase
    {
        private ?Example $example;

        public function testSomething(): void
        {
            $this->assertSame(
                'the-result',
                $this->example->doSomething()
            );
        }

        protected function setUp(): void
        {
            $this->example = new Example(
                $this->createStub(Collaborator::class)
            );
        }

        protected function tearDown(): void
        {
            $this->example = null;
        }
    }

The ``setUp()`` and ``tearDown()`` template methods are run once for each test method
(and on fresh instances) of the test case class.

One problem with the ``setUp()`` and ``tearDown()`` template methods is that they are called
even for tests that do not use the test fixture managed by these methods, in the example shown
above the ``$this->example`` property.

Another problem can occur when inheritance comes into play:

.. code-block:: php
    :caption: Example of an abstract test case class with a setUp() method
    :name: fixtures.examples.MyTestCase.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    abstract class MyTestCase extends TestCase
    {
        protected function setUp(): void
        {
            // ...
        }
    }

.. code-block:: php
    :caption: Example of a concrete test case class that extends an abstract test case class with a setUp() method
    :name: fixtures.examples.ExampleTest2.php

    <?php declare(strict_types=1);
    namespace example;

    use PHPUnit\Framework\TestCase;

    final class ExampleTest extends MyTestCase
    {
        protected function setUp(): void
        {
            // ...
        }
    }

If we forget to call ``parent::setUp()`` when implementing ``ExampleTest::setUp()``, the functionality provided
by ``MyTestCase`` will not work. To reduce this risk, the attributes ``PHPUnit\Framework\Attributes\Before`` and
``PHPUnit\Framework\Attributes\After`` are available. With these, multiple methods can be configured to be called
before and after a test, respectively.

.. _fixtures.more-setup-than-teardown:

More setUp() than tearDown()
============================

``setUp()`` and ``tearDown()`` are nicely symmetrical in theory, but not in practice.
In practice, you only need to implement ``tearDown()`` if you have allocated external
resources such as files or sockets in ``setUp()``. Unless you create large object graphs
in your ``setUp()`` and store them in properties of the test object, you can generally
ignore ``tearDown()``.

However, if you create large object graphs in your ``setUp()`` and store them in properties
of the test object, you may want to ``unset()`` the variables holding those objects in your
``tearDown()`` so that they can be garbage collected sooner.

Objects created within ``setUp()`` (or test methods) that are stored in properties of the
test object are only automatically garbage collected at the end of the PHP process that
runs PHPUnit.

.. _fixtures.sharing-fixture:

Sharing Fixture
===============

There are few good reasons to share fixtures between tests, but in most
cases the need to share a fixture between tests stems from an unresolved
design problem.

A good example of a fixture that makes sense to share across several
tests is a database connection: you log into the database once and reuse
the database connection instead of creating a new connection for each
test. This makes your tests run faster.

The ``setUpBeforeClass()`` and ``tearDownAfterClass()`` template methods are called before
the first test of the test case class is run and after the last test of the test case class
is run, respectively.

:numref:`fixtures.sharing-fixture.examples.DatabaseTest.php`
uses the ``setUpBeforeClass()`` and
``tearDownAfterClass()`` template methods to connect to the
database before the test case class' first test and to disconnect from the
database after the last test of the test case, respectively.

.. code-block:: php
    :caption: Sharing fixture between the tests of a test suite
    :name: fixtures.sharing-fixture.examples.DatabaseTest.php

    <?php declare(strict_types=1);
    use PHPUnit\Framework\TestCase;

    final class DatabaseTest extends TestCase
    {
        private static $dbh;

        public static function setUpBeforeClass(): void
        {
            self::$dbh = new PDO('sqlite::memory:');
        }

        public static function tearDownAfterClass(): void
        {
            self::$dbh = null;
        }
    }

It cannot be emphasized enough that sharing fixtures between tests
reduces the value of the tests. The underlying design problem is
that objects are not loosely coupled. You will achieve better
results solving the underlying design problem and then writing tests
using stubs (see :ref:`test-doubles`), than by creating
dependencies between tests at runtime and ignoring the opportunity
to improve your design.

.. _fixtures.global-state:

Global State
============

`It is hard to test code that uses singletons. <http://googletesting.blogspot.com/2008/05/tott-using-dependancy-injection-to.html>`_
The same is true for code that uses global variables. Typically, the code
you want to test is coupled strongly with a global variable and you cannot
control its creation. An additional problem is the fact that one test's
change to a global variable might break another test.

In PHP, global variables work like this:

-

  A global variable ``$foo = 'bar';`` is stored as ``$GLOBALS['foo'] = 'bar';``.

-

  The ``$GLOBALS`` variable is a so-called *super-global* variable.

-

  Super-global variables are built-in variables that are always available in all scopes.

-

  In the scope of a function or method, you may access the global variable ``$foo`` by either directly accessing ``$GLOBALS['foo']`` or by using ``global $foo;`` to create a local variable with a reference to the global variable.

Besides global variables, static attributes of classes are also part of
the global state.

PHPUnit can optionally run your tests in a way where changes to global and super-global variables
(``$GLOBALS``, ``$_ENV``, ``$_POST``, ``$_GET``, ``$_COOKIE``, ``$_SERVER``, ``$_FILES``,
``$_REQUEST``) do not affect other tests. You can activate this behaviour by using the
``--globals-backup`` option or by setting ``backupGlobals="true"`` in the XML configuration file.

By using the ``--static-backup`` option or setting ``backupStaticAttributes="true"`` in the
XML configuration file, this isolation can be extended to static properties of classes.

.. admonition:: Note

   The backup and restore operations for global variables and static class attributes use
   ``serialize()`` and ``unserialize()``.

   Objects of some classes (e.g., ``PDO``) cannot be serialized and the backup operation
   will break when such an object is stored e.g. in the ``$GLOBALS`` array.

The ``PHPUnit\Framework\Attributes\BackupGlobals`` attribute can be used to control the
backup and restore operations for global variables.

The ``PHPUnit\Framework\Attributes\ExcludeGlobalVariableFromBackup`` attribute can be used
to exclude specific global variables from the backup and restore operations for global variables.

The ``PHPUnit\Framework\Attributes\BackupStaticProperties`` attribute can be used to control
the backup and restore operations for static properties of classes. This affects all static
properties in all declared classes before each test and restore them afterwards. All classes
that are declared at the time a test starts are processed, not only the test class itself. It
only applies to static class properties, not static variables within functions.

The ``PHPUnit\Framework\Attributes\ExcludeStaticPropertyFromBackup`` attribute can be used
to exclude specific static properties from the backup and restore operations for static properties.

.. admonition:: Note

   The the backup operation for static properties of classes is performed before a test method,
   but only if it is enabled. If a static value was changed by a previously executed test that
   did not have ``BackupStaticProperties(true)``, then that value will be backed up and restored —
   not the originally declared default value.

   The same applies to static properties of classes that were newly loaded/declared within a test.
   They cannot be reset to their originally declared default value after the test, since that value
   is unknown. Whichever value is set will leak into subsequent tests.

For unit tests, it is recommended to explicitly reset the values of static properties under test
in your ``setUp()`` code instead (and ideally also ``tearDown()``, so as to not affect subsequently
executed tests).

