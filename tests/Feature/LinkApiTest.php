<?php

namespace Tests\Feature;

use App\Jobs\RegisterVisit;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class LinkApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_short_link(): void
    {
        $response = $this->postJson('/api/links', [
            'url' => 'https://example.com/some/long/path',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.url', 'https://example.com/some/long/path')
            ->assertJsonStructure(['data' => ['code', 'url', 'short_url', 'visits', 'created_at']]);

        $this->assertDatabaseHas('links', ['url' => 'https://example.com/some/long/path']);
    }

    public function test_it_rejects_an_invalid_url(): void
    {
        $this->postJson('/api/links', ['url' => 'not-a-url'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('url');
    }

    public function test_it_returns_link_statistics(): void
    {
        $link = Link::factory()->create(['visits' => 5]);

        $this->getJson('/api/links/'.$link->code)
            ->assertOk()
            ->assertJsonPath('data.visits', 5);
    }

    public function test_it_redirects_to_the_destination_and_queues_a_visit(): void
    {
        Queue::fake();
        $link = Link::factory()->create(['url' => 'https://laravel.com']);

        $this->get('/'.$link->code)
            ->assertRedirect('https://laravel.com');

        Queue::assertPushed(RegisterVisit::class, fn ($job) => $job->code === $link->code);
    }

    public function test_it_returns_404_for_an_unknown_code(): void
    {
        $this->get('/doesnotexist')->assertNotFound();
    }
}
