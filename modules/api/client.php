<?php

if (!function_exists('pdi_paywall_api_get')) {
    function pdi_paywall_api_get($path)
    {
        return pdi_curl([
            'path' => $path,
            'method' => 'GET'
        ]);
    }
}

/*
* [
*        'path' => $path,
*        'bearer_token' => $api_key,
*        'method' => 'POST',
*        'data' => $data
*    ]
*/
/*
function pdi_curl($options)
{
    $data = [];
    if (isset($options['data'])){
        $data = wp_json_encode($options['data']);
    }
    $curl = curl_init(PDI_PAYWALL_API_URI . $options['path']);

    if (get_option('_pdi_paywall_payment_sandbox')){
        $token = get_option('_pdi_paywall_payment_access_token_test');
    }else{
        $token = get_option('_pdi_paywall_payment_access_token');
    }
    $curlData = [
        CURLOPT_HEADER => 1,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $options['method'],
        CURLOPT_HTTPHEADER => [
            "Accept:application/json",
            "Content-Type:application/json",
            "Authorization:Bearer " . $token,
            "x-customer-key:".get_option('_pdi_paywall_payment_pdi_key'),
        ],
    ];
    if (isset($options['data'])){
        $curlData[CURLOPT_POSTFIELDS] = json_encode($data,true);
    }

    curl_setopt_array($curl,$curlData );

    $response = curl_exec($curl);

    $err = curl_error($curl);
    curl_close($curl);
    if ($err){
        var_dump($err);
    }
    var_dump($response);exit;
    return $response;
}
*/
function pdi_curl($options)
{
    $api_key = get_option('_pdi_paywall_payment_pdi_token');
    if (!empty($api_key)) {
        $data = '';
        if (isset($options['data'])){
            $data = wp_json_encode($options['data']);
        }
        $args = array(
            'method' => $options['method'],
            'body' => $data,
            'headers' => array(
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Authorization"=> "Bearer " . $api_key,
                "x-customer-key" => get_option('_pdi_paywall_payment_pdi_key'),
            ),
        );
        $response = wp_remote_request(PDI_PAYWALL_API_URI . $options['path'], $args);
        $http_code = wp_remote_retrieve_response_code($response);



        if(isset($response['body'])){
            $body = json_decode($response['body'],true);
            if (isset($body['message'])){
                add_settings_error('pdi-settings-save-error','pdi-error', $body['message']);
            }
        }
        if ($http_code == '200' || $http_code == '201') {
            return wp_remote_retrieve_body($response);
        } else {
             var_dump($http_code);
             var_dump($response);
            exit('erro: pdi_curl');
        }
    }

    return [];
}

if (!function_exists('pdi_paywall_api_post')) {
    function pdi_paywall_api_post($path, $data)
    {

        return pdi_curl([
            'path' => $path,
            'method' => 'POST',
            'data' => $data
        ]);
    }
}

//if (!function_exists('pdi_paywall_api_post')) {
//    function pdi_paywall_api_post($path, $data)
//    {
//        $api_key = get_option('_pdi_paywall_payment_pdi_token');
//
//        if (!empty($api_key)) {
//            $args = array(
//                'body' => wp_json_encode($data),
//                'headers' => array(
//                    "Accept" => "application/json",
//                    "Content-Type" => "application/json",
//                    "Authorization"=> "Bearer " . $api_key,
//                    "x-customer-key" => get_option('_pdi_paywall_payment_pdi_key'),
//                ),
//            );
//
//            $response = wp_remote_post(PDI_PAYWALL_API_URI . $path, $args);
//            $http_code = wp_remote_retrieve_response_code($response);
//
//            if ($http_code == '200') {
//                return wp_remote_retrieve_body($response);
//            }
//        }
//
//        return [];
//    }
//}

if (!function_exists('pdi_paywall_api_put')) {
    function pdi_paywall_api_put($path, $data)
    {
        $options = [
            'path' => $path,
            'method' => 'PUT',
            'data' => $data
        ];
        return pdi_curl($options);
    }
}

//if (!function_exists('pdi_paywall_api_put')) {
//    function pdi_paywall_api_put($path, $data)
//    {
//        $api_key = get_option('_pdi_paywall_payment_pdi_token');
//
//        if (!empty($api_key)) {
//            $args = array(
//                'method' => 'PUT',
//                'body' => wp_json_encode($data),
//                'headers' => array(
//                    "Accept" => "application/json",
//                    "Authorization"=> "Bearer " . $api_key,
//                    "x-customer-key" => get_option('_pdi_paywall_payment_pdi_key'),
//                ),
//            );
//            $response = wp_remote_request(PDI_PAYWALL_API_URI . $path, $args);
//            $http_code = wp_remote_retrieve_response_code($response);
////            var_dump($response);
//            var_dump(wp_remote_retrieve_body($response));exit(1);
//             if ($http_code == '200') {
//                return wp_remote_retrieve_body($response);
//            }
//        }
//
//        return [];
//    }
//}

if (!function_exists('pdi_paywall_api_delete')) {
    function pdi_paywall_api_delete($path)
    {
        $api_key = get_option('_pdi_paywall_payment_pdi_token');

        if (!empty($api_key)) {
            $args = array(
                'method' => 'DELETE',
                'headers' => array(
                    'Accept' => 'application/json',
                    'Access-Control-Allow-Credentials' => true,
                    'Authorization' => 'Bearer ' . $api_key
                ),
            );

            $response = wp_remote_request(PDI_PAYWALL_API_URI . $path, $args);
            $http_code = wp_remote_retrieve_response_code($response);

            if ($http_code == '200') {
                return wp_remote_retrieve_body($response);
            }
        }

        return [];
    }
}
