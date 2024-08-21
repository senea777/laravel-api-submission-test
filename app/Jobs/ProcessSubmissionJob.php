<?php

namespace App\Jobs;

use App\Events\SubmissionSaved;
use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessSubmissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public mixed $jobId;

    private const string QUEUE_NAME = 'ProcessSubmissionJob';

    public array $data;


    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }


    public function queue($queue, $command)
    {
        $this->jobId = $queue->push($command);
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $this->setJobIdValue($this->job->getJobId());

        try {
            DB::transaction(function () {
                $submission = new Submission($this->data);
                $submission->save();
                Cache::put('job_status_' . $this->getJobIdValue(), 'completed', 100);
                Log::info('ProcessSubmissionJob:', ['jobId' => $this->job->getJobId()]);

                event(new SubmissionSaved($this->job->getJobId(), $submission));
            });
        } catch (\Throwable $exception) {
            Cache::put('job_status_' . $this->getJobIdValue(), $exception->getMessage(), 100);
            Log::error('Error processing ProcessSubmissionJob:', [
                'message' => $exception->getMessage() . " - " . $this->getJobIdValue(),
            ]);

            $this->fail($exception);
        }
    }

    public function failed(\Throwable $exception): void
    {
    }

    /**
     * @return mixed
     */
    public function getJobIdValue(): mixed
    {
        return $this->jobId;
    }

    public function setJobIdValue($val)
    {
        return $this->jobId = $val;
    }
}
