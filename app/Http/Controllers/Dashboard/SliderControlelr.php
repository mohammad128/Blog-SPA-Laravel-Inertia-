<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Meta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SliderControlelr extends Controller
{
    public function index() {
        return Inertia::render('Dashboard/Appearance/Slider/Index', [
            'home_slider' => Meta::getHomeSlider()
        ]);
    }

    public function updateSlider( Request $request) {
        Meta::updateMeta($request->get('name'), $request->get('items') );
        return redirect()->back();
    }
}
