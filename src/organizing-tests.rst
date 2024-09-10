

.. _organizing-tests:

****************
Organizing Tests
****************

One of the goals of PHPUnit is that tests
should be composable: we want to be able to run any number or combination
of tests together, for instance all tests for the whole project, or the
tests for all classes of a component that is part of the project, or just
the tests for a single class.

PHPUnit supports different ways of organizing tests and composing them into
a test suite. This chapter shows the most commonly used approaches.

.. _organizing-tests.filesystem:

Composing a Test Suite Using the Filesystem
===========================================

Probably the easiest way to compose a test suite is to keep all test case
source files in a test directory. PHPUnit can automatically discover and
run the tests by recursively traversing the test directory.

Lets take a look at the test suite of the
`sebastianbergmann/raytracer <https://github.com/sebastianbergmann/raytracer>`_
project.

Looking at this project's directory structure, we see that the
test case classes in the :file:`tests/unit` directory mirror the
package and class structure of the System Under Test (SUT) in the
:file:`src` directory:

.. code-block:: none

    src                                          tests/unit
    ├── autoload.php                             ├── CameraTest.php
    ├── Camera.php                               ├── canvas
    ├── canvas                                   │   ├── AnsiMapperTest.php
    │   ├── AnsiMapper.php                       │   ├── CanvasTest.php
    │   ├── CanvasIterator.php                   │   └── PortablePixmapMapperTest.php
    │   ├── Canvas.php                           ├── ColorTest.php
    │   ├── PortablePixmapMapper.php             ├── intersection
    │   └── WebpMapper.php                       │   ├── IntersectionCollectionTest.php
    ├── Color.php                                │   └── IntersectionTest.php
    ├── exceptions                               ├── material
    │   ├── Exception.php                        │   ├── CheckersPatternTest.php
    │   ├── IntersectionHasNoHitException.php    │   ├── GradientPatternTest.php
    │   ├── InvalidArgumentException.php         │   ├── MaterialTest.php
    │   ├── OutOfBoundsException.php             │   ├── PatternTest.php
    │   ├── RuntimeException.php                 │   ├── RingPatternTest.php
    │   └── WorldHasNoLightException.php         │   └── StripePatternTest.php
    ├── intersection                             ├── math
    │   ├── IntersectionCollectionIterator.php   │   ├── MatrixTest.php
    │   ├── IntersectionCollection.php           │   ├── RayTest.php
    │   ├── Intersection.php                     │   ├── TransformationsTest.php
    │   └── PreparedComputation.php              │   └── TupleTest.php
    ├── material                                 ├── PointLightTest.php
    │   ├── CheckersPattern.php                  ├── shapes
    │   ├── GradientPattern.php                  │   ├── PlaneTest.php
    │   ├── Material.php                         │   ├── ShapeCollectionTest.php
    │   ├── Pattern.php                          │   ├── ShapeTest.php
    │   ├── RingPattern.php                      │   └── SphereTest.php
    │   └── StripePattern.php                    └── WorldTest.php
    ├── math
    │   ├── Matrix.php                           tests/integration
    │   ├── Ray.php                              └── PuttingItTogetherTest.php
    │   ├── Transformations.php
    │   └── Tuple.php
    ├── PointLight.php
    ├── shapes
    │   ├── Plane.php
    │   ├── ShapeCollectionIterator.php
    │   ├── ShapeCollection.php
    │   ├── Shape.php
    │   └── Sphere.php
    └── World.php

The :file:`tests/integration` directory contains integration test cases that are
kept separate from the :file:`tests/unit` directory's unit tests.

To run all tests for this project we need to point the PHPUnit
command-line test runner to the test directory:

.. parsed-literal::

    $ ./tools/phpunit --bootstrap tests/bootstrap.php tests
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.2

    ...............................................................  63 / 177 ( 35%)
    ............................................................... 126 / 177 ( 71%)
    ...................................................             177 / 177 (100%)

    Time: 00:17.100, Memory: 28.27 MB

    OK (177 tests, 657 assertions)

.. admonition:: Note

   If you point the PHPUnit command-line test runner to a directory it will
   look for :file:`*Test.php` files.

To run only the tests that are declared in the ``WorldTest``
test case class in :file:`tests/unit/WorldTest.php` we can use
the following command:

.. parsed-literal::

    $ ./tools/phpunit --bootstrap src/autoload.php tests/unit/WorldTest.php
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.2

    .............                                                     13 / 13 (100%)

    Time: 00:00.095, Memory: 8.00 MB

    OK (13 tests, 30 assertions)

For more fine-grained control of which tests to run we can use the
``--filter`` option:

.. parsed-literal::

    $ ./tools/phpunit --bootstrap src/autoload.php tests/unit --filter test_creating_a_world
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.2

    .                                                                   1 / 1 (100%)

    Time: 00:00.077, Memory: 10.00 MB

    OK (1 test, 2 assertions)

.. _organizing-tests.xml-configuration:

Composing a Test Suite Using XML Configuration
==============================================

PHPUnit's XML configuration file (:ref:`appendixes.configuration`)
can also be used to compose a test suite.
:numref:`organizing-tests.xml-configuration.examples.phpunit.xml`
shows a minimal :file:`phpunit.xml` file that will add all
``*Test`` classes that are found in
:file:`*Test.php` files when the :file:`tests`
directory is recursively traversed.

.. code-block:: xml
    :caption: Composing a Test Suite Using XML Configuration
    :name: organizing-tests.xml-configuration.examples.phpunit.xml

    <?xml version="1.0" encoding="UTF-8"?>
    <phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/11.4/phpunit.xsd"
             bootstrap="tests/bootstrap.php">
        <testsuites>
            <testsuite name="unit">
                <directory>tests/unit</directory>
            </testsuite>

            <testsuite name="integration">
                <directory>tests/integration</directory>
            </testsuite>
        </testsuites>
    </phpunit>

.. admonition:: Note

   You should reference the schema definition that is appropriate for the PHPUnit version
   you are using in your XML configuration file. The schema definition for PHPUnit |version|
   can always be found at https://schema.phpunit.de/|version|/phpunit.xsd, for instance.

Now that we have an XML configuration file, we can invoke the PHPUnit test runner without
arguments (``tests``, for instance) or options (``--bootstrap``, for instance) to run
our tests:

.. parsed-literal::

    $ ./tools/phpunit
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.2
    Configuration: /path/to/raytracer/phpunit.xml

    ...............................................................  63 / 177 ( 35%)
    ............................................................... 126 / 177 ( 71%)
    ...................................................             177 / 177 (100%)

    Time: 00:17.100, Memory: 28.27 MB

    OK (177 tests, 657 assertions)

The PHPUnit test runner's ``--list-suites`` option can be used to print a list of all test suites
defined in PHPUnit's XML configuration file:

.. parsed-literal::

    $ ./tools/phpunit --list-suites
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Available test suite(s):
     - unit
     - integration

We can use the PHPUnit test runner's ``--testsuite`` option to limit the tests that are run
to the tests of a specific test suite that is declared in the XML configuration file:

.. parsed-literal::

    $ ./tools/phpunit --testsuite unit
    PHPUnit |version|.0 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.2.2
    Configuration: /path/to/raytracer/phpunit.xml

    ...............................................................  63 / 172 ( 36%)
    ............................................................... 126 / 172 ( 73%)
    ..............................................                  172 / 172 (100%)

    Time: 00:00.213, Memory: 24.27 MB

    OK (172 tests, 637 assertions)
