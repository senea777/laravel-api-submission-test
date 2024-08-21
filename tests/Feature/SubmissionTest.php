<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubmissionTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function it_validates_submission_request(): void
    {
        $response = $this->postJson('/api/submissions', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'message']);
    }

    public function it_processes_submission_successfully(): void
    {
        $data = [
            'name' => 'Senea test',
            'email' => 'senea@example.com',
            'message' => 'Message from Senea.',
        ];

        $response = $this->postJson('/api/submissions', $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Submission received and is being processed.']);

        $this->assertDatabaseHas('submissions', $data);
    }
}
