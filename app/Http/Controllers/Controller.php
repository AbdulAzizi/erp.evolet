<?php

namespace App\Http\Controllers;

use App\History;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function addHistoryItem(string $modelClass, int $modelItemID, string $description)
    {
        $authorId = auth()->id();

        $previous = History::where('happened_with_type', $modelClass)
            ->where('happened_with_id', $modelItemID)
            ->orderByDesc('happened_at')
            ->first();

        History::create([
            'user_id' => $authorId,
            'previous_id' => $previous ? $previous->id : null,
            'description' => $description,
            'happened_at' => Carbon::now()->toDateTimeString(),
            'happened_with_id' => $modelItemID,
            'happened_with_type' => $modelClass,
        ]);
    }
}
