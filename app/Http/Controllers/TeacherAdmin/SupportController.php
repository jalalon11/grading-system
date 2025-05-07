<?php

namespace App\Http\Controllers\TeacherAdmin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Events\NewSupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SupportController extends Controller
{
    /**
     * Display a listing of the support tickets for the current teacher admin's school.
     */
    public function index()
    {
        $user = Auth::user();
        $tickets = SupportTicket::with(['user', 'latestMessage'])
            ->where('school_id', $user->school_id)
            ->orderBy('status', 'asc')
            ->orderBy('last_reply_at', 'desc')
            ->paginate(10);

        return view('teacher_admin.support.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new support ticket.
     */
    public function create()
    {
        return view('teacher_admin.support.create');
    }

    /**
     * Store a newly created support ticket.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $user = Auth::user();

        // Create the ticket
        $ticket = new SupportTicket([
            'user_id' => $user->id,
            'school_id' => $user->school_id,
            'subject' => $request->subject,
            'status' => 'open',
            'priority' => $request->priority,
            'last_reply_at' => Carbon::now(),
        ]);
        $ticket->save();

        // Create the initial message
        $message = new SupportMessage([
            'support_ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'is_read' => false,
        ]);
        $message->save();

        // Load the user relationship for broadcasting
        $message->load('user');

        // Broadcast the new message event
        event(new NewSupportMessage($message));

        return redirect()->route('teacher-admin.support.show', $ticket->id)
            ->with('success', 'Support ticket created successfully.');
    }

    /**
     * Display the specified support ticket.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $ticket = SupportTicket::with('user')
            ->where('school_id', $user->school_id)
            ->findOrFail($id);

        $messages = SupportMessage::where('support_ticket_id', $ticket->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark all messages as read for the teacher admin
        SupportMessage::where('support_ticket_id', $ticket->id)
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('teacher_admin.support.show', compact('ticket', 'messages'));
    }

    /**
     * Reply to a support ticket.
     */
    public function reply(Request $request, string $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user = Auth::user();
        $ticket = SupportTicket::where('school_id', $user->school_id)->findOrFail($id);

        // Create the message
        $message = new SupportMessage([
            'support_ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'is_read' => false,
        ]);
        $message->save();

        // Load the user relationship for broadcasting
        $message->load('user');

        // Broadcast the new message event
        event(new NewSupportMessage($message));

        // Update the ticket status and last reply time
        if ($ticket->status === 'closed') {
            $ticket->status = 'open';
        }
        $ticket->last_reply_at = Carbon::now();
        $ticket->save();

        // Check if a redirect URL was provided
        if ($request->has('redirect_url')) {
            return redirect($request->redirect_url);
        }

        // Default redirect with success parameter
        return redirect()->route('teacher-admin.support.show', ['id' => $ticket->id, 'success' => 'true']);
    }

    /**
     * Mark a message as read.
     */
    public function markAsRead(string $id)
    {
        $user = Auth::user();
        $message = SupportMessage::whereHas('ticket', function($query) use ($user) {
            $query->where('school_id', $user->school_id);
        })->findOrFail($id);

        $message->is_read = true;
        $message->save();

        return response()->json(['success' => true]);
    }

    /**
     * Get messages for a ticket with unread count.
     */
    public function getMessages(string $id)
    {
        $user = Auth::user();
        $ticket = SupportTicket::where('school_id', $user->school_id)->findOrFail($id);
        $lastMessageId = request('last_id', 0);

        // Get the latest message ID for this ticket
        $latestMessage = SupportMessage::where('support_ticket_id', $ticket->id)
            ->latest('id')
            ->first();

        $latestMessageId = $latestMessage ? $latestMessage->id : 0;

        // Count new messages from other users
        $newMessages = SupportMessage::where('support_ticket_id', $ticket->id)
            ->where('id', '>', $lastMessageId)
            ->where('user_id', '!=', $user->id)
            ->count();

        // Log for debugging
        Log::info('Teacher Admin checking for new messages', [
            'ticket_id' => $id,
            'user_id' => $user->id,
            'last_message_id' => $lastMessageId,
            'latest_message_id' => $latestMessageId,
            'new_messages_count' => $newMessages,
            'has_new_messages' => $newMessages > 0
        ]);

        return response()->json([
            'hasNewMessages' => $newMessages > 0,
            'newMessagesCount' => $newMessages,
            'latestMessageId' => $latestMessageId
        ]);
    }

    /**
     * Check read status for messages sent by the current user.
     */
    public function checkReadStatus()
    {
        $user = Auth::user();

        // Get all messages sent by the current user
        $messages = SupportMessage::where('user_id', $user->id)
            ->with('ticket')
            ->whereHas('ticket', function($query) use ($user) {
                $query->where('school_id', $user->school_id);
            })
            ->get();

        $messageStatuses = [];

        foreach ($messages as $message) {
            $messageStatuses[$message->id] = [
                'id' => $message->id,
                'is_read' => (bool) $message->is_read,
                'ticket_id' => $message->support_ticket_id
            ];
        }

        return response()->json([
            'success' => true,
            'messages' => $messageStatuses
        ]);
    }
}
