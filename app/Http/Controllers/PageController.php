<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function conditions()
    {
        return view('pages.conditions');
    }
}