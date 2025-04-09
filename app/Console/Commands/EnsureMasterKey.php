<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RegistrationKey;

class EnsureMasterKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ensure-master-key {key=admin123 : The master key to initialize with}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensures a master registration key exists without overriding existing keys';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if already initialized
        if (RegistrationKey::where('is_master', true)->exists()) {
            $this->info('Master key already exists. No changes made.');
            return;
        }
        
        $masterKey = $this->argument('key');
        
        // Create master key
        RegistrationKey::createMasterKey($masterKey);
        
        $this->info('Registration key system has been initialized with the master key: ' . $masterKey);
        $this->info('You can now access the registration page at /register?key=' . $masterKey);
    }
}
