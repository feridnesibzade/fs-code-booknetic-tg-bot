<?php

namespace App\Controllers;

use App\Services\BookneticService;




class MainController
{

    public $bookneticService;

    public function __construct()
    {
        $this->bookneticService = new BookneticService();
    }

    public function home()
    {
        // $this->bookneticService->postData = array(
        //     'payment_method' => 'local',
        //     'deposit_full_amount' => '0',
        //     'client_time_zone' => '-',
        //     'google_recaptcha_token' => 'undefined',
        //     'google_recaptcha_action' => 'booknetic_booking_panel_1',
        //     'step' => 'confirm',
        //     'cart' => '[
        //         {
        //             "location":-1,
        //             "staff":-1,
        //             "service_category":"",
        //             "service":11,
        //             "service_extras":[],
        //             "date":"2024-06-07",
        //             "time":"14:00",
        //             "brought_people_count":0,
        //             "recurring_start_date":"13:00",
        //             "recurring_end_date":"14:00",
        //             "recurring_times":"{}",
        //             "appointments":"[]",
        //             "customer_data":{
        //                 "first_name":"asdasd",
        //                 "last_name":"asdasd",
        //                 "email":"ferid.nesibzade@inbox.ru",
        //                 "phone":"+11312123"
        //             },
        //             "custom_fields":{}
        //         }
        //     ]',
        //     'current' => '0',
        //     'query_params' => '{}',
        //     'coupon' => '',
        //     'giftcard' => '',
        //     'action' => 'bkntc_confirm',
        //     'tenant_id' => '3'
        // );

        // $res = $this->bookneticService->request();

        // dump($res);

        foreach ($this->bookneticService->getServices() as $service) {
            if ($service['id'] == 12) { // Change the ID as needed
                $filteredService = $service;
                break; // Exit the loop once the desired element is found
            }
        }
        dump($filteredService);

    }
}