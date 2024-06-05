<?php

namespace App\Controllers;

use App\Services\TelegramBotService;
use Exception;

class TelegramBotController
{

    public $service;
    public $log;

    public function __construct()
    {
        $this->service = new TelegramBotService();
    }

    public function handleRequest()
    {

        if (isset($this->service->postData['callback_query'])) {
            return $this->callbackHandler();
        }

        $this->service->setButtons([
            [
                ['text' => 'Salam', 'callback_data' => 'yes'],
                ['text' => 'Çatı sonlandır', 'callback_data' => 'end'],
            ]
        ]);

        return $this->service->sendMessage('Booknetic rezerv sisteminə xoş gəlmisiniz. Başlamaq üçün aşağıda qeyd olunan xidmətlərdən birini seçin.');

    }


    public function callbackHandler()
    {
        $callbackData = $this->service->postData['callback_query']->data;

        // switch ($callbackData) {
        //     case 'yes':
        //         $replyText = "Great! I'm glad you like Telegram bots.";
        //         break;
        //     case 'end':
        //         $replyText = "Chat ended.";
        //         break;
        //     default:
        //         $replyText = "Unknown command.";
        //         break;
        // }

        $this->service->sendMessage($callbackData);
        return $this->service->answerCallbackQuery('Xidmət qəbul olundu.');
    }


}