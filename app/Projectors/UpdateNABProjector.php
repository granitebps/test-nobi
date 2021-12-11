<?php

namespace App\Projectors;

use App\Models\User;
use App\Models\ViewModels\Nab;
use App\StorableEvents\UpdateNAB;
use Carbon\Carbon;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UpdateNABProjector extends Projector
{
    public function onUpdateNAB(UpdateNAB $event)
    {
        // Check user units
        $usersUnit = User::sum('unit');
        if ($usersUnit === 0) {
            $nab = 1;
        } else {
            $cal = $event->balance / $usersUnit;
            $nab = round($cal, 4, PHP_ROUND_HALF_DOWN);
        }

        $date = Carbon::createFromTimestamp($event->timestamp);
        Nab::create([
            'nab' => $nab,
            'date' => $date
        ]);
    }
}
