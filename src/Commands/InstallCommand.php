<?php

namespace Diswebru\BitrixMigrations\Commands;

use Diswebru\BitrixMigrations\Interfaces\DatabaseStorageInterface;

class InstallCommand extends AbstractCommand
{
    /**
     * Interface that gives us access to the database.
     *
     * @var DatabaseStorageInterface
     */
    protected $database;

    /**
     * Table in DB to store migrations that have been already run.
     *
     * @var string
     */
    protected $table;

    protected string $defaultName = 'install';

    /**
     * Constructor.
     *
     * @param string                   $table
     * @param DatabaseStorageInterface $database
     * @param string|null              $name
     */
    public function __construct($table, DatabaseStorageInterface $database, $name = null)
    {
        $this->table = $table;
        $this->database = $database;

        parent::__construct($name);
    }

    /**
     * Configures the current command.
     */
    protected function configure() : void
    {
        $this->setDescription('Create the migration database table');
    }

    /**
     * Execute the console command.
     *
     * @return null|int
     */
    protected function fire() : int
    {
        if ($this->database->checkMigrationTableExistence()) {
            $this->abort("Table \"{$this->table}\" already exists");

            return self::SUCCESS;
        }

        $this->database->createMigrationTable();

        $this->info('Migration table has been successfully created!');

        return self::SUCCESS;
    }
}
