

.. _installation:

************
Installation
************

PHP is a general-purpose programming language. While it originally only supported the paradigm of procedural programming,
most PHP code that is written today leverages the language's capabilities for object-oriented programming. As it is
especially suited for web development, you probably made your first contact with PHP in an environment where PHP code
is executed on a web server.

PHPUnit provides a framework for writing tests as well as a command-line tool for running these tests. Before we discuss
obtaining and using PHPUnit, let us have a look at installing and configuring the PHP command-line interpreter.

PHPUnit 10 requires PHP 8.1; using the latest version of PHP is highly recommended.


.. _installation.php-on-the-command-line:

PHP on the Command-Line
=======================

We start by installing PHP's command-line interpreter as well as the PHP extensions required to use PHPUnit.


Installing the PHP Command-Line Interpreter
-------------------------------------------

Fedora
^^^^^^

At the time of writing, Fedora 38 is the current version of this Linux distribution. It ships with PHP 8.2 by default.
Here is how you install PHP's command-line interpreter together with the extensions required for PHPUnit:

.. code::

    sudo dnf install php-cli \
                     php-json \
                     php-mbstring \
                     php-process \
                     php-xml \
                     php-pecl-pcov \
                     php-pecl-xdebug

If you use an older version of Fedora then you should have a look at the `package repository maintained by
Remi Collet <https://rpms.remirepo.net/>`_.


Debian
^^^^^^

At the time of writing, Debian 12 is the current version of this Linux distribution. It ships with PHP 8.2 by default.
Here is how you install PHP's command-line interpreter together with the extensions required for PHPUnit:

.. code::

    sudo apt install php-cli \
                     php-json \
                     php-mbstring \
                     php-xml \
                     php-pcov \
                     php-xdebug

If you use an older version of Debian then you should have a look at the `package repository <https://deb.sury.org/>`_
maintained by Ondřej Surý.


Ubuntu
^^^^^^

At the time of writing, Ubuntu 23.04 is the current version of this Linux distribution. It ships with PHP 8.1 by default.
Here is how you install PHP's command-line interpreter together with the extensions required for PHPUnit:

.. code::

    sudo apt install php-cli \
                     php-json \
                     php-mbstring \
                     php-xml \
                     php-pcov \
                     php-xdebug

If you use an older version of Ubuntu then you should have a look at the `package repository <https://deb.sury.org/>`_
maintained by Ondřej Surý.


macOS
^^^^^

The two most common ways to install PHP on macOS are using `Homebrew <https://brew.sh/>`_ and `MacPorts <https://www.macports.org/>`_.
The instructions given below assume that you have Homebrew or MacPorts already set up.

Homebrew
""""""""

If you use Homebrew, then the following command will install PHP 8.1:

.. code::

    brew install php@81

If you install PHP 8.1 with Homebrew, the following extensions required by PHPUnit are already installed and enabled by default:

- ``dom``
- ``json``
- ``libxml``
- ``mbstring``
- ``xml``
- ``xmlwriter``

If you want to collect code coverage information, you need to additionally install and enable one of the following extensions:

- ``pcov``
- ``xdebug``

If you use Homebrew, then the following command will install and enable the ``pcov`` extension:

.. code::

  pecl install pcov

If you use Homebrew, then the following command will install and enable the ``xdebug`` extension:

.. code::

  pecl install xdebug

MacPorts
""""""""

If you use MacPorts, then the following command will install PHP 8.1:

.. code::

    sudo port install php81

If you install PHP 8.1 with MacPorts, the following extensions required by PHPUnit are already installed and enabled by default:

- ``dom``
- ``json``
- ``libxml``
- ``xml``
- ``xmlwriter``

You need to additionally install and enable the following extension:

- ``mbstring``

If you want to collect code coverage information, you need to additionally install and enable one of the following extensions:

- ``pcov``
- ``xdebug``

Windows
^^^^^^^

Native Binaries
"""""""""""""""

The PHP Project provides native binaries for Windows at `windows.php.net <https://windows.php.net/>`_.
Choose the appropriate binary package for your architecture (32-bit or 64-bit) and version of Windows and
follow the installation instructions given on this website.

Enable the ``mbstring`` extension by adding ``extension=mbstring`` to the ``php.ini`` configuration file used
by the PHP command-line interpreter.


Windows Subsystem for Linux
"""""""""""""""""""""""""""

