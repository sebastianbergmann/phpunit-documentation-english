--TEST--
greet complains when no name is given
--STDIN--

--FILE--
<?php declare(strict_types=1);
require __DIR__ . '/src/greet.php';
--EXPECT--
Error: no name given
