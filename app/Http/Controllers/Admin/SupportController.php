<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportMessage;

use App\Events\NewSupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SupportController extends Controller
{
    /**
     * Display a listing of the support tickets.
     */
    public function index()
    {
        $tickets = SupportTicket::with(['user', 'school', 'latestMessage'])
            ->orderBy('status', 'asc')
            ->orderBy('last_reply_at', 'desc')
            ->paginate(10);

        return view('admin.support.index', compact('tickets'));
    }

    /**
     * Show the specified support ticket.
     */
    public function show(string $id)
    {
        $ticket = SupportTicket::with(['user', 'school'])->findOrFail($id);
        $messages = SupportMessage::where('support_ticket_id', $ticket->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark all messages as read for the admin
        SupportMessage::where('support_ticket_id', $ticket->id)
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.support.show', compact('ticket', 'messages'));
    }

    /**
     * Reply to a support ticket.
     */
    public function reply(Request $request, string $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        // Create the message
        $message = new SupportMessage([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_read' => false,
        ]);
        $message->save();

        // Load the user relationship for broadcasting
        $message->load('user');

        // Broadcast the new message event
        event(new NewSupportMessage($message));

        // Update the ticket status and last reply time
        $ticket->status = 'in_progress';
        $ticket->last_reply_at = Carbon::now();
        $ticket->save();

        // Check if a redirect URL was provided
        if ($request->has('redirect_url')) {
            return redirect($request->redirect_url);
        }

        // Default redirect with success parameter
        return redirect()->route('admin.support.show', ['id' => $ticket->id, 'success' => 'true']);
    }

    /**
     * Close a support ticket.
     */
    public function close(string $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->status = 'closed';
        $ticket->closed_at = Carbon::now();
        $ticket->save();

        return redirect()->route('admin.support.index')
            ->with('success', 'Support ticket closed successfully.');
    }

    /**
     * Reopen a support ticket.
     */
    public function reopen(string $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->status = 'in_progress';
        $ticket->closed_at = null;
        $ticket->save();

        return redirect()->route('admin.support.show', ['id' => $ticket->id])
            ->with('success', 'Support ticket reopened successfully.');
    }

    /**
     * Mark a message as read.
     */
    public function markAsRead(string $id)
    {
        $message = SupportMessage::findOrFail($id);
        $message->is_read = true;
        $message->save();

        return response()->json(['success' => true]);
    }

    /**
     * Get messages for a ticket with unread count.
     */
    public function getMessages(string $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $lastMessageId = request('last_id', 0);

        // Get the latest message ID for this ticket
        $latestMessage = SupportMessage::where('support_ticket_id', $ticket->id)
            ->latest('id')
            ->first();

        $latestMessageId = $latestMessage ? $latestMessage->id : 0;

        // Count new messages from other users
        $newMessages = SupportMessage::where('support_ticket_id', $ticket->id)
            ->where('id', '>', $lastMessageId)
            ->where('user_id', '!=', Auth::id())
            ->count();

        // Log for debugging
        \Illuminate\Support\Facades\Log::info('Admin checking for new messages', [
            'ticket_id' => $id,
            'user_id' => Auth::id(),
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
}
