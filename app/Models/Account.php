<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $table = 'account';

    public static function newAccount($userId){
        $accountN = rand(1245783692,9999999999);
        $newA = new Account();
        $newA->userid = $userId;
        $newA->accountnumber = $accountN;
        $newA->save();
        return $newA;
    }

    public static function accountInfo($account){
        $data = Account::where('accountnumber',$account)->first();
        return $data;
    }
}
