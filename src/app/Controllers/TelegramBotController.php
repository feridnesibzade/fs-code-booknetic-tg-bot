<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\BookneticService;
use App\Services\TelegramBotService;

class TelegramBotController
{

    public $botService;
    public $bookneticService;
    public $userModel;
    public $user;

    public function __construct()
    {
        $this->botService = new TelegramBotService();
        $this->bookneticService = new BookneticService();
        $this->userModel = new User();
        $this->user();
    }

    public function handleRequest()
    {
        $data = @json_decode($this->user['data'] ?? '', true) ?? [];

        if (isset($this->botService->postData['callback_query'])) {
            return $this->callbackHandler();
        }

        // $this->step1();

        if ($this->botService->requestData->message->text == '/start' && !isset($data['step1']['service'])) {
            $this->step1();
        }


        if (isset($data['step2']['time'])) {
            if (!isset($data['step3']['name'])) {
                $this->askName();
                die();
            } else if ($this->isWaitingForReply('name')) {
                $this->step3Set('name', $this->botService->requestData->message->text);
            }

            if (!$this->isWaitingForReply('name') && !isset($data['step3']['surname'])) {
                $this->askSurname();
                die();
            } else if ($this->isWaitingForReply('surname')) {
                $this->step3Set('surname', $this->botService->requestData->message->text);
            }

            if (!$this->isWaitingForReply('surname') && !isset($data['step3']['email'])) {
                $this->askEmail();
                die();
            } else if ($this->isWaitingForReply('email')) {
                $this->step3Set('email', $this->botService->requestData->message->text);
            }

            if (!$this->isWaitingForReply('email') && !isset($data['step3']['phone'])) {
                $this->askPhone();
                die();
            } else if ($this->isWaitingForReply('phone')) {
                $this->step3Set('phone', $this->botService->requestData->message->text);
            }

            if (!$this->isWaitingForReply('phone')) {
                $this->summary();
            }
        }
    }

    public function callbackHandler()
    {
        $callbackData = $this->botService->postData['callback_query']->data;

        $key = explode('.', $callbackData)[0];

        if ($key == 'service') {
            $this->step1Set($callbackData);
            $this->sendMonths();
        } elseif ($key == 'month') {
            $this->step2Set($callbackData);
            $this->sendActiveDays();
        } elseif ($key == 'day') {
            $this->step2Set($callbackData);
            $this->sendHours();
        } elseif ($key == 'time') {
            $this->step2Set($callbackData);
            $this->botService->sendMessage('Qeydiyyatı tamamlamaq üçün bir sıra şəxsi məlumatları doldurmağınız xahiş olunur.');
            $this->askName();
        } elseif ($key == 'info') {
            $this->handleConfirm($callbackData);
        }

        $this->botService->answerCallbackQuery('Uğurlu.');

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

    public function step1Set($callbackData)
    {
        $user = $this->user;
        $data = [];
        $data['step1'] = ['service' => explode('.', $callbackData)[1]];

        $this->user = $this->userModel->updateAndGet([
            'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'id' => $user['id']
        ]);
    }

    public function step2Set($callbackData, $step = 'step2')
    {
        $user = $this->user;
        $data = json_decode($user['data'], true);

        $callBackParse = explode('.', $callbackData);
        $data[$step][$callBackParse[0]] = $callBackParse[1];

        $this->user = $this->userModel->updateAndGet([
            'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'id' => $user['id']
        ]);
    }

    public function step3Set($title, $message)
    {
        $user = $this->user;
        $data = json_decode($user['data'], true);

        $data['step3'][$title] = $message;

        $this->user = $this->userModel->updateAndGet([
            'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'id' => $user['id']
        ]);
    }

    public function isWaitingForReply($title)
    {
        $data = json_decode($this->user['data'], true);
        if (isset($data['step3'][$title])) {
            return $data['step3'][$title] == 'waiting' ? true : false;
        }
        return false;
    }

    public function sendMonths()
    {
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

    public function sendActiveDays()
    {
        $userData = json_decode($this->user['data'], true);
        $data = $this->bookneticService->getDate($userData['step1']['service'], $userData['step2']['month'])->data->dates;
        $days = mapDays($data);
        $buttons = [];
        foreach ($days as $day) {
            $buttons[] = $day;
        }
        $this->botService->setButtons($buttons);
        $this->botService->sendMessage('Boş günlərdən birini seçin.');
    }

    public function sendHours()
    {
        $userData = json_decode($this->user['data'], true);
        $data = $this->bookneticService->getDate($userData['step1']['service'], $userData['step2']['month'])->data->dates;
        $days = mapDays($data);
        $hours = findInMultiDimensionalNestedArray($days, $userData['step2']['day'])['times'];
        $this->botService->setButtons(mapTimes($hours));
        $this->botService->sendMessage('Qeyd olunan vaxtlardan seçin.');
    }

    public function askName()
    {
        $this->step3Set('name', 'waiting');
        $this->botService->forceReply();
        $this->botService->sendMessage('Adınızı daxil edin.');
    }

    public function askSurname()
    {
        $this->botService->forceReply();
        $this->botService->sendMessage('Soyadınızı daxil edin.');
        $this->step3Set('surname', 'waiting');
    }

    public function askEmail()
    {
        $this->botService->forceReply();
        $this->botService->sendMessage('E-mailinizi daxil edin.');
        $this->step3Set('email', 'waiting');
    }

    public function askPhone()
    {
        $this->botService->forceReply();
        $this->botService->sendMessage('Nömrənizi daxil edin.');
        $this->step3Set('phone', 'waiting');
    }

    public function summary()
    {
        $data = json_decode($this->user['data'], true);
        $date = $data['step2']['month'] . '-' . $data['step2']['day'] . ' ' . $data['step2']['time'];
        $fullName = $data['step3']['name'] . ', ' . $data['step3']['surname'];

        foreach ($this->bookneticService->getServices() as $service) {
            if ($service['id'] == 12) { // Change the ID as needed
                $filteredService = $service;
                break; // Exit the loop once the desired element is found
            }
        }
       
        $service = $filteredService['title'] . ' - ' . $filteredService['duration'] . ' - ' . $filteredService['price'];
        $email = $data['step3']['email'];
        $phone = $data['step3']['phone'];

        $message = "
            Xidmət: $service \n
            Tarix: $date \n
            Ad, Soyad: $fullName \n
            Email: $email \n
            Nömrə: $phone \n
        ";

        $this->botService->setButtons([
            [
                ['text' => 'Təstiqlə', 'callback_data' => 'info.confirm'],
                ['text' => 'Düzəliş et', 'callback_data' => 'info.edit']
            ]
        ]);

        $this->botService->sendMessage($message);
    }

    public function handleConfirm($callbackData)
    {
        $response = $this->bookneticService->confirm(json_decode($this->user['data'],true));
        // $this->botService->sendMessage(json_encode($response));
        $value = explode('.', $callbackData)[1];
        if($value == 'edit'){
            $this->botService->sendMessage('Düzəliş');
        }else{
            $this->botService->sendMessage('Rezerviniz təstiqləndi. Kodunuzu: '.$response->customer_id);
        }

    }


    public function user()
    {
        $user = $this->userModel->select($this->botService->postData['chat_id'] ?? 2323);

        if (!$user) {
            $this->userModel->insert([
                'chat_id' => $this->botService->postData['chat_id'],
                'data' => json_encode([], JSON_UNESCAPED_UNICODE)
            ]);

        }

        $this->user = $user;
        return $user;
    }



}