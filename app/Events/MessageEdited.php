<?php
namespace App\Events;
use App\Models\Message;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MessageEdited implements ShouldBroadcast, ShouldQueue {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public Message $message;
    public function __construct(Message $message) { $this->message = $message; }
    public function broadcastOn(): array {
        return [new PresenceChannel('chat.' . $this->message->conversation_id)];
    }
}
