<?php

if (!function_exists('pdi_paywall_api_get')) {
    function pdi_paywall_api_get($path)
    {
        $api_key = get_option('_pdi_paywall_payment_key');

        if (!empty($api_key)) {
            $response = pdi_curl([
                'path' => $path,
                'bearer_token' => $api_key,
                'method' => 'GET'
            ]);
            $http_code = wp_remote_retrieve_response_code($response);

            if ($http_code == '200') {
                return wp_remote_retrieve_body($response);
            }
        }

        return [];
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
function pdi_curl($options)
{
    $curl = curl_init();
    $curlData = [
        CURLOPT_URL => PDI_PAYWALL_API_URI . $options['path'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $options['method'],
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer " . $options['bearer_token'],
        ],
    ];

    if (isset($options['data'])){
        $curlData[CURLOPT_POSTFIELDS] = json_encode($options['data'],true);
    }

    curl_setopt_array($curl,$curlData );

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    return $response;
}

if (!function_exists('pdi_paywall_api_post')) {
    function pdi_paywall_api_post($path, $data)
    {
        $api_key = get_option('_pdi_paywall_payment_key');
        if (!empty($api_key)) {
            return pdi_curl([
                'path' => $path,
                'bearer_token' => $api_key,
                'method' => 'POST',
                'data' => $data
            ]);
        }

        return [];
    }
}

if (!function_exists('pdi_paywall_api_put')) {
    function pdi_paywall_api_put($path, $data)
    {
        $api_key = get_option('_pdi_paywall_payment_key');

        if (!!$api_key) {

            $response = pdi_curl([
                'path' => $path,
                'bearer_token' => $api_key,
                'method' => 'PUT',
                'data' => $data
            ]);
//            $response = wp_remote_request(PDI_PAYWALL_API_URI . $path, $args);
//            $http_code = wp_remote_retrieve_response_code($response);

//            if ($http_code == '200') {
                return wp_remote_retrieve_body($response);
//            }
        }

        return [];
    }
}

if (!function_exists('pdi_paywall_api_delete')) {
    function pdi_paywall_api_delete($path)
    {
        $api_key = get_option('_pdi_paywall_payment_key');

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
