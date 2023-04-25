<?php
function newToken() {
    $ducky = trim(file_get_contents("../secret/api.txt")) . "token";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CAINFO, "../cacert-2022-04-26.pem");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json' ,'Content-Type:application/x-www-form-urlencoded'));

    $data = array(
        'grant_type' => '',
        'username' => trim(file_get_contents("../secret/user.txt")),
        'password' => trim(file_get_contents("../secret/password.txt")),
        'scope' => '',
        'client_id' => '',
        'client_secret' => '',
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_URL, $ducky);
    $result = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($result, true);
    
    $token = $result['access_token'];
    $secret = fopen("../secret/key.txt", "w");
    fwrite($secret, $token);
    fclose($secret);
    return $token;
}
