<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class ApplicationPageController extends Controller
{
    public function __invoke(string $audience): RedirectResponse
    {
        abort_unless(in_array($audience, ['makers', 'organisations'], true), 404);

        return redirect(route('audience.show', $audience).'#application');
    }
}
