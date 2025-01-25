<?php

namespace Diswebru\BitrixMigrations\Commands;

use Diswebru\BitrixMigrations\Migrator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeCommand extends AbstractCommand
{
    /**
     * Migrator instance.
     *
     * @var Migrator
     */
    protected $migrator;

    protected static $defaultName = 'make';

    /**
     * Constructor.
     *
     * @param Migrator    $migrator
     * @param string|null $name
     */
    public function __construct(Migrator $migrator, $name = null)
    {
        $this->migrator = $migrator;

        parent::__construct($name);
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Create a new migration file')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the migration'
            )
            ->addOption(
                'template',
                't',
                InputOption::VALUE_REQUIRED,
                'Migration template'
            )
            ->addOption(
                'directory',
                'd',
                InputOption::VALUE_REQUIRED,
                'Migration directory'
            );
    }

    /**
     * Execute the console command.
     *
     * @return null|int
     */
    protected function fire()
    {
        $migration = $this->migrator->createMigration(
            $this->input->getArgument('name'),
            $this->input->getOption('template'),
            [],
            $this->input->getOption('directory')
        );

        $this->message("<info>Migration created:</info> {$migration}.php");

        return self::SUCCESS;
    }
}
