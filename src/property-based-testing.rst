

.. _property-based-testing:

**********************
Property-Based Testing
**********************

The tests shown in the other chapters of this manual are example-based: the author of a test picks an input, runs the code under test with it, and asserts the outcome that is expected for this one input.
A test like this is precise and easy to read, but it only verifies what its author thought of.
Bugs tend to hide in the inputs that nobody thought of: the empty string, zero, a value at the edge of a range, or an unusual combination of otherwise ordinary values.

Property-based testing turns this around.
Instead of stating what the code does for one input, you state a **property**: something that must hold for *every* input of a certain kind.
PHPUnit then generates many inputs, checks the property for each of them, and, when it finds an input for which the property does not hold, reduces this input to the simplest one that still shows the problem.

This chapter uses the following terms:

Property
    A test method with the ``#[Property]`` attribute. It states something that must hold for all arguments that it can be called with.

Example
    One set of arguments that a property is checked with. PHPUnit generates the examples of a property.

Generator
    Produces the values for one parameter of a property. PHPUnit derives generators from the types of the parameters, and you can specify your own.

Counterexample
    An example for which the property does not hold. A property for which a counterexample was found is **falsified**.

Shrinking
    Simplifying a counterexample, so that what PHPUnit reports is small enough to understand at a glance.


.. _property-based-testing.motivation:

A motivating example
====================

The ``Money`` class represents an amount of money in cents.
Its ``split()`` method splits the amount into a number of equal parts, for instance to split a bill among the guests at a table.

.. literalinclude:: examples/property-based-testing/src/Money.php
   :caption: A class that represents an amount of money
   :language: php

An example-based test for ``split()`` is quickly written:

.. literalinclude:: examples/property-based-testing/MoneyTest.php
   :caption: An example-based test for ``Money::split()``
   :language: php

.. literalinclude:: examples/property-based-testing/MoneyTest.php.out

The test passes, and it is correct: 100 cents split into two parts are two parts of 50 cents.
It also executes every line of ``split()``, so a code coverage report shows nothing that is left to test.
And yet ``split()`` has a bug that loses money.

The author of the test picked 100 and 2 because they are convenient numbers.
What should be true for *all* amounts and *all* numbers of parts, though?
Whatever the parts are, they must add up to the amount that was split.
This is a property, and it can be written down as a test:

.. literalinclude:: examples/property-based-testing/MoneySplitTest.php
   :caption: A property of ``Money::split()``
   :language: php

.. literalinclude:: examples/property-based-testing/MoneySplitTest.php.out

PHPUnit generated amounts and numbers of parts, found a combination for which the parts do not add up to the whole, and simplified it: one cent that is split into two parts.
Each part is zero cents, because ``intdiv(1, 2)`` is ``0``, and the cent is lost.
The same happens for every amount that is not a multiple of the number of parts, which the example-based test did not show because 100 is a multiple of 2.

The counterexample makes the fix obvious: the remainder of the division has to be distributed among the parts.
It also suggests the next property to write down: no part may differ from another part by more than one cent.

**When to use a property**

**Use a property when:**

- You can state something that holds for all inputs, even if you cannot state the exact result for each input
- The code under test accepts many different inputs, such as strings, numbers, collections, or sequences of operations
- An operation can be undone by another operation, or there is a second, simpler way to compute the same result
- You want to find the edge cases that you did not think of

**Use an example-based test when:**

- You want to document the exact result for a specific, meaningful input
- The expected result can only be stated by repeating the logic of the code under test
- A specific input is important enough that it must be checked in every test run, such as a bug that was fixed

Properties do not replace example-based tests, they complement them.
Examples document how the code is used and pin down specific results, properties cover the space of inputs between and around the examples.
Both can be combined in one test method, see :ref:`property-based-testing.reproducing-failures.explicit-examples`.


.. _property-based-testing.first-property:

A first property
================

This section walks through the property of ``Money::split()`` shown above and through the output of PHPUnit when the property is falsified.

**1. Declaring the property**

.. code-block:: php

   #[Property]
   public function testPartsAddUpToTheWhole(int $cents, int $parts): void

* **What**: Declares that this method is a property
* **How**: The ``#[Property]`` attribute marks the method as a test, and the parameters of the method are what the property is stated for
* **Why**: PHPUnit calls a property many times, each time with other arguments: 100 times by default

A method with the ``#[Property]`` attribute is a test even when its name does not begin with ``test``, like a method with the :ref:`Test <appendixes.attributes.Test>` attribute.

**2. Describing the arguments**

.. code-block:: php

   /**
    * @param int<0, 100000> $cents
    * @param int<1, 100>    $parts
    */

* **What**: Narrows down the values that are generated for the parameters
* **How**: PHPUnit reads the types of the parameters from the ``@param`` tags of the doc comment, and falls back to the native type declarations for parameters without such a tag
* **Why**: The native type ``int`` includes negative numbers and zero, but an amount cannot be split into zero parts, and splitting it into millions of parts would only exhaust the memory

The types say for which arguments the property is supposed to hold.
Arguments for which it is not supposed to hold, such as zero parts, are not generated at all.
:ref:`property-based-testing.generating-arguments` explains which types are supported and what to do when types are not enough.

**3. Stating what must hold**

.. code-block:: php

   $money = new Money($cents);

   $sum = 0;

   foreach ($money->split($parts) as $part) {
       $sum += $part->cents();
   }

   $this->assertSame($cents, $sum);

* **What**: Splits the amount and asserts that the parts add up to it
* **How**: The body of a property is ordinary test code that uses the arguments and the assertions of PHPUnit
* **Why**: The test cannot know which amount it gets, so it cannot assert a specific result. It asserts a relation between the input and the output instead

**4. Reading the result**

.. literalinclude:: examples/property-based-testing/MoneySplitTest.php.out

``Random Seed: 1234``
    The examples of a property are generated from the random seed of the test run.
    PHPUnit shows the seed when a property is among the tests that are run.
    Here it was set with the ``--random-seed`` option, see :ref:`property-based-testing.reproducing-failures.random-seed`.

``F`` and ``Tests: 1``
    A property is one test, no matter how many examples it is checked with.
    It is selected with ``--filter`` and listed by ``--list-tests`` like any other test.

``Failed asserting that 0 is identical to 1.``
    The message of the assertion that failed for the counterexample that is reported.

``Property falsified after 1 example, counterexample shrunk in 2 steps:``
    The first example that was generated already falsified the property.
    PHPUnit then looked for simpler examples that falsify the property in the same way, and found a simpler one twice.

``$cents = 1;`` and ``$parts = 2;``
    The arguments of the minimal counterexample, written as PHP code.
    They are not the arguments that were generated first, which were larger, but the simplest ones that PHPUnit found.

A property that holds for all examples is reported like any other test that passes.
The number of assertions is the sum of the assertions of all examples.


.. _property-based-testing.finding-properties:

Finding properties
==================

The hardest part of property-based testing is not the tooling, it is finding out what the properties of your code are.
A property does not have to describe everything that the code does.
A property that only describes one aspect of the result is useful already, and several such properties together can describe the code completely.
The following kinds of property come up again and again.


.. _property-based-testing.finding-properties.round-trips:

Round trips
-----------

When one operation undoes another, applying both must give back what you started with: decoding what was encoded, parsing what was formatted, loading what was saved.

``RunLengthEncoding`` compresses a string by replacing each run of a character with the length of the run and the character:

.. literalinclude:: examples/property-based-testing/src/RunLengthEncoding.php
   :caption: An encoder and a decoder for run-length encoding
   :language: php

.. literalinclude:: examples/property-based-testing/RunLengthEncodingTest.php
   :caption: Two example-based tests and a round-trip property
   :language: php

.. literalinclude:: examples/property-based-testing/RunLengthEncodingTest.php.out

**Explanation:**

* The two example-based tests pass: ``'aaabbc'`` is encoded and decoded as expected
* The parameter of the property is declared as ``string``, so PHPUnit generates all kinds of strings: empty ones, ASCII and multibyte characters, control characters, and emoji
* The property does not hold for strings that contain digits: ``'0a'`` is encoded as ``'101a'``, which is decoded as a run of 101 times the character ``a``
* The counterexample is as short as it can be: one digit followed by one other character

