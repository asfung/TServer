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

    public function __construct($user, $followerId, $message) {
        $this->user = $user;
        $this->followerId = $followerId;
        $this->message = $message;
    }

    // public function broadcastOn(): array {
    //     return collect($this->followers)->map(function ($follower) {
    //         $encryptedId = ApiCommon::encryptUserId($follower->user_id_follower);
    //         return new PrivateChannel('_notifications.' . $encryptedId);
    //     })->toArray();
    // }

    public function broadcastOn() {
        // return new PrivateChannel('_notifications.' . ApiCommon::encryptUserId($this->followerId));
        return new Channel('_notifications.' . $this->followerId);
    }

    public function broadcastWith(): array {
        return [
            'user' => $this->user,
            'message' => $this->message,
        ];
    }
}
