<?php

namespace App\Listeners;

use App\Events\SubmissionSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogSubmissionListener
{
    use InteractsWithQueue;
    /**
     * Handle the event.
     * @param SubmissionSaved $event
     *
     * @return void
     */
    public function handle(SubmissionSaved $event) : void
    {
        $submission = $event->submission;
        Log::info('LogSubmissionListener: Submission saved: ', ['name' => $submission->getName(), 'email' => $submission->getEmail(), 'jobId' => $event->getJobIdValue()]);
    }

    public function failed(SubmissionSaved $event, \Throwable $exception): void
    {
        $submission = $event->submission;
        Log::error('Error: ' . $exception->getMessage(), ['name' => $submission->getName(), 'email' => $submission->getEmail(), 'jobId' => $event->getJobIdValue()]);
    }
}
