<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AccountRequest;

class AccountController extends Controller
{
    public function index(){
        $account = Account::where('user_id',Auth::id())->get();
        return response()->json($account,200);
    }
    public function store(AccountRequest $request){
       
        $account = Account::create([
            'user_id' => Auth::id(),
            'account_name' => $request->account_name,
            'account_number' => $this->generateLuhnNumber(),
            'account_type' => $request->account_type,
            'currency' => $request->currency,
            'balance' => $request->initial_balance ?? 0,
        ]);

       
        return response()->json(['message' => 'Account created successfully','data'=>$account],201);
    }

    private function generateLuhnNumber()
    {
        do {
            $number = rand(100000000000, 999999999999);
        } while (!$this->isLuhnValid($number));
        return $number;
    }

    private function isLuhnValid($number)
    {
        $digits = str_split(strrev($number));
        $sum = 0;
        foreach ($digits as $index => $digit) {
            if ($index % 2 == 1) {
                $digit *= 2;
                if ($digit > 9) $digit -= 9;
            }
            $sum += $digit;
        }
        return $sum % 10 === 0;
    }

    public function show($account_number){
        $account = Account::where('account_number',$account_number)->first();
        return response()->json($account);
    }

    public function update(AccountRequest $request, $account_number){
        $account = Account::where('account_number',$account_number)->first();
        $account->update($request->except('account_number'));
        return response()->json(['message' => 'Account updated successfully','data'=>$account],200);

    }

    public function destroy($account_number){
        $account = Account::where('account_number',$account_number)->first();
        $account->delete();
        return response()->json(['message' => 'Account deleted successfully'],200);
    }
}
