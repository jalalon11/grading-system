<?php

namespace App\Events;

use App\Models\SupportMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewSupportMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(SupportMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the message data.
     *
     * @return array
     */
    public function getMessageData()
    {
        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'user' => [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
                'role' => $this->message->user->role
            ],
            'created_at' => $this->message->created_at->format('M d, Y h:i A'),
            'is_read' => $this->message->is_read
        ];
    }
}
