<?php

namespace App\Controllers;
use App\Services\TelegramBotService;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class TelegramBotController {

    public $service;
    public $log;

    public function __construct(){
        $this->service = new TelegramBotService();
        $this->log = new Logger("app");
        $this->log->pushHandler(new StreamHandler(__DIR__.'/log.log', Logger::DEBUG));
    }

    public function handleRequest(){
        $this->log->info(file_get_contents('php://input'));
        // $data = json_decode(file_get_contents('php://input'));
        // $data = json_decode('{"update_id":731687744,
        //     "message":{"message_id":3,"from":{"id":857847737,"is_bot":false,"first_name":"F\u0259rid","username":"N0Ferid","language_code":"en"},"chat":{"id":857847737,"first_name":"F\u0259rid","username":"N0Ferid","type":"private"},"date":1717507951,"text":"s"}}');
        // print_r($data->message->chat->id);

        // return $this->service->request('sendMessage', [
        //     'text'=> 'salam',
        //     'chat_id' => '857847737'
        // ]);

        // return $this->service->request("setWebhook", [
        //     'url' => 'https://fscode-tgbot.faridnasibzade.az/api'
        // ]);
        // $data = json_decode('{"update_id":731687744,
        //     "message":{"message_id":3,"from":{"id":857847737,"is_bot":false,"first_name":"F\u0259rid","username":"N0Ferid","language_code":"en"},"chat":{"id":857847737,"first_name":"F\u0259rid","username":"N0Ferid","type":"private"},"date":1717507951,"text":"s"}}');
            
        // echo '<pre>';
        // print_r($data->message->chat->id);
        // echo '</pre>';
        }
    
    public function index(){
        // $this->service->setWebhook();
    }

}