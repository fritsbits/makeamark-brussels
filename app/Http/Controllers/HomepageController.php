<?php

namespace App\Http\Controllers;

use App\Content\Homepage;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function __invoke(Homepage $homepage): View
    {
        return view('home', $homepage->data());
    }
}
