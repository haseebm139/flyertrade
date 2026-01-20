<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class GlobalLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Disable Foreign Key Checks
        Schema::disableForeignKeyConstraints();

        $tables = ['countries', 'states', 'currencies', 'languages', 'timezones', 'cities'];
        $sqlPath = database_path('seeders/sql');

        foreach ($tables as $table) {
            $filePath = "{$sqlPath}/{$table}.sql";

            if (File::exists($filePath)) {
                $this->command->info("Seeding table: {$table} from SQL file...");
                
                // Truncate table before seeding to avoid duplicates
                DB::table($table)->truncate();

                // Read and execute SQL file
                // Since the files can be huge (cities.sql), we read them line by line or in blocks
                $sql = File::get($filePath);
                
                // For massive files, we might need DB::unprepared
                // or splitting by semicolon
                DB::unprepared($sql);

                $this->command->info("Table {$table} seeded successfully!");
            } else {
                $this->command->warn("SQL file for {$table} not found at {$filePath}");
            }
        }

        // 2. Re-enable Foreign Key Checks
        Schema::enableForeignKeyConstraints();
    }
}
