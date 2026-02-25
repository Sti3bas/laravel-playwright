<?php declare(strict_types=1);

namespace Saucebase\LaravelPlaywright\Services;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Truncate
{

    /**
     * @param array<null | string> $connections
     */
    public function truncate(array $connections = [null]) : void
    {

        foreach ($connections as $connection) {
            $this->truncateTablesOfConnection($connection);
        }

    }

    private function truncateTablesOfConnection(?string $connection) : void
    {

        /** @var string[] $tables */
        $tables = Schema::connection($connection)->getTableListing();
        Schema::disableForeignKeyConstraints();

        foreach ($tables as $table) {
            try {
                DB::connection($connection)->table($table)->truncate();
            } catch (QueryException) {
                // SQLite fallback: truncate() fails when sqlite_sequence doesn't exist
                // (occurs when no AUTOINCREMENT tables have been used yet)
                DB::connection($connection)->table($table)->delete();
            }
        }

        Schema::enableForeignKeyConstraints();

    }

}