<?php

// Function to generate access token
function generateToken() {

    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    $credentials = base64_encode('s0V0g6quhI93Yga8VnBS8ANommGCVeGm:SY1mNEPtVm26AOXG');
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $curl_response = curl_exec($curl);

    $json_deocde = json_decode($curl_response);

    $access_token = $json_deocde->access_token;

    return $access_token;
}

// Function to register confirmation and validation urls
function registerURL() {

    $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer'.generateToken())); //setting custom header


    $curl_post_data = array(
    //Fill in the request parameters with valid values
    'ShortCode' => '600000',
    'ResponseType' => 'Completed',
    'ConfirmationURL' => 'http://127.0.0.1/mpesa-c2b/confirmation',
    'ValidationURL' => 'http://127.0.0.1/mpesa-c2b/validation'
    );

    $data_string = json_encode($curl_post_data);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    $curl_response = curl_exec($curl);

    return $curl_response;

}

// Function to simulate C2B transaction
function simulateC2B($amount, $phone) {

    $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer'.generateToken())); //setting custom header


    $curl_post_data = array(
            //Fill in the request parameters with valid values
            'ShortCode' => '600000',
            'CommandID' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'Msisdn' => $phone,
            'BillRefNumber' => 'test'
    );

    $data_string = json_encode($curl_post_data);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    $curl_response = curl_exec($curl);

    return $curl_response; 

}

?>