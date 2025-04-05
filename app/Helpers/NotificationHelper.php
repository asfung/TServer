<?php

namespace App\Helpers;

use App\Events\PostNotificationEvent;
use App\Events\WatcherPostEvent;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Notifications\PostNotification;
use App\Models\User;

class NotificationHelper
{
    /**
     * Send a notification via event and optionally store it in the database.
     *
     * @param mixed $sender The user sending the notification.
     * @param int|string $receiverId The ID of the receiving user.
     * @param string $message The notification message.
     * @param $details data depends on message
     * @param bool $storeInDatabase Whether to save the notification in the database.
     */
    public static function sendNotification($sender, int|string $receiverId, string $message, $details, bool $storeInDatabase = false)
    {
        $receiver = User::find($receiverId);
        if (!$receiver) {
            return;
        }

        event(new PostNotificationEvent($sender, $receiverId, $message, $details));

        if ($storeInDatabase) {
            $receiver->notify(new PostNotification([
                'user' => $sender,
                'message' => $message,
                'details' => $details,
            ]));
        }
    }

    public static function sendWatcherPostNotification($post){
        $postResource = new PostResource($post);
        event(new WatcherPostEvent($postResource));
    }

    public static function sendWatcherPostNotificationByPostId($postId){
        $post = Post::find($postId);
        $postResource = new PostResource($post);
        event(new WatcherPostEvent($postResource));
    }

}
