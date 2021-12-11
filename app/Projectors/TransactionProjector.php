<?php

namespace App\Projectors;

use App\Models\User;
use App\Models\ViewModels\Nab;
use App\Models\ViewModels\Transaction as ViewModelsTransaction;
use App\StorableEvents\Transaction;
use Carbon\Carbon;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class TransactionProjector extends Projector
{
    public function onTransaction(Transaction $event)
    {
        $user = User::find($event->user_id);
        if (!$user) {
            return;
        }

        if ($user->unit == 0 && $event->type === 'withdraw') {
            return;
        }

        $nab = Nab::latest('date')->first();
        if ($nab) {
            $nab = $nab->nab;
        } else {
            $nab = 1;
        }

        $unit = round($event->amount / $nab, 4, PHP_ROUND_HALF_DOWN);

        if ($unit > $user->unit && $event->type === 'withdraw') {
            return;
        }

        if ($event->type === 'topup') {
            $total_unit = $user->unit + $unit;
        } else {
            $total_unit = $user->unit - $unit;
        }

        $user->unit = $total_unit;
        $user->save();

        $date = Carbon::createFromTimestamp($event->timestamp);
        ViewModelsTransaction::create([
            'user_id' => $user->id,
            'type' => $event->type,
            'nab' => $nab,
            'unit' => $unit,
            'total_unit' => $total_unit,
            'amount' => $event->amount,
            'date' => $date,
        ]);
    }
}
