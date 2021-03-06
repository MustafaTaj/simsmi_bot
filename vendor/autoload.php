<?php
function CurlRequest($url, $fields = array("do" => "nothing"))
    {
        $fields_string = '';
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . urlencode($value) . '&';
        }
        rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result);
        return json_decode($result, true);
    }
// autoload.php @generated by Composer

require_once __DIR__ . '/composer' . '/autoload_real.php';

return ComposerAutoloaderInit95419506929a0927fedda1e6652c31ff::getLoader();
