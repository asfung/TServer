<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostNotification extends Notification
{
  use Queueable;

  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  public function via($notifiable)
  {
    return ['database'];
  }

  public function toArray($notifiable)
  {
    // return $this->data;
    return [
      'user' => collect($this->data['user'])->except(['followers']),
      'message' => $this->data['message'],
      'details' => $this->data['details'],
  ];
  }
}
