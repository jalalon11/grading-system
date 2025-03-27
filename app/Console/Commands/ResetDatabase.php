<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class ResetDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the database by dropping all tables and running migrations from scratch';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->confirm('This will drop all tables in your database. Do you wish to continue?', true)) {
            $this->info('Operation cancelled.');
            return;
        }

        $this->info('Dropping all tables...');
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Get all table names
        $tables = DB::select('SHOW TABLES');
        $dbname = env('DB_DATABASE');
        $tableKey = "Tables_in_{$dbname}";

        // Drop each table
        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            $this->info("Dropping table: {$tableName}");
            DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->info('All tables dropped successfully.');
        
        // Run migrations from scratch
        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->info('Migrations completed successfully.');
        
        // Seed the database if needed
        if ($this->confirm('Do you want to seed the database with initial data?', true)) {
            $this->info('Seeding database...');
            Artisan::call('db:seed', ['--force' => true]);
            $this->info('Database seeded successfully.');
        }
        
        $this->info('Database reset completed successfully.');
    }
}
