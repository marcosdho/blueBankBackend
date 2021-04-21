<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;
    protected $table = 'user';
    
    public static function regUser($user){
        $u = new Users();
        $u->username = $user;
        $u->save();
        return $u->id; 
    }

    public static function validUser($user){
        $user = Users::where('username',$user)->get();
        return (count($user)>0) ? true : false;
    }

    public static function user($id){
        $user = Users::select('username')
        ->where('id',$id)
        ->first();
        return $user;
    }
}