The authors of the example-based tests did not think of digits.
The property did not have to think of them.


.. _property-based-testing.finding-properties.invariants:

Invariants
----------

An invariant is something that is true for every result, whatever the input was.
The result of sorting a list, for instance, is ordered and consists of the elements of the input.

``Sorter::sort()`` sorts a list of integers.
The examples in this section and in the next two sections are properties of this method.

.. literalinclude:: examples/property-based-testing/src/Sorter.php
   :caption: An implementation of insertion sort
   :language: php

.. code-block:: php

   /**
    * @param list<int> $values
    */
   #[Property]
   public function testResultIsOrdered(array $values): void
   {
       $sorted = Sorter::sort($values);

       $this->assertCount(count($values), $sorted);

       for ($i = 1; $i < count($sorted); $i++) {
           $this->assertLessThanOrEqual($sorted[$i], $sorted[$i - 1]);
       }
   }

   /**
    * @param list<int> $values
    */
   #[Property]
   public function testResultHasTheSameElementsAsTheInput(array $values): void
   {
       $this->assertEqualsCanonicalizing($values, Sorter::sort($values));
   }

**Explanation:**

* Neither property is enough on its own: a method that always returns an empty list satisfies the first one, and a method that returns its input unchanged satisfies the second one
* Together, they describe sorting completely


.. _property-based-testing.finding-properties.relations:

Relations between results
-------------------------

Sometimes you cannot say what the result for an input is, but you can say how the results for two related inputs relate to each other.
Doing something twice has the same effect as doing it once, the order of the input does not matter, or a larger input cannot lead to a smaller result.

.. code-block:: php

   /**
    * @param list<int> $values
    */
   #[Property]
   public function testSortingTwiceIsTheSameAsSortingOnce(array $values): void
   {
       $sorted = Sorter::sort($values);

       $this->assertSame($sorted, Sorter::sort($sorted));
   }

   /**
    * @param list<int> $values
    */
   #[Property]
   public function testOrderOfTheInputDoesNotMatter(array $values): void
   {
       $this->assertSame(
           Sorter::sort($values),
           Sorter::sort(array_reverse($values)),
       );
   }

Properties of this kind are valuable when the correct result is expensive or impossible to compute in a test, for instance for a search engine, a price calculation, or a report: adding a filter to a search never yields more results, and applying a discount never makes an order more expensive.


.. _property-based-testing.finding-properties.comparison:

Comparison with another implementation
--------------------------------------

When there is a second way to compute the same result, both must agree.
The other implementation can be the function of PHP that your code replaces, the slow but obviously correct algorithm that an optimized one replaces, or the legacy code that is being rewritten.

.. code-block:: php

   /**
    * @param list<int> $values
    */
   #[Property]
   public function testAgreesWithTheSortFunctionOfPhp(array $values): void
   {
       $expected = $values;

       sort($expected);

       $this->assertSame($expected, Sorter::sort($values));
   }

The five properties of ``Sorter::sort()`` shown in this section are five tests:

.. literalinclude:: examples/property-based-testing/SorterTest.php.out

For code that has state, the other implementation is a simple model of this state, see :ref:`property-based-testing.state-machines`.


.. _property-based-testing.finding-properties.robustness:

Robustness
----------

The weakest property is that the code can deal with every input: it does not raise an unexpected exception, does not trigger a warning, and does not run forever.
Such a property needs no assertion, and it is surprisingly effective for code that processes input from outside, such as parsers and decoders.

.. literalinclude:: examples/property-based-testing/RunLengthEncodingRobustnessTest.php
   :caption: A property without assertions
   :language: php

.. literalinclude:: examples/property-based-testing/RunLengthEncodingRobustnessTest.php.out

A test that does not perform assertions is :ref:`considered risky <risky-tests.useless-tests>`, and so is a property none of whose examples performs an assertion.
The :ref:`DoesNotPerformAssertions <appendixes.attributes.DoesNotPerformAssertions>` attribute states that this is intended.


.. _property-based-testing.generating-arguments:

Generating arguments
====================

For each parameter of a property, PHPUnit needs a generator.
It uses the first of these that applies:

1. The generator that is specified for the parameter with the ``#[ForAll]`` or the ``#[ForAllExternal]`` attribute
2. A generator that is derived from the type of the parameter

When neither applies, for instance because the type of the parameter is an interface that no generator is registered for, the property cannot be checked.
PHPUnit reports this as an error before it runs the tests.


.. _property-based-testing.generating-arguments.types:

From the types of parameters
----------------------------

In the simplest case, the type of a parameter says everything that there is to say:

.. code-block:: php

   #[Property]
   public function testShippingIsNeverFree(ParcelSize $size, bool $express): void
   {
       $this->assertGreaterThan(0, ShippingCost::inCents($size, $express));
   }

``ParcelSize`` is an enumeration, so its cases are generated.
For ``bool``, ``true`` and ``false`` are generated.

The native types of PHP are often too wide, though.
PHPUnit therefore understands the types that static analysis tools such as PHPStan and Psalm use in doc comments:

.. code-block:: php

   /**
    * @param non-empty-string                    $name
    * @param int<1, 10>                          $quantity
    * @param list<positive-int>                  $prices
    * @param array{id: int, tags?: list<string>} $options
    * @param 'asc'|'desc'                        $direction
    */
   #[Property]
   public function testSomething(string $name, int $quantity, array $prices, array $options, string $direction): void
   {
       // ...
   }

The type of a parameter is taken from its ``@phpstan-param`` tag, from its ``@psalm-param`` tag, from its ``@param`` tag, or from its native type declaration, in this order.
The types that you declare for static analysis and the types that the examples are generated from are the same, so that the property is checked for exactly the values that static analysis allows.
:ref:`property-based-testing.reference.types` lists the types that are supported.

The values that are generated are not spread evenly over the type.
Values that often reveal bugs are generated more often: zero, the smallest and the largest value of a range, small values, the empty string, the empty list, and ``null``.
For ``int``, this includes ``PHP_INT_MIN`` and ``PHP_INT_MAX``, and for ``float`` it includes ``-0.0`` and very small and very large numbers.

**Objects**

A parameter whose type is a class gets objects of this class.
PHPUnit creates them with ``new`` and generates the arguments of the constructor from the types of its parameters, in the same way as for the parameters of the property.
This works for classes whose constructor accepts all values of the types that it declares.

``EmailAddress`` is not such a class: its constructor takes a ``string``, but it only accepts strings that are email addresses.
``Customer`` is a class that has a name and an ``EmailAddress``.

.. literalinclude:: examples/property-based-testing/src/EmailAddress.php
   :caption: A value object whose constructor validates its argument
   :language: php

.. literalinclude:: examples/property-based-testing/CustomerWithoutGeneratorTest.php
   :caption: A property with parameters whose types are classes
   :language: php

.. literalinclude:: examples/property-based-testing/CustomerWithoutGeneratorTest.php.out

PHPUnit generated a string for the constructor of ``EmailAddress``, and the constructor rejected it.
This is not a counterexample of the property, it is a mistake in the test: PHPUnit does not know how to create an ``EmailAddress``.
As the message says, this is solved by registering a generator for the class, see :ref:`property-based-testing.generating-arguments.classes`.

When the constraint can be expressed as a type, it is enough to declare this type for the parameter of the constructor, with a ``@param`` tag in the doc comment of the constructor.
The constructor of ``Customer``, for instance, declares its ``$name`` parameter as ``non-empty-string``.

A constructor parameter that has a default value and whose type no generator can be derived for, such as an interface, is not passed an argument, so that its default value is used.


.. _property-based-testing.generating-arguments.generators:

With generators
---------------

When a type cannot express which values a property needs, you specify a generator.
A generator is an object that implements the ``PHPUnit\Framework\PropertyBased\ValueGenerator`` interface.
Generators are created with the static methods of the ``PHPUnit\Framework\PropertyBased\Generators`` class and are combined into new generators.

The ``#[ForAll]`` attribute on a parameter specifies the method that returns the generator for this parameter:

.. literalinclude:: examples/property-based-testing/SortedListByConstructionTest.php
   :caption: Specifying a generator with the ``#[ForAll]`` attribute
   :language: php

