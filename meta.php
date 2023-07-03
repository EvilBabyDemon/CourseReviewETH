<?php
function getMetaStats(String $api)
{
    $ducky = $api . "stats";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ducky);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CAINFO, "cacert-2022-04-26.pem");
    $result = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code != 200) {
        return true;
    }
    $js = json_decode(json_decode($result, true), true);
    return $js;
}
$metaStats = getMetaStats($api);
$metaString = "";
if (!is_bool($metaStats)) {
    $metaString = ": " . htmlspecialchars($metaStats[0]['total']) . " reviews & ratings for " . htmlspecialchars($metaStats[0]['percourse']) . " courses";
}
?>
<meta property="og:type" content="website">
<meta property="og:site_name" content="CourseReview">
<meta property="og:image" content="https://n.ethz.ch/~lteufelbe/coursereview/icon.png">
<meta property="og:description" content="Review courses from ETHZ<?php print $metaString ?>">