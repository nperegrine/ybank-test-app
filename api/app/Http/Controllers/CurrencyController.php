<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Fetch currencies
     */
    public function getCurrencies() {
        $currencies = Currency::all();

        return response()->json($currencies, 200);
    }
}