The Windows Subsystem for Linux allows Linux binary executables (in ELF format) to be run on Windows 10 (or later).

Update to the latest version of Windows, install the latest version of Windows Subsystem for Linux, and install the
Linux distribution of your choice from the Microsoft Store.

Then follow the installation instructions in this chapter for the Linux distribution you chose.


Using the PHP Command-Line Interpreter
--------------------------------------

Now we have the PHP command-line interpreter set up, and it is time to learn how to use it.

With ``php --version`` we can verify that the PHP command-line interpreter, ``php``, is on the path, works, and check which version it is.


Configuring PHP for Development
-------------------------------

In this section we ensure that the PHP command-line interpreter is configured in such a way that we can properly use PHPUnit.

The configuration directives shown below should be added to your PHP configuration file. Using ``php --ini`` we can ask the PHP
command-line interpreter for the configuration file, or files, that is (are) being used.

We want to see all PHP errors, warnings, notices, etc. when we run our tests. The value used with ``error_reporting``
is a bitmask that can be used to toggle the reporting of the various types of errors supported by PHP. Setting this to ``-1``
ensures that we always see all errors:

.. code::

    error_reporting=-1

When something goes really wrong then we want to see the entire error message (which is truncated to 1024 characters by default):

.. code::

    log_errors_max_len=0

When Xdebug is loaded, we do not want it to print its exception traces while our tests are being executed:

.. code::

    xdebug.show_exception_trace=0

This is how you enable Xdebug's code coverage functionality:

.. code::

    xdebug.mode=coverage

Please note that the ``xdebug.mode`` configuration directive takes a comma-separated list of modes.
``coverage`` must be one of these modes for code coverage to work.

When the code we test contains ``assert()`` statements then we want them to be evaluated and to raise exceptions:

.. code::

    zend.assertions=1
    assert.exception=1

The collection of code coverage data and the generation of a code coverage report sometimes requires more memory
than PHP is allowed to use by default:

.. code::

    memory_limit=-1

It is recommended to only load Xdebug when it is needed, for instance when you want to use it for debugging or to collect code coverage data.

When it comes to collecting code coverage data and when you are interested only in line coverage, the
`PCOV <https://github.com/krakjoe/pcov>`_ extension is recommended over Xdebug for performance reasons.

Do not worry if terms such as "code coverage" or "line coverage" do not mean anything to you just yet. We will cover them in great detail later.


Installing PHPUnit
==================

PHP Archive (PHAR)
------------------

The recommended way to install and use PHPUnit is to download a distribution that is packaged as a PHP Archive (PHAR).
Releases of PHPUnit packaged as PHP archives are available on ``https://phar.phpunit.de/``.

At ``https://phar.phpunit.de/phpunit-10.phar``, for instance, you will always find the latest version of PHPUnit 10.
At ``https://phar.phpunit.de/phpunit-10.0.0.phar``, for instance, you will always find that specific version of PHPUnit.
At ``https://phar.phpunit.de/phpunit-snapshot.phar`` you will always find the latest development snapshot of PHPUnit.

Such a PHP archive has all required (as well as some optional) dependencies of PHPUnit bundled in a single file. The PHAR (``ext/phar``) extension is required if you want to use PHPUnit from a PHP archive.

Manual Download of PHAR
^^^^^^^^^^^^^^^^^^^^^^^

You can simply download a release of PHPUnit packaged as a PHP archive and immediately use it:

.. code::

    wget -O phpunit.phar https://phar.phpunit.de/phpunit-10.phar

.. code::

    php phpunit.phar --version
    PHPUnit 10.0.0 by Sebastian Bergmann and contributors.

It is a common practice to make the PHAR executable:

.. code::

    chmod +x phpunit.phar

Now you can directly run the PHAR:

.. code::

    ./phpunit.phar --version
    PHPUnit 10.0.0 by Sebastian Bergmann and contributors.

All official releases distributed by the PHPUnit Project are signed by the release manager for the release.
PGP signatures and SHA256 hashes are available for verification on ``https://phar.phpunit.de/``.

Here is an example of how you can manually verify a PHP archive of a PHPUnit release using its PGP signature:

.. code::

    wget -O phpunit.phar https://phar.phpunit.de/phpunit-10.phar
    wget -O phpunit.phar.asc https://phar.phpunit.de/phpunit-10.phar.asc
    gpg --keyserver pgp.uni-mainz.de --recv-keys 0x4AA394086372C20A
    gpg phpunit.phar.asc

