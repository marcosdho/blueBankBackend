<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Users;
use App\Models\Movements;
use App\Models\Account;
use App\Models\MonologModel;

class BancoController extends Controller
{
    //
    private static function formate($number) {
        return number_format($number, 2, ',', '.');
    }

    public function buscar(Request $request){
        $search = Movements::getMovements($request->account);

        $data = [
            "account"=>$request->account,
            "message"=>"Esta es una petición desde React",
            "lista"=>$search,
        ];
        return response()->json($data);
    }//

    public function createAccount(Request $request) {
        if(!$request->name){
            MonologModel::error("Debe colocar el nombre del cliented");
            return response()->json(['error'=>true,'message'=>"Debe colocar el nombre del cliente..."]);
        }
        
        if(!$request->amount){
            MonologModel::error("Debe ingresar el monto");
            return response()->json(['error'=>true,'message'=>"Debe ingresar el monto..."]);
        }
        if(!is_numeric($request->amount)){
            MonologModel::error("Debe colocar caracteres numericos");
            return response()->json(['error'=>true,'message'=>"Debe colocar caracteres numericos..."]);
        }
        if(!$request->amount || $request->amount < 1000){
            MonologModel::error("El monto minimo de apertura es de 1000");
            return response()->json(['error'=>true,'message'=>"El monto minimo de apertura es de 1000..."]);
        }


        
        $userid = Users::regUser($request->name);
        $accountId = Account::newAccount($userid);
        $deposit = Movements::addDeposit($accountId->id,$request->amount);

        return response()->json(['error'=>false,'accountData'=>$accountId]);
    }

    public function createDeposit(Request $request){
        if(!$request->account){
            MonologModel::error("Debe colocar el número de cuenta");
            return response()->json(['error'=>true,'message'=>"Debe colocar el número de cuenta..."]);
        }
        if(!$request->amount){
            MonologModel::error("Debe colocar el monto a consignar");
            return response()->json(['error'=>true,'message'=>"Debe colocar el monto a consignar..."]);
        }
        if(!is_numeric($request->amount)){
            MonologModel::error("Debe colocar caracteres numericos");
            return response()->json(['error'=>true,'message'=>"Debe colocar caracteres numericos..."]);
        }
        
        $account = Account::accountInfo($request->account);
        $deposit = Movements::addDeposit($account->id,$request->amount);
        $name = Users::user($account->userid);
        return response()->json(['error'=>false,'info'=>$name]);
    
    }

    public function createWithdraw(Request $request){
        if(!$request->account){
            MonologModel::error("Debe colocar el número de cuenta");
            return response()->json(['error'=>true,'message'=>"Debe colocar el número de cuenta..."]);
        }
        if(!$request->amount){
            MonologModel::error("Debe colocar el monto a consignar");
            return response()->json(['error'=>true,'message'=>"Debe colocar el monto a consignar..."]);
        }
        if(!is_numeric($request->amount)){
            MonologModel::error("Debe colocar caracteres numericos");
            return response()->json(['error'=>true,'message'=>"Debe colocar caracteres numericos..."]);
        }
        $account = Account::accountInfo($request->account);
        $withdraw = Movements::addWithdraw($account->id,$request->amount);
        $name = Users::user($account->userid);
        return response()->json(['error'=>false,'info'=>$name]);
    }

    public function getBalance(Request $request){
        $account = Account::accountInfo($request->account);
        $balance = Movements::balance($account->id);
        $name = Users::user($account->userid);
        
        return response()->json([
            'error'=>false,
            'details'=>[
                'totalDepositos'=>Movements::formatAmount($balance->deposito),
                'totalRetiros'=>Movements::formatAmount($balance->retiro),
                'actualBalance' => Movements::formatAmount(($balance->deposito - $balance->retiro))
            ]
        ]);
    }
    


}
