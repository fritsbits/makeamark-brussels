<?php

use App\Content\Homepage;
use App\Http\Controllers\ApplicationPageController;
use App\Http\Controllers\ApplicationSubmissionController;
use App\Http\Controllers\AudiencePageController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\MessagingPreviewController;
use App\Http\Controllers\PrivacyNoticeController;
use Illuminate\Support\Facades\Route;

// Local creative tooling, outside the public website and sitemap.
if (app()->environment('local', 'testing')) {
    Route::get('/tools/messaging/compare', [MessagingPreviewController::class, 'compare'])->name('tools.messaging.compare');
    Route::get('/tools/messaging/original', [MessagingPreviewController::class, 'original'])->name('tools.messaging.original');
    Route::get('/tools/messaging', [MessagingPreviewController::class, 'home'])->name('tools.messaging.home');
    Route::get('/tools/messaging/{audience}', [MessagingPreviewController::class, 'audience'])
        ->whereIn('audience', ['makers', 'organisations'])->name('tools.messaging.audience');

    Route::get('/tools/riso', function (Homepage $homepage) {
        abort_unless(app()->environment('local', 'testing'), 404);

        return response()->view('tools.riso', ['site' => $homepage->site()])->header('X-Robots-Tag', 'noindex, nofollow');
    })->name('tools.riso');
    Route::get('/tools/riso/sample', function () {
        abort_unless(app()->environment('local', 'testing'), 404);

        return response()->file(resource_path('images/riso-sample.png'));
    });
}

Route::get('/', HomepageController::class)->name('home');
Route::get('/privacy', PrivacyNoticeController::class)->name('privacy');
Route::get('/{audience}/apply', ApplicationPageController::class)->whereIn('audience', ['makers', 'organisations'])->name('audience.apply');
Route::post('/{audience}/apply', ApplicationSubmissionController::class)->whereIn('audience', ['makers', 'organisations'])->middleware('throttle:5,1')->name('audience.apply.submit');
Route::get('/{audience}', AudiencePageController::class)->whereIn('audience', ['makers', 'organisations'])->name('audience.show');

Route::get('/sitemap-index.xml', function (Homepage $homepage) {
    $domain = rtrim($homepage->site()['domain'], '/');

    return response('<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><sitemap><loc>'.$domain.'/sitemap-0.xml</loc></sitemap></sitemapindex>')
        ->header('Content-Type', 'application/xml');
});

Route::get('/sitemap-0.xml', function (Homepage $homepage) {
    $domain = rtrim($homepage->site()['domain'], '/');

    return response('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>'.$domain.'/</loc></url><url><loc>'.$domain.'/makers</loc></url><url><loc>'.$domain.'/organisations</loc></url><url><loc>'.$domain.'/privacy</loc></url></urlset>')
        ->header('Content-Type', 'application/xml');
});
