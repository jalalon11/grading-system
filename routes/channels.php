<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\SupportTicket;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('support.ticket.{ticketId}', function ($user, $ticketId) {
    // Admin can access all tickets
    if ($user->role === 'admin') {
        return true;
    }
    
    // Teacher admin can only access tickets from their school
    $ticket = SupportTicket::findOrFail($ticketId);
    return $user->is_teacher_admin && $user->school_id === $ticket->school_id;
});
