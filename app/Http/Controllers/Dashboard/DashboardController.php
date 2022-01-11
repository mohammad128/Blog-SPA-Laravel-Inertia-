<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as RequestFacade;
use Inertia\Inertia;
use function Sodium\add;

class DashboardController extends Controller
{
    public function index() {

        $fromDate = null;
        $toDate = null;
//        $now = Carbon::now();
//
//        $diff = $date->diffInDays($now);
//
        if(RequestFacade::input('fromDate'))
            $fromDate = Carbon::parse(RequestFacade::input('fromDate'));
        else $fromDate = now()->subDay(30)->endOfDay();

        if(RequestFacade::input('toDate'))
            $toDate = Carbon::parse(RequestFacade::input('toDate'));
        else $toDate = now();

        $diff = $fromDate->diffInDays($toDate);
        $chart_data = [];
        $days = [];
        $tmp = $fromDate;//now()->subDay(30)->endOfDay();
        $days[$tmp->format('Y-m-d')] = 0;
        for ($i=0; $i<=$diff; $i++) {
            $days[$tmp->addDay()->format('Y-m-d')] = 0;
        }

        if(RequestFacade::input('fromDate'))
            $fromDate = Carbon::parse(RequestFacade::input('fromDate'));
        else $fromDate = now()->subDay(30)->endOfDay();



        /*
         * Posts
         * */
        $posts = Post::select('created_at','updated_at', 'slug')
            ->where('created_at', '>=', $fromDate)
            ->where('created_at', '<=', $toDate)
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->toArray();
        $post_data = $days;
        $count = 0;
        foreach ($posts as $key=>$post) {
            $post_data[$key] = count($post);
            $count += count($post);
        }
        $chart_data[0] = [
            'name' => 'Post ('.$count.')',
            'data' => array_values($post_data)
        ];

        /*
         * Page
         * */
        $pages = Page::select('created_at','updated_at', 'slug')
            ->where('created_at', '>=', $fromDate)
            ->where('created_at', '<=', $toDate)
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->toArray();
        $page_data = $days;
        $count = 0;
        foreach ($pages as $key=>$page) {
            $page_data[$key] = count($page);
            $count += count($page);
        }
        $chart_data[1] = [
            'name' => 'Page ('.$count.')',
            'data' => array_values($page_data)
        ];
        /*
         * User
         * */
        $users = User::select('created_at','updated_at')
            ->where('created_at', '>=', $fromDate)
            ->where('created_at', '<=', $toDate)
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->toArray();
        $user_data = $days;
        $count = 0;
        foreach ($users as $key=>$user) {
            $user_data[$key] = count($user);
            $count += count($user);
        }
        $chart_data[2] = [
            'name' => 'User ('.$count.')',
            'data' => array_values($user_data)
        ];
        /*
         * Comments
         * */
        $comments = Comment::select('created_at','updated_at')
            ->where('created_at', '>=', $fromDate)
            ->where('created_at', '<=', $toDate)
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->toArray();
        $comment_data = $days;
        $count = 0;
        foreach ($comments as $key=>$comment) {
            $comment_data[$key] = count($comment);
            $count += count($comment);
        }
        $chart_data[3] = [
            'name' => 'Comment ('.$count.')',
            'data' => array_values($comment_data)
        ];


        return Inertia::render('Dashboard/Index', [
            'counts'=>[
                'post' => Post::count(),
                'page' => Page::count(),
                'user' => User::count(),
                'comment' => Comment::count(),
            ],
            'in_30_days_chart' => [
                'series' => $chart_data,
                'categories' => array_keys( $days ),
            ],
            'post_chart' => [
                'series' => [Post::published()->count(), Post::draft()->count(), Post::onlyTrashed()->count()],
                'labels' => ['Published ('.Post::published()->count().')', 'Draft ('.Post::draft()->count().')', 'Trash ('.Post::onlyTrashed()->count().')']
            ],
            'page_chart' => [
                'series' => [Page::published()->count(), Page::draft()->count(), Page::onlyTrashed()->count()],
                'labels' => ['Published ('.Page::published()->count().')', 'Draft ('.Page::draft()->count().')', 'Trash ('.Page::onlyTrashed()->count().')']
            ],
            'comment_chart' => [
                'series' => [Comment::approved()->count(), Comment::pending()->count(), Comment::spam()->count(), Comment::onlyTrashed()->count()],
                'labels' => ['Approved ('.Comment::approved()->count().')', 'Pending ('.Comment::pending()->count().')', 'Spam ('.Comment::spam()->count().')', 'Trash ('.Comment::onlyTrashed()->count().')']
            ],
            'user_chart' => [
                'series' => [User::count()],
                'labels' => ['All ('.User::count().')']
            ],
        ]);
    }
}
