<?php

namespace App\Http\Controllers;

class ThemeController extends Controller
{
    public function toggle()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user->theme = $user->theme === 'dark' ? 'light' : 'dark';
            $user->save();
        } else {
            $theme = session('theme', 'light') === 'dark' ? 'light' : 'dark';
            session(['theme' => $theme]);
        }

        return back();
    }
}