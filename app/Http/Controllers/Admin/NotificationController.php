<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function count()
    {
        $count = Notification::where('seen', false)->count();
        return response()->json(['count' => $count]);
    }

    public function markAsSeen(Request $request)
    {
        if ($request->has('id')) {
            $notification = Notification::findOrFail($request->id);
            $notification->update(['seen' => true]);
        } else {
            Notification::where('seen', false)->update(['seen' => true]);
        }
        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return response()->json(['success' => true]);
    }

    public function dropdown()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->limit(10)->get();

        if ($notifications->isEmpty()) {
            return '';
        }

        $html = '';
        foreach ($notifications as $notification) {
            $bgClass = $notification->seen ? 'bg-gray-50' : 'bg-blue-50';
            $badgeClass = $notification->seen ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800';
            $seenButton = !$notification->seen ? "<button onclick=\"markAsSeen({$notification->id})\" class=\"seen-button text-xs text-blue-600 hover:text-blue-800\">Seen</button>" : '';
            $date = $notification->created_at->format('M j, Y');

            $messageHtml = $notification->message;
            if ($notification->type === 'payment_submitted' && isset($notification->data['payment_id'])) {
                $messageHtml = "<a href=\"/admin/payments/{$notification->data['payment_id']}\" class=\"text-sm text-blue-600 hover:text-blue-800\">{$notification->message}</a>";
            } elseif ($notification->type === 'property_submitted' && isset($notification->data['property_id'])) {
                $messageHtml = "<a href=\"/admin/properties/{$notification->data['property_id']}\" class=\"text-sm text-blue-600 hover:text-blue-800\">{$notification->message}</a>";
            } else {
                $messageHtml = "<p class=\"text-sm text-gray-900\">{$notification->message}</p>";
            }

            $html .= "<div class=\"px-4 py-3 border-b border-gray-100 {$bgClass}\" data-notification-id=\"{$notification->id}\">
                <div class=\"flex justify-between items-start\">
                    <div class=\"flex-1\">
                        <div class=\"flex items-center space-x-2 mb-1\">
                            <span class=\"inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium type-badge {$badgeClass}\">
                                " . ucfirst($notification->type) . "
                            </span>
                            <span class=\"text-xs text-gray-500\">{$date}</span>
                        </div>
                        {$messageHtml}
                    </div>
                    <div class=\"flex space-x-1 ml-2\">
                        {$seenButton}
                        <button onclick=\"deleteNotification({$notification->id})\" class=\"text-xs text-red-600 hover:text-red-800\">Delete</button>
                    </div>
                </div>
            </div>";
        }

        return $html;
    }
}