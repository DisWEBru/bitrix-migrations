<?php

namespace Diswebru\BitrixMigrations\Commands;

use Diswebru\BitrixMigrations\Migrator;

class StatusCommand extends AbstractCommand
{
    /**
     * Migrator instance.
     *
     * @var Migrator
     */
    protected $migrator;

    protected string $defaultName = 'status';

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
        $this->setDescription('Show status about last migrations');
    }

    /**
     * Execute the console command.
     *
     * @return null|int
     */
    protected function fire() : int
    {
        $this->showOldMigrations();

        $this->output->write("\r\n");

        $this->showNewMigrations();

        return self::SUCCESS;
    }

    /**
     * Show old migrations.
     *
     * @return void
     */
    protected function showOldMigrations() : void
    {
        $old = collect($this->migrator->getRanMigrations());

        $this->output->writeln("<fg=yellow>Old migrations:\r\n</>");

        $max = 5;
        if ($old->count() > $max) {
            $this->output->writeln('<fg=yellow>...</>');

            $old = $old->take(-$max);
        }

        foreach ($old as $migration) {
            $this->output->writeln("<fg=yellow>{$migration}.php</>");
        }
    }

    /**
     * Show new migrations.
     *
     * @return void
     */
    protected function showNewMigrations() : void
    {
        $new = collect($this->migrator->getMigrationsToRun());

        $this->output->writeln("<fg=green>New migrations:\r\n</>");

        foreach ($new as $migration) {
            $this->output->writeln("<fg=green>{$migration}.php</>");
        }
    }
}
