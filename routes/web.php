<?php

use App\Models\Menu;
use App\Models\Meta;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Jenssegers\Agent\Agent;

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

    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('index');

    // Auth User
    Route::middleware(['auth:sanctum', 'verified', 'inertia_dashboard'])->group(function () {
        /*
         * Dashboard Routes
         * */
        Route::prefix('/dashboard')->group(function () {
            // Index Dashboard
            Route::get('/', [\App\Http\Controllers\Dashboard\DashboardController::class,'index'])->name('dashboard');

            // Post Routes
            Route::prefix('/Post')->group(function (){
                Route::middleware(['can:read_post'])
                    ->get("/", [ \App\Http\Controllers\Dashboard\PostController::class, 'allPosts' ] )
                    ->name('dashboard.post.allPosts');
                Route::middleware(['can:create_post'])
                    ->get("/Create", [ \App\Http\Controllers\Dashboard\PostController::class, 'create' ] )
                    ->name('dashboard.post.create');
                Route::middleware(['can:create_post'])
                    ->post('/Create', [\App\Http\Controllers\Dashboard\PostController::class, 'store'])
                    ->name('dashboard.post.create');
                Route::middleware(['can:edit_post'])
                    ->get("/Edit/{post:slug}", [ \App\Http\Controllers\Dashboard\PostController::class, 'edit' ] )
                    ->name('dashboard.post.edit');
                Route::middleware(['can:edit_post'])
                    ->put("/Edit/{post:slug}", [ \App\Http\Controllers\Dashboard\PostController::class, 'update' ] )
                    ->name('dashboard.post.update');

                Route::middleware(['can:read_category'])
                    ->get('/Categories', [\App\Http\Controllers\Dashboard\PostController::class, 'categories'])
                    ->name('dashboard.post.categories');

                Route::middleware(['can:delete_post'])
                    ->post('/MultiDelete', [\App\Http\Controllers\Dashboard\PostController::class, 'destroy'])
                    ->name('dashboard.post.multiDelete');
                Route::middleware(['can:delete_post'])
                    ->delete('/{post:id}', [\App\Http\Controllers\Dashboard\PostController::class, 'delete'])
                    ->name('dashboard.post.delete');

                Route::middleware(['can:force_delete_post'])
                    ->delete('/forceDelete/{post:id}', [\App\Http\Controllers\Dashboard\PostController::class, 'forceDelete'])
                    ->name('dashboard.post.forceDelete');
                Route::middleware(['can:force_delete_post'])
                    ->post('/multiForceDelete', [\App\Http\Controllers\Dashboard\PostController::class, 'multiForceDelete'])
                    ->name('dashboard.post.multiForceDelete');



                Route::middleware(['can:restore_post'])
                    ->get('/restore/{id}', [\App\Http\Controllers\Dashboard\PostController::class, 'restore'])
                    ->name('dashboard.post.restore');
                Route::middleware(['can:restore_post'])
                    ->post('/restore', [\App\Http\Controllers\Dashboard\PostController::class, 'multiRestore'])
                    ->name('dashboard.post.multiRestore');

                //Web Services
                Route::middleware(['can:read_post'])
                    ->post("/PublishedPost", [ \App\Http\Controllers\Dashboard\PostController ::class, 'publishedPost' ] )->name('dashboard.post.service.published_posts');
            });

            // Post Routes
            Route::prefix('/Page')->group(function (){
                Route::middleware(['can:read_page'])
                    ->get("/", [ \App\Http\Controllers\Dashboard\PageController ::class, 'index' ] )
                    ->name('dashboard.page.index');

                Route::middleware(['can:create_page'])
                    ->get("/Create", [ \App\Http\Controllers\Dashboard\PageController::class, 'create' ] )
                    ->name('dashboard.page.create');

                Route::middleware(['can:create_page'])
                    ->post("/Create", [ \App\Http\Controllers\Dashboard\PageController ::class, 'store' ] )
                    ->name('dashboard.page.store');

                Route::middleware(['can:edit_page'])
                    ->get("/Edit/{page:slug}", [ \App\Http\Controllers\Dashboard\PageController::class, 'edit' ] )
                    ->name('dashboard.page.edit');

                Route::middleware(['can:edit_page'])
                    ->put("/Edit/{page:slug}", [ \App\Http\Controllers\Dashboard\PageController::class, 'update' ] )
                    ->name('dashboard.page.update');

                Route::middleware(['can:delete_page'])
                    ->delete('/{page:id}', [\App\Http\Controllers\Dashboard\PageController::class, 'delete'])
                    ->name('dashboard.page.delete');

                Route::middleware(['can:delete_page'])
                    ->post('/', [\App\Http\Controllers\Dashboard\PageController::class, 'destroy'])
                    ->name('dashboard.page.multiDelete');

                Route::middleware(['can:force_delete_page'])
                    ->post('/multiForceDelete', [\App\Http\Controllers\Dashboard\PageController::class, 'multiForceDelete'])
                    ->name('dashboard.page.multiForceDelete');

                Route::middleware(['can:force_delete_page'])
                    ->delete('/forceDelete/{page:id}', [\App\Http\Controllers\Dashboard\PageController::class, 'forceDelete'])
                    ->name('dashboard.page.forceDelete');

                Route::middleware(['can:restore_page'])
                    ->get('/restore/{id}', [\App\Http\Controllers\Dashboard\PageController::class, 'restore'])
                    ->name('dashboard.page.restore');

                Route::middleware(['can:restore_page'])
                    ->post('/restore', [\App\Http\Controllers\Dashboard\PageController::class, 'multiRestore'])
                    ->name('dashboard.page.multiRestore');

                //Web Services
                Route::middleware(['can:read_page'])
                    ->post("/PublishedPage", [ \App\Http\Controllers\Dashboard\PageController ::class, 'publishedPage' ] )
                    ->name('dashboard.page.service.published_pages');
            });

            // Tag Routes
            Route::prefix('/Tag')->group(function() {
                Route::middleware(['can:read_tag'])
                    ->get('/', [\App\Http\Controllers\Dashboard\TagController::class, 'index'])
                    ->name('dashboard.tag');
                Route::middleware(['can:create_tag'])
                    ->post('/', [\App\Http\Controllers\Dashboard\TagController::class, 'store'])
                    ->name('dashboard.tag.store');
                Route::middleware(['can:edit_tag'])
                    ->put('/{id}', [\App\Http\Controllers\Dashboard\TagController::class, 'update'])
                    ->name('dashboard.tag.update');
                Route::middleware(['can:delete_tag'])
                    ->delete('/{id}', [\App\Http\Controllers\Dashboard\TagController::class, 'delete'])
                    ->name('dashboard.tag.delete');
                Route::middleware(['can:delete_tag'])
                    ->post('/mulitDelete', [\App\Http\Controllers\Dashboard\TagController::class, 'multiDelete'])
                    ->name('dashboard.tag.multiDelete');
            });

            // Category Routes
            Route::prefix('/Category')->group(function (){
                Route::middleware(['can:create_category'])
                    ->post("/", [ \App\Http\Controllers\Dashboard\CategoryController::class, 'create' ] )
                    ->name('dashboard.category.create');
                Route::middleware(['can:edit_category'])
                    ->put("/{id}", [ \App\Http\Controllers\Dashboard\CategoryController::class, 'update' ] )
                    ->name('dashboard.category.update');
                Route::middleware(['can:delete_category'])
                    ->delete("/{id}", [ \App\Http\Controllers\Dashboard\CategoryController::class, 'delete' ] )
                    ->name('dashboard.category.delete');
                Route::middleware(['can:delete_category'])
                    ->post("/multiDelete", [ \App\Http\Controllers\Dashboard\CategoryController::class, 'multiDelete' ] )
                    ->name('dashboard.category.multiDelete');

                //Service
                Route::middleware(['can:read_category'])
                    ->post("/Service/getAllCategories", [ \App\Http\Controllers\Dashboard\CategoryController::class, 'getAllCategories' ] )
                    ->name('dashboard.category.all_categories');
            });

            // Media Routes
            Route::prefix('/Media')->group(function () {
                Route::middleware(['can:read_media'])
                    ->get('/', [ \App\Http\Controllers\Dashboard\MediaController::class, 'index'])
                    ->name('dashboard.media.index');
                Route::middleware(['can:create_media'])
                    ->get('/AddMedia', [ \App\Http\Controllers\Dashboard\MediaController::class, 'create'])
                    ->name('dashboard.media.create');
                Route::middleware(['can:create_media'])
                    ->post('/AddMedia/Service', [ \App\Http\Controllers\Dashboard\MediaController::class, 'store'])
                    ->name('dashboard.media.service');

                Route::get('/Download/{media:id}', [ \App\Http\Controllers\Dashboard\MediaController::class, 'download'])
                    ->name('dashboard.media.download');

                Route::middleware(['can:edit_media'])
                    ->put('/AddMedia/Service/{media:id}', [ \App\Http\Controllers\Dashboard\MediaController::class, 'update'])
                    ->name('dashboard.media.service.update');
                Route::middleware(['can:delete_media'])
                    ->delete('/AddMedia/Service/{media:id}', [ \App\Http\Controllers\Dashboard\MediaController::class, 'delete'])
                    ->name('dashboard.media.service.delete');
                Route::middleware(['can:delete_media'])
                    ->post('/AddMedia/Service/MultiDelete', [ \App\Http\Controllers\Dashboard\MediaController::class, 'multiDelete'])
                    ->name('dashboard.media.service.multidelte');
                Route::middleware(['can:read_media'])
                    ->post('/AddMedia/Service/filter', [ \App\Http\Controllers\Dashboard\MediaController::class, 'filter'])
                    ->name('dashboard.media.filter');
            });

            //Comments Route
            Route::middleware(['can:comments_actions'])->prefix('/Comments')->group(function() {
                Route::get('/', [\App\Http\Controllers\Dashboard\CommentController::class, 'index'])
                    ->name('dashboard.comment.index');
                Route::put('/{comment:id}', [\App\Http\Controllers\Dashboard\CommentController::class, 'update'])
                    ->name('dashboard.comment.update');
                Route::delete('/{comment:id}', [\App\Http\Controllers\Dashboard\CommentController::class, 'trash'])
                    ->name('dashboard.comment.trash');

                Route::post('/approveComments', [\App\Http\Controllers\Dashboard\CommentController::class, 'approveComments'])
                    ->name('dashboard.comment.approveComments');
                Route::post('/trashComments', [\App\Http\Controllers\Dashboard\CommentController::class, 'trashComments'])
                    ->name('dashboard.comment.trashComments');
                Route::post('/spamComments', [\App\Http\Controllers\Dashboard\CommentController::class, 'spamComments'])
                    ->name('dashboard.comment.spamComments');

                Route::get('/delete/{id}', [\App\Http\Controllers\Dashboard\CommentController::class, 'delete'])
                    ->name('dashboard.comment.delete');
                Route::post('/deleteComments', [\App\Http\Controllers\Dashboard\CommentController::class, 'deleteComments'])
                    ->name('dashboard.comment.deleteComments');
                Route::get('/restore/{id}', [\App\Http\Controllers\Dashboard\CommentController::class, 'restore'])
                    ->name('dashboard.comment.restore');
                Route::post('/restoreComments', [\App\Http\Controllers\Dashboard\CommentController::class, 'restoreComments'])
                    ->name('dashboard.comment.restoreComments');
            });


            // Users Routes
            Route::prefix('/User')->group(function() {

                Route::middleware(['can:read_users'])
                    ->get('/', [\App\Http\Controllers\Dashboard\UserController::class, 'index'])
                    ->name('dashboard.user.index');
                Route::middleware(['can:delete_users'])
                    ->delete('/{user:id}', [\App\Http\Controllers\Dashboard\UserController::class, 'delete'])
                    ->name('dashboard.user.delete');
                Route::middleware(['can:create_users'])
                    ->get('/Create', [\App\Http\Controllers\Dashboard\UserController::class, 'create'])
                    ->name('dashboard.user.create');
                Route::middleware(['can:create_users'])
                    ->post('/', [\App\Http\Controllers\Dashboard\UserController::class, 'store'])
                    ->name('dashboard.user.store');
                Route::middleware(['can:delete_users'])
                    ->post('/multiDelete', [\App\Http\Controllers\Dashboard\UserController::class, 'multiDelete'])
                    ->name('dashboard.user.multiDelete');

                Route::middleware(['can:edit_users'])
                    ->put('/{user:id}', [\App\Http\Controllers\Dashboard\UserController::class, 'update'])
                    ->name('dashboard.user.update');
                Route::middleware(['can:edit_users'])
                    ->delete('/removeImage/{user:id}', [\App\Http\Controllers\Dashboard\UserController::class, 'removeImage'])
                    ->name('dashboard.user.removeImage');

                /* Roles */
                Route::prefix('/Role')->group(function(){
                    Route::middleware(['can:read_role'])
                        ->get('/', [ \App\Http\Controllers\Dashboard\UserRoleController::class,'index' ])
                        ->name('dashboard.user.role');
                    Route::middleware(['can:delete_role'])
                        ->delete('/{role:id}', [ \App\Http\Controllers\Dashboard\UserRoleController::class,'delete' ])
                        ->name('dashboard.user.role.delete');
                    Route::middleware(['can:delete_role'])
                        ->post('/multiDelete', [ \App\Http\Controllers\Dashboard\UserRoleController::class,'multiDelete' ])
                        ->name('dashboard.user.role.multiDelete');
                    Route::middleware(['can:create_role'])
                        ->get('/Create', [ \App\Http\Controllers\Dashboard\UserRoleController::class,'create' ])
                        ->name('dashboard.user.role.create');
                    Route::middleware(['can:create_role'])
                        ->post('/Create', [ \App\Http\Controllers\Dashboard\UserRoleController::class,'store' ])
                        ->name('dashboard.user.role.store');
                    Route::middleware(['can:edit_role'])
                        ->get('/Edit/{role:name}', [ \App\Http\Controllers\Dashboard\UserRoleController::class,'edit' ])
                        ->name('dashboard.user.role.edit');
                    Route::middleware(['can:edit_role'])
                        ->put('/Edit/{role:name}', [ \App\Http\Controllers\Dashboard\UserRoleController::class,'update' ])
                        ->name('dashboard.user.role.update');
                });


                Route::prefix('/Profile')->group(function (){
                    Route::get('/', [\App\Http\Controllers\Dashboard\ProfileController::class, 'index'])
                        ->name('dashboard.user.profile');
                    Route::put('/UpdateProfile', [\App\Http\Controllers\Dashboard\ProfileController::class, 'update'])
                        ->name('dashboard.user.profile.update');
                    Route::delete('/removeImage/', [\App\Http\Controllers\Dashboard\ProfileController::class, 'removeImage'])
                        ->name('dashboard.user.profile.removeImage');

                });
            });

            // Settings Routes
            Route::middleware(['can:change_site_settings'])->prefix('/Settings')->controller(\App\Http\Controllers\Dashboard\SettingsController::class)->group(function() {
                Route::get('/', 'index')->name('dashboard.settings.index');
                Route::post('/', 'update')->name('dashboard.settings.update');
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


        Route::prefix('/Appearance')->group(function () {
            Route::get('/Menu', [\App\Http\Controllers\Dashboard\MenuController::class, 'index'])->name('dashboard.appearance.menu');
            Route::post('/Menu', [\App\Http\Controllers\Dashboard\MenuController::class, 'store'])->name('dashboard.appearance.store');
            Route::put('/Menu/{menu:id}', [\App\Http\Controllers\Dashboard\MenuController::class, 'update'])->name('dashboard.appearance.menu.update');
            Route::get('/Menu/Edit/{menu:name}', [\App\Http\Controllers\Dashboard\MenuController::class, 'edit'])->name('dashboard.appearance.menu.edit');
            Route::delete('/Menu/{menu:id}', [\App\Http\Controllers\Dashboard\MenuController::class, 'delete'])->name('dashboard.appearance.menu.delete');

            // Menu Items
            Route::delete('/MenuItem/{id}', [\App\Http\Controllers\Dashboard\MenuController::class, 'deleteMenuItem'])->name('dashboard.appearance.menuitem.delete');
            Route::post('/MenuItem/{menu:id}', [\App\Http\Controllers\Dashboard\MenuController::class, 'storeMenuItem'])->name('dashboard.appearance.menuitem.store');
            Route::post('/MenuItems/{menu:id}', [\App\Http\Controllers\Dashboard\MenuController::class, 'storeMenuItems'])->name('dashboard.appearance.menuitems.store');
            Route::put('/MenuItem/{menuItem:id}', [\App\Http\Controllers\Dashboard\MenuController::class, 'updateMenuItem'])->name('dashboard.appearance.menuitem.update');
            Route::post('/MenuItem/MoveNode/{id}/{parent_id}', [\App\Http\Controllers\Dashboard\MenuController::class, 'moveNode'])->name('dashboard.appearance.menuitem.moveNode');
            Route::post('/MenuItem/RebuildMenuItems/{menu:id}', [\App\Http\Controllers\Dashboard\MenuController::class, 'rebuildMenuItems'])->name('dashboard.appearance.menuitem.rebuildMenuItems');

            Route::prefix('/Sliders')->controller(\App\Http\Controllers\Dashboard\SliderControlelr::class)->group(function () {
                Route::get('/', 'index')->name('dashboard.appearance.sliders.index');
                Route::post('/', 'updateSlider')->name('dashboard.appearance.sliders.update');
            });
        });
    });


    // Gust Or Auth User
    Route::prefix("/Post")->group(function () {
        Route::get('/', [\App\Http\Controllers\PostController::class, 'index'])->name('Post.index');
        Route::post('/{id}', [\App\Http\Controllers\PostController::class, 'postPreview'])->name('Post.postPreview');
        Route::middleware(['auth:sanctum', 'verified'])->post('/SetPostRate/{post:id}', [\App\Http\Controllers\PostController::class, 'setPostRate'])->name('Post.setPostRate');
        Route::middleware(['auth:sanctum', 'verified'])->post('/like/{post:id}', [\App\Http\Controllers\PostController::class, 'like'])->name('Post.like');
        Route::post('/{post:id}/Comment/GetChild', [\App\Http\Controllers\PostController::class, 'getPostChildComment'])->name('Post.getPostChildComment');
        Route::middleware(['auth:sanctum', 'verified'])->post('/{post:id}/Comment/Like', [\App\Http\Controllers\PostController::class, 'likeComment'])->name('Post.likeComment');
        Route::middleware(['auth:sanctum', 'verified', 'can:create_comment'])->post('/{post:id}/Comment/Create', [\App\Http\Controllers\PostController::class, 'createComment'])->name('Post.createComment');
        Route::get('/{post:slug}', [\App\Http\Controllers\PostController::class, 'show'])->name('Post.show');
    });
    // Gust Or Auth User
    Route::prefix("/Page")->group(function () {
//        Route::get('/', [\App\Http\Controllers\PageController::class, 'index'])->name('Page.index');
//        Route::post('/{id}', [\App\Http\Controllers\PageController::class, 'postPreview'])->name('Page.postPreview');
        Route::get('/{page:slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('Page.show');
    });

    Route::prefix('/User')->group(function () {
        Route::get('/{user:name}')->name('User.index');
    });


});


Route::get('test', function (\Illuminate\Http\Request $request) {


    $post = \App\Models\Post::query()->published()->first();
//    $post->dislikeThis();
//    for($i=10; $i<30; $i++)
//        $post->addComment("this is test ". $i);
//    dd($post->comments->toArray());


//    dd( \App\Models\Post::orderByRate()->published()->limit(10)->get()->toArray() );
//    dd(\App\Models\Post::orderByRate()->limit(10)->get()->toArray());
//    dd($post->setUserRate(10));
//    dd($post->onChangeRate());
//    dd($post->toArray());
//    dd($post->rate());

//    for($i=13; $i<=14; $i++)
//        \App\Models\MenuItem::find(12)->appendNode( \App\Models\MenuItem::find($i) );
//
//    dd( $tree = \App\Models\MenuItem::withDepth()->having('depth', '<=', 1)->get()->toTree()->toArray() );

//    dd( \App\Models\MenuItem::find(21)->delete() );

//    $menu = Menu::query()->find(1);
//    dd($menu->items()->where('id', '=', 27)->delete());

//    $node = new \App\Models\MenuItem([
//        'text'=> 'TEST',
//        'href'=> 'TEST',
//    ]);
//    $node->makeRoot(); // Saved as root
//    $menu->items()->save( $node );

//    dd($menu->items()->defaultOrder()->get()->toTree()->toArray());
//    $menus = Menu::query()->select(['id', 'name', 'created_at'])->orderBy('id', 'asc')->get();
//    $menuItems = $menu->items()->select('id','text', 'href', 'menu_id', 'parent_id')->get()->toTree()->toArray();
//    $menu = $menu->toArray();
//    $menu['items']=$menuItems;
//
    return Inertia::render('test');

//    for($i=0; $i<2; $i++)
//        \App\Models\Post::find(867)->comments()->create([
//            'content' => "This is Test Comemnt ".$i,
//            'user_id' => auth()->id(),
//            'parent_id' => 2
//        ]);

//    $comment = \App\Models\Post::find(867)->comments()->rootComments()->paginate(null, ['*'], 'page', 2);//->offset(10)->limit(10)->get();
//
//    return Inertia::render('test', [
//        'comments' => $comment
//    ]);
});


//require_once __DIR__.'/fortify.php';
