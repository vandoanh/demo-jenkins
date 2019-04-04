<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use Illuminate\Http\Request;

class IndexController extends BackendController
{
    public function dashboard(Request $request)
    {
        return view('backend.dashboard');
    }

    public function createCode(Request $request)
    {
        $title = $request->title ?? null;

        if (!empty($title)) {
            return str_slug($title);
        }

        return '';
    }
}
