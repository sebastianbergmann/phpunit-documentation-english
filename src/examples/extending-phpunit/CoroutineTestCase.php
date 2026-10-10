<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

abstract class CoroutineTestCase extends TestCase
{
    protected function invokeTestMethod(string $methodName, array $testArguments): mixed
    {
        $this->suspendOutputBuffering();

        try {
            return $this->runInCoroutine(
                function () use ($methodName, $testArguments): mixed
                {
                    $this->resumeOutputBuffering();

                    try {
                        return parent::invokeTestMethod($methodName, $testArguments);
                    } finally {
                        $this->suspendOutputBuffering();
                    }
                },
            );
        } finally {
            $this->resumeOutputBuffering();
        }
    }

    /**
     * Runs the callback in a coroutine, waits for the coroutine to finish,
     * and then returns the callback's result or rethrows its exception.
     */
    abstract protected function runInCoroutine(Closure $callback): mixed;
}
