<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->notifications();

        if ($request->has('unread_only') && $request->boolean('unread_only')) {
            $query->where('is_read', false);
        }

        $notifications = $query->latest()->paginate(20);

        return $this->success($notifications);
    }

    public function show(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            return $this->error('Unauthorized', 403);
        }

        return $this->success($notification);
    }

    public function markAsRead(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            return $this->error('Unauthorized', 403);
        }

        $notification->markAsRead();

        return $this->success($notification);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()
            ->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return $this->success(['message' => 'All notifications marked as read']);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $count = $request->user()
            ->notifications()
            ->where('is_read', false)
            ->count();

        return $this->success(['count' => $count]);
    }

    public function destroy(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            return $this->error('Unauthorized', 403);
        }

        $notification->delete();

        return $this->success(['message' => 'Notification deleted']);
    }
}
