<?php
function getMetaStats(String $token, String $api)
{
    $ducky = $api . "stats";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ducky);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CAINFO, "cacert-2022-04-26.pem");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
    $result = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code == 401) {
        return false;
    }
    if ($code != 200) {
        return true;
    }
    $js = json_decode(json_decode($result, true), true);
    return $js;
}
if (!$metaStats = getMetaStats($token, $api)) {
    //get new token
    require_once('newToken.php');
    $token = newToken();
    $metaStats = getMetaStats($token, $api);
}
$metaString = "";
if (!is_bool($metaStats)) {
    $metaString = " with " . htmlspecialchars($metaStats[0]['total']) . " reviews for " . htmlspecialchars($metaStats[0]['percourse']) . " courses";
}
?>
<meta property="og:type" content="website">
<meta property="og:url" content="https://n.ethz.ch/~lteufelbe/coursereview/">
<meta property="og:title" content="CourseReview">
<meta property="og:description" content="Site to review courses from ETHZ<?php print $metaString ?>.">
<meta property="og:image" content="https://n.ethz.ch/~lteufelbe/coursereview/icon.png">