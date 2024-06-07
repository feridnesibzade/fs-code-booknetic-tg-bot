<?php

namespace App\Services;

use stdClass;
use PHPHtmlParser\Dom;

class BookneticService
{

    protected const END_POINT = 'https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-admin/admin-ajax.php';

    public $postData;

    public function __construct()
    {
        $this->postData = [
            "payment_method" => "undefined",
            "deposit_full_amount" => "0",
            "client_time_zone" => "-",
            "google_recaptcha_token" => "undefined",
            "google_recaptcha_action" => "booknetic_booking_panel_1",
            "step" => "",
            "tenant_id" => 3,
            "cart" => [
                [
                    "location" => -1,
                    "staff" => -1,
                    "service_category" => "",
                    "service" => "",
                    "service_extras" => [],
                    "date" => "",
                    "time" => "",
                    "brought_people_count" => 0,
                    "recurring_start_date" => "",
                    "recurring_end_date" => "",
                    "recurring_times" => "{}",
                    "appointments" => "[]",
                    "customer_data" => "{}"
                ]
            ],
            "current" => "0",
            "query_params" => "{}",
            "action" => ""
        ];
    }

    public function getServices()
    {

        $this->postData['step'] = 'service';
        $this->postData['action'] = 'bkntc_get_data_service';

        $response = html_entity_decode($this->request()->html);

        $dom = new Dom;
        $dom->loadStr($response);
        // $categories = $dom->find('.booknetic_service_category');
        $services = $dom->find('.booknetic_service_card');
        // $catArr = [];
        $ss = [];

        // foreach ($categories as $category) {
        //     $catArr[] = $category->text . "\n";
        // }

        foreach ($services as $service) {
            $title = $service->find('.booknetic_service_title_span')->text;
            $duration = $service->find('.booknetic_service_duration_span')->text;
            $price = $service->find('.booknetic_service_card_price')->text;
            $dataId = $service->getAttribute('data-id');
            $ss[] = [
                'id' => $dataId,
                'title' => trim($title),
                'duration' => trim($duration),
                'price' => trim($price)
            ];
        }

        return $ss;
    }

    public function getDate($serviceId, $month=null)
    {
        $year = date('Y');
        $month = $month ?? date('m'); 

        $boundary = '---------------------------18834218767000265463402635281';

        // Prepare the form data
        $data = [
            "--$boundary",
            'Content-Disposition: form-data; name="payment_method"',
            '',
            'undefined',
            "--$boundary",
            'Content-Disposition: form-data; name="deposit_full_amount"',
            '',
            '0',
            "--$boundary",
            'Content-Disposition: form-data; name="client_time_zone"',
            '',
            '-',
            "--$boundary",
            'Content-Disposition: form-data; name="google_recaptcha_token"',
            '',
            'undefined',
            "--$boundary",
            'Content-Disposition: form-data; name="google_recaptcha_action"',
            '',
            'booknetic_booking_panel_1',
            "--$boundary",
            'Content-Disposition: form-data; name="step"',
            '',
            'date_time',
            "--$boundary",
            'Content-Disposition: form-data; name="year"',
            '',
            "$year",
            "--$boundary",
            'Content-Disposition: form-data; name="month"',
            '',
            "$month",
            "--$boundary",
            'Content-Disposition: form-data; name="cart"',
            '',
            '[{"location":-1,"staff":-1,"service_category":"","service":'.$serviceId.',"service_extras":[],"date":"","time":"","brought_people_count":0,"recurring_start_date":"","recurring_end_date":"","recurring_times":"{}","appointments":"[]","customer_data":{}}]',
            "--$boundary",
            'Content-Disposition: form-data; name="current"',
            '',
            '0',
            "--$boundary",
            'Content-Disposition: form-data; name="query_params"',
            '',
            '{}',
            "--$boundary",
            'Content-Disposition: form-data; name="coupon"',
            '',
            '',
            "--$boundary",
            'Content-Disposition: form-data; name="giftcard"',
            '',
            '',
            "--$boundary",
            'Content-Disposition: form-data; name="action"',
            '',
            'bkntc_get_data_date_time',
            "--$boundary",
            'Content-Disposition: form-data; name="tenant_id"',
            '',
            '3',
            "--$boundary--",
        ];

        // Convert the array to a string
        $post_fields = implode("\r\n", $data);

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, "https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-admin/admin-ajax.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "X-Requested-With: XMLHttpRequest",
            "Content-Type: multipart/form-data; boundary=$boundary",
            "Origin: https://sandbox.booknetic.com",
            "Alt-Used: sandbox.booknetic.com",
            "Connection: keep-alive",
            "Referer: https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/tutor2",
            "Sec-Fetch-Dest: empty",
            "Sec-Fetch-Mode: cors",
            "Sec-Fetch-Site: same-origin",
            "TE: trailers"
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            // Print the response
            return json_decode($response);
        }

        // Close cURL session
        curl_close($ch);

    }

    public function confirm($data){
        $time = explode('-',$data['step2']['time']);
        $this->postData = [
            'payment_method' => 'local',
            'deposit_full_amount' => '0',
            'client_time_zone' => '-',
            'google_recaptcha_token' => 'undefined',
            'google_recaptcha_action' => 'booknetic_booking_panel_1',
            'step' => 'confirm',
            'cart' => '[
                {"location":-1,
                    "staff":-1,
                    "service_category":"",
                    "service":'.$data['step1']['service'].',
                    "service_extras":[],
                    "date":"'.date('Y').'-'.$data['step2']['month'].'-'.$data['step2']['day'].'",
                    "time":"'.$time[0].'",
                    "brought_people_count":0,
                    "recurring_start_date":"'.$time[0].'",
                    "recurring_end_date":"'.$time[1].'",
                    "recurring_times":"{}",
                    "appointments":"[]",
                    "customer_data":
                        {
                            "first_name":"'.$data['step3']['name'].'",
                            "last_name":"'.$data['step3']['surname'].'",
                            "email":"'.$data['step3']['email'].'",
                            "phone":"'.$data['step3']['phone'].'"
                        },
                        "custom_fields":{}
                    }
                ]',
            'current' => '0',
            'query_params' => '{}',
            'coupon' => '',
            'giftcard' => '',
            'action' => 'bkntc_confirm',
            'tenant_id' => '3'
        ];

        return $this->request();
    }



    public function request()
    {
        $ch = curl_init(self::END_POINT);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0",
        //     "Accept: */*",
        //     "Accept-Language: en-US,en;q=0.5",
        //     "Accept-Encoding: gzip, deflate, br, zstd",
        //     "X-Requested-With: XMLHttpRequest",
        //     "Content-Type: multipart/form-data;",
        //     "Origin: https://sandbox.booknetic.com",
        //     "Alt-Used: sandbox.booknetic.com",
        //     "Connection: keep-alive",
        //     "Referer: https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/tutor2",
        //     "Sec-Fetch-Dest: empty",
        //     "Sec-Fetch-Mode: cors",
        //     "Sec-Fetch-Site: same-origin",
        //     "TE: trailers"
        // ]);


        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->postData));
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postData);

        $response = curl_exec($ch);

        curl_close($ch);

        if ($response === false) {
            echo 'cURL Error: ' . curl_error($ch);
        } else {
            return json_decode($response);
        }
    }

}