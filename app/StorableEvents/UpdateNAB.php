<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UpdateNAB extends ShouldBeStored
{
    public $balance;
    public $timestamp;

    public function __construct(int $balance, int $timestamp)
    {
        $this->balance = $balance;
        $this->timestamp = $timestamp;
    }
}
