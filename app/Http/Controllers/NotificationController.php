<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * Mark a notification as read
     */
    public function markRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Check if notification belongs to authenticated user
        if ($notification->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }
        
        $notification->update(['read' => true]);
        
        // If notification has a link, redirect to it
        if ($notification->link) {
            return redirect($notification->link);
        }

        return redirect()->back()->with('success', 'Notification marked as read.');
    }


    
    /**
     * Mark all notifications as read
     */
    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);
            
        return redirect()->back()->with('success', 'All notifications marked as read');
    }
    
    /**
     * Create a new notification
     */
    public static function createNotification($initiatorUserId, $type, $message, $link = null, $data = null, $by = null, $status = null)
    {
        $users = User::where('id', '!=', $initiatorUserId)->get();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'message' => $message,
                'link' => $link,
                'read' => false,
                'data' => json_encode(array_merge((array) $data, ['by' => $by, 'status' => $status]))
            ]);
        }
    }

    /**
     * Fetch unread notifications for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('read', false)
            ->orderBy('created_at', 'desc')
            ->take(5) // Limit to 5 notifications for the dropdown
            ->get();

        $formattedNotifications = $notifications->map(function ($notification) {
             return [
                 'id' => $notification->id,
                'type' => $notification->type,
                'message' => $notification->message,
                'link' => $notification->link,
                'created_at_diff' => $notification->created_at->diffForHumans(),
                'data' => $notification->data
            ];
        });

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $formattedNotifications
        ]);
    }

    /**
     * Fetch all notifications for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedNotifications = $notifications->map(function ($notification) {
             return [
                 'id' => $notification->id,
                'type' => $notification->type,
                'message' => $notification->message,
                'link' => $notification->link,
                'created_at_diff' => $notification->created_at->diffForHumans(),
                'data' => $notification->data
            ];
        });

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $formattedNotifications
        ]);
    }
}