**1. Creating a generator**

.. code-block:: php

   Generators::lists(Generators::integers(), minimumSize: 1)

* **What**: Creates a generator for lists of integers that have at least one element
* **How**: ``Generators::integers()`` generates integers, and ``Generators::lists()`` generates lists of the values of the generator that it is given
* **Why**: Generators for simple values are the building blocks for generators for composite values

**2. Transforming the generated values**

.. code-block:: php

   ->map(
       static function (array $values): array
       {
           sort($values);

           return $values;
       },
   )

* **What**: Turns the generator for lists into a generator for sorted lists
* **How**: ``map()`` returns a new generator that passes each generated value to the closure and generates what the closure returns
* **Why**: A binary search requires a sorted list. No type expresses "sorted", but a list that is sorted after it was generated is sorted by construction

**3. Specifying the generator for a parameter**

.. code-block:: php

   public function testFindsTheFirstValue(#[ForAll('sortedLists')] array $values): void

* **What**: Specifies that the values for ``$values`` are generated by the generator that ``sortedLists()`` returns
* **How**: The ``#[ForAll]`` attribute takes the name of a public and static method of the test case class that has no required parameters and returns a ``ValueGenerator``
* **Why**: The generator of a parameter is declared right where the parameter is declared

The generator method does not generate values itself.
It is called once, and the generator that it returns generates a value for each example.

**Other ways to specify a generator**

The ``#[ForAllExternal]`` attribute specifies a generator method of another class, so that generators can be shared between test case classes:

.. code-block:: php

   #[Property]
   public function testSomething(#[ForAllExternal(ExternalGenerators::class, 'evenNumbers')] int $number): void
   {
       // ...
   }

The ``#[ForAll]`` attribute also accepts a closure that returns the generator:

.. code-block:: php

   #[Property]
   public function testSomething(
       #[ForAll(static function (): ValueGenerator
       {
           return Generators::integers(1, 3);
       })]
       int $value,
   ): void {
       // ...
   }

**Combining generators**

Every generator has three methods that return a new generator:

``map(Closure $mapper)``
    Generates what ``$mapper`` returns for the values of the generator.

    .. code-block:: php

       Generators::integers(-1000, 1000)->map(
           static fn (int $number): int => $number * 2,
       );

``filter(Closure $predicate)``
    Generates only the values of the generator for which ``$predicate`` returns ``true``.
    A value for which it returns ``false`` is generated again, and the example is rejected when this happens three times in a row (see :ref:`property-based-testing.generating-arguments.assume`).
    Use ``filter()`` only when most values satisfy the predicate, and construct the values that you need with ``map()`` otherwise.

    .. code-block:: php

       Generators::integers(-1000, 1000)->filter(
           static fn (int $number): bool => $number !== 0,
       );

``flatMap(Closure $mapper)``
    Generates the values of the generator that ``$mapper`` returns for a value of the generator.
    This is for values that depend on other values, here an index that is valid for the list that was generated before it.

    .. code-block:: php

       Generators::lists(Generators::integers(), minimumSize: 1)->flatMap(
           static fn (array $list): ValueGenerator => Generators::tuples(
               Generators::constant($list),
               Generators::integers(0, count($list) - 1),
           ),
       );

``Generators::combine()`` builds a value from the values of several generators that do not depend on each other, an object, for instance:

.. code-block:: php

   Generators::combine(
       static fn (int $width, int $height): Rectangle => new Rectangle($width, $height),
       Generators::integers(1, 100),
       Generators::integers(1, 100),
   );

However generators are combined, counterexamples are shrunk without further ado, and a shrunk value is always one that the combined generator can generate: a list that was sorted with ``map()`` is still sorted after it was shrunk, and a number that was doubled is still even.
:ref:`property-based-testing.falsified-properties.shrinking` explains why.

:ref:`property-based-testing.reference.generators` lists the generators that are available, among them generators for strings that match a regular expression, email addresses, URLs, UUIDs, IP addresses, and points in time.

.. admonition:: Generators must not have side effects

   The values of a generator must only depend on the generators that it is built from.
   A closure that is passed to ``map()``, ``filter()``, ``flatMap()``, or ``combine()`` must not use random numbers, the current time, or any other state.
   Otherwise, a counterexample cannot be shrunk and reproduced reliably.
   PHPUnit emits a warning when it detects that the generators of a property do not always generate the same values.

The ``ValueGenerator`` interface is not meant to be implemented by your own classes.
PHPUnit reports an error when an object of a class that it does not provide is used as a generator.


.. _property-based-testing.generating-arguments.classes:

For classes
-----------

A generator for the objects of a class is registered with the ``#[GeneratorFor]`` attribute.
It is then used wherever a property of the test case class needs an object of this class.

.. literalinclude:: examples/property-based-testing/CustomerTest.php
   :caption: Registering a generator for a class with the ``#[GeneratorFor]`` attribute
   :language: php

**1. Registering the generator**

.. code-block:: php

   #[GeneratorFor(EmailAddress::class)]
   public static function emailAddresses(): ValueGenerator

* **What**: Registers the generator that ``emailAddresses()`` returns for the ``EmailAddress`` class
* **How**: The ``#[GeneratorFor]`` attribute is used on a public and static method that returns a ``ValueGenerator``
* **Why**: PHPUnit cannot know which strings the constructor of ``EmailAddress`` accepts, so the test has to say how objects of this class are created

**2. Creating the objects**

.. code-block:: php

   return Generators::emailAddresses()->map(
       static fn (string $address): EmailAddress => new EmailAddress($address),
   );

* **What**: Generates ``EmailAddress`` objects for generated email addresses
* **How**: ``Generators::emailAddresses()`` generates strings that are valid email addresses, and ``map()`` creates an object for each of them
* **Why**: The generated email addresses are not only valid, they also include unusual ones: long ones and ones with special characters

**3. Using the objects**

.. code-block:: php

   #[Property]
   public function testEmailAddressCanBeChanged(Customer $customer, EmailAddress $newAddress): void

* **What**: Declares a property for all customers and all email addresses
* **How**: The registered generator is used for the ``EmailAddress`` parameter and for the ``EmailAddress`` that the constructor of ``Customer`` requires. The ``Customer`` object itself is still created with ``new``
* **Why**: A generator is registered once for a class and is then used for all objects that are built from objects of this class

**Sharing registered generators**

Generators that are needed in more than one test case class are registered in a class of their own:

.. literalinclude:: examples/property-based-testing/src/DomainGenerators.php
   :caption: A class that registers generators
   :language: php

.. literalinclude:: examples/property-based-testing/CustomerUsingSharedGeneratorsTest.php
   :caption: Using the generators that another class registers with the ``#[UseGenerators]`` attribute
   :language: php

The ``#[UseGenerators]`` attribute can be used on a test case class and on a property, and it can be used more than once.
When more than one generator is registered for a class, a generator that the test case class registers itself takes precedence over one of a class that is used for the property, which takes precedence over one of a class that is used for the test case class.
Two generators for the same class on the same level are an error.

There is no way to register a generator for all tests of a test suite.
Which generators a property uses can always be seen by looking at its test case class.

Inside a generator, ``Generators::objects(Customer::class)`` generates objects the way they are generated for a parameter of this type, and ``Generators::fromType('list<Customer>')`` does the same for any type that can be used in a ``@param`` tag.


.. _property-based-testing.generating-arguments.assume:

Rejecting examples
------------------

A property can state that it only holds under a condition by calling ``assume()``.
An example for which the condition is not met is **rejected**: it neither passes nor fails, and it does not count towards the number of examples that the property is checked with.

.. code-block:: php

   #[Property]
   public function testDivisionReversesMultiplication(int $a, int $b): void
   {
       $this->assume($b !== 0);

       // ...
   }

This is convenient when almost all generated examples meet the condition, as they do here.
When they do not, ``assume()`` causes two problems.
The first one is that examples are wasted:

.. literalinclude:: examples/property-based-testing/EmailAddressByAssumptionTest.php
   :caption: A property that rejects almost all of its examples
   :language: php

.. literalinclude:: examples/property-based-testing/EmailAddressByAssumptionTest.php.out

