--TEST--
greet greets the person named on the command line
--ARGS--
Alice
--FILE--
<?php declare(strict_types=1);
require __DIR__ . '/src/greet.php';
--EXPECT--
Hello, Alice!
