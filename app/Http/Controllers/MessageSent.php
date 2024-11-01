<?php
namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $canalId;

    public function __construct(Message $message, $canalId)
    {
        $this->message = $message;
        $this->canalId = $canalId;
    }

    public function broadcastOn()
    {
        return new Channel('chat-channel-' . $this->canalId);
    }

    public function broadcastAs()
    {
        return 'message-sent';
    }
}
