

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

.. code-block:: bash

    $ wget https://phar.phpunit.de/phpunit-|version|.phar
    $ php phpunit-|version|.phar --version
    PHPUnit x.y.z by Sebastian Bergmann and contributors.

It is a common practice to make the PHAR executable:

.. code-block:: bash

    $ wget https://phar.phpunit.de/phpunit-|version|.phar
    $ chmod +x phpunit-|version|.phar
    $ ./phpunit-|version|.phar --version
    PHPUnit x.y.z by Sebastian Bergmann and contributors.

.. _installation.phar.verification:

Verifying PHPUnit PHAR Releases
===============================

All official releases of code distributed by the PHPUnit Project are
signed by the release manager for the release. PGP signatures and SHA256
hashes are available for verification on `phar.phpunit.de <https://phar.phpunit.de/>`_.

The following example details how release verification works. We start
by downloading :file:`phpunit.phar` as well as its
detached PGP signature :file:`phpunit.phar.asc`:

.. code-block:: bash

    $ wget https://phar.phpunit.de/phpunit-|version|.phar
    $ wget https://phar.phpunit.de/phpunit-|version|.phar.asc

We want to verify PHPUnit's PHP Archive (:file:`phpunit-|version|.phar`)
against its detached signature (:file:`phpunit-|version|.phar.asc`):

.. code-block:: bash

    $ gpg phpunit-|version|.phar.asc
    gpg: Signature made Sat 19 Jul 2014 01:28:02 PM CEST using RSA key ID 6372C20A
    gpg: Can't check signature: public key not found

We don't have the release manager's public key (``6372C20A``)
in our local system. In order to proceed with the verification we need
to retrieve the release manager's public key from a key server. One such
server is :file:`pgp.uni-mainz.de`. The public key servers
are linked together, so you should be able to connect to any key server.

.. code-block:: bash

    $ gpg --keyserver pgp.uni-mainz.de --recv-keys 0x4AA394086372C20A
    gpg: requesting key 6372C20A from hkp server pgp.uni-mainz.de
    gpg: key 6372C20A: public key "Sebastian Bergmann <sb@sebastian-bergmann.de>" imported
    gpg: Total number processed: 1
    gpg:               imported: 1  (RSA: 1)

Now we have received a public key for an entity known as "Sebastian
Bergmann <sb@sebastian-bergmann.de>". However, we have no way of
verifying this key was created by the person known as Sebastian
Bergmann. But, let's try to verify the release signature again.

.. code-block:: bash

    $ gpg phpunit-|version|.phar.asc
    gpg: Signature made Sat 19 Jul 2014 01:28:02 PM CEST using RSA key ID 6372C20A
    gpg: Good signature from "Sebastian Bergmann <sb@sebastian-bergmann.de>"
    gpg:                 aka "Sebastian Bergmann <sebastian@php.net>"
    gpg:                 aka "Sebastian Bergmann <sebastian@thephp.cc>"
    gpg:                 aka "Sebastian Bergmann <sebastian@phpunit.de>"
    gpg:                 aka "Sebastian Bergmann <sebastian.bergmann@thephp.cc>"
    gpg:                 aka "[jpeg image of size 40635]"
    gpg: WARNING: This key is not certified with a trusted signature!
    gpg:          There is no indication that the signature belongs to the owner.
    Primary key fingerprint: D840 6D0D 8294 7747 2937  7831 4AA3 9408 6372 C20A

At this point, the signature is good, but we don't trust this key. A
good signature means that the file has not been tampered. However, due
to the nature of public key cryptography, you need to additionally
verify that key ``6372C20A`` was created by the real
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

Simply add a (development-time) dependency on
``phpunit/phpunit`` to your project's
``composer.json`` file if you use `Composer <https://getcomposer.org/>`_ to manage the
dependencies of your project:

.. code-block:: bash

    composer require --dev phpunit/phpunit ^|version|

.. _installation.global:

Global Installation
###################

Please note that it is not recommended to install PHPUnit globally, as ``/usr/bin/phpunit`` or
``/usr/local/bin/phpunit``, for instance.

Instead, PHPUnit should be managed as a project-local dependency.

Either put the PHAR of the specific PHPUnit version you need in your project's
``tools`` directory (which should be managed by PHIVE) or depend on the specific PHPUnit version
you need in your project's ``composer.json`` if you use Composer.
