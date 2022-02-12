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

        $data = [
        ];

        $countData = $this->getCounts();
        if($countData) {
            $data[] = [
                'component' => 'Counts',
                'data' => $countData,
            ];
        }

        $in_30_days = $this->in30Days();
        $data[] = [
            'component' => 'In30Days',
            'data' => [
                'series' => $in_30_days['chart_data'],
                'categories' => array_keys( $in_30_days['days'] ),
            ],
        ];


        $overViewData = $this->getOverview();
        if($overViewData) {
            $data[] = [
                'component' => 'AngleCircleChart',
                'data' => $overViewData
            ];
        }

        $charts = $this->getCharts();
        if ($charts) {
            $data[] = [
                'component' => 'Charts',
                'data' => $charts
            ];
        }


        return Inertia::render('Dashboard/Index', [
            'data'=>$data,
        ]);
    }

    public function getOverview() {
        $series = [];
        $lables = [];

        $user = auth()->user();
        $countData = [];
        if( $user->can('read_post') ) {
            $series[] = Post::query()->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPosts() )->count();
            $lables[] = "Post";
        }
        if( $user->can('read_page') ) {
            $series[] = Page::query()->when(!auth()->user()->can('read_all_page'), fn($q) => $q->userPages() )->count();
            $lables[] = "Page";
        }
        if( $user->can('read_users') ) {
            $series[] = User::query()->count();
            $lables[] = "User";
        }
        if( $user->can('comments_actions') ) {
            $series[] = Comment::query()->count();
            $lables[] = "Comment";
        }
        if ($series)
            return  [
                'series' => $series,
                'labels' => $lables,
            ];
        return null;
    }


    public function getCharts() {

        $user = auth()->user();
        $charts = [];
        if( $user->can('read_post') ) {
            $published = Post::query()->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPosts() )->published()->count();
            $draft = Post::query()->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPosts() )->draft()->count();
            $trashed = Post::query()->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPosts() )->onlyTrashed()->count();
            $charts['Post'] = [
                'series' => [$published, $draft, $trashed],
                'labels' => ['Published ('.$published.')', 'Draft ('.$draft.')', 'Trash ('.$trashed.')']
            ];
        }
        if( $user->can('read_page') ) {
            $published = Page::query()->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPages() )->published()->count();
            $draft = Page::query()->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPages() )->draft()->count();
            $trashed = Page::query()->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPages() )->onlyTrashed()->count();
            $charts['Page'] = [
                'series' => [$published, $draft, $trashed],
                'labels' => ['Published ('.$published.')', 'Draft ('.$draft.')', 'Trash ('.$trashed.')']
            ];
        }
        if( $user->can('comments_actions') ) {
            $approved = Comment::approved()->count();
            $pending = Comment::pending()->count();
            $spam = Comment::spam()->count();
            $trashed = Comment::onlyTrashed()->count();
            $charts['Comment'] = [
                'series' => [$approved, $pending, $spam, $trashed],
                'labels' => ['Approved ('.$approved.')', 'Pending ('.$pending.')', 'Spam ('.$spam.')', 'Trash ('.$trashed.')']
            ];
        }
        return $charts;
    }

    public function getCounts() {

        $user = auth()->user();
        $countData = [];
        if( $user->can('read_post') ) {
            $countData['post'] = Post::query()->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPosts() )->count();
        }
        if( $user->can('read_page') ) {
            $countData['page'] = Page::query()->when(!auth()->user()->can('read_all_page'), fn($q) => $q->userPages() )->count();
        }
        if( $user->can('read_users') ) {
            $countData['user'] = User::query()->count();
        }
        if( $user->can('comments_actions') ) {
            $countData['comment'] = Comment::query()->count();
        }
        return $countData;
    }

    public function in30Days() {

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
        if( auth()->user()->can('read_post') ) {
            $posts = Post::query()->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPosts() )->select('created_at', 'updated_at', 'slug')
                ->where('created_at', '>=', $fromDate)
                ->where('created_at', '<=', $toDate)
                ->get()
                ->groupBy(function ($item) {
                    return $item->created_at->format('Y-m-d');
                })
                ->toArray();
            $post_data = $days;
            $count = 0;
            foreach ($posts as $key => $post) {
                $post_data[$key] = count($post);
                $count += count($post);
            }
            $chart_data[] = [
                'name' => 'Post (' . $count . ')',
                'data' => array_values($post_data)
            ];
        }
        /*
         * Page
         * */
        if( auth()->user()->can('read_page') ) {
            $pages = Page::query()->when(!auth()->user()->can('read_all_page'), fn($q) => $q->userPages() )->select('created_at', 'updated_at', 'slug')
                ->where('created_at', '>=', $fromDate)
                ->where('created_at', '<=', $toDate)
                ->get()
                ->groupBy(function ($item) {
                    return $item->created_at->format('Y-m-d');
                })
                ->toArray();
            $page_data = $days;
            $count = 0;
            foreach ($pages as $key => $page) {
                $page_data[$key] = count($page);
                $count += count($page);
            }
            $chart_data[] = [
                'name' => 'Page (' . $count . ')',
                'data' => array_values($page_data)
            ];
        }
        /*
         * User
         * */
        if( auth()->user()->can('read_users') ) {
            $users = User::select('created_at', 'updated_at')
                ->where('created_at', '>=', $fromDate)
                ->where('created_at', '<=', $toDate)
                ->get()
                ->groupBy(function ($item) {
                    return $item->created_at->format('Y-m-d');
                })
                ->toArray();
            $user_data = $days;
            $count = 0;
            foreach ($users as $key => $user) {
                $user_data[$key] = count($user);
                $count += count($user);
            }
            $chart_data[] = [
                'name' => 'User (' . $count . ')',
                'data' => array_values($user_data)
            ];
        }
        /*
         * Comments
         * */
        if( auth()->user()->can('comments_actions') ) {
            $comments = Comment::select('created_at', 'updated_at')
                ->where('created_at', '>=', $fromDate)
                ->where('created_at', '<=', $toDate)
                ->get()
                ->groupBy(function ($item) {
                    return $item->created_at->format('Y-m-d');
                })
                ->toArray();
            $comment_data = $days;
            $count = 0;
            foreach ($comments as $key => $comment) {
                $comment_data[$key] = count($comment);
                $count += count($comment);
            }
            $chart_data[] = [
                'name' => 'Comment (' . $count . ')',
                'data' => array_values($comment_data)
            ];
        }

        return [
            'chart_data' => $chart_data,
            'days' => $days
        ];
    }

}