A string that is generated from all strings is practically never an email address.
PHPUnit notices this: when 50 examples were rejected before 10 examples were checked, it stops generating examples for the property and considers the property :ref:`risky <risky-tests>`, as it was not checked with the number of examples that it should have been checked with.

The second problem is harder to notice, because the property passes.
The examples that are not rejected can be very different from what you have in mind:

.. literalinclude:: examples/property-based-testing/SortedListByAssumptionTest.php
   :caption: A property for sorted lists that rejects the lists that are not sorted
   :language: php

.. literalinclude:: examples/property-based-testing/SortedListByAssumptionTest.php.out

The property was checked with 100 sorted lists, but 255 other lists had to be generated and thrown away for this.
More importantly, the lists that happen to be sorted are short: half of them have one element, and the longest has four.
A binary search in a list of one element does not test much.

Compare this with the statistics of the property shown in :ref:`property-based-testing.generating-arguments.generators`, which sorts the lists that are generated instead of rejecting the ones that are not sorted:

.. literalinclude:: examples/property-based-testing/SortedListByConstructionTest.php.out

No example is rejected, and the lists have up to 21 elements.

**Generate the values that a property needs instead of rejecting the others.**
Use a narrower type, a generator that fits, or ``map()`` to construct the values.
The statistics that are shown here are explained in :ref:`property-based-testing.knowing-what-was-tested`.

``assume()`` can only be called while a property is run.
It can also be called in a method that is called after the test method, such as ``tearDown()``.


.. _property-based-testing.generating-arguments.draw:

In the body of a property
-------------------------

Some values can only be generated when other values are known: an element of a list that was generated, an index into it, or a second date that is later than the first one.
The ``draw()`` method generates a value in the body of a property, with a generator that can be built from the arguments and from the values that were drawn before.

.. literalinclude:: examples/property-based-testing/BinarySearchWithDrawnNeedleTest.php
   :caption: Drawing a value that depends on an argument
   :language: php

**1. Building a generator from an argument**

.. code-block:: php

   Generators::oneOf(
       Generators::integers(),
       Generators::elements(...$values),
   ),

* **What**: Creates a generator for integers of which about half are elements of the list that was generated for ``$values``
* **How**: ``Generators::elements()`` generates one of the values that it is given, and ``Generators::oneOf()`` generates a value of one of the generators that it is given
* **Why**: The search has to be tested with values that are in the list and with values that are not. An integer that is generated independently of the list is almost never in the list

**2. Drawing a value**

.. code-block:: php

   $needle = $this->draw(
       // ...
       'needle',
   );

* **What**: Generates a value with the generator and returns it
* **How**: ``draw()`` takes a generator and, optionally, a label that describes the value
* **Why**: A value that is drawn is part of the example, like the arguments are: it is shrunk together with them, and it is reported with its label when the property is falsified

The values that the minimal counterexample of a property drew are listed after its arguments, for instance ``Draw 1 (needle): 0``.

``draw()`` can be called any number of times in a property.
When the generator cannot generate a value, because no value satisfies a ``filter()``, for instance, the example is rejected.


.. _property-based-testing.knowing-what-was-tested:

Knowing what was tested
=======================

A property that passes was checked with 100 examples, but with which ones?
When the generated examples do not include the cases that matter, the property passes without having tested much, and nothing tells you so.
PHPUnit therefore lets a property record what its examples looked like.

``BinarySearch::contains()`` searches a sorted list for a value:

.. literalinclude:: examples/property-based-testing/src/BinarySearch.php
   :caption: A binary search
   :language: php

.. literalinclude:: examples/property-based-testing/BinarySearchTest.php
   :caption: A property that classifies its examples and observes a value
   :language: php

**1. Classifying examples**

.. code-block:: php

   $this->classify($expected ? 'needle is in the list' : 'needle is not in the list');

* **What**: Labels the current example with one of two labels
* **How**: ``classify()`` takes a label. An example can be classified with more than one label
* **Why**: The two cases exercise different paths through the search, and both have to be tested

**2. Observing values**

.. code-block:: php

   $this->observe('size of the list', count($values));

* **What**: Records the number of elements of the generated list
* **How**: ``observe()`` takes a label and an integer or a floating-point number
* **Why**: A search in a list of one or two elements is not much of a search

**3. Displaying the statistics**

The ``--display-property-statistics`` option makes PHPUnit print what the properties recorded:

.. literalinclude:: examples/property-based-testing/BinarySearchTest.php.out

``100 examples checked, 0 rejected``
    How many examples the property was checked with, and how many examples were :ref:`rejected <property-based-testing.generating-arguments.assume>`.

``92.0% needle is not in the list`` and ``8.0% needle is in the list``
    The percentage of the examples that were classified with each label.
    Only 8 of the 100 examples test that a value that is in the list is found.

``size of the list: count 100, min 0, median 3, mean 3.77, p90 8, max 22``
    The number of values that were observed with the label, their minimum, median, mean, 90th percentile, and maximum.

The statistics show a problem of the test, not of the code under test: the more interesting of the two cases is hardly tested.
The property shown in :ref:`property-based-testing.generating-arguments.draw` solves this by drawing the needle from the list half of the time:

.. literalinclude:: examples/property-based-testing/BinarySearchWithDrawnNeedleTest.php.out


.. _property-based-testing.knowing-what-was-tested.required-classifications:

Requiring classifications
-------------------------

Looking at statistics is something that you do while you write a property.
To make sure that the examples still cover a case after the code and its tests have changed, use the ``#[RequireClassification]`` attribute.
It takes a label and the minimum percentage of the examples that have to be classified with it:

.. literalinclude:: examples/property-based-testing/BinarySearchWithRequiredClassificationTest.php
   :caption: Requiring that a minimum percentage of the examples is classified with a label
   :language: php

.. literalinclude:: examples/property-based-testing/BinarySearchWithRequiredClassificationTest.php.out

A property that does not meet a requirement is considered :ref:`risky <risky-tests>`.
The attribute can be used more than once on a property, for different labels.

The percentage is that of the examples that were generated for this test run, so it varies a little from one test run to the next.
Do not require a percentage that is close to the one that you observe.


.. _property-based-testing.knowing-what-was-tested.exhaustion:

Properties with few possible examples
-------------------------------------

PHPUnit does not check a property with the same generated example twice.
When the parameters of a property can only take a few values, all possible examples have been checked before the configured number of examples is reached, and PHPUnit stops:

.. literalinclude:: examples/property-based-testing/ShippingCostTest.php
   :caption: A property with six possible examples
   :language: php

.. literalinclude:: examples/property-based-testing/ShippingCostTest.php.out

There are three parcel sizes and two values of ``bool``, so six examples are all that there are.
The property was checked for every one of them: it has been shown to hold, not just for a sample of the possible arguments, but for all of them.


.. _property-based-testing.finding-rare-failures:

Finding rare failures
=====================

Generating examples at random finds bugs that many examples trigger.
It does not find a bug that only one in a million examples triggers, unless there is a hint where to look.
When a property can compute a number that tells how close an example came to a failure, the ``target()`` method gives PHPUnit this hint.

A ``Truck`` has a capacity and must not accept a parcel that would overload it:

.. literalinclude:: examples/property-based-testing/src/Truck.php
   :caption: A truck that is loaded with parcels
   :language: php

.. literalinclude:: examples/property-based-testing/TruckTest.php
   :caption: A property that only fails for rare examples
   :language: php

.. literalinclude:: examples/property-based-testing/TruckTest.php.out

The property passes, although it is checked with 300 examples and although ``load()`` is wrong: it only refuses a parcel when the truck is already full, not when the parcel does not fit.
To overload a truck with a capacity of 1000, the parcels must weigh more than 1000 in total, though, and a list of at least eleven heavy parcels is rarely generated.

.. literalinclude:: examples/property-based-testing/TruckWithTargetTest.php
   :caption: Targeting a score to find rare examples
   :language: php

.. literalinclude:: examples/property-based-testing/TruckWithTargetTest.php.out

**Explanation:**

