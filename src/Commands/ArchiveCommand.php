<?php

namespace Diswebru\BitrixMigrations\Commands;

use Diswebru\BitrixMigrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

class ArchiveCommand extends AbstractCommand
{
    /**
     * Migrator instance.
     *
     * @var Migrator
     */
    protected $migrator;
    protected string $defaultName = 'archive';

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
        $this->setDescription('Move migration into archive')
            ->addOption('without', 'w', InputOption::VALUE_REQUIRED, 'Archive without last N migration');
    }

    /**
     * Execute the console command.
     *
     * @return null|int
     */
    protected function fire() : int
    {
        $files = $this->migrator->getAllMigrations();
        $without = $this->input->getOption('without') ?: 0;
        if ($without > 0) {
            $files = array_slice($files, 0, $without * -1);
        }

        $count = $this->migrator->moveMigrationFiles($files);

        if ($count) {
            $this->message("<info>Moved to archive:</info> {$count}");
        } else {
            $this->info('Nothing to move');
        }

        return self::SUCCESS;
    }
}
