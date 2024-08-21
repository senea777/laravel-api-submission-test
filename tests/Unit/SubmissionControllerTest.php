<?php

namespace Tests\Unit;

use App\Jobs\ProcessSubmissionJob;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Event;
use App\Jobs\ProcessSubmission;
use App\Events\SubmissionSaved;
use Illuminate\Support\Facades\Cache;
use App\Models\Submission;

class SubmissionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSubmitEndpointWithInvalidData()
    {
        // Define the invalid input data
        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'message' => ''
        ];

        // Send a POST request to the /submit endpoint
        $response = $this->postJson('/api/submissions', $data);

        // Assert that the response contains validation errors
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'status',
            'errors' => [
                'name',
                'email',
                'message',
            ]
        ]);
    }
}
