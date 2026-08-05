--TEST--
greet --version prints the version of greet and the version of PHP
--ARGS--
--version
--FILE--
<?php declare(strict_types=1);
require __DIR__ . '/src/greet.php';
--EXPECTF--
greet 1.0.0 (PHP %s)
