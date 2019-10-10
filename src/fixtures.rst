

.. _fixtures:

========
Fixtures
========

One of the most time-consuming parts of writing tests is writing the
code to set the world up in a known state and then return it to its
original state when the test is complete. This known state is called
the *fixture* of the test.

In :ref:`writing-tests-for-phpunit.examples.StackTest.php`, the
fixture was simply the array that is stored in the ``$stack``
variable. Most of the time, though, the fixture will be more complex
than a simple array, and the amount of code needed to set it up will
grow accordingly. The actual content of the test gets lost in the noise
of setting up the fixture. This problem gets even worse when you write
several tests with similar fixtures. Without some help from the testing
framework, we would have to duplicate the code that sets up the fixture
for each test we write.

PHPUnit supports sharing the setup code. Before a test method is run, a
template method called ``setUp()`` is invoked.
``setUp()`` is where you create the objects against which
you will test. Once the test method has finished running, whether it
succeeded or failed, another template method called
``tearDown()`` is invoked. ``tearDown()``
is where you clean up the objects against which you tested.

In :ref:`writing-tests-for-phpunit.examples.StackTest2.php` we
used the producer-consumer relationship between tests to share a fixture. This
is not always desired or even possible. :numref:`fixtures.examples.StackTest.php`
shows how we can write the tests of the ``StackTest`` in such
a way that not the fixture itself is reused but the code that creates it.
First we declare the instance variable, ``$stack``, that we
are going to use instead of a method-local variable. Then we put the
creation of the ``array`` fixture into the
``setUp()`` method. Finally, we remove the redundant code
from the test methods and use the newly introduced instance variable,
``$this->stack``, instead of the method-local variable
``$stack`` with the ``assertSame()``
assertion method.

.. code-block:: php
    :caption: Using setUp() to create the stack fixture
    :name: fixtures.examples.StackTest.php

    <?php
    use PHPUnit\Framework\TestCase;

    class StackTest extends TestCase
    {
        protected $stack;

        protected function setUp(): void
        {
            $this->stack = [];
        }

        public function testEmpty()
        {
            self::assertTrue(empty($this->stack));
        }

        public function testPush()
        {
            array_push($this->stack, 'foo');
            self::assertSame('foo', $this->stack[count($this->stack)-1]);
            self::assertFalse(empty($this->stack));
        }

        public function testPop()
        {
            array_push($this->stack, 'foo');
            self::assertSame('foo', array_pop($this->stack));
            self::assertTrue(empty($this->stack));
        }
    }

The ``setUp()`` and ``tearDown()`` template
methods are run once for each test method (and on fresh instances) of the
test case class.

In addition, the ``setUpBeforeClass()`` and
``tearDownAfterClass()`` template methods are called before
the first test of the test case class is run and after the last test of the
test case class is run, respectively.

The example below shows all template methods that are available in a test
case class.

.. code-block:: php
    :caption: Example showing all template methods available
    :name: fixtures.examples.TemplateMethodsTest.php

    <?php
    use PHPUnit\Framework\TestCase;

    class TemplateMethodsTest extends TestCase
    {
        public static function setUpBeforeClass(): void
        {
            fwrite(STDOUT, __METHOD__ . "\n");
        }

        protected function setUp(): void
        {
            fwrite(STDOUT, __METHOD__ . "\n");
        }

        protected function assertPreConditions(): void
        {
            fwrite(STDOUT, __METHOD__ . "\n");
        }

        public function testOne()
        {
            fwrite(STDOUT, __METHOD__ . "\n");
            self::assertTrue(true);
        }

        public function testTwo()
        {
            fwrite(STDOUT, __METHOD__ . "\n");
            self::assertTrue(false);
        }

        protected function assertPostConditions(): void
        {
            fwrite(STDOUT, __METHOD__ . "\n");
        }

        protected function tearDown(): void
        {
            fwrite(STDOUT, __METHOD__ . "\n");
        }

        public static function tearDownAfterClass(): void
        {
            fwrite(STDOUT, __METHOD__ . "\n");
        }

        protected function onNotSuccessfulTest(Throwable $t): void
        {
            fwrite(STDOUT, __METHOD__ . "\n");
            throw $t;
        }
    }

.. parsed-literal::

    $ phpunit TemplateMethodsTest
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    TemplateMethodsTest::setUpBeforeClass
    TemplateMethodsTest::setUp
    TemplateMethodsTest::assertPreConditions
    TemplateMethodsTest::testOne
    TemplateMethodsTest::assertPostConditions
    TemplateMethodsTest::tearDown
    .TemplateMethodsTest::setUp
    TemplateMethodsTest::assertPreConditions
    TemplateMethodsTest::testTwo
    TemplateMethodsTest::tearDown
    TemplateMethodsTest::onNotSuccessfulTest
    FTemplateMethodsTest::tearDownAfterClass

    Time: 0 seconds, Memory: 5.25Mb

    There was 1 failure:

    1) TemplateMethodsTest::testTwo
    Failed asserting that <boolean:false> is true.
    /home/sb/TemplateMethodsTest.php:30

    FAILURES!
    Tests: 2, Assertions: 2, Failures: 1.

