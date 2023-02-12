

.. _appendixes.attributes:

**********
Attributes
**********

Prior to PHPUnit 10, annotations in special PHP comments, so-called "DocBlocks" or "doc-comments",
were the only means of attaching metadata to code units. These annotations are documented in
:ref:`another appendix <appendixes.annotations>`.

PHP 8 introduced `attributes <https://wiki.php.net/rfc/attributes_v2>`_ as "a form of structured,
syntactic metadata to declarations of classes, properties, functions, methods, parameters and
constants. Attributes allow to define configuration directives directly embedded with the
declaration of that code."

PHPUnit will first look for metadata in attributes before it looks for annotations in comments.
When metadata is found in attributes, metadata in comments is ignored.

The attributes supported by PHPUnit are all declared in the ``PHPUnit\Framework\Attributes``
namespace. They are documented in this appendix.

``Test``
========

...

``TestDox``
===========

...

``DoesNotPerformAssertions``
============================

...

Code Coverage
=============

``CoversClass``
---------------

...


``CoversFunction``
------------------

...


``CoversNothing``
-----------------

...


``UsesClass``
-------------

...


``UsesFunction``
----------------

...


``CodeCoverageIgnore``
----------------------

...


Data Provider
=============

``DataProvider``
----------------

...


``DataProviderExternal``
------------------------

...


``TestWith``
------------

...


``TestWithJson``
----------------

...


Test Dependencies
=================

``Depends``
-----------

...


``DependsUsingDeepClone``
-------------------------

...


``DependsUsingShallowClone``
----------------------------

...

``DependsExternal``
-------------------

...


``DependsExternalUsingDeepClone``
---------------------------------

...


``DependsExternalUsingShallowClone``
------------------------------------

...


``DependsOnClass``
------------------

...


``DependsOnClassUsingDeepClone``
--------------------------------

...


``DependsOnClassUsingShallowClone``
-----------------------------------

...


Test Groups
===========

``Group``
---------

...


``Small``
---------

...


``Medium``
----------

...


``Large``
---------

...


``Ticket``
----------

...


Template Methods
================

``BeforeClass``
---------------

...


``Before``
----------

...


``PreCondition``
----------------

...


``PostCondition``
-----------------

...


``After``
---------

...


``AfterClass``
--------------

...


Test Isolation
==============

``BackupGlobals``
-----------------

...


``ExcludeGlobalVariableFromBackup``
-----------------------------------

...


``BackupStaticProperties``
--------------------------

...


``ExcludeStaticPropertyFromBackup``
-----------------------------------

...


``RunInSeparateProcess``
------------------------

...


``RunTestsInSeparateProcesses``
-------------------------------

...


``RunClassInSeparateProcess``
-----------------------------

...


``PreserveGlobalState``
-----------------------

...


Skipping Tests
==============

``RequiresPhp``
---------------

...


``RequiresPhpExtension``
------------------------

...


``RequiresSetting``
-------------------

...


``RequiresPhpunit``
-------------------

...


``RequiresFunction``
--------------------

...


``RequiresMethod``
------------------

...


``RequiresOperatingSystem``
---------------------------

...


``RequiresOperatingSystemFamily``
---------------------------------

...
