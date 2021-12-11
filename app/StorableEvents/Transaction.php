<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class Transaction extends ShouldBeStored
{
    public $user_id;
    public $amount;
    public $type;
    public $timestamp;

    public function __construct(
        int $user_id,
        int $amount,
        string $type,
        int $timestamp,
    ) {
        $this->user_id = $user_id;
        $this->amount = $amount;
        $this->type = $type;
        $this->timestamp = $timestamp;
    }
}