.. _fixtures.more-setup-than-teardown:

More setUp() than tearDown()
############################

``setUp()`` and ``tearDown()`` are nicely
symmetrical in theory but not in practice. In practice, you only need
to implement ``tearDown()`` if you have allocated
external resources like files or sockets in ``setUp()``.
If your ``setUp()`` just creates plain PHP objects, you
can generally ignore ``tearDown()``. However, if you
create many objects in your ``setUp()``, you might want
to ``unset()`` the variables pointing to those objects
in your ``tearDown()`` so they can be garbage collected.
The garbage collection of test case objects is not predictable.

.. _fixtures.variations:

Variations
##########

What happens when you have two tests with slightly different setups?
There are two possibilities:

-

  If the ``setUp()`` code differs only slightly, move
  the code that differs from the ``setUp()`` code to
  the test method.

-

  If you really have a different ``setUp()``, you need
  a different test case class. Name the class after the difference in
  the setup.

.. _fixtures.sharing-fixture:

Sharing Fixture
###############

There are few good reasons to share fixtures between tests, but in most
cases the need to share a fixture between tests stems from an unresolved
design problem.

A good example of a fixture that makes sense to share across several
tests is a database connection: you log into the database once and reuse
the database connection instead of creating a new connection for each
test. This makes your tests run faster.

:numref:`fixtures.sharing-fixture.examples.DatabaseTest.php`
uses the ``setUpBeforeClass()`` and
``tearDownAfterClass()`` template methods to connect to the
database before the test case class' first test and to disconnect from the
database after the last test of the test case, respectively.

.. code-block:: php
    :caption: Sharing fixture between the tests of a test suite
    :name: fixtures.sharing-fixture.examples.DatabaseTest.php

    <?php
    use PHPUnit\Framework\TestCase;

    class DatabaseTest extends TestCase
    {
        protected static $dbh;

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
############

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

Prior to version 6, by default, PHPUnit ran your tests in a way where
changes to global and super-global variables (``$GLOBALS``,
``$_ENV``, ``$_POST``,
``$_GET``, ``$_COOKIE``,
``$_SERVER``, ``$_FILES``,
``$_REQUEST``) do not affect other tests.

As of version 6, PHPUnit does not perform this backup and restore
operation for global and super-global variables by default anymore.
It can be activated by using the ``--globals-backup``
option or setting ``backupGlobals="true"`` in the
XML configuration file.

By using the ``--static-backup`` option or setting
``backupStaticAttributes="true"`` in the
XML configuration file, this isolation can be extended to static
attributes of classes.

.. admonition:: Note

   The backup and restore operations for global variables and static
   class attributes use ``serialize()`` and
   ``unserialize()``.

   Objects of some classes (e.g., ``PDO``) cannot be
   serialized and the backup operation will break when such an object is
   stored e.g. in the ``$GLOBALS`` array.

The ``@backupGlobals`` annotation that is discussed in
:ref:`appendixes.annotations.backupGlobals` can be used to
control the backup and restore operations for global variables.
Alternatively, you can provide a blacklist of global variables that are to
be excluded from the backup and restore operations like this

.. code-block:: php

    class MyTest extends TestCase
    {
        protected $backupGlobalsBlacklist = ['globalVariable'];

        // ...
    }

.. admonition:: Note

   Setting the ``$backupGlobalsBlacklist`` property inside
   e.g. the ``setUp()`` method has no effect.

The ``@backupStaticAttributes`` annotation discussed in
:ref:`appendixes.annotations.backupStaticAttributes`
can be used to back up all static property values in all declared classes
before each test and restore them afterwards.

It processes all classes that are declared at the time a test starts, not
only the test class itself. It only applies to static class properties,
not static variables within functions.

.. admonition:: Note

   The ``@backupStaticAttributes`` operation is executed
   before a test method, but only if it is enabled. If a static value was
   changed by a previously executed test that did not have
   ``@backupStaticAttributes`` enabled, then that value will
   be backed up and restored — not the originally declared default value.
   PHP does not record the originally declared default value of any static
   variable.

   The same applies to static properties of classes that were newly
   loaded/declared within a test. They cannot be reset to their originally
   declared default value after the test, since that value is unknown.
   Whichever value is set will leak into subsequent tests.

   For unit tests, it is recommended to explicitly reset the values of
   static properties under test in your ``setUp()`` code
   instead (and ideally also ``tearDown()``, so as to not
   affect subsequently executed tests).

You can provide a blacklist of static attributes that are to be excluded
from the backup and restore operations:

.. code-block:: php

    class MyTest extends TestCase
    {
        protected $backupStaticAttributesBlacklist = [
            'className' => ['attributeName']
        ];

        // ...
    }

.. admonition:: Note

   Setting the ``$backupStaticAttributesBlacklist`` property
   inside e.g. the ``setUp()`` method has no effect.


