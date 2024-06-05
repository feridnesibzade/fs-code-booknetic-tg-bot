<?php

namespace App\Controllers;

class MainController
{

    public function home()
    {

        $url = "https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-admin/admin-ajax.php";

        // The form data to be sent in the POST request
        $data = "-----------------------------33822731239914128313659525509\r\n"
            . "Content-Disposition: form-data; name=\"payment_method\"\r\n\r\n"
            . "undefined\r\n"
            . "-----------------------------33822731239914128313659525509\r\n"
            . "Content-Disposition: form-data; name=\"deposit_full_amount\"\r\n\r\n"
            . "0\r\n"
            . "-----------------------------33822731239914128313659525509\r\n"
            . "Content-Disposition: form-data; name=\"client_time_zone\"\r\n\r\n"
            . "-\r\n"
            . "-----------------------------33822731239914128313659525509\r\n"
            . "Content-Disposition: form-data; name=\"google_recaptcha_token\"\r\n\r\n"
            . "undefined\r\n"
            . "-----------------------------33822731239914128313659525509\r\n"
            . "Content-Disposition: form-data; name=\"google_recaptcha_action\"\r\n\r\n"
            . "booknetic_booking_panel_1\r\n"
            . "-----------------------------33822731239914128313659525509\r\n"
            . "Content-Disposition: form-data; name=\"step\"\r\n\r\n"
            . "service\r\n"
            . "-----------------------------33822731239914128313659525509\r\n"
            . "Content-Disposition: form-data; name=\"cart\"\r\n\r\n"
            . "[{\"location\":-1,\"staff\":-1,\"service_category\":\"\",\"service\":\"\",\"service_extras\":[],\"date\":\"\",\"time\":\"\",\"brought_people_count\":0,\"recurring_start_date\":\"\",\"recurring_end_date\":\"\",\"recurring_times\":\"{}\",\"appointments\":\"[]\",\"customer_data\":{}}]\r\n"
            . "-----------------------------33822731239914128313659525509\r\n"
            . "Content-Disposition: form-data; name=\"current\"\r\n\r\n"
            . "0\r\n"
            . "-----------------------------33822731239914128313659525509\r\n"
            . "Content-Disposition: form-data; name=\"query_params\"\r\n\r\n"
            . "{}\r\n"
            . "-----------------------------33822731239914128313659525509\r\n"
            . "Content-Disposition: form-data; name=\"action\"\r\n\r\n"
            . "bkntc_get_data_service\r\n"
            . "-----------------------------33822731239914128313659525509\r\n"
            . "Content-Disposition: form-data; name=\"tenant_id\"\r\n\r\n"
            . "3\r\n"
            . "-----------------------------33822731239914128313659525509--\r\n";

        $headers = [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding: gzip, deflate, br, zstd",
            "X-Requested-With: XMLHttpRequest",
            "Content-Type: multipart/form-data; boundary=---------------------------33822731239914128313659525509",
            "Origin: https://sandbox.booknetic.com",
            "Alt-Used: sandbox.booknetic.com",
            "Connection: keep-alive",
            "Referer: https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/tutor2",
            "Sec-Fetch-Dest: empty",
            "Sec-Fetch-Mode: cors",
            "Sec-Fetch-Site: same-origin",
            "TE: trailers"
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Disable automatic decoding
        curl_setopt($ch, CURLOPT_ENCODING, '');

        // Execute the request
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_message = 'Curl error: ' . curl_error($ch);
            error_log($error_message, 3, $_SERVER['DOCUMENT_ROOT'] . '/../src/storage/error.log');
            curl_close($ch);
            throw new \Exception($error_message);
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200) {
            $error_message = 'Request failed with status code: ' . $http_code . ' Response: ' . $response;
            error_log($error_message, 3, $_SERVER['DOCUMENT_ROOT'] . '/../src/storage/error.log');
            curl_close($ch);
            throw new \Exception($error_message);
        }

        curl_close($ch);

        // Manually decode the response if necessary
        if (strpos($response, "\x28\xb5\x2f\xfd") === 0) {
            // Zstd header magic bytes, need to decode
            $response = zstd_uncompress($response);
        }

        echo "Response: " . $response;
    }
}