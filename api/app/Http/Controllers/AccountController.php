<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Fetch account with ID passed as parameter
     */
    public function getAccount($id) {

        // Return  a generic error message if no ID was passed
        if(!$id) {
            return response()->json(['message' => 'Error, please try again.'], 403);
        }

        // Find account by ID or send 404 Not found exception if account is not found
        $account = Account::findOrFail($id);
        
        // Send successful response with account payload
        return response()->json($account, 200);
    }

    /**
     * Fetch transactions for account ID passed in params
     */
    public function getAccountTransactions($id) {

        // Return  a generic error message if no ID was passed
        if(!$id) {
            return response()->json(['message' => 'Error, please try again.'], 403);
        }

        // Find transactions for account ID or send 404 Not found exception if account is not found
        $transactions = Transaction::where('from', $id)
            ->orWhere('to', $id)
            ->get();

        return response()->json($transactions, 200);
    }
}