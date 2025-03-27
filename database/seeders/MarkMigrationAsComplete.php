<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarkMigrationAsComplete extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $migrations = [
            '2025_03_27_022055_recreate_subjects_table',
            '2025_03_27_022531_recreate_subjects_table'
        ];

        foreach ($migrations as $migration) {
            // Check if the migration is already in the migrations table
            $exists = DB::table('migrations')
                ->where('migration', $migration)
                ->exists();

            if (!$exists) {
                // Insert the migration as completed
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => DB::table('migrations')->max('batch') + 1
                ]);

                $this->command->info("Marked migration {$migration} as complete");
            } else {
                $this->command->info("Migration {$migration} is already marked as complete");
            }
        }
    }
}
