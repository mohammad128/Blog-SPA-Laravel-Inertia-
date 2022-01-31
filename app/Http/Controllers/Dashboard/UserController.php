<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index() {
        $pre_page = RequestFacade::input('postPrePage') ? RequestFacade::input('postPrePage') : 15;
        $users = User::with(['roles'])
            ->when(RequestFacade::input('search'), function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'LIKE', '%'.RequestFacade::input('search').'%')
                        ->orWhere('email', 'LIKE', '%'.RequestFacade::input('search').'%');
                });
            })
            ->when(RequestFacade::input('fromDate'), function ($query) {
                $query->where('created_at', '>=', RequestFacade::input('fromDate'));
            })
            ->when(RequestFacade::input('toDate'), function ($query) {
                $query->where('created_at', '<=', RequestFacade::input('toDate'));
            })
            ->when(RequestFacade::input('selectedRoles'), function ($query) {
                $_roles = explode('|', RequestFacade::input('selectedRoles'));
                if(is_array($_roles))
                    $query->whereHas('roles', function ($query) use($_roles) {
                        $query->whereIn('name', $_roles);
                    });
            })
            ->when(RequestFacade::input('orderKey') && RequestFacade::input('orderType'), function ($query){
                $orderKey = RequestFacade::input('orderKey');
                $orderType = RequestFacade::input('orderType');
                if( !in_array($orderType, ['desc', 'asc']) OR !in_array($orderKey, ['name', 'email', 'role', 'created_at', 'post', 'page','comment']) )
                    return $query;
                switch($orderKey) {
                    case 'name':
                        return $query->orderBy('name', $orderType);
                        break;
                    case 'email':
                        return $query->orderBy('email', $orderType);
                        break;
                    case 'created_at':
                        return $query->orderBy('created_at', $orderType);
                        break;
                    case 'post':
                        return $query->orderBy('posts_count', $orderType);
                        break;
                    case 'page':
                        return $query->orderBy('pages_count', $orderType);
                        break;
                    case 'comment':
                        return $query->orderBy('comments_count', $orderType);
                        break;
                }
                return  $query;
            })
            ->withCount(['posts','comments', 'pages'])
            ->paginate($pre_page)->withQueryString();

        $roles = Role::all('id', 'name');
        return Inertia::render('Dashboard/User/Index', [
            'users'=> $users,
            '_roles'=> $roles,
            '_filters'=>[
                'page' => RequestFacade::input('page', '1'),
                'fromDate' => RequestFacade::input('fromDate', ''),
                'toDate' => RequestFacade::input('toDate', ''),
                'search' => RequestFacade::input('search', ''),
                'postPrePage' => RequestFacade::input('postPrePage', '15'),
                'selectedRoles' => RequestFacade::input('selectedRoles', ''),
                'orderKey' => RequestFacade::input('orderKey', ''),
                'orderType' => RequestFacade::input('orderType', ''),
            ]
        ]);
    }

    public function create() {
        return Inertia::render('Dashboard/User/Create', [
            '_roles'=>Role::all('name', 'id')
        ]);
    }

    public function store( Request $request ) {
        $request->validate([
            'photo'=>[ 'nullable', 'image','mimes:jpg,jpeg,png,bmp,gif', 'dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000']
        ]);
        $user = ( new CreateNewUser)->create($request->all());
        $user->syncRoles($request->get('roles'));
        if($request->hasFile('photo')) {
            $profile_photo_path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $profile_photo_path;
            $user->save();
        }
        return redirect()->route('dashboard.user.index');
    }


    public function update(User $user, Request $request) {
        $request->validate([
            'photo'=>[ 'nullable', 'image','mimes:jpg,jpeg,png,bmp,gif', 'dimensions:min_width=100,min_height=100,max_width=1500,max_height=1500'],
            'name'=>['required', Rule::unique('users')->ignore($user)],
            'email'=>['required', 'email', Rule::unique('users')->ignore($user)],
            'roles'=>['nullable', 'array']
        ]);

        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if($request->exists('roles'))
            $user->syncRoles($request->get('roles'));

        if($request->hasFile('photo')) {
            Storage::delete('public/'.$user->profile_photo_path);
            $profile_photo_path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $profile_photo_path;
        }

        $user->save();
        return redirect()->back();
    }

    public function delete( User $user) {
        $user->delete();
        return redirect()->back();
    }
    public function removeImage(User $user) {
        Storage::delete('public/'.$user->profile_photo_path);
        $user->profile_photo_path = '';
        $user->save();
        return redirect()->back();
    }
    public function multiDelete( Request $request) {
        User::whereIn('id', $request->get('ids'))->delete();
        return redirect()->back();
    }
}
