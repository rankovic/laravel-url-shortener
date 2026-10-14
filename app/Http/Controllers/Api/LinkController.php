<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Resources\LinkResource;
use App\Models\Link;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkController extends Controller
{
    /**
     * Create a new short link.
     */
    public function store(StoreLinkRequest $request): JsonResource
    {
        $link = Link::create([
            'code' => Link::newCode(),
            'url' => $request->validated('url'),
        ]);

        return new LinkResource($link);
    }

    /**
     * Show a single link and its visit statistics.
     */
    public function show(Link $link): JsonResource
    {
        return new LinkResource($link);
    }
}
