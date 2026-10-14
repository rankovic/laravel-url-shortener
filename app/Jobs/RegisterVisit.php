<?php

namespace App\Jobs;

use App\Models\Link;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class RegisterVisit implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $code) {}

    /**
     * Record the visit. Runs on the queue so the redirect itself stays fast,
     * and the counter is incremented atomically in the database.
     */
    public function handle(): void
    {
        Link::query()->where('code', $this->code)->update([
            'visits' => DB::raw('visits + 1'),
            'last_visited_at' => now(),
        ]);
    }
}
