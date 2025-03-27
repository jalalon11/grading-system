<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixTeacherAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:teacher-admin-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix teacher admin roles to use is_teacher_admin flag instead of role=teacher_admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix teacher admin roles...');
        
        try {
            DB::beginTransaction();
            
            // Find all users with role='teacher_admin'
            $teacherAdmins = User::where('role', 'teacher_admin')->get();
            
            $this->info("Found {$teacherAdmins->count()} users with role='teacher_admin'");
            
            // Update each user to have role='teacher' and is_teacher_admin=true
            foreach ($teacherAdmins as $user) {
                $this->info("Fixing user: {$user->name} ({$user->email})");
                
                $user->role = 'teacher';
                $user->is_teacher_admin = true;
                $user->save();
                
                $this->info("User fixed successfully");
            }
            
            DB::commit();
            
            $this->info('All teacher admin roles have been fixed successfully!');
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }
}
