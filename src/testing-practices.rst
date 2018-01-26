

.. _testing-practices:

=================
Testing Practices
=================

    *Erich Gamma*:

    You can always write more tests. However, you will quickly find that
    only a fraction of the tests you can imagine are actually useful. What
    you want is to write tests that fail even though you think they should
    work, or tests that succeed even though you think they should fail.
    Another way to think of it is in cost/benefit terms. You want to write
    tests that will pay you back with information.

.. _testing-practices.during-development:

During Development
##################

When you need to make a change to the internal structure of the software
you are working on to make it easier to understand and cheaper to modify
without changing its observable behavior, a test suite is invaluable in
applying these so called `refactorings <http://martinfowler.com/bliki/DefinitionOfRefactoring.html>`_
safely. Otherwise, you might not notice the system breaking while you
are carrying out the restructuring.

The following conditions will help you to improve the code and design
of your project, while using unit tests to verify that the refactoring's
transformation steps are, indeed, behavior-preserving and do not
introduce errors:

#.

   All unit tests run correctly.

#.

   The code communicates its design principles.

#.

   The code contains no redundancies.

#.

   The code contains the minimal number of classes and methods.

When you need to add new functionality to the system, write the tests
first. Then, you will be done developing when the test runs. This
practice will be discussed in detail in the next chapter.

.. _testing-practices.during-debugging:

During Debugging
################

When you get a defect report, your impulse might be to fix the defect as
quickly as possible. Experience shows that this impulse will not serve
you well; it is likely that the fix for the defect causes another
defect.

You can hold your impulse in check by doing the following:

#.

   Verify that you can reproduce the defect.

#.

   Find the smallest-scale demonstration of the defect in the code.
   For example, if a number appears incorrectly in an output, find the
   object that is computing that number.

#.

   Write an automated test that fails now but will succeed when the
   defect is fixed.

#.

   Fix the defect.

Finding the smallest reliable reproduction of the defect gives you the
opportunity to really examine the cause of the defect. The test you
write will improve the chances that when you fix the defect, you really
fix it, because the new test reduces the likelihood of undoing the fix
with future code changes. All the tests you wrote before reduce the
likelihood of inadvertently causing a different problem.

    *Benjamin Smedberg*:

    Unit testing offers many advantages:

    -

      Testing gives code authors and reviewers confidence that patches produce the correct results.

    -

      Authoring testcases is a good impetus for developers to discover edge cases.

    -

      Testing provides a good way to catch regressions quickly, and to make sure that no regression will be repeated twice.

    -

      Unit tests provide working examples for how to use an API and can significantly aid documentation efforts.

    Overall, integrated unit testing makes the cost and risk of any
    individual change smaller. It will allow the project to make \[...]
    major architectural improvements \[...] quickly and confidently.


