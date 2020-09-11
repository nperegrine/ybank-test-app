<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Create(store) transaction 
     * for provided account ID in the Database
     */
    public function createTransaction(Request $request, $id) {

        // Check/sanitize ID param and forbid user from making a transfer to themselves (to same account)
        // Return a generic error message if no ID was passed
        if(!(int)$id || (int)$id === (int)$request->input('to')) {
            return response()->json(['message' => 'Error, please try again.'], 403);
        }

        // Validate createTransaction request
        $request->validate([
          'to' => 'required|integer|exists:accounts,id',
          'amount' => 'required|numeric|gt:0|between:1,100000', // for security purposes, the acceptable transferable amount must be between 1 and 100,000 (usd)
          'details' => 'nullable|max:250'
        ]);

        /**
         * TODO (Improvement)::Properly validate $id route param for more robustness
         */

        // Extract necessary fields from the request inputs
        $to = $request->input('to');
        $amount = $request->input('amount');
        $details = $request->input('details');

        // Fetch account with provide account ID or fail if not found
        $account_to_be_debited = Account::findOrFail((int)$id);

        /**
         * yBank needs to verify and make sure user is not overspending their balance 
        */ 
        // If account balance is less than amount to be sent then we must forbid and prevent
        // overspending of account balance by stopping and sending a 403 error message
        if($account_to_be_debited->isOverspendingBalance((float)$amount)){
            return response()->json(['message' => 'Error, insufficient balance!'], 403);
        }

        /**
         * Initiate a Database Transaction to ensure Data Integrity: 
         * If an exception is thrown within the transaction Closure, 
         * the transaction will automatically be rolled back. 
         */
      DB::transaction(function () use($account_to_be_debited, $id, $to, $amount, $details) {

            // Deduct balance from the "from" debit account
            $account_to_be_debited->debitAccountBalance((float)$amount);

            // Fetch credit account from DB
            $account_to_be_credited = Account::findOrFail((int)$to);

            // Top-up (increment) balance of the "to" credit account
            $account_to_be_credited->creditAccountBalance((float)$amount);

            Transaction::create([
                    'from' => $id,
                    'to' => $to,
                    'amount' => $amount,
                    'details' => $details
            ]);

        });

        return response()->json(null, 200);    
    }
}