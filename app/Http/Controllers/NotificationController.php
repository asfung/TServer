<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\DTO\NotificationDTO;
use Illuminate\Http\Request;
use App\Services\NotificationService;

class NotificationController extends Controller {

  protected $notificationService;

  public function __construct(NotificationService $notificationService) {
    $this->notificationService = $notificationService;
  }

  public function getAllCTLL(Request $request){
    try{
      $request->validate([
        'page' => 'required',
        'per_page' => 'nullable',
        'limit' => 'nullable'
      ]);

      $page = $request->input('page');
      $per_page = $request->has('per_page') ? $request->input('per_page') : null;
      $limit = $request->has('limit') ? $request->input('limit') : null;

      $notificationDTO = new NotificationDTO();
      $notificationDTO->setPage($page);
      $notificationDTO->setPer_page($per_page);
      $notificationDTO->setLimit($limit);

      return $this->notificationService->getAll($notificationDTO);

    }catch(\Exception $e){
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  // public function index()
  // {
  //     return response()->json(Auth::user()->notifications);
  // }

  // public function markAsRead($id)
  // {
  //     $notification = Auth::user()->notifications()->where('id', $id)->first();

  //     if ($notification) {
  //         $notification->update(['read_at' => now()]);
  //         return response()->json(['message' => 'Notification marked as read']);
  //     }

  //     return response()->json(['error' => 'Notification not found'], 404);
  // }

  // public function destroy($id)
  // {
  //     $notification = Auth::user()->notifications()->where('id', $id)->first();

  //     if ($notification) {
  //         $notification->delete();
  //         return response()->json(['message' => 'Notification deleted']);
  //     }

  //     return response()->json(['error' => 'Notification not found'], 404);
  // }

}
