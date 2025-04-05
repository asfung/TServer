<?php

namespace App\Events;

use App\Common\ApiCommon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostNotificationEvent implements ShouldBroadcast{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $followerId;
    public $message;
    private $details;

    public function __construct($user, $followerId, $message, $details) {
        $this->user = $user;
        $this->followerId = $followerId;
        $this->message = $message;
        $this->details = $details;
    }

    public function broadcastOn() {
        $channelName = '_notifications.' . $this->followerId;
        $hashedChannel = hash('sha256', $channelName);
        // return new Channel('_notifications.' . $this->followerId);
        return new Channel($hashedChannel);
    }

    public function broadcastWith(): array {
        return [
            'user' => $this->user,
            'message' => $this->message,
            'details' => $this->details,
        ];
    }
}
