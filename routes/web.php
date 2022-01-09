<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
            // Index Dashboard
            Route::get('/', [\App\Http\Controllers\Dashboard\DashboardController::class,'index'])->name('dashboard');

            // Post Routes
            Route::prefix('/Post')->group(function (){
                Route::get("/", [ \App\Http\Controllers\Dashboard\PostController::class, 'allPosts' ] )->name('dashboard.post.allPosts');
                Route::get("/Create", [ \App\Http\Controllers\Dashboard\PostController::class, 'create' ] )->name('dashboard.post.create');
                Route::get("/Edit/{post:slug}", [ \App\Http\Controllers\Dashboard\PostController::class, 'edit' ] )->name('dashboard.post.edit');
                Route::put("/Edit/{post:slug}", [ \App\Http\Controllers\Dashboard\PostController::class, 'update' ] )->name('dashboard.post.update');
                Route::get('/Categories', [\App\Http\Controllers\Dashboard\PostController::class, 'categories'])->name('dashboard.post.categories');

                Route::post('/Create', [\App\Http\Controllers\Dashboard\PostController::class, 'store'])->name('dashboard.post.create');

                Route::post('/MultiDelete', [\App\Http\Controllers\Dashboard\PostController::class, 'destroy'])->name('dashboard.post.multiDelte');
                Route::delete('/{post:id}', [\App\Http\Controllers\Dashboard\PostController::class, 'delete'])->name('dashboard.post.delete');
            });

            // Post Routes
            Route::prefix('/Page')->group(function (){
                Route::get("/", [ \App\Http\Controllers\Dashboard\PageController ::class, 'index' ] )->name('dashboard.page.index');
                Route::get("/Create", [ \App\Http\Controllers\Dashboard\PageController::class, 'create' ] )->name('dashboard.page.create');
                Route::post("/Create", [ \App\Http\Controllers\Dashboard\PageController ::class, 'store' ] )->name('dashboard.page.store');
                Route::get("/Edit/{page:slug}", [ \App\Http\Controllers\Dashboard\PageController::class, 'edit' ] )->name('dashboard.page.edit');
                Route::put("/Edit/{page:slug}", [ \App\Http\Controllers\Dashboard\PageController::class, 'update' ] )->name('dashboard.page.update');

                Route::delete('/{page:id}', [\App\Http\Controllers\Dashboard\PageController::class, 'delete'])->name('dashboard.page.delete');
                Route::post('/', [\App\Http\Controllers\Dashboard\PageController::class, 'destroy'])->name('dashboard.page.multiDelete');
                Route::post('/multiForceDelete', [\App\Http\Controllers\Dashboard\PageController::class, 'multiForceDelete'])->name('dashboard.page.multiForceDelete');
                Route::delete('/forceDelete/{page:id}', [\App\Http\Controllers\Dashboard\PageController::class, 'forceDelete'])->name('dashboard.page.forceDelete');

                Route::get('/restore/{id}', [\App\Http\Controllers\Dashboard\PageController::class, 'restore'])->name('dashboard.page.restore');
                Route::post('/restore', [\App\Http\Controllers\Dashboard\PageController::class, 'multiRestore'])->name('dashboard.page.multiRestore');
            });

            // Tag Routes
            Route::prefix('/Tag')->group(function() {
                Route::get('/', [\App\Http\Controllers\Dashboard\TagController::class, 'index'])->name('dashboard.tag');
                Route::post('/', [\App\Http\Controllers\Dashboard\TagController::class, 'store'])->name('dashboard.tag.store');
                Route::put('/{id}', [\App\Http\Controllers\Dashboard\TagController::class, 'update'])->name('dashboard.tag.update');
                Route::delete('/{id}', [\App\Http\Controllers\Dashboard\TagController::class, 'delete'])->name('dashboard.tag.delete');
                Route::post('/mulitDelete', [\App\Http\Controllers\Dashboard\TagController::class, 'multiDelete'])->name('dashboard.tag.multiDelete');
            });

            // Category Routes
            Route::prefix('/Category')->group(function (){
                Route::post("/", [ \App\Http\Controllers\Dashboard\CategoryController::class, 'create' ] )->name('dashboard.category.create');
                Route::put("/{id}", [ \App\Http\Controllers\Dashboard\CategoryController::class, 'update' ] )->name('dashboard.category.update');
                Route::delete("/{id}", [ \App\Http\Controllers\Dashboard\CategoryController::class, 'delete' ] )->name('dashboard.category.delete');
                Route::post("/multiDelete", [ \App\Http\Controllers\Dashboard\CategoryController::class, 'multiDelete' ] )->name('dashboard.category.multiDelete');
            });

            // Media Routes
            Route::prefix('/Media')->group(function () {
                Route::get('/', [ \App\Http\Controllers\Dashboard\MediaController::class, 'index'])->name('dashboard.media.index');
                Route::get('/AddMedia', [ \App\Http\Controllers\Dashboard\MediaController::class, 'create'])->name('dashboard.media.create');

                Route::get('/Download/{media:id}', [ \App\Http\Controllers\Dashboard\MediaController::class, 'download'])->name('dashboard.media.download');

                Route::post('/AddMedia/Service', [ \App\Http\Controllers\Dashboard\MediaController::class, 'store'])->name('dashboard.media.service');
                Route::put('/AddMedia/Service/{media:id}', [ \App\Http\Controllers\Dashboard\MediaController::class, 'update'])->name('dashboard.media.service.update');;
                Route::delete('/AddMedia/Service/{media:id}', [ \App\Http\Controllers\Dashboard\MediaController::class, 'delete'])->name('dashboard.media.service.delete');
                Route::post('/AddMedia/Service/MultiDelete', [ \App\Http\Controllers\Dashboard\MediaController::class, 'multiDelete'])->name('dashboard.media.service.multidelte');
                Route::post('/AddMedia/Service/filter', [ \App\Http\Controllers\Dashboard\MediaController::class, 'filter'])->name('dashboard.media.filter');
            });

            //Comments Route
            Route::prefix('/Comments')->group(function() {
                Route::get('/', [\App\Http\Controllers\Dashboard\CommentController::class, 'index'])->name('dashboard.comment.index');
                Route::put('/{comment:id}', [\App\Http\Controllers\Dashboard\CommentController::class, 'update'])->name('dashboard.comment.update');
                Route::delete('/{comment:id}', [\App\Http\Controllers\Dashboard\CommentController::class, 'trash'])->name('dashboard.comment.trash');

                Route::post('/approveComments', [\App\Http\Controllers\Dashboard\CommentController::class, 'approveComments'])->name('dashboard.comment.approveComments');
                Route::post('/trashComments', [\App\Http\Controllers\Dashboard\CommentController::class, 'trashComments'])->name('dashboard.comment.trashComments');
                Route::post('/spamComments', [\App\Http\Controllers\Dashboard\CommentController::class, 'spamComments'])->name('dashboard.comment.spamComments');

                Route::get('/delete/{id}', [\App\Http\Controllers\Dashboard\CommentController::class, 'delete'])->name('dashboard.comment.delete');
                Route::post('/deleteComments', [\App\Http\Controllers\Dashboard\CommentController::class, 'deleteComments'])->name('dashboard.comment.deleteComments');
                Route::get('/restore/{id}', [\App\Http\Controllers\Dashboard\CommentController::class, 'restore'])->name('dashboard.comment.restore');
                Route::post('/restoreComments', [\App\Http\Controllers\Dashboard\CommentController::class, 'restoreComments'])->name('dashboard.comment.restoreComments');
            });

            // Users Routes
            Route::prefix('/User')->group(function() {
                Route::get('/', function (){
                    dd("Users");
                })->name('dashboard.user.index');

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
        Route::get('/{post:slug}', [\App\Http\Controllers\PostController::class, 'show'])->name('Post.show');
    });

});


Route::get('test', function () {
//    for($i=0; $i<2; $i++)
//        \App\Models\Post::find(867)->comments()->create([
//            'content' => "This is Test Comemnt ".$i,
//            'user_id' => auth()->id(),
//            'parent_id' => 2
//        ]);

    $comment = \App\Models\Post::find(867)->comments()->rootComments()->paginate(null, ['*'], 'page', 2);//->offset(10)->limit(10)->get();

    return Inertia::render('test', [
        'comments' => $comment
    ]);
});


//require_once __DIR__.'/fortify.php';
