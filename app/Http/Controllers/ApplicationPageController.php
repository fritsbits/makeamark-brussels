<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class ApplicationPageController extends Controller
{
    public function __invoke(string $audience): RedirectResponse
    {
        return redirect(route('audience.show', $audience).'#application');
    }
}
