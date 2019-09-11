<?php

namespace App\Events;


abstract class HistoryEvent {

    protected $historyDescription;
    protected $historyItem;
    protected $historyType;

    public function getHistoryDescription() : string
    {
        return $this->historyDescription;
    }

    public function getHistoryItem() : int
    {
        return $this->historyItem;
    }

    public function getHistoryType() : string
    {
        return $this->historyType;
    }
}