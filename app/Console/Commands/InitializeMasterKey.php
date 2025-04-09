<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RegistrationKey;

class InitializeMasterKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialize-master-key {key=admin123 : The master key to initialize with}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the master registration key with a default value';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if already initialized
        if (RegistrationKey::where('is_master', true)->exists()) {
            $this->error('Registration key system is already initialized.');
            
            if ($this->confirm('Do you want to reset the master key?')) {
                RegistrationKey::where('is_master', true)->delete();
            } else {
                return;
            }
        }
        
        $masterKey = $this->argument('key');
        
        // Create master key
        RegistrationKey::createMasterKey($masterKey);
        
        $this->info('Registration key system has been initialized with the master key: ' . $masterKey);
        $this->info('You can now access the registration page at /register?key=' . $masterKey);
    }
}
