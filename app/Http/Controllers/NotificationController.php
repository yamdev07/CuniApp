<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use \App\Traits\Notifiable;

    // public function index()
    // {
    //     $notifications = Notification::where('user_id', Auth::id())
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(25);

    //     $unreadCount = Notification::where('user_id', Auth::id())
    //         ->where('is_read', false)
    //         ->count();

    //     return view('notifications.index', compact('notifications', 'unreadCount'));
    // }

    // public function markAsRead(string $id)
    // {
    //     $notification = Notification::where('user_id', Auth::id())
    //         ->findOrFail($id);

    //     $notification->markAsRead();

    //     // Always return JSON for AJAX calls from dashboard
    //     if ($notification->action_url) {
    //         return response()->json([
    //             'success' => true,
    //             'redirect' => $notification->action_url
    //         ]);
    //     }

    //     return response()->json(['success' => true]);
    // }

    

        public function index(Request $request)
    {
        $query = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Gestion du filtre (lus, non-lus, ou tous)
        $filter = $request->get('filter', 'all'); // Par défaut: 'all'

        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        }

        $notifications = $query->paginate(25);

        // Le compteur de non-lues reste global pour l'affichage
        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return view('notifications.index', compact('notifications', 'unreadCount', 'filter'));
    }
    
        public function markAsRead(string $id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->markAsRead();

        // Vérifie si la requête est une demande AJAX (attend du JSON)
        if (request()->expectsJson() || request()->ajax()) {
            // Cas 1 : Appel depuis le dashboard (petite cloche) via JS
            if ($notification->action_url) {
                return response()->json([
                    'success' => true,
                    'redirect' => $notification->action_url
                ]);
            }
            return response()->json(['success' => true]);
        }

        // Cas 2 : Appel direct depuis le bouton dans la page "Notifications"
        // On retourne simplement vers la page précédente avec un message de succès
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
