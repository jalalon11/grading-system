<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SchoolDivisionController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeacherAdminController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\GradeController;
use App\Http\Controllers\Teacher\StudentController;
use App\Http\Controllers\Teacher\SubjectController;
use App\Http\Controllers\Teacher\GradeConfigurationController;
use App\Http\Controllers\TeacherAdmin\DashboardController as TeacherAdminDashboardController;
use App\Http\Controllers\TeacherAdmin\SectionController;
use App\Http\Controllers\TeacherAdmin\SubjectController as TeacherAdminSubjectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Section;
use App\Models\Student;
use App\Http\Middleware\CheckSchoolStatus;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth', CheckSchoolStatus::class])->group(function () {
    Route::get('/home', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        } else {
            return redirect()->route('login');
        }
    })->name('home');

    // Admin Routes
    Route::prefix('admin')->middleware(['auth', 'check.role:admin'])->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', function() {
            return view('admin.profile');
        })->name('profile');
        Route::put('/profile/update', [AdminDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [AdminDashboardController::class, 'updatePassword'])->name('password.update');
        Route::resource('school-divisions', SchoolDivisionController::class);
        Route::resource('schools', SchoolController::class);
        Route::patch('schools/{school}/disable', [SchoolController::class, 'disable'])->name('schools.disable');
        Route::patch('schools/{school}/enable', [SchoolController::class, 'enable'])->name('schools.enable');
        Route::resource('teachers', TeacherController::class);
        Route::post('teachers/{teacher}/reset-password', [TeacherController::class, 'resetPassword'])->name('teachers.reset-password');
        Route::resource('teacher-admins', TeacherAdminController::class);
        
        // API Routes
        Route::get('/api/schools/{school}/teachers', [TeacherAdminController::class, 'getTeachers']);
    });

    // Teacher Routes
    Route::prefix('teacher')->middleware(['auth', 'check.role:teacher'])->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/attendance-data', [TeacherDashboardController::class, 'getAttendanceData'])->name('dashboard.attendance-data');
        Route::get('/dashboard/performance-data', [TeacherDashboardController::class, 'getPerformanceData'])->name('dashboard.performance-data');
        Route::get('/profile', function() {
            return view('teacher.profile');
        })->name('profile');
        Route::put('/profile/update', [TeacherDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [TeacherDashboardController::class, 'updatePassword'])->name('password.update');
        
        // Gender distribution endpoint for AJAX
        Route::get('/students/gender-distribution', [StudentController::class, 'genderDistribution'])->name('students.gender-distribution');
        
        // Regular teacher functionality - all teachers including teacher admins
        Route::resource('students', StudentController::class);
        Route::get('grades/assessment-setup', [GradeController::class, 'assessmentSetup'])->name('grades.assessment-setup');
        Route::post('grades/store-assessment-setup', [GradeController::class, 'storeAssessmentSetup'])->name('grades.store-assessment-setup');
        Route::get('grades/batch-create', [GradeController::class, 'batchCreate'])->name('grades.batch-create');
        Route::post('grades/batch-store', [GradeController::class, 'batchStore'])->name('grades.batch-store');
        
        // New Configure Grades routes
        Route::get('grades/configure', [GradeController::class, 'showConfigureForm'])->name('grades.configure');
        Route::post('grades/configure', [GradeController::class, 'configureGrades'])->name('grades.store-configure');
        
        // Grades routes
        Route::get('grades', [GradeController::class, 'index'])->name('grades.index');
        Route::get('grades/create', [GradeController::class, 'create'])->name('grades.create');
        Route::post('grades', [GradeController::class, 'store'])->name('grades.store');
        Route::get('grades/{grade}/edit', [GradeController::class, 'edit'])->name('grades.edit');
        Route::put('grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
        Route::delete('grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');
        Route::get('grades/{grade}', [GradeController::class, 'show'])->name('grades.show');
        Route::post('grades/lock-transmutation-table', [GradeController::class, 'lockTransmutationTable'])->name('grades.lock-transmutation');
        Route::post('grades/update-transmutation-preference', [GradeController::class, 'updateTransmutationPreference'])->name('grades.update-transmutation-preference');
        
        // Reports Routes
        Route::get('reports', [\App\Http\Controllers\Teacher\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/class-record', [\App\Http\Controllers\Teacher\ReportController::class, 'classRecord'])->name('reports.class-record');
        Route::post('reports/generate-class-record', [\App\Http\Controllers\Teacher\ReportController::class, 'generateClassRecord'])->name('reports.generate-class-record');
        Route::get('reports/section-subjects', [\App\Http\Controllers\Teacher\ReportController::class, 'getSectionSubjects'])->name('reports.section-subjects');
        Route::post('reports/students-by-grade-ranges', [\App\Http\Controllers\Teacher\ReportController::class, 'getStudentsByGradeRanges'])->name('reports.students-by-grade-ranges');
        
        Route::resource('attendances', AttendanceController::class);
        
        // API endpoint to get students by section ID
        Route::get('/sections/{section}/students', function($section) {
            $section = Section::where('id', $section)
                ->where('adviser_id', Auth::id())
                ->firstOrFail();
                
            $students = Student::where('section_id', $section->id)
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();
                
            return response()->json(['students' => $students]);
        });
        
        // Grade Configuration routes
        Route::get('grade-configurations/{subject}', [GradeConfigurationController::class, 'edit'])->name('grade-configurations.edit');
        Route::put('grade-configurations/{subject}', [GradeConfigurationController::class, 'update'])->name('grade-configurations.update');
        
        // Redirect section/subject routes to teacher-admin routes for teacher admins
        Route::middleware(['teacher.admin'])->group(function () {
            // Redirect sections routes to new-sections
            Route::get('sections', function() {
                return redirect()->route('teacher-admin.sections.index');
            })->name('sections.index');
            
            Route::get('sections/create', function() {
                return redirect()->route('teacher-admin.sections.create');
            })->name('sections.create');
            
            Route::get('sections/{section}', function($section) {
                return redirect()->route('teacher-admin.sections.show', $section);
            })->name('sections.show');
            
            Route::get('sections/{section}/edit', function($section) {
                return redirect()->route('teacher-admin.sections.edit', $section);
            })->name('sections.edit');
            
            // Redirect subjects routes to teacher-admin versions
            Route::get('subjects', function() {
                return redirect()->route('teacher-admin.subjects.index');
            })->name('subjects.index');
            
            Route::get('subjects/create', function() {
                return redirect()->route('teacher-admin.subjects.create');
            })->name('subjects.create');
            
            Route::get('subjects/{subject}', function($subject) {
                return redirect()->route('teacher-admin.subjects.show', $subject);
            })->name('subjects.show');
            
            Route::get('subjects/{subject}/edit', function($subject) {
                return redirect()->route('teacher-admin.subjects.edit', $subject);
            })->name('subjects.edit');
        });

        // Add bulk update route
        Route::post('/grades/bulk-update', [GradeController::class, 'bulkUpdate'])->name('grades.bulk-update');
        // Add edit assessment route
        Route::post('/reports/edit-assessment', [GradeController::class, 'editAssessment'])->name('reports.edit-assessment');
    });

    // Teacher Admin Routes
    Route::middleware(['auth', 'teacher.admin'])
        ->name('teacher-admin.')
        ->prefix('teacher-admin')
        ->group(function () {
            // Dashboard
            Route::get('/dashboard', [\App\Http\Controllers\TeacherAdmin\DashboardController::class, 'index'])->name('dashboard');
            Route::get('/profile', function() {
                return view('teacher_admin.profile');
            })->name('profile');
            Route::put('/profile/update', [\App\Http\Controllers\TeacherAdmin\DashboardController::class, 'updateProfile'])->name('profile.update');
            Route::put('/profile/password', [\App\Http\Controllers\TeacherAdmin\DashboardController::class, 'updatePassword'])->name('password.update');
            
            // Subjects Management
            Route::resource('subjects', TeacherAdminSubjectController::class);
            Route::post('subjects/{subject}/assign-teachers', [TeacherAdminSubjectController::class, 'assignTeachers'])
                ->name('subjects.assign-teachers');
            Route::patch('subjects/{subject}/toggle-status', [TeacherAdminSubjectController::class, 'toggleStatus'])
                ->name('subjects.toggle-status');

            // Sections Management
            Route::resource('sections', SectionController::class);
            Route::post('sections/{section}/assign-subjects', [SectionController::class, 'assignSubjects'])
                ->name('sections.assign-subjects');
            Route::patch('sections/{section}/toggle-status', [SectionController::class, 'toggleStatus'])
                ->name('sections.toggle-status');
            Route::patch('sections/{section}/update-adviser', [SectionController::class, 'updateAdviser'])
                ->name('sections.update-adviser');
        });

    // Dark mode preference update route
    Route::post('/user/update-dark-mode', [App\Http\Controllers\UserController::class, 'updateDarkMode'])->name('user.update-dark-mode');
});
