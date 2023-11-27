<?php

namespace App\Http\Middleware\Service;

use Exception;

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

    public function checkTelegramAuthorization($auth_data,$bot_token) {
        $check_hash = $auth_data['hash'];

        unset($auth_data['hash']);
        $data_check_arr = [];
        foreach ($auth_data as $key => $value) {
            $data_check_arr[] = $key . '=' . $value;
        }
        sort($data_check_arr);
        $data_check_string = implode("\n", $data_check_arr);
        $secret_key = hash('sha256', $bot_token, true);
        $hash = hash_hmac('sha256', $data_check_string, $secret_key);
        if (strcmp($hash, $check_hash) !== 0) {
            throw new Exception('Data is NOT from Telegram');
        }
        if ((time() - $auth_data['auth_date']) > 86400)
            throw new Exception('Data is outdated');
        return $auth_data;
    }
}
