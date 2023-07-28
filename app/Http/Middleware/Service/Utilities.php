<?php

namespace App\Http\Middleware\Service;

trait Utilities
{
    public function validateTGData($botToken, $data) : bool {
        $bot_secret = $botToken;

        $in =  $data;

        parse_str($in, $arr);

        $check_hash = $arr['hash'];
        unset($arr['hash']);
        ksort($arr);
        $data_str = "";
        foreach($arr as $k=>$v) {
            $data_str .= $k."=".$v."\x0A";
        }
        $data_str = trim($data_str);

        $secret = hash_hmac('sha256', $bot_secret, 'WebAppData', true);
        $hash = hash_hmac('sha256', $data_str, $secret);

        return strcmp($hash, $check_hash) === 0;

    }
}
