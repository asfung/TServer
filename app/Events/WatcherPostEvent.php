<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WatcherPostEvent implements ShouldBroadcast{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $post;
    public function __construct($post) {
        $this->post = $post;
    }

    public function broadcastOn() {
        $channelName = '_watcherpost.' . $this->post->id;
        $hashedChannel = hash('sha256', $channelName);
        return new Channel($hashedChannel);
    }

    public function broadcastWith(): array {
        return $this->post->toArray(request());
    }
}
