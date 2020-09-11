<?php

namespace Tests\Unit;

use App\Models\Account;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserCannotOverSpendBalanceTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /**
     * A unit test to ensure that a user
     * is not allowed to overspend their balance
     *
     * @return void
     */
    public function test_user_cannot_overspend_balance()
    {
        $createdAccount = factory(Account::class)->create(['currency' => 'usd']);
        
        $amount = 6000;

        $this->assertTrue($createdAccount->isOverspendingBalance($amount));
       
    }
}