<?php

namespace App\Http\Controllers;

use App\Content\Homepage;
use Illuminate\Http\Response;

class MessagingPreviewController extends Controller
{
    public function home(Homepage $homepage): Response
    {
        return $this->preview('home', $homepage->data());
    }

    public function audience(string $audience, Homepage $homepage): Response
    {
        return $this->preview('audience', AudiencePageController::pageData($audience, $homepage->data()));
    }

    public function compare(Homepage $homepage): Response
    {
        return $this->preview('previews.messaging.compare', $homepage->data());
    }

    public function original(): Response
    {
        return $this->preview('previews.messaging.original', json_decode(
            file_get_contents(resource_path('content/previews/original-homepage.json')),
            true,
            flags: JSON_THROW_ON_ERROR,
        ));
    }

    private function preview(string $view, array $data): Response
    {
        abort_unless(app()->environment('local', 'testing'), 404);

        return response()->view($view, $data)->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
