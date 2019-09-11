<?php

namespace App\Listeners;

use App\Events\HistoryEvent;
use App\History;
use Illuminate\Support\Carbon;

class HistoryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(HistoryEvent $historableEvent)
    {
        $type = $historableEvent->getHistoryType();
        $item = $historableEvent->getHistoryItem();
        $description = $historableEvent->getHistoryDescription();

        $this->addHistoryItem($type, $item, $description);
    }

    private function addHistoryItem(string $modelClass, int $modelItemID, string $description)
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
