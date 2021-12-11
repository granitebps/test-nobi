<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\ViewModels\Nab;
use App\Models\ViewModels\Transaction as ViewModelsTransaction;
use App\StorableEvents\Transaction;
use App\StorableEvents\UpdateNAB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IBController extends Controller
{
    public function index(): JsonResponse
    {
        $nabs = Nab::latest('date')->get();

        return Helpers::successResponse('Get NAB Success', $nabs);
    }

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

        return Helpers::successResponse('Update Total Balance Success', $nab);
    }

    public function topup(Request $request): JsonResponse
    {
        $input = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'amount_rupiah' => 'required|integer|min:1'
        ]);

        /** @var User $user */
        $user = User::find($input['user_id']);

        event(new Transaction(
            $input['user_id'],
            $input['amount_rupiah'],
            'topup',
            now()->timestamp
        ));

        $transaction = ViewModelsTransaction::where('user_id', $input['user_id'])
            ->latest('date')
            ->first();

        if ($transaction) {
            $unit = $transaction->unit;
        } else {
            $unit = 0;
        }

        $user->refresh();

        $data = [
            'nilai_unit_hasil_topup' => $unit,
            'nilai_unit_total' => $user->unit,
            'saldo_rupiah_total' => $user->balance
        ];

        return Helpers::successResponse('Topup Success', $data);
    }

    public function member(Request $request): JsonResponse
    {
        if ($request->has('limit') && $request->limit > 0) {
            $limit = $request->limit;
        } else {
            $limit = 20;
        }
        $users = User::orderBy('id')->paginate($limit);

        $nab = Nab::latest('date')->first();
        if ($nab) {
            $nab = $nab->nab;
        } else {
            $nab = 1;
        }

        $data = [
            'users' => UserResource::collection($users),
            'nab' => $nab
        ];

        return Helpers::successResponse('Get Member Success', $data);
    }
}
