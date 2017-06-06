<?php

namespace Org\OT;

class jlcurl{   
    static function get($url,Closure $func){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        
        if (curl_errno($ch) !== 0) {
            echo curl_error($ch);
            curl_close($ch);
            exit();
        }
        curl_close($ch);
        return  $func($result);
    }
    
    static  function post($url,$data,Closure $func){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        
        if (curl_errno($ch) !== 0) {
            echo curl_error($ch);
            curl_close($ch);
            exit();
        }
        curl_close($ch);
        return $func($result);
    }
}
