<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use \App\Traits\Notifiable;

    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(25);
        
        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(string $id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $notification->markAsRead();
        
        if ($notification->action_url && request()->wantsJson()) {
            return response()->json([
                'redirect' => $notification->action_url
            ]);
        }
        
        return back()->with('success', 'Notification marquée comme lue');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    public function destroy(string $id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);
        $notification->delete();
        
        return back()->with('success', 'Notification supprimée');
    }
}