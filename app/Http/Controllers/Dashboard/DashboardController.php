<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use function Sodium\add;

class DashboardController extends Controller
{
    public function index() {

        $chart_data = [];
        $days = [];
        $tmp = now()->subDay(30)->endOfDay();
        $days[$tmp->format('Y-m-d')] = 0;
        for ($i=0; $i<30; $i++) {
            $days[$tmp->addDay()->format('Y-m-d')] = 0;
        }
        /*
         * Posts
         * */
        $posts = Post::select('created_at','updated_at', 'slug')
            ->where('created_at', '>', now()->subDay(30)->endOfDay())
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->toArray();
        $post_data = $days;
        foreach ($posts as $key=>$post) {
            $post_data[$key] = count($post);
        }
        $chart_data[0] = [
            'name' => 'Post',
            'data' => array_values($post_data)
        ];

        /*
         * Page
         * */
        $posts = Page::select('created_at','updated_at', 'slug')
            ->where('created_at', '>', now()->subDay(30)->endOfDay())
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->toArray();
        $post_data = $days;
        foreach ($posts as $key=>$post) {
            $post_data[$key] = count($post);
        }
        $chart_data[1] = [
            'name' => 'Page',
            'data' => array_values($post_data)
        ];
        /*
         * User
         * */
        $posts = User::select('created_at','updated_at')
            ->where('created_at', '>', now()->subDay(30)->endOfDay())
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->toArray();
        $post_data = $days;
        foreach ($posts as $key=>$post) {
            $post_data[$key] = count($post);
        }
        $chart_data[2] = [
            'name' => 'User',
            'data' => array_values($post_data)
        ];
        /*
         * Comments
         * */
        $posts = Comment::select('created_at','updated_at')
            ->where('created_at', '>', now()->subDay(30)->endOfDay())
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->toArray();
        $post_data = $days;
        foreach ($posts as $key=>$post) {
            $post_data[$key] = count($post);
        }
        $chart_data[3] = [
            'name' => 'Comment',
            'data' => array_values($post_data)
        ];

        return Inertia::render('Dashboard/Index', [
            'counts'=>[
                'post' => Post::count(),
                'page' => Page::count(),
                'user' => User::count(),
                'comment' => Comment::count(),
            ],
            'in_30_days_chart' => [
                'chart_data' => $chart_data,
                'days' => array_keys( $days ),
            ],
            'post_chart' => [
                'series' => [Post::published()->count(), Post::draft()->count(), Post::onlyTrashed()->count()],
                'labels' => ['Published', 'Draft', 'Trash']
            ],
            'page_chart' => [
                'series' => [Page::published()->count(), Page::draft()->count(), Page::onlyTrashed()->count()],
                'labels' => ['Published', 'Draft', 'Trash']
            ],
            'comment_chart' => [
                'series' => [Comment::approved()->count(), Comment::pending()->count(), Comment::spam()->count(), Comment::onlyTrashed()->count()],
                'labels' => ['Approved', 'Pending', 'Spam', 'Trash']
            ],
            'user_chart' => [
                'series' => [User::count()],
                'labels' => ['All']
            ],
        ]);
    }
}
