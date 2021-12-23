<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MediaController extends Controller
{

    public function index() {
//        return Inertia::render('Dashboard/Media/Index',[
//            'all_media' => Media::with('user')->orderBy('id', 'desc')->paginate()
//        ]);
        return Inertia::render('Dashboard/Media/Index');
    }

    public function create() {
        return Inertia::render('Dashboard/Media/Create');
    }

    public function store(Request $request) {
//        dd($request->file('file') );
//        $path = Storage::putFile('medias', $request->file('file'));
        $originalName = $request->file('file')->getClientOriginalName();
        $filename = pathinfo($originalName, PATHINFO_FILENAME);
        $mime = $request->file('file')->getMimeType();

        $_type = explode("/", $mime);
//        'image', 'video',  'audio', 'document',  'file', 'other'
        $type = 'file';
        switch ($_type[0]) {
            case 'image':
                $type = 'image';
                break;
            case 'audio':
                $type = 'audio';
                break;
            case 'video':
                $type = 'video';
                break;
            case 'application':
                $type = 'document';
                break;
        }

        $path = $request->file('file')->store('media', 'public');
        $result = Media::create([
            'title' => $filename,
            'description' => '',
            'path' => $path,
            'user_id' => auth()->user()->id,
            'type' => $type,
        ]);

        return $result;
    }


    public function filter(Request $request) {

        $currentPage = $request->get('page');
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

//        return Media::select("*")->when( $request->get('search'), function ($query) use ($request){
//            $query->where('title', 'LIKE' , "%".$request->get('search') ."%");
//        } )->get();


        $medias = Media::with('user')->orderBy('id', 'desc');
        if( $request->get('search') ) {
            $medias->where('title', 'LIKE' , '%'.$request->get('search').'%');
        }
        if( $request->get('mediaType') != 'all' &&
            $request->get('mediaType') !='' &&
            in_array( $request->get('mediaType'), ['image', 'video',  'audio', 'document',  'file', 'other'] ) ) {
            $medias->where('type', $request->get('mediaType'));
        }
        if( auth()->user()->can('read_all_media') && $request->get('user_id') != 0 ) {
            $medias->where('user_id', $request->get('user_id'));
        }
        return $medias->paginate();
    }

    public function update(Request $request,Media $media) {
        $request->validate([
            'title'=> ['required']
        ]);
        return $media->update($request->only(['title','description']));
    }

    public function delete(Media $media) {
        return $media->delete();
    }

    public function multiDelete(Request $resquest) {
        if($resquest->get('ids') && is_array($resquest->get('ids')))
            return Media::destroy($resquest->get('ids'));
        return '0';
//        $count = 0;
//        if($resquest->get('ids') && is_array($resquest->get('ids'))) {
//            foreach ($resquest->get('ids') as $id) {
//                $count++;
//                Media::find($id)->delete();
//            }
//        }
//        return $count;
    }

    public function download(Media $media)
    {
        $ext = pathinfo($media['path'], PATHINFO_EXTENSION);
        return response()->download( storage_path( 'app/public/'.$media['path'] ), $media['title'].'.'.$ext );
    }


}
