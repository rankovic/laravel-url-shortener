<?php

namespace Tests\Feature;

use App\Jobs\RegisterVisit;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterVisitTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_increments_the_visit_count_and_timestamps_the_visit(): void
    {
        $link = Link::factory()->create(['visits' => 0, 'last_visited_at' => null]);

        (new RegisterVisit($link->code))->handle();

        $link->refresh();
        $this->assertSame(1, $link->visits);
        $this->assertNotNull($link->last_visited_at);
    }

    public function test_it_does_not_create_a_row_for_an_unknown_code(): void
    {
        (new RegisterVisit('missing'))->handle();

        $this->assertDatabaseCount('links', 0);
    }

    public function test_the_visit_is_recorded_end_to_end_through_the_queue(): void
    {
        $link = Link::factory()->create(['visits' => 0, 'url' => 'https://laravel.com']);

        $this->get('/'.$link->code)->assertRedirect('https://laravel.com');

        $this->assertSame(1, $link->fresh()->visits);
    }
}
