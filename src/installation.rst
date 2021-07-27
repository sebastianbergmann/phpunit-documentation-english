

.. _installation:

==================
Installing PHPUnit
==================

.. _installation.requirements:

Requirements
############

PHPUnit |version| requires PHP 7.2; using the latest version of PHP is highly
recommended.

PHPUnit requires the `dom <http://php.net/manual/en/dom.setup.php>`_ and `json <http://php.net/manual/en/json.installation.php>`_
extensions, which are normally enabled by default.

PHPUnit also requires the
`pcre <http://php.net/manual/en/pcre.installation.php>`_,
`reflection <http://php.net/manual/en/reflection.installation.php>`_,
and `spl <http://php.net/manual/en/spl.installation.php>`_
extensions. These standard extensions are enabled by default and cannot be
disabled without patching PHP's build system and/or C sources.

The code coverage report feature requires the
`Xdebug <http://xdebug.org/>`_ (2.7.0 or later) and
`tokenizer <http://php.net/manual/en/tokenizer.installation.php>`_
extensions.
Generating XML reports requires the
`xmlwriter <http://php.net/manual/en/xmlwriter.installation.php>`_
extension.

.. _installation.configuration:

Recommended PHP configuration
#############################

For the output of PHPUnit to be most informative, it is recommended to have
the following configuration set in the ``php.ini`` file used for test runs:

.. parsed-literal::

    memory_limit=-1
    error_reporting=-1
    log_errors_max_len=0
    zend.assertions=1
    assert.exception=1
    xdebug.show_exception_trace=0

.. _installation.phar:

PHP Archive (PHAR)
##################

The easiest way to obtain PHPUnit is to download a `PHP Archive (PHAR) <http://php.net/phar>`_ that has all required
(as well as some optional) dependencies of PHPUnit bundled in a single
file.

The `phar <http://php.net/manual/en/phar.installation.php>`_
extension is required for using PHP Archives (PHAR).

If the `Suhosin <http://suhosin.org/>`_ extension is
enabled, you need to allow execution of PHARs in your
``php.ini``:

.. code-block:: bash

    suhosin.executor.include.whitelist = phar

The PHPUnit PHAR can be used immediately after download:

.. parsed-literal::

    $ curl -LO https://phar.phpunit.de/phpunit-|version|.phar
    $ php phpunit-|version|.phar --version
    PHPUnit x.y.z by Sebastian Bergmann and contributors.

It is a common practice to make the PHAR executable:

.. parsed-literal::

    $ curl -LO https://phar.phpunit.de/phpunit-|version|.phar
    $ chmod +x phpunit-|version|.phar
    $ ./phpunit-|version|.phar --version
    PHPUnit x.y.z by Sebastian Bergmann and contributors.

.. _installation.phar.implementation-details:

PHAR Implementation Details
===========================

To avoid problems that occur when the code under test shares dependencies with PHPUnit but requires different versions than the ones bundled in the PHAR, the following measures have been implemented.

With the exception of classes such as `PHPUnit\\Framework\\TestCase` that are part of PHPUnit's public API, all units of code bundled in PHPUnit's PHAR distribution, including all dependencies such as vendor directories, are moved to a new and distinct namespace.

PHPUnit's PHAR distribution does not use dynamic autoloading to load the bundled units of code. Instead, all units of code bundled in the PHAR are loaded on startup.

.. _installation.phar.verification:

Verifying PHPUnit PHAR Releases
===============================

All official releases of code distributed by the PHPUnit Project are
signed by the release manager for the release. PGP signatures and SHA256
hashes are available for verification on `phar.phpunit.de <https://phar.phpunit.de/>`_.

The following example details how release verification works. We start
by downloading :file:`phpunit.phar` as well as its
detached PGP signature :file:`phpunit.phar.asc`:

.. parsed-literal::

    $ curl -LO https://phar.phpunit.de/phpunit-|version|.phar
    $ curl -LO https://phar.phpunit.de/phpunit-|version|.phar.asc

We want to verify PHPUnit's PHP Archive (:file:`phpunit-x.y.phar`)
against its detached signature (:file:`phpunit-x.y.phar.asc`):

