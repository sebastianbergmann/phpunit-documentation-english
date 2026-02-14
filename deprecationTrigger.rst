The ``<deprecationTrigger>`` Element
=====================================

The ``<deprecationTrigger>`` element is an optional child of the ``<source>``
element in PHPUnit's XML configuration file. It allows you to declare functions
and methods that act as wrappers around PHP's ``trigger_error()`` for emitting
``E_USER_DEPRECATED`` errors. This enables PHPUnit to correctly attribute
deprecations to the code that *called* the wrapper rather than to the wrapper
itself.

.. contents:: On this page
   :local:


Background
----------

PHPUnit classifies every deprecation triggered during a test into one of four
categories based on where in the call stack the deprecation originates relative
to the project's first-party source code (the directories and files listed in
``<source><include>``):

``self``
    The deprecation was triggered by first-party code calling into first-party
    code.

``direct``
    The deprecation was triggered by first-party code calling into third-party
    code.

``indirect``
    The deprecation was triggered by third-party code without any first-party
    code on the call path between the test and the trigger.

``test``
    The deprecation was triggered directly in the test file itself.

This classification drives several features: the ``ignoreSelfDeprecations``,
``ignoreDirectDeprecations``, and ``ignoreIndirectDeprecations`` attributes on
the ``<source>`` element, as well as baseline generation and the deprecation
details shown in test output.

Many libraries do not call ``trigger_error()`` directly but instead use a
dedicated helper function or method. For example, a library might provide a
``trigger_deprecation()`` function:

.. code-block:: php

    namespace Vendor\Library;

    function trigger_deprecation(string $message): void
    {
        @trigger_error($message, E_USER_DEPRECATED);
    }

Without configuration, PHPUnit sees this helper function on the stack and uses
its location (inside the library's code) to classify the deprecation. This can
lead to incorrect attribution -- a deprecation triggered from first-party code
through the helper may be classified as ``indirect`` instead of ``direct``.

The ``<deprecationTrigger>`` element solves this problem by telling PHPUnit
which functions and methods are deprecation-triggering wrappers. PHPUnit then
filters these frames out of the stack trace before classifying the deprecation.


Configuration
-------------

The ``<deprecationTrigger>`` element accepts any number of ``<function>`` and
``<method>`` child elements. Each element's text content must be a
fully-qualified name.

For functions, provide the fully-qualified function name (including namespace):

.. code-block:: xml

    <source>
        <include>
            <directory>src</directory>
        </include>

        <deprecationTrigger>
            <function>Vendor\Library\trigger_deprecation</function>
        </deprecationTrigger>
    </source>

For methods, provide the fully-qualified class name and method name separated
by ``::``:

.. code-block:: xml

    <source>
        <include>
            <directory>src</directory>
        </include>

        <deprecationTrigger>
            <method>Vendor\Library\DeprecationTrigger::triggerDeprecation</method>
        </deprecationTrigger>
    </source>

Multiple functions and methods can be combined in a single
``<deprecationTrigger>`` element:

.. code-block:: xml

    <source>
        <include>
            <directory>src</directory>
        </include>

        <deprecationTrigger>
            <function>Vendor\Library\trigger_deprecation</function>
            <method>Vendor\Library\DeprecationTrigger::triggerDeprecation</method>
        </deprecationTrigger>
    </source>


Effects
-------

Configuring deprecation triggers has three effects:

1. **Correct classification of deprecations.** When PHPUnit analyses the call
   stack to determine whether a deprecation is ``self``, ``direct``, or
   ``indirect``, it removes frames belonging to configured triggers before
   making the determination. This means the classification reflects the code
   that *called* the wrapper, not the wrapper itself.

2. **Correct file and line attribution.** For ``E_USER_DEPRECATED`` errors, PHP
   reports the file and line of the ``trigger_error()`` call (inside the
   wrapper). When a deprecation trigger is configured, PHPUnit replaces the
   reported file and line with the location of the call to the wrapper function
   or method. This results in more useful output when displaying deprecation
   details and more accurate baseline matching.

3. **Correct baseline handling.** Because the reported file and line are
   adjusted to point to the call site of the wrapper rather than the wrapper
   itself, baselines generated with ``--generate-baseline`` will record the
   correct location. This ensures that the baseline remains stable and that
   ``<source baseline="...">`` can correctly suppress known deprecations.


Validation
----------

At startup, PHPUnit validates each configured deprecation trigger:

- For each ``<function>`` element, PHPUnit checks that the function is declared
  (using ``function_exists()``). If it is not, a test runner warning is emitted:

  ``Function <name> cannot be configured as a deprecation trigger because it is not declared``

- For each ``<method>`` element, PHPUnit first checks that the value is in
  ``ClassName::methodName`` format. If it is not, a warning is emitted:

  ``<value> cannot be configured as a deprecation trigger because it is not in ClassName::methodName format``

  If the format is correct, PHPUnit checks that both the class and the method
  exist. If they do not, a warning is emitted:

  ``Method <ClassName>::<methodName> cannot be configured as a deprecation trigger because it is not declared``

Invalid entries are silently skipped after the warning is emitted and do not
prevent the test suite from running.


Example
-------

Consider a project with the following structure:

- ``src/`` contains first-party code (configured in ``<source><include>``)
- ``vendor/`` contains third-party code, including a helper function
  ``Vendor\trigger_deprecation()`` that wraps ``trigger_error()``

A third-party class calls the helper:

.. code-block:: php

    namespace Vendor;

    class ThirdPartyClass
    {
        public function method(): void
        {
            trigger_deprecation('this feature is deprecated');
        }
    }

First-party code calls the third-party class:

.. code-block:: php

    namespace App;

    use Vendor\ThirdPartyClass;

    class MyClass
    {
        public function doSomething(): void
        {
            (new ThirdPartyClass)->method();
        }
    }

**Without** ``<deprecationTrigger>`` configured, PHPUnit sees the
``trigger_deprecation()`` helper in the stack trace. Since the helper is in
third-party code and was called by third-party code
(``ThirdPartyClass::method()``), the deprecation may be classified as
``indirect`` even though first-party code initiated the call chain.

**With** ``<deprecationTrigger>`` configured:

.. code-block:: xml

    <source>
        <include>
            <directory>src</directory>
        </include>

        <deprecationTrigger>
            <function>Vendor\trigger_deprecation</function>
        </deprecationTrigger>
    </source>

PHPUnit filters the ``trigger_deprecation()`` frame out of the stack trace.
It then sees that ``ThirdPartyClass::method()`` triggered the deprecation and
that it was called from first-party code (``MyClass::doSomething()``),
correctly classifying the deprecation as ``direct``.
