<?php

namespace App\Models;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class MonologModel
{

    private static function _init(){
        $log = new Logger('banco');
        $log->pushHandler(new StreamHandler(storage_path().'logs/'.date("Ymd").'.log'), Logger::WARNING);
        $log->pushHandler(new FirePHPHandler());

        return $log;
    }

    public static function warning($message){
        self::_init()->warning($message);
    }

    public static function error($message){
        self::_init()->error($message);
    }

    public static function info($message){
        self::_init()->info($message);
    }
}