It is a common practice to use different versions of PHPUnit on a per-project basis. This is achieved by putting a PHP archive of PHPUnit into your project directory. A typical directory structure for a PHP project looks like this:

.. code::

    ├── public
    ├── src
    ├── tests
    └── tools

The ``public`` directory contains the application's static assets (CSS, JavaScript, images, ...); it is the webserver's document root.

The ``src`` directory contains the application's PHP source code. The ``tests`` directory contains the application's test suite.

The ``tools`` directory contains tools such as PHPUnit packaged as PHP archives.

You can download PHPUnit's PHP archive to that ``tools`` directory manually, of course:

.. code::

    wget -O phpunit.phar https://phar.phpunit.de/phpunit-10.phar
    chmod +x phpunit.phar
    mv phpunit.phar tools

Installing PHPUnit with Phive
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You can use `Phive <https://phar.io/>`_, the *PHAR Installation and Verification Environment*,
to manage the PHAR-based tools of your PHP project.

This is how you install Phive:

.. code::

    wget https://phar.io/releases/phive.phar
    wget https://phar.io/releases/phive.phar.asc
    gpg --keyserver hkps.pool.sks-keyservers.net --recv-keys 0x9B2D5D79
    gpg --verify phive.phar.asc phive.phar
    chmod +x phive.phar
    mv phive.phar /usr/local/bin/phive

Once Phive is installed, PHPUnit can be installed like so:

.. code::

    phive install phpunit

After executing the command shown above the project's directory will look like this:

.. code::

    ├── phive.xml
    ├── public
    ├── src
    ├── tests
    └── tools
        └── phpunit -> ~/.phive/phars/phpunit-10.0.0.phar

Phive has downloaded the PHP archive for PHPUnit 10.0.0, placed it in a cache located in your home directory,
and created a symbolic link from there to ``tools/phpunit``.

You can now invoke the project-local installation of PHPUnit by running ``./tools/phpunit``:

.. code::

    ./tools/phpunit --version
    PHPUnit 10.0.0 by Sebastian Bergmann and contributors.

The ``.phive/phars.xml`` file that was generated in your project's root directory contains metadata about your project's tool dependencies:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <phive xmlns="https://phar.io/phive">
      <phar name="phpunit"
            version="^10.0" installed="10.0.0"
            location="./tools/phpunit" copy="true"/>
    </phive>

``.phive/phars.xml`` should be put under version control.

The ``^10.0`` is a semantic version constraint: Phive will always install the latest version of PHPUnit
that is compatible with PHPUnit 10.0.

Phive does not only provide a convenient way for installing, managing, and updating tools that are distributed as a PHP archive.
Phive also keeps you safe by automatically verifying the PGP signatures while downloading the PHAR files.

If you want to keep PHPUnit's PHP archive under version control, then you should use Phive's ``--copy`` option to copy the PHP
archive from its cache located in your home directory into your project's tools directory:

.. code::

    phive install --copy phpunit

After executing the command shown above the project's directory will look like this:

.. code::

    ├── phive.xml
    ├── public
    ├── src
    ├── tests
    └── tools
        └── phpunit

.. admonition:: Note

    Unfortunately, PhpStorm only recognizes a file as a PHP archive when it has the ``.phar`` suffix.
    This is remedied by creating a symbolic link: ``ln -s phpunit tools/phpunit.phar``.

Updating PHPUnit with Phive
^^^^^^^^^^^^^^^^^^^^^^^^^^^

``phive install phpunit`` adds a dependency on PHPUnit with a version constraint that uses the caret operator (``^``) for semantic versioning: ``version="^10.0"``.

With this configuration, Phive will always install the latest version of PHPUnit that is compatible with PHPUnit 10.0.

This ensures you "stay fresh" as long as PHPUnit 10 is the current stable version of PHPUnit and includes new minor versions such as PHPUnit 10.1. And when the time comes and PHPUnit 11 is released then Phive will not automatically and unexpectedly install it.


