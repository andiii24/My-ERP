<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\JobExtra;
use App\Services\Models\JobService;

class JobExtraController extends Controller
{
    private $jobService;

    public function __construct(JobService $jobService)
    {
        $this->middleware('isFeatureAccessible:Job Management');

        $this->jobService = $jobService;
    }

    public function add(JobExtra $jobExtra)
    {
        $this->authorize('addExtra', $jobExtra->job);

        [$isExecuted, $message] = $this->jobService->addExtra($jobExtra, auth()->user());

        if (! $isExecuted) {
            return back()->with('jobExtraFailedMessage', $message);
        }

        return back();
    }

    public function subtract(JobExtra $jobExtra)
    {
        $this->authorize('subtractExtra', $jobExtra->job);

        [$isExecuted, $message] = $this->jobService->subtractExtra($jobExtra, auth()->user());

        if (! $isExecuted) {
            return back()->with('jobExtraFailedMessage', $message);
        }

        return back();
    }
}
