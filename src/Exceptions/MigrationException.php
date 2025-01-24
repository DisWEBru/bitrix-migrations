<?php

namespace Diswebru\BitrixMigrations\Exceptions;

use Exception;

class MigrationException extends Exception
{
    protected $code = 1;
}
