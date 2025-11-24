<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Tracks\StoreTrackRequest;
use App\Services\TrackService;
use Illuminate\Http\JsonResponse;

class TrackController extends Controller
{
    public function __construct(
        private readonly TrackService $trackService,
    ) {
        //
    }

    public function store(StoreTrackRequest $request): JsonResponse
    {
        $this->trackService->trackEventWithRequest($request);

        return response()->json(['message' => 'success'], 201);
    }
}
