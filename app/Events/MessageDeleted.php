<?php
namespace App\Events;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MessageDeleted implements ShouldBroadcast, ShouldQueue {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public int $messageId;
    public int $conversationId;
    public function __construct(int $messageId, int $conversationId) {
        $this->messageId = $messageId;
        $this->conversationId = $conversationId;
    }
    public function broadcastOn(): array {
        return [new PresenceChannel('chat.' . $this->conversationId)];
    }
}