Updating to a new minor or patch version
""""""""""""""""""""""""""""""""""""""""

Consider the following situation: you use the semantic version constraint ``^9.6`` for PHPUnit in your
``.phive/phars.xml`` file and have PHPUnit 9.6.0 installed. Here is what your ``.phive/phars.xml`` file
currently looks like:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <phive xmlns="https://phar.io/phive">
      <phar name="phpunit"
            version="^9.6" installed="9.6.0"
            location="./tools/phpunit" copy="true"/>
    </phive>

Since you used ``phive update`` last, PHPUnit 9.6.3 became available. You can use the ``phive outdated``
command to check whether an update is available for any of your project's PHP archives that are managed
by Phive:

.. code::

    phive outdated
    Phive 0.15.2 - Copyright (C) 2015-2023 by Arne Blankerts, Sebastian Heuer and Contributors
    Found 1 outdated PHARs in phive.xml:

    Name       Version Constraint    Installed    Available

    phpunit    ^9.6                  9.6.0        9.6.3

Because PHPUnit 9.6.3 is a new patch version (and not a new major version), ``phive update``
will update from PHPUnit 9.6.0 to PHPUnit 9.6.3.


Updating to a new major version
"""""""""""""""""""""""""""""""

Consider the following situation:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <phive xmlns="https://phar.io/phive">
      <phar name="phpunit"
            version="^9.6" installed="9.6.3"
            location="./tools/phpunit" copy="true"/>
    </phive>

Now PHPUnit 10, a new major version, became available. However, running ``phive outdated`` does
not offer us the update to PHPUnit 10:

.. code::

    phive outdated
    Phive 0.15.2 - Copyright (C) 2015-2023 by Arne Blankerts, Sebastian Heuer and Contributors
    Congrats, no outdated phars found

.. admonition:: Note

    Unfortunately, the output of ``phive outdated`` is confusing when no new minor or patch
    versions are available, but a new major version is available.

This is because PHPUnit 10 is a new major version and updates to a new major version should be
an explicit operation following a conscious decision.

If you use semantic version constraints in your ``.phive/phars.xml`` file
(`and you should! <https://thephp.cc/articles/the-death-star-version-constraint?ref=phpunit>`_)
then you will have to manually update PHPUnit's version constraint when you want to update to
a new major version.

Here is what you should do: edit your project's ``.phive/phars.xml`` file and change ``^9.6``
to ``^10.0``:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <phive xmlns="https://phar.io/phive">
      <phar name="phpunit"
            version="^10.0" installed="9.6.3"
            location="./tools/phpunit" copy="true"/>
    </phive>

See how the output of ``phive outdated`` changes:

.. code::

    phive outdated
    Phive 0.15.2 - Copyright (C) 2015-2023 by Arne Blankerts, Sebastian Heuer and Contributors
    Found 1 outdated PHARs in phive.xml:

    Name       Version Constraint    Installed    Available

    phpunit    ^10.0                 9.6.3        10.0.7

Now we can run ``phive update`` and the new major version will be installed.


What is inside the PHAR?
^^^^^^^^^^^^^^^^^^^^^^^^

To avoid `problems <https://github.com/sebastianbergmann/phpunit/issues/2014>`_  that occur when the code under
test shares dependencies with PHPUnit but requires different versions than the ones bundled in the PHAR, a couple
of measures have been implemented.

Most units of code bundled in PHPUnit's PHAR distribution, including all dependencies such as vendor directories,
are moved to a new and distinct namespace, for instance. Classes that are part of PHPUnit's public API, for example
``PHPUnit\Framework\TestCase``, are exempt from this.

PHPUnit's PHAR does not use dynamic autoloading to load the bundled units of code. Instead, all units of code bundled
in the PHAR are loaded on startup.

Here is an `article <https://thephp.cc/articles/ready-or-not-here-it-comes?ref=phpunit>`_ that explains these
measures in more detail.

Sometimes you need to know exactly which versions of PHPUnit's dependencies are bundled in PHPUnit's PHAR distribution,
for example in the context of `software supply chain security <https://thephp.cc/presentations/the-php-stacks-supply-chain?ref=phpunit>`_.
For this purpose, PHPUnit's PHAR distribution offers additional CLI options that the Composer-installed test runner
does not have.

When PHPUnit's PHAR is invoked with the ``--manifest`` CLI option then it will print a plain-text manifest with
information about the versions of PHPUnit's dependencies that are bundled in the PHAR:

.. code::

    php phpunit-10.1.3.phar --manifest
    phpunit/phpunit: 10.1.3
    myclabs/deep-copy: 1.11.1
    nikic/php-parser: v4.15.4
    phar-io/manifest: 2.0.3
    phar-io/version: 3.2.1
    phpunit/php-code-coverage: 10.1.1
    phpunit/php-file-iterator: 4.0.2
    phpunit/php-invoker: 4.0.0
    phpunit/php-text-template: 3.0.0
    phpunit/php-timer: 6.0.0
    sebastian/cli-parser: 2.0.0
    sebastian/code-unit: 2.0.0
    sebastian/code-unit-reverse-lookup: 3.0.0
    sebastian/comparator: 5.0.0
    sebastian/complexity: 3.0.0
    sebastian/diff: 5.0.3
    sebastian/environment: 6.0.1
    sebastian/exporter: 5.0.0
    sebastian/global-state: 6.0.0
    sebastian/lines-of-code: 2.0.0
    sebastian/object-enumerator: 5.0.0
    sebastian/object-reflector: 3.0.0
    sebastian/recursion-context: 5.0.0
    sebastian/type: 4.0.0
    sebastian/version: 4.0.1
    theseer/tokenizer: 1.2.1

When PHPUnit's PHAR is invoked with the ``--sbom`` CLI option then it will print a Software Bill of Materials (SBOM)
in XML format with information about the versions of PHPUnit's dependencies that are bundled in the PHAR:

.. code::

    php phpunit-10.1.3.phar --sbom
    <?xml version="1.0"?>
    <bom xmlns="http://cyclonedx.org/schema/bom/1.4">
     <components>
      <component type="library">
       <group>phpunit</group>
       <name>phpunit</name>
       <version>10.1.3</version>
       <description>The PHP Unit Testing framework.</description>
       <licenses>
        <license>
         <id>BSD-3-Clause</id>
        </license>
       </licenses>
       <purl>pkg:composer/phpunit/phpunit@10.1.3</purl>
      </component>
      .
      .
      .


Composer
--------

Using a PHP Archive (PHAR) is the recommended way of installing PHPUnit, but it is not the only way.

You can add PHPUnit as a development-time dependency to your project using `Composer <https://getcomposer.org/>`_.

Installing PHPUnit with Composer
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The command shown below assumes that you have previously installed Composer and that its ``composer`` executable is on your ``$PATH``.
The installation of Composer is explained on the tool's website.

.. code::

    composer require --dev phpunit/phpunit

After executing the command shown above the project's directory will look like this:

.. code::

    ├── composer.json
    ├── composer.lock
    ├── public
    ├── src
    ├── tests
    └── vendor

The ``composer.json`` file contains metadata about the dependencies of your project, for instance.
This file must be put under version control.

The ``composer.lock`` file contains the list of the exact versions of the dependencies which were installed by Composer.
While technically not required, it is considered a best practice to put this file under version control.

The project-local installation of PHPUnit can be invoked like this:

.. code::

    ./vendor/bin/phpunit --version
    PHPUnit 10.0.0 by Sebastian Bergmann and contributors.


Updating PHPUnit with Composer
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

``composer require --dev phpunit/phpunit`` adds a development-time dependency on PHPUnit with a version constraint that uses the caret operator (``^``) for semantic versioning: ``"phpunit/phpunit": "^10.0"``.

With this configuration, Composer will always install the latest version of PHPUnit that is compatible with PHPUnit 10.0.

This ensures you "stay fresh" as long as PHPUnit 10 is the current stable version of PHPUnit and includes new minor versions such as PHPUnit 10.1. And when the time comes and PHPUnit 11 is released then Composer will not automatically and unexpectedly install it.

Updating to a new minor or patch version
""""""""""""""""""""""""""""""""""""""""

