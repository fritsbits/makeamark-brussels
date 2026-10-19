<?php

namespace App\Http\Controllers;

use App\Content\Homepage;
use Illuminate\View\View;

class PrivacyNoticeController extends Controller
{
    public function __invoke(Homepage $homepage): View
    {
        return view('privacy', [
            'site' => $homepage->site(),
            'pageTitle' => 'Privacy notice · Make a Mark Brussels',
            'pageDescription' => 'How Make a Mark Brussels uses and protects application information.',
        ]);
    }
}
