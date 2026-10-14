<?php

namespace App\Http\Controllers;

use App\Jobs\RegisterVisit;
use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class RedirectController extends Controller
{
    /**
     * Resolve a short code to its destination URL and redirect.
     *
     * The code -> url lookup is cached in Redis so a hot link does not hit
     * the database on every request; recording the visit is pushed onto the
     * queue so the redirect stays fast.
     */
    public function __invoke(string $code): RedirectResponse
    {
        $url = Cache::remember(
            "link:{$code}",
            now()->addHour(),
            fn () => Link::query()->where('code', $code)->value('url'),
        );

        abort_if($url === null, 404);

        RegisterVisit::dispatch($code);

        return redirect()->away($url);
    }
}
