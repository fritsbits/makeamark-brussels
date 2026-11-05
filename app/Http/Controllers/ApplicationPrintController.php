<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Team;
use App\Support\ApplicationPrint;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ApplicationPrintController extends Controller
{
    public function organisation(Application $application): View
    {
        abort_unless($application->audience === 'organisations', 404);
        Gate::authorize('view', $application);

        return $this->briefing($application);
    }

    public function team(Team $team): View
    {
        Gate::authorize('view', $team->organisation);

        return $this->briefing($team->organisation);
    }

    private function briefing(Application $organisation): View
    {
        return view('print.briefing', ['briefing' => ApplicationPrint::briefing($organisation)]);
    }
}