Consider the following situation:

.. code-block:: json

    {
        "require-dev": {
            "phpunit/phpunit": "^9.6"
        }
    }

Using the ``composer outdated`` command we can see that we have PHPUnit 9.6.0 in our project and that a new patch version is available:

.. code::

    composer outdated --minor-only
    Legend:
    ! patch or minor release available - update recommended
    ~ major release available - update possible

    Direct dependencies required in composer.json:
    phpunit/phpunit 9.6.0 ! 9.6.3 The PHP Unit Testing framework.

Because PHPUnit 9.6.3 is a new patch version, ``composer update`` will update from PHPUnit 9.6.0 to PHPUnit 9.6.3.


Updating to a new major version
"""""""""""""""""""""""""""""""

Consider the following situation:

.. code-block:: json

    {
        "require-dev": {
            "phpunit/phpunit": "^9.6"
        }
    }

Using the ``composer outdated`` command we can see that we have PHPUnit 9.6.3 in our project and that a new major version is available:

.. code::

    composer outdated
    Legend:
    ! patch or minor release available - update recommended
    ~ major release available - update possible

    Direct dependencies required in composer.json:
    phpunit/phpunit                    9.6.3  ~ 10.0.7 The PHP Unit Testing framework.

Because PHPUnit 10 is a new major version, ``composer update`` will not update from PHPUnit 9.6.3 to PHPUnit 10.0.7.
Updates to a new major version should be an explicit operation following a conscious decision.

