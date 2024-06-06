<?php

namespace App\Controllers;

use App\Services\BookneticService;
use App\Services\TelegramBotService;
use Exception;

class TelegramBotController
{

    public $botService;
    public $bookneticService;
    public $log;

    public function __construct()
    {
        $this->botService = new TelegramBotService();
        $this->bookneticService = new BookneticService();
    }

    public function handleRequest()
    {

        if (isset($this->botService->postData['callback_query'])) {
            return $this->callbackHandler();
        }

        $this->step2();



        // dump();

        // dump($this->bookneticService->getDate(12)->data->dates);
        // dump($this->bookneticService->getServices());

        // if($this->botService->requestData->message->text == '/start'){
        //     $this->step1();
        // }

    }

    public function callbackHandler()
    {
        $callbackData = $this->botService->postData['callback_query']->data;

        if (preg_match('/^service./', $callbackData)) {
            $this->step2();
        }


        $this->botService->sendMessage($callbackData);
        return $this->botService->answerCallbackQuery('Xidmət qəbul olundu.');
    }

    public function step1()
    {
        $services = $this->bookneticService->getServices();

        $buttons = [];

        foreach ($services as $service) {
            $buttons[] = [
                [
                    'text' => $service['title'] . ' - ' . $service['duration'] . ' - ' . $service['price'],
                    'callback_data' => 'service.' . $service['id']
                ]
            ];
        }

        $this->botService->setButtons(
            $buttons
        );

        return $this->botService->sendMessage('Booknetic rezerv sisteminə xoş gəlmisiniz. Başlamaq üçün aşağıda qeyd olunan xidmətlərdən birini seçin.');
    }

    public function step2()
    {

        


        // $id = explode('.', $this->botService->postData['callback_query']->data)[1];
        // $this->bookneticService->getDate($id ?? 12);
    }

    public function sendMonths(){
        $buttons = [];
        foreach (getActiveMonths() as $key => $month) {
            $buttons[] = [
                [
                    'text' => $month,
                    'callback_data' => 'month.' . $key
                ]
            ];
        }
        $this->botService->setButtons($buttons);
        $this->botService->sendMessage('Ay seçin:');
    }

}