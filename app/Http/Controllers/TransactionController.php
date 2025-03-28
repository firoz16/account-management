<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{

    public function index(Request $request)
{
    $request->validate([
        'account_id' => 'required|exists:accounts,id',
        'from' => 'nullable|date',
        'to' => 'nullable|date|after_or_equal:from',
    ]);

    $query = Transaction::where('account_id', $request->account_id);

    if ($request->has('from')) {
        $query->whereDate('created_at', '>=', Carbon::parse($request->from));
    }

    if ($request->has('to')) {
        $query->whereDate('created_at', '<=', Carbon::parse($request->to));
    }

    $transactions = $query->orderBy('created_at', 'desc')->get();

    return response()->json($transactions);
}

    public function store(TransactionRequest $request){
        
        $account = Account::findOrFail($request->account_id);

        if ($request->type === 'Debit' && $account->balance < $request->amount) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        Transaction::create([
            'account_id'=> $request->account_id,
            'type' => $request->type,
            'amount' => $request->amount ,
            'description' => $request->description
        ]);
        $account->balance += $request->type === 'Credit' ? $request->amount : -$request->amount;
        $account->save();

        return response()->json(['message' => 'Transaction successful','data'=>$account], 201);
    }

    public function transfer(Request $request)
{
    $request->validate([
        'from_account_id' => 'required|exists:accounts,id',
        'to_account_id' => 'required|exists:accounts,id|different:from_account_id',
        'amount' => 'required|numeric|min:1',
        'description' => 'nullable|string'
    ]);

    DB::beginTransaction(); // Start transaction

    try {
        $fromAccount = Account::lockForUpdate()->findOrFail($request->from_account_id);
        $toAccount = Account::lockForUpdate()->findOrFail($request->to_account_id);

        // Check if sender has enough balance
        if ($fromAccount->balance < $request->amount) {
            return response()->json(["error" => "Insufficient balance"], 400);
        }

        // Deduct amount from sender
        $fromAccount->balance -= $request->amount;
        $fromAccount->save();

        // Add amount to receiver
        $toAccount->balance += $request->amount;
        $toAccount->save();

        // Log debit transaction for sender
        Transaction::create([
            'account_id' => $fromAccount->id,
            'type' => 'Debit',
            'amount' => $request->amount,
            'description' => $request->description ?: 'Fund Transfer'
        ]);

        // Log credit transaction for receiver
        Transaction::create([
            'account_id' => $toAccount->id,
            'type' => 'Credit',
            'amount' => $request->amount,
            'description' => $request->description ?: 'Fund Transfer'
        ]);

        DB::commit(); // Commit transaction
        return response()->json(["message" => "Transfer successful"], 200);

    } catch (\Exception $e) {
        DB::rollBack(); // Rollback transaction on error
        return response()->json(["error" => "Transfer failed", "details" => $e->getMessage()], 500);
    }
}
}
