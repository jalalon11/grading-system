<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_teacher_admin && $user->role === 'teacher';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Subject $subject): bool
    {
        // Only allow teacher admins to view subjects from their school
        return $user->is_teacher_admin && 
               $user->role === 'teacher' && 
               $user->school_id === $subject->school_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_teacher_admin && $user->role === 'teacher';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Subject $subject): bool
    {
        // Only allow teacher admins to update subjects from their school
        return $user->is_teacher_admin && 
               $user->role === 'teacher' && 
               $user->school_id === $subject->school_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Subject $subject): bool
    {
        // Only allow teacher admins to delete subjects from their school
        return $user->is_teacher_admin && 
               $user->role === 'teacher' && 
               $user->school_id === $subject->school_id;
    }
} 