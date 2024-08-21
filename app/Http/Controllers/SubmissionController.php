<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use App\Jobs\ProcessSubmissionJob;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) : JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $job = new ProcessSubmissionJob($request->only('name', 'email', 'message'));
            dispatch($job);
            $jobId = $job->getJobIdValue();

            $i = 10;
            while (true) {
                if ($i-- <= 0) {
                    throw new \Exception('Submission processing timed out.');
                }

                if (Cache::get('job_status_' . $jobId) === 'completed') {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Submission processed successfully.',
                    ], 200);
                }

                if (!empty(Cache::get('job_status_' . $jobId))) {
                    $message = Cache::get('job_status_' . $jobId);
                    throw new \Exception('Submission processing failed. Error: ' . $message);
                }

                usleep(500000);
            }

        } catch (\Throwable $e) {
            Log::error('Error processing submission:', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred. Please try again later.' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubmissionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Submission $submission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Submission $submission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubmissionRequest $request, Submission $submission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submission $submission)
    {
        //
    }
}
