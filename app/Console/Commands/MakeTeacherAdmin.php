<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MakeTeacherAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teacher:make-admin {email : The email of the teacher to promote}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promote a teacher to teacher admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        try {
            DB::beginTransaction();
            
            $teacher = User::where('email', $email)->first();
            
            if (!$teacher) {
                $this->error("No user found with email: {$email}");
                return 1;
            }
            
            if ($teacher->role !== 'teacher') {
                $this->error("User with email {$email} is not a teacher");
                return 1;
            }
            
            // Check if the school already has 2 teacher admins
            $adminCount = User::where('school_id', $teacher->school_id)
                ->where('is_teacher_admin', true)
                ->count();
                
            if ($adminCount >= 2) {
                $this->error("School already has the maximum of 2 teacher admins");
                return 1;
            }
            
            $teacher->is_teacher_admin = true;
            $teacher->save();
            
            DB::commit();
            
            $this->info("Teacher {$teacher->name} has been promoted to teacher admin");
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Failed to promote teacher: " . $e->getMessage());
            return 1;
        }
    }
}
