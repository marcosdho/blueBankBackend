<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\MonologModel;

class Movements extends Model
{
    use HasFactory;

    public static function addDeposit($account,$amount){
        MonologModel::info("Depósito en cuenta ahorros ID $account por monto  $amount");

        $deposit = new Movements();
        $deposit->accountid = $account;
        $deposit->type = 'in';
        $deposit->amount = $amount;
        $deposit->save();
        return $deposit->id;
    }
    
    public static function getMovements($account){
        MonologModel::info("Solicitud de movimientos de cuenta de ahorros $account");

        $movement = Movements::select('movements.id','movements.accountid',DB::raw('IF(movements.type = "in", "Depósito","Retiro") as type'),DB::raw('FORMAT(movements.amount, 2) as amount'),DB::raw('DATE_FORMAT(movements.created_at,"%d-%m-%Y") as fecha'),'account.id','account.accountnumber','username')
        ->Join('account','movements.accountid','=','account.id')
        ->Join('user','account.userid','=','user.id')
        ->where('account.accountnumber',$account)
        ->get();
        return $movement;
    }

    public static function addWithdraw($account,$amount){

        MonologModel::info("Retiro de cuenta ahorros $account por monto $amount");

        $deposit = new Movements();
        $deposit->accountid = $account;
        $deposit->type = 'out';
        $deposit->amount = $amount;
        $deposit->save();
        return $deposit->id;
    }

    public static function balance($accountId){
        MonologModel::info("Consulta de saldos de cuenta ahorros ID $accountId");

        $balance = DB::table('movements')->select(DB::RAW("(select SUM(amount) as total from movements where accountid=$accountId AND type = 'in') as deposito,(select SUM(amount) as total from movements where accountid=$accountId AND type = 'out') as retiro"))
        ->where('accountid',$accountId)
        ->orderBy('id','desc')
        ->limit(1)
        ->get();

        return $balance[0];
    }

    public static function formatAmount($amount) {
        return number_format($amount,2,',','.');
    }
}
