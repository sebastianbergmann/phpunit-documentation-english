--TEST--
greet greets the person named on standard input
--STDIN--
Bob
--FILE--
<?php declare(strict_types=1);
require __DIR__ . '/src/greet.php';
--EXPECT--
Hello, Bob!