If you use semantic version constraints in your ``composer.json`` file
(`and you should! <https://thephp.cc/articles/the-death-star-version-constraint?ref=phpunit>`_)
then you will have to manually update PHPUnit's version constraint when you want to update to
a new major version.

Here is what you should do: edit your project's ``composer.json`` file and change ``^9.6``
to ``^10.0``:

.. code-block:: json

    {
        "require-dev": {
            "phpunit/phpunit": "^10.0"
        }
    }

Now we can run ``composer update`` and the new major version will be installed.


PHAR or Composer?
-----------------

According to its own documentation, Composer "[e]nables you to declare the libraries you depend on" and "[f]inds out which versions of which packages can and need to be installed, and installs them (meaning it downloads them into your project)". This is exactly what you need -- and want -- for dealing with your project's dependencies that are required at runtime. It is, however, not what you want for your project's development-time dependencies, for instance tools for static analysis.

While Composer allows for the separate declaration of dependencies that are only required during development and dependencies that are actually required to run the software, the implementation of this separation is merely cosmetic: the entirety of both development-time dependencies and runtime dependencies is resolved to one installable set. This set of dependencies is then installed into the same ``vendor`` directory. What happens, for instance, when a tool that you install using Composer requires a version of a library that is not compatible with the version of that library that is required by another tool -- or even by your own software? Such a conflict cannot be resolved and Composer will abort the installation process.

The really frustrating thing about this situation is the fact that such a conflict is, in most cases, unwarranted. A static analysis tool, for instance, never loads or executes the code of your software (it only looks at it in order to reason about it). Therefore, the conflicting versions of the library -- one depended upon by your software, the other depended upon by the tool -- are never (tried to be) loaded in the same PHP process. Hence: no problem.

This is the primary reason why I do not use Composer to install a tool but instead use a PHP Archive (PHAR). The self-contained PHAR of a tool ensures that its dependencies cannot conflict with the actual software's dependencies.


Global Installation
-------------------

So far we have discussed how to install PHPUnit on a per-project basis using a PHP Archive (PHAR) -- manually as well as using Phive -- and Composer.

For the sake of completeness, we shall also discuss the possibility of installing PHPUnit globally. What we mean by that is having one global installation of PHPUnit where the command-line tool, ``phpunit``, is on your ``$PATH`` to make it globally available in all your projects.

A common approach for installing PHPUnit globally is to download a release of PHPUnit packaged as a PHP archive, make it executable, and put it into your ``$PATH``:

.. code::

    wget -O phpunit.phar https://phar.phpunit.de/phpunit-10.phar
    chmod +x phpunit.phar
    sudo mv phpunit.phar /usr/local/bin/phpunit
    phpunit --version
    PHPUnit 10.0.0 by Sebastian Bergmann and contributors.

Both Composer and Phive can be used to perform a global installation of PHPUnit.

Using such a global installation of PHPUnit is almost always a bad idea as the different projects you work on may require
different versions of PHPUnit, for instance.

It is therefore best to use a project-local installation of the version of PHPUnit that should be used for the project at hand.

Consequently, the package manager of your operating system should not be used to install PHPUnit as this would result in a global installation of PHPUnit.


Web Server
----------

PHPUnit is a framework for writing as well as a command-line tool for running tests.
Writing and running tests is a development-time activity.
There is no reason why PHPUnit should be installed on a web server.

If you put PHPUnit on a web server then your deployment process is broken.
On a more general note, if your ``vendor`` directory is publicly accessible on your web server then your deployment process is also broken.

`Please note that if you put PHPUnit on a web server "bad things" may happen. You have been warned. <https://thephp.cc/articles/phpunit-a-security-risk?ref=phpunit>`_

Make sure your deployment process does not make PHPUnit, or any other development tool, publicly accessible on a web server.
