<?php
function submitRating(String $ducky, String $token)
{
    $ch = curl_init($ducky);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CAINFO, "cacert-2022-04-26.pem");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

    // Set HTTP Header for POST request
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization: Bearer ' . $token));

    $result = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // Close cURL session handle
    curl_close($ch);

    if ($code == 401) {
        return true;
    }
    // handle curl error
    if ($code != 200) {
        print "Error Code: $code <br>";
        print "Something went wrong I am sorry and the rating did not get saved.<br>";
    } 
    return false;
}
?>