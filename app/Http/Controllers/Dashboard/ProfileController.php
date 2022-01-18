<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Jenssegers\Agent\Agent;

class ProfileController extends Controller
{


    public function index() {
        return Inertia::render('Dashboard/User/Profile',[
            'sessions'=>$this->getSessionsProperty()
        ]);
    }


    /**
     * Get the current sessions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSessionsProperty()
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->where('user_id', Auth::user()->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) {
            return (object) [
                'agent' => $this->createAgent($session),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    /**
     * Create a new agent instance from the given session.
     *
     * @param  mixed  $session
     * @return \Jenssegers\Agent\Agent
     */
    protected function createAgent($session)
    {
        $agent = new Agent();
        $agent->setUserAgent( $session->user_agent );
//        dump($agent->isDesktop());
//        dump($agent->isMobile());
//        dump( $agent->platform() );
//        dump( $agent->browser() );
        return [
            'browser'=> $agent->browser(),
            'is_desktop'=> $agent->isDesktop(),
            'platform' => $agent->platform(),
        ];
//        return tap(new Agent, function ($agent) use ($session) {
//            $agent->setUserAgent($session->user_agent);
//        });
    }
}
