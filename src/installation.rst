

.. _installation:

==================
Installing PHPUnit
==================

.. _installation.requirements:

Requirements
############

PHPUnit |version| requires PHP 7.1; using the latest version of PHP is highly
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
`Xdebug <http://xdebug.org/>`_ (2.5.0 or later) and
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

To globally install the PHAR:

.. code-block:: bash

    $  wget https://phar.phpunit.de/phpunit-|version|.phar
    $  chmod +x phpunit-|version|.phar
    $  sudo mv phpunit-|version|.phar /usr/local/bin/phpunit
    $  phpunit --version
    PHPUnit x.y.z by Sebastian Bergmann and contributors.

You may also use the downloaded PHAR file directly:

.. code-block:: bash

    $  wget https://phar.phpunit.de/phpunit-|version|.phar
    $  php phpunit-|version|.phar --version
    PHPUnit x.y.z by Sebastian Bergmann and contributors.

.. _installation.phar.windows:

Windows
=======

Globally installing the PHAR involves the same procedure as manually
`installing Composer on Windows <https://getcomposer.org/doc/00-intro.md#installation-windows>`_:

#.

   Create a directory for PHP binaries; e.g., :file:`C:\\bin`

#.

   Append ;C:\bin to your ``PATH``
   environment variable
   (`related help <http://stackoverflow.com/questions/6318156/adding-python-path-on-windows-7>`_)

#.

   Download `<https://phar.phpunit.de/phpunit-|version|.phar>`_ and
   save the file as :file:`C:\\bin\\phpunit.phar`

#.

   Open a command line (e.g.,
   press :kbd:`Windows`:kbd:`R`
   » type cmd
   » :kbd:`ENTER`)

#.

   Create a wrapping batch script (results in
   :file:`C:\\bin\\phpunit.cmd`):

   .. code-block:: bash

       C:\Users\username>  cd C:\bin
       C:\bin>  echo @php "%~dp0phpunit.phar" %* > phpunit.cmd
       C:\bin>  exit

#.

   Open a new command line and confirm that you can execute PHPUnit
   from any path:

   .. code-block:: bash

       C:\Users\username>  phpunit --version
       PHPUnit x.y.z by Sebastian Bergmann and contributors.

For Cygwin and/or MingW32 (e.g., TortoiseGit) shell environments, you
may skip step 5. above, simply save the file as
:file:`phpunit` (without :file:`.phar`
extension), and make it executable via
``chmod 775 phpunit``.

.. _installation.phar.verification:

Verifying PHPUnit PHAR Releases
===============================

All official releases of code distributed by the PHPUnit Project are
signed by the release manager for the release. PGP signatures and SHA1
hashes are available for verification on `phar.phpunit.de <https://phar.phpunit.de/>`_.

The following example details how release verification works. We start
by downloading :file:`phpunit.phar` as well as its
detached PGP signature :file:`phpunit.phar.asc`:

.. code-block:: bash

    wget https://phar.phpunit.de/phpunit.phar
    wget https://phar.phpunit.de/phpunit.phar.asc

We want to verify PHPUnit's PHP Archive (:file:`phpunit.phar`)
against its detached signature (:file:`phpunit.phar.asc`):

.. code-block:: bash

    gpg phpunit.phar.asc
    gpg: Signature made Sat 19 Jul 2014 01:28:02 PM CEST using RSA key ID 6372C20A
    gpg: Can't check signature: public key not found

We don't have the release manager's public key (``6372C20A``)
in our local system. In order to proceed with the verification we need
to retrieve the release manager's public key from a key server. One such
server is :file:`pgp.uni-mainz.de`. The public key servers
are linked together, so you should be able to connect to any key server.

.. code-block:: bash

    gpg --keyserver pgp.uni-mainz.de --recv-keys 0x4AA394086372C20A
    gpg: requesting key 6372C20A from hkp server pgp.uni-mainz.de
    gpg: key 6372C20A: public key "Sebastian Bergmann <sb@sebastian-bergmann.de>" imported
    gpg: Total number processed: 1
    gpg:               imported: 1  (RSA: 1)

Now we have received a public key for an entity known as "Sebastian
Bergmann <sb@sebastian-bergmann.de>". However, we have no way of
verifying this key was created by the person known as Sebastian
Bergmann. But, let's try to verify the release signature again.

.. code-block:: bash

    gpg phpunit.phar.asc
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

It may be prudent to create a shell script to manage PHPUnit installation
that verifies the GnuPG signature before running your test suite. For
example:

.. code-block:: bash

    #!/usr/bin/env bash
    clean=1 # Delete phpunit.phar after the tests are complete?
    aftercmd="php phpunit.phar --bootstrap bootstrap.php src/tests"
    gpg --fingerprint D8406D0D82947747293778314AA394086372C20A
    if [ $? -ne 0 ]; then
        echo -e "\033[33mDownloading PGP Public Key...\033[0m"
        gpg --recv-keys D8406D0D82947747293778314AA394086372C20A
        # Sebastian Bergmann <sb@sebastian-bergmann.de>
        gpg --fingerprint D8406D0D82947747293778314AA394086372C20A
        if [ $? -ne 0 ]; then
            echo -e "\033[31mCould not download PGP public key for verification\033[0m"
            exit
        fi
    fi

    if [ "$clean" -eq 1 ]; then
        # Let's clean them up, if they exist
        if [ -f phpunit.phar ]; then
            rm -f phpunit.phar
        fi
        if [ -f phpunit.phar.asc ]; then
            rm -f phpunit.phar.asc
        fi
    fi

    # Let's grab the latest release and its signature
    if [ ! -f phpunit.phar ]; then
        wget https://phar.phpunit.de/phpunit.phar
    fi
    if [ ! -f phpunit.phar.asc ]; then
        wget https://phar.phpunit.de/phpunit.phar.asc
    fi

    # Verify before running
    gpg --verify phpunit.phar.asc phpunit.phar
    if [ $? -eq 0 ]; then
        echo
        echo -e "\033[33mBegin Unit Testing\033[0m"
        # Run the testing suite
        `$after_cmd`
        # Cleanup
        if [ "$clean" -eq 1 ]; then
            echo -e "\033[32mCleaning Up!\033[0m"
            rm -f phpunit.phar
            rm -f phpunit.phar.asc
        fi
    else
        echo
        chmod -x phpunit.phar
        mv phpunit.phar /tmp/bad-phpunit.phar
        mv phpunit.phar.asc /tmp/bad-phpunit.phar.asc
        echo -e "\033[31mSignature did not match! PHPUnit has been moved to /tmp/bad-phpunit.phar\033[0m"
        exit 1
    fi

.. _installation.composer:

Composer
########

Simply add a (development-time) dependency on
``phpunit/phpunit`` to your project's
``composer.json`` file if you use `Composer <https://getcomposer.org/>`_ to manage the
dependencies of your project:

.. code-block:: bash

    composer require --dev phpunit/phpunit ^|version|

.. _installation.optional-packages:

Optional packages
#################

The following optional packages are available:

``PHP_Invoker``

    A utility class for invoking callables with a timeout. This package is
    required to enforce test timeouts in strict mode.

    This package is included in the PHAR distribution of PHPUnit. It can
    be installed via Composer using the following command:

    .. code-block:: bash

        composer require --dev phpunit/php-invoker

``DbUnit``

    DbUnit port for PHP/PHPUnit to support database interaction testing.

    This package is not included in the PHAR distribution of PHPUnit. It can
    be installed via Composer using the following command:

    .. code-block:: bash

        composer require --dev phpunit/dbunit


