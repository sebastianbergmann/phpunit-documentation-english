

.. _other-uses-for-tests:

====================
Other Uses for Tests
====================

Once you get used to writing automated tests, you will likely discover
more uses for tests. Here are some examples.

.. _other-uses-for-tests.agile-documentation:

Agile Documentation
###################

Typically, in a project that is developed using an agile process,
such as Extreme Programming, the documentation cannot keep up with the
frequent changes to the project's design and code. Extreme Programming
demands *collective code ownership*, so all
developers need to know how the entire system works. If you are
disciplined enough to consequently use "speaking names" for your tests
that describe what a class should do, you can use PHPUnit's TestDox
functionality to generate automated documentation for your project based
on its tests. This documentation gives developers an overview of what
each class of the project is supposed to do.

PHPUnit's TestDox functionality looks at a test class and all the test
method names and converts them from camel case PHP names to sentences:
``testBalanceIsInitiallyZero()`` becomes "Balance is
initially zero". If there are several test methods whose names only
differ in a suffix of one or more digits, such as
``testBalanceCannotBecomeNegative()`` and
``testBalanceCannotBecomeNegative2()``, the sentence
"Balance cannot become negative" will appear only once, assuming that
all of these tests succeed.

Let us take a look at the agile documentation generated for a
``BankAccount`` class:

.. code-block:: bash

    $ phpunit --testdox BankAccountTest
    PHPUnit 7.0.0 by Sebastian Bergmann and contributors.

    BankAccount
     [x] Balance is initially zero
     [x] Balance cannot become negative

Alternatively, the agile documentation can be generated in HTML or plain
text format and written to a file using the ``--testdox-html``
and ``--testdox-text`` arguments.

Agile Documentation can be used to document the assumptions you make
about the external packages that you use in your project. When you use
an external package, you are exposed to the risks that the package will
not behave as you expect, and that future versions of the package will
change in subtle ways that will break your code, without you knowing it.
You can address these risks by writing a test every time you make an
assumption. If your test succeeds, your assumption is valid. If you
document all your assumptions with tests, future releases of the
external package will be no cause for concern: if the tests succeed,
your system should continue working.

.. _other-uses-for-tests.cross-team-tests:

Cross-Team Tests
################

When you document assumptions with tests, you own the tests. The
supplier of the package -- who you make assumptions about -- knows
nothing about your tests. If you want to have a closer relationship
with the supplier of a package, you can use the tests to communicate
and coordinate your activities.

When you agree on coordinating your activities with the supplier of a
package, you can write the tests together. Do this in such a way that
the tests reveal as many assumptions as possible. Hidden assumptions are
the death of cooperation. With the tests, you document exactly what you
expect from the supplied package. The supplier will know the package is
complete when all the tests run.

By using stubs (see the chapter on "Mock Objects", earlier in this book),
you can further decouple yourself from the supplier: The job of the
supplier is to make the tests run with the real implementation of the
package. Your job is to make the tests run for your own code. Until
such time as you have the real implementation of the supplied package,
you use stub objects. Following this approach, the two teams can develop
independently.


