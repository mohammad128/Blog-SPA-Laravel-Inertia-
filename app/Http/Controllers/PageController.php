<?php

namespace App\Http\Controllers;

use App\FlashMessage\Facade\FlashMessage;
use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function show(Page $page) {
        return Inertia::render('Page/Show/Index', [
            "post" => $page,
        ]);
    }
}
