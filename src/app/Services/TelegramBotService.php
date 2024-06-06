<?php

namespace App\Services;

class TelegramBotService
{
    protected const KEY = '7129402465:AAEsiZEgc2m-C4fVk-DGK63i5XEmpL3SXOI';
    protected const END_POINT = 'https://api.telegram.org/bot';

    public $postData = [];

    public $requestData;

    public function __construct(){
        $this->requestData = json_decode(file_get_contents('php://input'));
        $this->postData['chat_id'] = $this->requestData->message->chat->id ?? 857847737;
        
        if (isset($this->requestData->callback_query)) {
            $this->postData['callback_query'] = $this->requestData->callback_query;
        }
    }

    public function sendMessage($message){
        $this->postData['text'] = $message;
        return $this->request('sendMessage');
    }

    public function setButtons($buttons){
        $this->postData['reply_markup'] = json_encode([
            'inline_keyboard' => $buttons
        ]);
    }

    public function answerCallbackQuery($text){
        $this->postData = [
            'callback_query_id' => $this->postData['callback_query']->id,
            'text' => $text,
        ];
        return $this->request('answerCallbackQuery');
    }


    public function request($method){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::END_POINT.self::KEY.'/'.$method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->postData));
        curl_setopt($ch, CURLOPT_POST, 1);
        $headers = [];
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if(curl_errno($ch)){
            $errorMessage = 'Curl error: ' . curl_error($ch);
            error_log($errorMessage, 3, $_SERVER['DOCUMENT_ROOT'].'/../src/storage/error.log');
            curl_close($ch);
            throw new \Exception($errorMessage);
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            $errorMessage = 'Request failed with status code: ' . $httpCode . ' Response: ' . $result;
            error_log($errorMessage, 3, $_SERVER['DOCUMENT_ROOT'].'/../src/storage/error.log');
            curl_close($ch);
            throw new \Exception($errorMessage);
        }
        curl_close($ch);
        return $result;
    }
}