* ``target()`` takes a label and a score, an integer or a floating-point number. The higher the score, the closer the example is to what you are looking for
* The first half of the examples of a property is generated as usual. The second half is spent on changing the example with the highest score so far, bit by bit, as long as this raises its score
* Here, the score is the load of the truck. PHPUnit made the list of parcels heavier and heavier until the truck was overloaded
* ``target()`` can be called once per label for an example. A property can target more than one score, each with its own label

Targeting a score raises the chance that a rare failure is found, it does not guarantee that it is found.
With the same seed, the property without ``target()`` passes, and for most other seeds it passes as well.

Scores that work well are sizes, sums, depths, durations, the number of retries, or the distance to a limit: any number of which you suspect that the code under test gets into trouble when it becomes large.
To target small values, negate the number.

The highest score for each label is part of the :ref:`statistics <property-based-testing.knowing-what-was-tested>` of the property.


.. _property-based-testing.state-machines:

Testing stateful code
=====================

The properties shown so far are properties of functions: values go in, a result comes out.
Objects that have state are different, because what a method does depends on the methods that were called before.
The bugs of such objects are in sequences of method calls, and a test that is written by hand only tries the few sequences that its author thought of.

A property can generate these sequences.
You describe which operations there are and what must be true after each of them, and PHPUnit generates sequences of operations, runs them, and, when something is not true, shrinks the sequence to the shortest one that still fails.
The description is an object that is called a **state machine**.

A ``ParkingGarage`` has a number of parking spaces.
A car that enters gets a ticket, and it needs the ticket to leave.

.. literalinclude:: examples/property-based-testing/src/ParkingGarage.php
   :caption: A parking garage
   :language: php

.. literalinclude:: examples/property-based-testing/src/ParkingGarageMachine.php
   :caption: A state machine for ``ParkingGarage``
   :language: php

.. literalinclude:: examples/property-based-testing/ParkingGarageTest.php
   :caption: An example-based test and a property that runs the state machine
   :language: php

**1. The state machine and its model**

