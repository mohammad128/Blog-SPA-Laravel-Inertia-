<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Fortify\UpdateUserPassword;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Jenssegers\Agent\Agent;

class ProfileController extends Controller
{


    public function index() {
        return Inertia::render('Dashboard/User/Profile',[
            'sessions'=>$this->getSessionsProperty()
        ]);
    }



    public function update(Request $request) {
        $request->validate([
            'photo'=>[ 'nullable', 'image','mimes:jpg,jpeg,png,bmp,gif', 'dimensions:min_width=100,min_height=100,max_width=1500,max_height=1500'],
//            'username'=>['required', Rule::unique('users')->ignore($user)],
//            'email'=>['required', 'email', Rule::unique('users')->ignore($user)],
            'first_name' => ['required'],
            'last_name' => ['required'],
        ]);

        $user = \auth()->user();
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');


        if($request->hasFile('photo')) {
            Storage::delete('public/'.$user->profile_photo_path);
            $profile_photo_path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $profile_photo_path;
        }

        $user->save();
        return redirect()->back();
    }


    public function removeImage() {
        $user = \auth()->user();
        Storage::delete('public/'.$user->profile_photo_path);
        $user->profile_photo_path = '';
        $user->save();
        return redirect()->back();
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
