<?php

namespace App\Http\Controllers;

use App\Models\Training;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function about()
    {
        return view('about');
    }

    public function terms()
    {
        return view('terms');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function training()
    {
        $trainings = Training::active()->get();
        return view('training', compact('trainings'));
    }
}
