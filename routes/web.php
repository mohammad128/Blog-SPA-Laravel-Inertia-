<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([])->group(function () {

    Route::get('/', function () {
        return Inertia::render('Welcome');
    })->name('index');

    // Auth User
    Route::middleware(['auth:sanctum', 'verified', 'inertia_dashboard'])->group(function () {
        /*
         * Dashboard Routes
         * */
        Route::prefix('/dashboard')->group(function () {
            Route::get('', function () {
                sleep(2);
                return Inertia::render('Dashboard/Index');
            })->name('dashboard');

            // Post Routes
            Route::prefix('/Post')->group(function (){
                Route::get("/", [ \App\Http\Controllers\Dashboard\PostController::class, 'allPosts' ] )->name('dashboard.post.allPosts');
                Route::get("/Create", [ \App\Http\Controllers\Dashboard\PostController::class, 'create' ] )->name('dashboard.post.create');
                Route::get('/Categories', [\App\Http\Controllers\Dashboard\PostController::class, 'categories'])->name('dashboard.post.categories');
            });

            // Post Routes
            Route::prefix('/Category')->group(function (){
                Route::post("/", [ \App\Http\Controllers\Dashboard\CategoryController::class, 'create' ] )->name('dashboard.category.create');
                Route::put("/{id}", [ \App\Http\Controllers\Dashboard\CategoryController::class, 'update' ] )->name('dashboard.category.update');
                Route::delete("/{id}", [ \App\Http\Controllers\Dashboard\CategoryController::class, 'delete' ] )->name('dashboard.category.delete');
                Route::post("/multiDelete", [ \App\Http\Controllers\Dashboard\CategoryController::class, 'multiDelete' ] )->name('dashboard.category.multiDelete');
            });

        });

        /*
         * Other Routes
         * */
        Route::get('user/profile', function () {
            abort(404);
        });
        Route::get('dashboard/user/profile', function () {
            return Inertia::render('Profile/Show');
        })->name('profile.show');

    });


    // Gust Or Auth User
    Route::prefix("/Post")->group(function () {
        Route::get('/', [\App\Http\Controllers\PostController::class, 'index'])->name('Post.index');
        Route::post('/{id}', [\App\Http\Controllers\PostController::class, 'postPreview'])->name('Post.postPreview');
    });

});


Route::get('test', function () {
//    auth()->user()->syncRoles('admin');

//    $permissions = [
//        "Post" => [
//            'desc' => "Permissions For Manage Posts",
//            'permissions' => [
//                [ 'name' => 'read_post', 'desc' => "Read Post" ],
//                [ 'name' => 'create_post', 'desc' => "Create Post" ],
//                [ 'name' => 'delete_post', 'desc' => "Delete Post" ],
//                [ 'name' => 'edit_post', 'desc' => "Edit Post" ],
//                [ 'name' => 'force_delete_post', 'desc' => "Delete From Trash" ],
//                [ 'name' => 'read_all_post', 'desc' => "Read All Post" ],
//                [ 'name' => 'delete_all_post', 'desc' => "Delete All Post" ],
//                [ 'name' => 'edit_all_post', 'desc' => "Edit All Post" ],
//                [ 'name' => 'force_delete_all_post', 'desc' => "Delete All Users From Trash" ],
//            ],
//        ],
//        "Page" => [
//            'desc' => "Permissions For Manage Pages",
//            'permissions' => [
//                [ 'name' => 'read_page', 'desc' => "Read Page" ],
//                [ 'name' => 'create_page', 'desc' => "Create Page" ],
//                [ 'name' => 'delete_page', 'desc' => "Delete Page" ],
//                [ 'name' => 'edit_page', 'desc' => "Edit Page" ],
//                [ 'name' => 'force_delete_page', 'desc' => "Delete Page From Trash" ],
//                [ 'name' => 'read_all_page', 'desc' => "Read All Page" ],
//                [ 'name' => 'delete_all_page', 'desc' => "Delete All Page" ],
//                [ 'name' => 'edit_all_page', 'desc' => "Edit All Page" ],
//                [ 'name' => 'force_delete_all_page', 'desc' => "Delete All Users Page From Trash" ],
//            ],
//        ],
//    ];
//
//    $roles = [
//        'Editor' => $permissions,
//    ];
//    foreach ($roles as $role_name=>$role_permissions) {
//        $__role_permissions = [];
//        foreach ($role_permissions as $permission_cat => $_p) {
//            foreach ($_p['permissions'] as $__p) {
//                $name = $__p['name'];
//                $desc = $__p['desc'];
//                array_push($__role_permissions, Permission::query()->where(['name'=>$name])->first() );
//            }
//        }
//        $r = Role::create(['name'=>$role_name]);
//        $r->syncPermissions($__role_permissions);
//    }
//
//    $user = \App\Models\User::find(402)->syncRoles('Editor');



//    app('rinvex.categories.category')->create(['name' => ['en' => 'New Category 3'], 'slug' => 'Cat3']);
//    $parent = app('rinvex.categories.category')->find(12);
//    $parent->children()->create(['name' => ['en' => 'Sub Category 12-1']]);

//    $attributes = [
//        'name' => "Cat7",
//        'parent_id' => '',
//        'slug' => 'دسته هفت',
//        'description' =>'Test Description For Cat 6'
//    ];
//
//    app('rinvex.categories.category')->create($attributes); // Saved as root


//    $categories = app('rinvex.categories.category')->get()->toTree();
//    $traverse = function ($categories, $prefix = '') use (&$traverse) {
//        foreach ($categories as $category) {
//            echo PHP_EOL.$prefix.' '.$category->name.'<br>';
//
//            $traverse($category->children, $prefix.'___');
//        }
//    };
//    $traverse($categories);

//    echo \Illuminate\Support\Str::slug("", "-");

//    echo ($categories = app('rinvex.categories.category')->get()->toTree() );

});


//require_once __DIR__.'/fortify.php';
