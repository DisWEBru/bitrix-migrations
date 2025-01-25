<?php

namespace Diswebru\BitrixMigrations\Commands;

use DomainException;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Command name
     * @var string
     */
    protected string $defaultName;

    /**
     * Command name
     * @return string
     */
    public function getName() : string
    {
        return $this->defaultName;
    }

    /**
     * Configures the current command.
     *
     * @param string $message
     */
    protected function abort($message = '') : void
    {
        if ($message) {
            $this->error($message);
        }

        $this->error('Abort!');

        throw new DomainException();
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code.
     */
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $this->input = $input;
        $this->output = $output;

        try {
            return $this->fire();
        } catch (DomainException $e) {
            return 1;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            $this->error('Abort!');

            return $e->getCode();
        }
    }

    /**
     * Echo an error message.
     *
     * @param string$message
     */
    protected function error($message) : void
    {
        $this->output->writeln("<error>{$message}</error>");
    }

    /**
     * Echo an info.
     *
     * @param string $message
     */
    protected function info($message) : void
    {
        $this->output->writeln("<info>{$message}</info>");
    }

    /**
     * Echo a message.
     *
     * @param string $message
     */
    protected function message($message) : void
    {
        $this->output->writeln("{$message}");
    }

    /**
     * Execute the console command.
     *
     * @return null|int
     */
    abstract protected function fire() : int;
}
