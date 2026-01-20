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
        $jsonPath = database_path('seeders/json');

        foreach ($tables as $table) {
            $filePath = "{$jsonPath}/{$table}.json";

            if (File::exists($filePath)) {
                $this->command->info("Seeding table: {$table} from JSON file...");
                
                // Truncate table before seeding to avoid duplicates
                DB::table($table)->truncate();

                // Read JSON content
                $json = File::get($filePath);
                $data = json_decode($json, true);

                if (is_array($data)) {
                    // Insert in chunks of 500 to avoid query size limits
                    $chunks = array_chunk($data, 500);
                    foreach ($chunks as $chunk) {
                        DB::table($table)->insert($chunk);
                    }
                    $this->command->info("Table {$table} seeded successfully!");
                } else {
                    $this->command->error("Failed to decode JSON for table {$table}");
                }
            } else {
                $this->command->warn("JSON file for {$table} not found at {$filePath}");
            }
        }

        // 2. Re-enable Foreign Key Checks
        Schema::enableForeignKeyConstraints();
    }
}
