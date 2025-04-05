<?php

namespace App\Services;

use App\Common\ApiCommon;
use App\DTO\NotificationDTO;
use App\Models\Notifications;

class NotificationService {

  public function getAll(NotificationDTO $notificationDTO) {
    try {

      $user_id = ApiCommon::getUserId();
      $page = $notificationDTO->getPage();
      $per_page = $notificationDTO->getPer_page() ?? 15;
      $limit = $notificationDTO->getLimit();

      $query = Notifications::where('notifiable_id', $user_id)
        ->orderBy('created_at', 'desc');

      if ($limit) {
        $query->limit($limit);
      }

      $notifications = $query->paginate($per_page, ['data', 'created_at'], 'page', $page);

      $transformNotif = collect($notifications->items())->map(function ($notification) {
        return [
          'user' => $notification['data']['user'] ?? null,
          'message' => $notification['data']['message'] ?? null,
          'details' => $notification['data']['details'] ?? null,
          'created_at' => $notification['created_at'] ?? null, 
        ];
      });
      $notifications->setCollection($transformNotif);

      return ApiCommon::sendPaginatedResponse($notifications, 'data notification berhasil', 200);
    } catch (\Exception $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

}