.. code-block:: php

   final class ParkingGarageMachine
   {
       private ParkingGarage $garage;
       private int $capacity;
       private int $parkedCars = 0;

* **What**: Holds the object under test and a model of its state
* **How**: The state machine is an ordinary class. It does not extend a class or implement an interface of PHPUnit
* **Why**: The model is the simplest thing that tells what the object under test should do. For a parking garage, the number of cars that are parked is all that is needed

**2. Rules**

.. code-block:: php

   #[StateMachineRule(precondition: 'hasFreeSpaces', produces: 'tickets')]
   public function enter(): int
   {
       $ticket = $this->garage->enter();

       $this->parkedCars++;

       return $ticket;
   }

* **What**: Declares an operation that can be a step of a sequence
* **How**: A rule is a public method with the ``#[StateMachineRule]`` attribute. It performs the operation on the object under test and on the model
* **Why**: PHPUnit builds sequences from the rules of the state machine

The rule has a **precondition**: ``precondition: 'hasFreeSpaces'`` names a method of the state machine that returns a ``bool``.
The rule is only used for a step when this method returns ``true``, so a car never tries to enter a garage that is full.

**3. Pools**

.. code-block:: php

   #[StateMachineRule]
   public function leave(#[FromPool('tickets', consume: true)] int $ticket): void
   {
       $this->garage->leave($ticket);

       $this->parkedCars--;
   }

* **What**: Declares an operation that needs a value that an earlier step produced
* **How**: ``produces: 'tickets'`` on the ``enter()`` rule puts the value that the rule returns into a pool named ``tickets``. ``#[FromPool('tickets')]`` on a parameter takes a value from this pool. With ``consume: true``, the value is removed from the pool, as a ticket can only be used once
* **Why**: A car can only leave with a ticket that was issued before. A ticket that is generated at random would not be a valid one

A rule whose parameter takes a value from a pool is only used for a step when the pool has a value.
The other parameters of a rule get generated arguments like the parameters of a property: from their types or from ``#[ForAll]`` and ``#[ForAllExternal]``, using the generators that are registered for the property.

**4. Invariants**

.. code-block:: php

   #[StateMachineInvariant]
   public function freeSpacesMatchTheNumberOfParkedCars(): void
   {
       Assert::assertSame(
           $this->capacity - $this->parkedCars,
           $this->garage->freeSpaces(),
       );
   }

* **What**: States what must be true whatever happened before
* **How**: An invariant is a public method with the ``#[StateMachineInvariant]`` attribute. It is called before the first step and after every step
* **Why**: The object under test and the model must agree at all times

The state machine is not a test case class, so the assertions are called statically on ``PHPUnit\Framework\Assert``.
Rules can perform assertions as well, for instance on the value that an operation returns.

**5. Running the state machine**

.. code-block:: php

   #[Property]
   public function testKeepsTrackOfItsFreeSpaces(): void
   {
       $this->runStateMachine(
           new ParkingGarageMachine(new ParkingGarage(5), 5),
       );
   }

* **What**: Runs a sequence of steps of the state machine
* **How**: ``runStateMachine()`` takes the state machine and, optionally, the maximum number of steps of a sequence, which is 50 by default
* **Why**: Each example of the property is one sequence of steps, on a new state machine and a new object under test

**6. Reading the result**

.. literalinclude:: examples/property-based-testing/ParkingGarageTest.php.out

The example-based test passes.
The property is falsified, and the counterexample is a sequence of four steps, written as PHP code that calls the methods of the state machine:

* Values that a step put into a pool are assigned to variables that are named after the pool: ``$tickets1``, ``$tickets2``, and so on
* A value from a pool is passed as the variable that it was assigned to
* The invariant that failed is marked with ``// invariant failed``
* A value that a rule returns and that is not put into a pool is shown as a comment after the step

Read as a story, the counterexample says: two cars enter, the first one leaves, and a third car enters.
After that, the garage reports four free spaces although two cars are parked.
The reason is that ``enter()`` numbers the tickets by counting the cars that are parked: the third car gets the number of the second car, and the garage loses track of one of them.

No shorter sequence shows this bug, and no step of the sequence can be left out.
A sequence like this is exactly what an example-based test does not contain, because nobody thinks of it before the bug is known.
The code of the counterexample can be pasted into such a test once the bug is known.

**More about state machines**

* A public method with the ``#[InitializeStateMachine]`` attribute is called once before the first step, for instance to put the object under test into a certain state. With ``produces:``, the value that it returns is put into a pool
* An invariant with ``#[StateMachineInvariant(atEnd: true)]`` is only checked before the first step and after the last step. Use this for checks that are expensive
* The rules, invariants, and initializations of a parent class come before those of the class that extends it
* ``runStateMachine()`` can be called once in an example. The property can have parameters, and it can draw values before it runs the state machine
* The number of steps of each example, and the number of steps with each rule, are :ref:`observed <property-based-testing.knowing-what-was-tested>`, so that ``--display-property-statistics`` shows how long the sequences were and which rules were used
* A property is considered :ref:`risky <risky-tests>` when the state machine took no step in more than half of its examples because no rule could be applied
* A state machine that is not valid, for instance because it has no rule or because a precondition does not return a ``bool``, is a mistake in the test and is reported as an error


.. _property-based-testing.falsified-properties:

When a property is falsified
============================

.. _property-based-testing.falsified-properties.what-falsifies:

What falsifies a property
-------------------------

An example falsifies a property when it would make any other test fail or error:

* An assertion fails
* An exception is raised that the test does not expect
* The example takes longer than the :ref:`time limit <risky-tests.test-execution-timeout>` of the test, when time limits are enforced
* A deprecation, notice, or warning is triggered, and PHPUnit is configured to :ref:`fail the test run <appendixes.configuration.fail-on>` because of it. An issue that the test run does not fail for is reported for the property, but does not falsify it

A failure before the test method is called, in ``setUp()``, for instance, does not have anything to do with the arguments of the example.
It is reported as it is, without a counterexample.

When an example is skipped or marked as incomplete, the property is skipped or incomplete.


.. _property-based-testing.falsified-properties.shrinking:

How a counterexample is shrunk
------------------------------

The example that falsifies a property first is rarely a good counterexample.
It is as large and as noisy as random examples are: a list of twenty numbers of which two matter, or a string of emoji of which one character is the problem.
PHPUnit therefore shrinks the example before it reports it.

Shrinking needs no code from you, and it works for every generator, including the ones that you build with ``map()``, ``filter()``, ``flatMap()``, and ``draw()``.
This is so because PHPUnit does not shrink the generated values.
A generator makes a sequence of choices when it generates a value: how many elements a list has, which number each element is, which of two alternatives is used.
PHPUnit records these choices, and it shrinks a counterexample by making the choices fewer and smaller and running the generators again with the changed choices.

It helps to know the following about shrinking:

* **A shrunk value is always a value that the generator could have generated.** A value of the type ``int<1, 100>`` is never shrunk to ``0``, and a list that was sorted with ``map()`` stays sorted
* **Simpler means fewer choices first and smaller choices second.** Collections become shorter, strings become shorter, numbers move towards zero, strings move towards ``'a'``, ``null`` is preferred over a value, the first case of an enumeration over the others, and the first alternative of a union type or of ``Generators::oneOf()`` over the others
* **Non-negative numbers are simpler than negative numbers.** A property that fails for all numbers whose absolute value is at least 1000 is reported with ``1000``, not with ``-1000``
* **The counterexample falsifies the property in the same way as the example that it was shrunk from.** A simpler example that makes another assertion fail, or raises another exception, is not what was found
* **Every attempt to simplify the counterexample is a run of the test method**, with a new object of the test case class and with ``setUp()`` and ``tearDown()``
* **The minimal counterexample is small, but not necessarily the smallest one that exists.** PHPUnit tries many ways to simplify it, but not all values that there are

The number of attempts to shrink a counterexample is limited to 1000 by default.
When this limit is reached, the counterexample that was found so far is reported, together with a hint:

.. code-block:: text

   Shrinking was stopped after 1000 attempts, a simpler counterexample may exist.

The limit is configured with the ``--property-maximum-shrinking-attempts`` option or in the XML configuration file, see :ref:`property-based-testing.configuration`.
A limit of ``0`` disables shrinking.


.. _property-based-testing.falsified-properties.other-falsifications:

More than one way to fail
-------------------------

While a counterexample is shrunk, examples can turn up that falsify the property in another way: another assertion fails, or another exception is raised.
These may be other bugs.
PHPUnit reports the simplest example that it came across for each of them after the minimal counterexample:

.. code-block:: text

   Property falsified after 3 examples, counterexample shrunk in 25 steps:

       $value = 500;

   Property also falsified by RuntimeException at /path/to/tests/ExampleTest.php:12 with this counterexample:

       $value = 501;


.. _property-based-testing.falsified-properties.flaky-properties:

Flaky properties
----------------

A property must only depend on its arguments.
When it also depends on something else, such as the current time, random numbers, global state, or the order in which tests are run, the same example can falsify it once and not falsify it the next time.
A counterexample of such a property means nothing, and shrinking it would be a waste of time.

PHPUnit therefore runs the example that falsified a property again, several times, before it shrinks it:

* When the example does not falsify the property again, the property is reported as an error with the message ``The property is flaky: it was not falsified when the example that falsified it was replayed 4 times, so its outcome does not depend on its arguments alone``
* When the example falsifies the property again only some of the times, the counterexample is shrunk and reported together with the hint ``The example that falsified the property falsified it again in only 2 of 4 replays, so its outcome does not depend on its arguments alone.``

The minimal counterexample is run once more after shrinking.
The failure that PHPUnit reports is the one of this last run.
When the minimal counterexample does not falsify the property in this run, the property is reported as flaky as well.

Because of this, the :ref:`Repeat <appendixes.attributes.Repeat>` and :ref:`Retry <appendixes.attributes.Retry>` attributes and the ``--repeat`` and ``--retry`` options do not apply to properties.

When a test run is started with the ``--random-seed`` option, PHPUnit seeds the :ref:`random number generators of PHP <textui.random-numbers>` again before each example.
Code under test that calls ``mt_rand()`` or ``shuffle()`` then gets the same random numbers for every example, so that it does not make the property flaky.


.. _property-based-testing.falsified-properties.counterexample-formatter:

Describing a counterexample
---------------------------

The arguments of a counterexample are reported as PHP code: scalar values and arrays as literals, cases of enumerations by their name, and objects that PHPUnit created with ``new`` as the ``new`` expression that created them.
This code can be pasted into a test.
Other objects are reported with their properties.

Sometimes the arguments alone do not explain a failure, and something that is computed from them does.
The ``#[CounterexampleFormatter]`` attribute names a method that returns a text for a counterexample, which is reported below the arguments:

.. literalinclude:: examples/property-based-testing/RunLengthEncodingWithFormatterTest.php
   :caption: Describing a counterexample with the ``#[CounterexampleFormatter]`` attribute
   :language: php

.. literalinclude:: examples/property-based-testing/RunLengthEncodingWithFormatterTest.php.out

**Explanation:**

* The method that is named by the attribute must be public and static. It is called with the arguments of the counterexample and returns a string
* Here it shows the encoded string, ``101a``, which explains at once why ``'0a'`` cannot be decoded
* The method is called once for each counterexample that is reported, with arguments that are generated again. It must not change them
* The ``#[CounterexampleFormatterExternal]`` attribute names a method of another class


.. _property-based-testing.reproducing-failures:

Reproducing failures
====================

A property is checked with other examples in every test run.
This is intended, as every test run can find a bug that the test runs before it did not find.
It must not mean that a failure that was found once cannot be found again, though.


.. _property-based-testing.reproducing-failures.random-seed:

The random seed
---------------

All examples of a test run are generated from one number, the random seed of the test run.
PHPUnit shows it when a property is among the tests that are run:

.. code-block:: text

   Random Seed:   1234

Running the tests again with this seed generates the same examples, as long as the code and its tests did not change:

.. parsed-literal::

    $ ./tools/phpunit --random-seed 1234

The examples of a property only depend on the seed of the test run and on the property.
They do not depend on which other tests are run or in which order, so that a single property can be run again with ``--filter`` and the seed of the test run in which it was falsified.

Without ``--random-seed``, PHPUnit uses another seed for each test run.
With the ``--random-seed-from-git-commit`` option, the seed is derived from the Git commit that is checked out.
All test runs for a commit then use the same examples, and each new commit is tested with new ones.
This is useful for continuous integration: a build that is run again gives the same result, and a failure can be reproduced on another machine by checking out the commit.


.. _property-based-testing.reproducing-failures.remembered-counterexamples:

Remembered counterexamples
--------------------------

When a property is falsified, PHPUnit remembers its counterexample in the :ref:`test run history <appendixes.xml-configuration-file.phpunit.recordTestRunHistory>`.
The next time the property is run, the remembered counterexample is checked first, before any examples are generated, whatever the seed of the test run is:

.. code-block:: text

   Property falsified after 1 example with this counterexample:

       $cents = 1;
       $parts = 2;

While you work on a fix, the property therefore fails immediately and with the same counterexample for as long as the bug exists.
When the property passes, the counterexample is forgotten.

The test run history is stored in the cache directory of PHPUnit, which is usually not shared with others or with continuous integration.
What is remembered is also not the arguments themselves, but how they were generated.
When the generators of the property change, the remembered counterexample can lead to other arguments.
A counterexample that must not be forgotten should become an explicit example.


.. _property-based-testing.reproducing-failures.explicit-examples:

Explicit examples
-----------------

The :ref:`TestWith <appendixes.attributes.TestWith>` and :ref:`TestWithJson <appendixes.attributes.TestWithJson>` attributes on a property specify examples that the property is always checked with, in addition to the examples that are generated.
This is how a counterexample becomes a regression test: copy its arguments from the output of PHPUnit into a ``#[TestWith]`` attribute.

.. literalinclude:: examples/property-based-testing/MoneySplitWithExplicitExampleTest.php
   :caption: A property with an explicit example
   :language: php

.. literalinclude:: examples/property-based-testing/MoneySplitWithExplicitExampleTest.php.out

**Explanation:**

* Explicit examples are checked first, in the order in which they are declared, before any example is generated
* They do not count towards the number of examples that are generated
* An explicit example that falsifies the property is reported as it is. It is not shrunk
* An explicit example is identified by its number, starting with ``#0``, or by the name that is passed as the second argument of ``#[TestWith]``
* The arguments are given by position or by the names of the parameters. The types of the parameters in the doc comment do not restrict them: an explicit example can use a value that is never generated
* ``draw()`` and ``runStateMachine()`` cannot be used in an explicit example, as there is nothing that their values could be generated from

Explicit examples are also the way to combine a property with the examples that document important cases.
Data providers cannot be used for this: ``#[Property]`` cannot be combined with the :ref:`DataProvider <appendixes.attributes.DataProvider>` attribute and its variants.


.. _property-based-testing.configuration:

Configuration
=============

**The number of examples**

A property is checked with 100 generated examples by default.
More examples find more bugs and take more time.

.. code-block:: php

   #[Property(examples: 500)]
   public function testSomething(int $value): void
   {
       // ...
   }

``#[Property(examples: 500)]``
    Sets the number of examples for one property.

``--property-examples <N>``
    Sets the number of examples for all properties that do not specify it themselves.

``--property-examples-multiplier <N>``
    Multiplies the number of examples of every property by ``N``, including the properties that specify it themselves.
    This keeps the proportions between the properties and is meant for test runs that have more time, such as a nightly build.

**Shrinking**

``--property-maximum-shrinking-attempts <N>``
    Limits the number of attempts to :ref:`shrink a counterexample <property-based-testing.falsified-properties.shrinking>`, which is 1000 by default. ``0`` disables shrinking.

**Statistics**

``--display-property-statistics``
    Displays the :ref:`statistics <property-based-testing.knowing-what-was-tested>` of the properties.

**The XML configuration file**

All of this can be configured in the :ref:`XML configuration file <appendixes.xml-configuration-file.propertyBasedTesting>` as well, together with the limits for :ref:`rejected examples <property-based-testing.generating-arguments.assume>`:

.. code-block:: xml

   <phpunit displayPropertyStatistics="true">
       <!-- ... -->

       <propertyBasedTesting examples="100"
                             examplesMultiplier="1"
                             maximumShrinkingAttempts="1000"
                             examplesCheckedUntilHealthIsAssumed="10"
                             maximumRejectedExamplesUntilHealthIsAssumed="50"
                             maximumTooLargeExamplesUntilHealthIsAssumed="20"/>
   </phpunit>


.. _property-based-testing.other-features:

Properties and other features of PHPUnit
========================================

A property is one test that runs its test method many times.
This has consequences for the features of PHPUnit that deal with test methods.

Fixtures
    Each example is run with a new object of the test case class.
    ``setUp()``, ``tearDown()``, and the other methods that are called :ref:`before and after a test method <fixtures>` are called for each example.
    The methods that are called before the first and after the last test of the test case class are called once.

Expectations
    Expectations that are configured for a test, such as ``expectException()`` or the expectations of mock objects, apply to each example.

Assertions
    The assertions of all examples are added up.
    A property is considered risky for not performing assertions when none of its examples performed an assertion.

Skipped and incomplete tests
    When an example calls ``markTestSkipped()`` or ``markTestIncomplete()``, the property is skipped or incomplete, and no further examples are run.

Issues
    The deprecations, notices, and warnings that the examples trigger are reported for the property.

Output
    The output of all examples is the output of the property.

Time limits
    A :ref:`time limit <risky-tests.test-execution-timeout>` applies to each example, not to the property as a whole.

Code coverage
    Code coverage is collected for the examples that the property is checked with, not for the attempts to shrink a counterexample.

Data providers
    ``#[Property]`` cannot be combined with ``#[DataProvider]``, ``#[DataProviderExternal]``, or ``#[DataProviderClosure]``.
    Use :ref:`explicit examples <property-based-testing.reproducing-failures.explicit-examples>`.

Dependencies
    A property cannot depend on another test with ``#[Depends]`` or its variants.
    Another test can depend on a property.

Repeating, retrying, and process isolation
    The ``#[Repeat]``, ``#[Retry]``, and ``#[RunInSeparateProcess]`` attributes have no effect on a property, and PHPUnit emits a warning when they are used on one.
    The corresponding command-line options and settings of the XML configuration file do not apply to properties either.

Parallel test execution
    When tests are run in :ref:`parallel <parallel-test-execution>`, a property is checked by the worker process that runs its test class.
    The examples are generated from the random seed of the test run and are the same as in a test run that does not run tests in parallel.

Test impact analysis
    When :ref:`test impact data <test-impact-analysis>` is recorded, what the examples that the property is checked with execute is recorded for the property.
    Nothing is recorded for the attempts to shrink a counterexample.

Events
    The ``PHPUnit\Event\Test\PropertyFalsified`` and ``PHPUnit\Event\Test\PropertyChecked`` :ref:`events <appendixes.events>` carry the counterexample and the statistics of a property for extensions.
    The other events for a test are emitted once for a property.


.. _property-based-testing.reference:

Reference
=========

.. _property-based-testing.reference.types:

Types
-----

Generators can be derived from the following types, in a doc comment or, where PHP supports them, as a native type declaration.

.. list-table::
    :header-rows: 1
    :widths: 45 55

    * - Type
      - Generated values
    * - ``int``, ``integer``
      - All integers
    * - ``int<min, max>``
      - The integers of the range. The keywords ``min`` and ``max`` can be used for a bound that is open
    * - ``positive-int``, ``negative-int``, ``non-negative-int``, ``non-positive-int``, ``non-zero-int``
      - The respective integers
    * - ``float``, ``double``
      - Finite floating-point numbers
    * - ``bool``, ``boolean``, ``true``, ``false``, ``null``
      - The respective values
    * - ``string``
      - Strings of any Unicode characters, encoded as UTF-8
    * - ``non-empty-string``, ``non-falsy-string``, ``truthy-string``, ``numeric-string``, ``lowercase-string``
      - The respective strings
    * - ``42``, ``1.5``, ``'asc'``
      - The literal value
    * - ``array-key``
      - Integers and strings
    * - ``list<T>``, ``non-empty-list<T>``, ``T[]``
      - Lists of values of type ``T``
    * - ``array<V>``, ``array<K, V>``, ``non-empty-array<K, V>``
      - Arrays with keys of type ``K`` and values of type ``V``. The type of the keys must only allow integers and strings
    * - ``array``, ``list``, ``non-empty-array``, ``non-empty-list``
      - Arrays and lists of integers, strings, floating-point numbers, booleans, and ``null``
    * - ``array{name: string, age?: int}``, ``list{int, string}``
      - Arrays of this shape. Optional keys are sometimes left out
    * - ``?T``, ``T|null``
      - ``null`` or a value of type ``T``
    * - ``A|B``
      - A value of one of the types
    * - Enumerations
      - The cases of the enumeration
    * - ``DateTimeImmutable``, ``DateTimeInterface``, ``DateTime``
      - Points in time from the year 1900 to the year 2199, in UTC
    * - ``DateTimeZone``
      - Time zones
    * - Other classes
      - Objects that are generated by the generator that is :ref:`registered <property-based-testing.generating-arguments.classes>` for the class, or that are created with ``new``. The class must not be abstract and must have a public constructor

No generator can be derived from ``mixed``, ``object``, ``callable``, ``iterable``, ``resource``, ``scalar``, ``numeric``, ``class-string``, intersection types, interfaces, abstract classes, and classes without a public constructor.
Specify a generator with ``#[ForAll]`` for a parameter of such a type, or register one with ``#[GeneratorFor]`` for an interface or a class.
Variadic parameters are not supported.

Names of classes in doc comments are resolved like PHP resolves them, using the namespace and the ``use`` statements of the file.


.. _property-based-testing.reference.generators:

Generators
----------

The static methods of ``PHPUnit\Framework\PropertyBased\Generators`` create generators.
In the signatures shown here, ``ValueGenerator`` parameters are generators for the parts of a value.

**Scalar values**

``integers(int $minimum = PHP_INT_MIN, int $maximum = PHP_INT_MAX)``
    Integers from ``$minimum`` to ``$maximum``.

``floats(float $minimum = -PHP_FLOAT_MAX, float $maximum = PHP_FLOAT_MAX, bool $allowInfinity = false, bool $allowNan = false, bool $excludeMinimum = false, bool $excludeMaximum = false, bool $allowSubnormals = true)``
    Floating-point numbers from ``$minimum`` to ``$maximum``.

``booleans()``
    ``true`` and ``false``.

``strings(int $minimumLength = 0, ?int $maximumLength = null, ?string $alphabet = null, ?array $codePointRanges = null)``
    UTF-8 strings. The length is counted in characters.
    ``$alphabet`` is a string of the characters to choose from, for instance ``'01'``.
    ``$codePointRanges`` is a list of ranges of code points to choose from, for instance ``[[0x41, 0x5A], [0x30, 0x39]]``.
    Without either, the strings consist of any Unicode characters.

``bytes(int $minimumLength = 0, ?int $maximumLength = null)``
    Binary strings of any bytes.

``fromRegularExpression(string $regularExpression)``
    Strings that the regular expression matches as a whole, for instance ``'/\A[a-z]+-\d{2,4}\z/'``.
    Character classes, quantifiers, groups, alternation, and modifiers are supported. Lookahead and lookbehind assertions, backreferences, recursion, conditional subpatterns, inline options, and Unicode properties are not supported.

**Fixed sets of values**

``elements(mixed ...$values)``
    One of the given values.

``cases(string $enumeration)``
    The cases of the enumeration.

``constant(mixed $value)``
    Always the given value.

**Collections**

``lists(ValueGenerator $elements, int $minimumSize = 0, ?int $maximumSize = null, bool $recursive = false, bool $unique = false)``
    Lists of values of ``$elements``. With ``$unique``, no element of a list equals another one.

``arrays(ValueGenerator $keys, ValueGenerator $values, int $minimumSize = 0, ?int $maximumSize = null, bool $recursive = false)``
    Arrays with keys of ``$keys`` and values of ``$values``.

``tuples(ValueGenerator ...$generators)``
    Lists that consist of one value of each of the generators, in their order.

``records(array $generators, array $optionalKeys = [])``
    Arrays with the keys of ``$generators`` and a value of the respective generator for each key. The keys in ``$optionalKeys`` are sometimes left out.

**Combinations**

``combine(Closure $combiner, ValueGenerator $first, ...)``
    What ``$combiner`` returns when it is called with one value of each of the generators.

``nullable(ValueGenerator $generator, bool $recursive = false)``
    ``null`` or a value of the generator.

``oneOf(ValueGenerator ...$generators)``
    A value of one of the generators.

``frequency(array ...$weightedGenerators)``
    A value of one of the generators, each of which is chosen with a probability that is proportional to its weight: ``Generators::frequency([9, Generators::integers()], [1, Generators::constant(null)])``.

``objects(string $className)``
    Objects of the class, generated as for a parameter of this type.

``fromType(string $type)``
    Values of the type, generated as for a parameter with this type in its ``@param`` tag.

**Recursive values**

``recursive(ValueGenerator $base, Closure $extend, int $maximumDepth = 5)``
    The values of ``$base`` and the values that ``$extend`` builds from them, nested up to ``$maximumDepth`` levels.
    ``$extend`` is passed a generator for the values of one level and returns a generator for the values of the next level.
    This generates integers, lists of integers, lists of lists of integers, and so on:

    .. code-block:: php

       Generators::recursive(
           Generators::integers(),
           static fn (ValueGenerator $values): ValueGenerator => Generators::lists($values),
       );

``deferred(Closure $generator)``
    The values of the generator that ``$generator`` returns, which is only called when the first value is generated.
    This way, a generator can refer to itself.

When a generator that you write for a class generates collections or nullable values that contain objects of the same class, pass ``recursive: true`` to ``lists()``, ``arrays()``, and ``nullable()``.
The generated values are then kept small.
Generators that are derived from types do this on their own.

**Values of common formats**

``uuids(array $versions = [1, 2, 3, 4, 5, 6, 7, 8], bool $nilAndMax = false)``
    UUIDs of the given versions as strings.

``ipv4Addresses(string $network = '0.0.0.0/0')``, ``ipv6Addresses(string $network = '::/0')``
    IP addresses of the network as strings, many of them from ranges that have a special purpose, such as private networks and loopback addresses.

``domainNames(int $maximumLength = 253)``
    Domain names.

``emailAddresses(bool $quotedLocalParts = false, bool $ipAddressLiterals = false)``
    Email addresses.

``urls(array $schemes = ['http', 'https'], bool $userInformation = false)``
    URLs with a host, and optionally with a port, a path, a query, and a fragment.

``dateTimes(?DateTimeInterface $minimum = null, ?DateTimeInterface $maximum = null, array $timezones = ['UTC'], bool $microseconds = false)``
    Points in time as ``DateTimeImmutable`` objects.
    Half of them are points in time that are known to cause trouble: leap days, the first and the last second of a month or a year, and the transitions between standard time and daylight saving time.

``timezones()``
    Identifiers of time zones as strings, such as ``Europe/Berlin``.

Each of these generators can be further refined with ``map()``, ``filter()``, and ``flatMap()``, see :ref:`property-based-testing.generating-arguments.generators`.
These three methods must not be called with named arguments.


.. _property-based-testing.reference.methods:

Methods for the body of a property
----------------------------------

These methods of ``PHPUnit\Framework\TestCase`` can only be called while a property is run.

``assume(bool $condition): void``
    :ref:`Rejects <property-based-testing.generating-arguments.assume>` the current example when ``$condition`` is ``false``.

``draw(ValueGenerator $generator, ?string $label = null): mixed``
    :ref:`Generates a value <property-based-testing.generating-arguments.draw>` in the body of the property.

``classify(string $label): void``
    :ref:`Classifies <property-based-testing.knowing-what-was-tested>` the current example with a label.

``observe(string $label, float|int $value): void``
    :ref:`Records a number <property-based-testing.knowing-what-was-tested>` for the statistics of the property.

``target(string $label, float|int $score): void``
    :ref:`Targets a score <property-based-testing.finding-rare-failures>`, so that examples with higher scores are searched for.

``runStateMachine(object $machine, int $maximumNumberOfSteps = 50): void``
    :ref:`Runs a state machine <property-based-testing.state-machines>`.

The attributes that are used for property-based testing are documented in the :ref:`appendix on attributes <appendixes.attributes.property-based-testing>`.


.. _property-based-testing.best-practices:

Best Practices
==============

1. **Start with a simple property.** "Does not raise an exception" and "decoding reverses encoding" find real bugs. Add more specific properties as you understand the code better.
2. **Generate what you need, do not reject what you do not need.** Prefer a narrower type or ``map()`` over ``assume()`` and ``filter()``.
3. **Declare precise types.** A type such as ``int<1, 100>`` or ``non-empty-list<positive-int>`` is documentation, input for static analysis, and a generator at the same time.
4. **Register generators for the value objects of your domain** in one class and share it with ``#[UseGenerators]``.
5. **Look at the statistics** before you trust a property that passes, and require the classifications that you depend on.
6. **Keep properties deterministic.** The outcome of a property must only depend on its arguments.
7. **Keep properties fast.** A property is run at least 100 times. Use test doubles for slow dependencies, as in any other test.
8. **Turn counterexamples into explicit examples**, so that a bug that was fixed stays fixed.
9. **Do not repeat the implementation in the test.** A property that computes the expected result in the same way as the code under test proves nothing. State a relation, an invariant, or a comparison with something simpler instead.
10. **Use state machines for objects with state** instead of writing loops over random operations by hand. The steps are shrunk, and the counterexample is reported as code.


.. _property-based-testing.limitations:

Limitations
===========

* A property that passes was checked for the examples that were generated. This is evidence, not proof, unless the property has :ref:`so few possible examples <property-based-testing.knowing-what-was-tested.exhaustion>` that all of them were checked
* The minimal counterexample is not always the smallest one that exists. In particular, a property that only fails for values that are far apart and do not follow a simple pattern, such as square numbers, is reported with a counterexample that is larger than necessary
* A collection reaches the maximum size that is configured for it only rarely. A bug that requires exactly the maximum size can need more than 100 examples to be found
* ``Generators::timezones()`` generates identifiers of time zones as strings, whereas a parameter of type ``DateTimeZone`` gets objects
