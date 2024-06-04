<?php

namespace App\Services;

class TelegramBotService
{
    
    public $apiUrl;

    public function __construct(){
        $this->apiUrl = "https://api.telegram.org/bot".config("tgBot")['secret-key'];
    }
    
    

    public function request($method, $posts){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl.'/'.$method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($posts));
        curl_setopt($ch, CURLOPT_POST, 1);
        $headers = [];
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if(curl_errno($ch)){
            echo 'Error:'. curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }        


}
