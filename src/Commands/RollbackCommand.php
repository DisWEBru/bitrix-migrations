<?php

namespace Diswebru\BitrixMigrations\Commands;

use Diswebru\BitrixMigrations\Migrator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class RollbackCommand extends AbstractCommand
{
    /**
     * Migrator instance.
     *
     * @var Migrator
     */
    protected $migrator;

    protected string $defaultName = 'rollback';

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
    protected function configure() : void
    {
        $this->setDescription('Rollback the last migration')
            ->addOption('hard', null, InputOption::VALUE_NONE, 'Rollback without running down()')
            ->addOption('delete', null, InputOption::VALUE_NONE, 'Delete migration file after rolling back');
    }

    /**
     * Execute the console command.
     *
     * @return null|int
     */
    protected function fire() : int
    {
        $ran = $this->migrator->getRanMigrations();

        if (empty($ran)) {
            $this->info('Nothing to rollback');
            return self::SUCCESS;
        }

        $migration = $ran[count($ran) - 1];

        $this->input->getOption('hard')
            ? $this->hardRollbackMigration($migration)
            : $this->rollbackMigration($migration);

        $this->deleteIfNeeded($migration);

        return self::SUCCESS;
    }

    /**
     * Call rollback.
     *
     * @param $migration
     *
     * @return null
     */
    protected function rollbackMigration($migration) : void
    {
        if ($this->migrator->doesMigrationFileExist($migration)) {
            $this->migrator->rollbackMigration($migration);
        } else {
            $this->markRolledBackWithConfirmation($migration);
        }

        $this->message("<info>Rolled back:</info> {$migration}.php");
    }

    /**
     * Call hard rollback.
     *
     * @param $migration
     *
     * @return null
     */
    protected function hardRollbackMigration($migration) : void
    {
        $this->migrator->removeSuccessfulMigrationFromLog($migration);

        $this->message("<info>Rolled back with --hard:</info> {$migration}.php");
    }

    /**
     * Ask a user to confirm rolling back non-existing migration and remove it from log.
     *
     * @param $migration
     *
     * @return void
     */
    protected function markRolledBackWithConfirmation($migration) : void
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion("<error>Migration $migration was not found.\r\nDo you want to mark it as rolled back? (y/n)</error>\r\n", false);

        if (!$helper->ask($this->input, $this->output, $question)) {
            $this->abort();
        }

        $this->migrator->removeSuccessfulMigrationFromLog($migration);
    }

    /**
     * Delete migration file if options is set
     *
     * @param string $migration
     *
     * @return null
     */
    protected function deleteIfNeeded($migration) : void
    {
        if (!$this->input->getOption('delete')) {
            return;
        }

        if ($this->migrator->deleteMigrationFile($migration)) {
            $this->message("<info>Deleted:</info> {$migration}.php");
        }
    }
}
