<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Models\ViewModels\Nab;
use App\StorableEvents\UpdateNAB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IBController extends Controller
{
    public function updateTotalBalance(Request $request): JsonResponse
    {
        $input = $request->validate([
            'current_balance' => [
                'required',
                'integer',
                'min:1'
            ]
        ]);

        event(new UpdateNAB($input['current_balance'], now()->timestamp));

        $nab = Nab::latest('date')->first();

        return Helpers::successResponse('Update Total Balance Success', $nab->toArray());
    }
}
