<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class SupportTicket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'school_id',
        'subject',
        'status',
        'priority',
        'last_reply_at',
        'closed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_reply_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Get the user who created this ticket
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the school this ticket belongs to
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the messages for this ticket
     */
    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class);
    }

    /**
     * Get the latest message for this ticket
     */
    public function latestMessage()
    {
        return $this->hasOne(SupportMessage::class)->latest();
    }

    /**
     * Get the unread messages count for a specific user
     */
    public function unreadMessagesCount($userId)
    {
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get all teacher admins for this ticket's school
     */
    public function getSchoolTeacherAdmins()
    {
        return User::where('school_id', $this->school_id)
            ->where('role', 'teacher')
            ->where('is_teacher_admin', true)
            ->get();
    }

    /**
     * Mark messages as read for all teacher admins of this school
     */
    public function markMessagesAsReadForSchool()
    {
        $teacherAdminIds = $this->getSchoolTeacherAdmins()->pluck('id')->toArray();

        return $this->messages()
            ->whereNotIn('user_id', $teacherAdminIds)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Get the unread messages count for all teacher admins of this school
     */
    public function unreadMessagesCountForSchool()
    {
        $teacherAdminIds = $this->getSchoolTeacherAdmins()->pluck('id')->toArray();

        return $this->messages()
            ->whereNotIn('user_id', $teacherAdminIds)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get created_at attribute in Asia/Manila timezone
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->setTimezone('Asia/Manila') : null;
    }

    /**
     * Get updated_at attribute in Asia/Manila timezone
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->setTimezone('Asia/Manila') : null;
    }

    /**
     * Get last_reply_at attribute in Asia/Manila timezone
     *
     * @param  string  $value
     * @return string
     */
    public function getLastReplyAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->setTimezone('Asia/Manila') : null;
    }
}
