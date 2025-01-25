<?php

namespace Diswebru\BitrixMigrations;

class ExceptionTracing
{
    protected $exception;

    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    public function full()
    {
        echo 'Error: ' . $this->exception->getMessage() . PHP_EOL;
        echo 'Path: ' . $this->exception->getFile() . ':' . $this->exception->getLine() . PHP_EOL;
        echo 'Tracing: ' . PHP_EOL;
        echo ' ---' . PHP_EOL;
        foreach ($this->exception->getTrace() as $trace) {
            echo ' Path: ' . ($trace['file'] ?? '[no data]') . ':' . ($trace['line'] ?? '[no data]') . PHP_EOL;
            echo ' Function: ' . ($trace['function'] ?? '[no data]') . PHP_EOL;
            echo ' ---' . PHP_EOL;
        }
    }

    public static function print($exception)
    {
        (new self($exception))->full();
    }
}
