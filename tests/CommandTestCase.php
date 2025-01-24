<?php

namespace Diswebru\Tests\BitrixMigrations;

use Mockery;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

abstract class CommandTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Tear down.
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @param $command
     * @param array $input
     *
     * @return mixed
     */
    protected function runCommand(Command $command, $input = [])
    {
        return $command->run(new ArrayInput($input), new NullOutput());
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return [
            'table' => 'migrations',
            'dir'   => 'migrations',
        ];
    }
}