.. parsed-literal::

    $ gpg --verify phpunit-|version|.phar.asc
    gpg: assuming signed data in 'phpunit-8.5.phar'
    gpg: Signature made Mon Jul 19 06:13:42 2021 UTC
    gpg:                using RSA key D8406D0D82947747293778314AA394086372C20A
    gpg:                issuer "sb@sebastian-bergmann.de"
    gpg: Can't check signature: No public key

We do not have the release manager's public key in our local system. In order to proceed with the verification we need to import this key:

.. parsed-literal::

    $ curl --silent https://sebastian-bergmann.de/gpg.asc | gpg --import
    gpg: key 4AA394086372C20A: 452 signatures not checked due to missing keys
    gpg: key 4AA394086372C20A: public key "Sebastian Bergmann <sb@sebastian-bergmann.de>" imported
    gpg: Total number processed: 1
    gpg:               imported: 1
    gpg: no ultimately trusted keys found

Now we have imported a public key for an entity known as "Sebastian
Bergmann <sb@sebastian-bergmann.de>". However, we have no way of
verifying this key was created by the person known as Sebastian
Bergmann. But, let's try to verify the release signature again.

.. parsed-literal::

    $ gpg --verify phpunit-|version|.phar.asc
    gpg: assuming signed data in 'phpunit-|version|.phar'
    gpg: Signature made Mon Jul 19 06:13:42 2021 UTC
    gpg:                using RSA key D8406D0D82947747293778314AA394086372C20A
    gpg:                issuer "sb@sebastian-bergmann.de"
    gpg: Good signature from "Sebastian Bergmann <sb@sebastian-bergmann.de>" [unknown]
    gpg:                 aka "Sebastian Bergmann <sebastian@thephp.cc>" [unknown]
    gpg:                 aka "Sebastian Bergmann <sebastian@phpunit.de>" [unknown]
    gpg:                 aka "Sebastian Bergmann <sebastian@php.net>" [unknown]
    gpg:                 aka "Sebastian Bergmann <sebastian.bergmann@thephp.cc>" [unknown]
    gpg:                 aka "[jpeg image of size 40635]" [unknown]
    gpg: WARNING: This key is not certified with a trusted signature!
    gpg:          There is no indication that the signature belongs to the owner.
    Primary key fingerprint: D840 6D0D 8294 7747 2937  7831 4AA3 9408 6372 C20A

At this point, the signature is good, but we do not trust this key. A
good signature means that the file has not been tampered. However, due
to the nature of public key cryptography, you need to additionally
verify that the key you just imported was created by the real
Sebastian Bergmann.

Any attacker can create a public key and upload it to the public key
servers. They can then create a malicious release signed by this fake
key. Then, if you tried to verify the signature of this corrupt release,
it would succeed because the key was not the "real" key. Therefore, you
need to validate the authenticity of this key. Validating the
authenticity of a public key, however, is outside the scope of this
documentation.

Manually verifying the authenticity and integrity of a PHPUnit PHAR using
GPG is tedious. This is why PHIVE, the PHAR Installation and Verification
Environment, was created. You can learn about PHIVE on its `website <https://phar.io/>`_

.. _installation.composer:

Composer
########

Add a (development-time) dependency on
``phpunit/phpunit`` to your project's
``composer.json`` file if you use `Composer <https://getcomposer.org/>`_ to manage the
dependencies of your project:

.. parsed-literal::

    composer require --dev phpunit/phpunit ^\ |version|

.. _installation.global:

Global Installation
###################

Please note that it is not recommended to install PHPUnit globally, as ``/usr/bin/phpunit`` or
``/usr/local/bin/phpunit``, for instance.

Instead, PHPUnit should be managed as a project-local dependency.

Either put the PHAR of the specific PHPUnit version you need in your project's
``tools`` directory (which should be managed by PHIVE) or depend on the specific PHPUnit version
you need in your project's ``composer.json`` if you use Composer.

Webserver
#########

PHPUnit is a framework for writing as well as a commandline tool for running tests. Writing and running tests is a development-time activity. There is no reason why PHPUnit should be installed on a webserver.

**If you upload PHPUnit to a webserver then your deployment process is broken. On a more general note, if your** ``vendor`` **directory is publicly accessible on your webserver then your deployment process is also broken.**

Please note that if you upload PHPUnit to a webserver "bad things" may happen. `You have been warned. <https://thephp.cc/news/2020/02/phpunit-a-security-risk>`_
