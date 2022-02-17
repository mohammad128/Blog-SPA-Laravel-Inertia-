<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index() {
        return Inertia::render('Dashboard/User/Notifications/index');
    }

    public function clearNotifications() {
        auth()->user()->notifications()->delete();
        return back();
    }

    public function getNotifications(Request $request) {
        $request->validate([
            'page' => ['required', 'integer']
        ]);
        return $this->getNotificationsPaginate($request->get('page'));
    }

    public function getNotificationsPaginate($page=1) {
        $notifications = auth()->user()->notifications()
            ->paginate(null, ['*'], 'notif_page', $page)
            ->withQueryString()
            ->through(function ($item) {
                $result = collect($item)->only(['id','type', 'data', 'created_at','read_at', 'url']);
                switch ($result['type']) {
                    case 'App\\Notifications\\PostPublished':
                        $result['component'] = 'PostNotificationItem';
                        break;
                    case 'App\Notifications\CommentNotification':
                        $result['component'] = 'CommentNotificationItem';
                        break;
                    case 'App\Notifications\ReplyCommentNotification':
                        $result['component'] = 'ReplyCommentNotificationItem';
                        break;
                    case 'App\Notifications\CommentLikeNotification':
                        $result['component'] = 'CommentLikeNotificationItem';
                        break;
                    default:
                        $result['component'] = 'NotificationItem';

                }
                $result['dif_for_human'] = $item->created_at->diffForHumans();
                return $result;
            });;
        auth()->user()->unreadNotifications->markAsRead();
        return $notifications;
    }
}
