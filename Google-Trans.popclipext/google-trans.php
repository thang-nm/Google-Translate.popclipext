<?php

function googleTrans($q) {
    $query = array(
      "client" => "gtx",
      "sl" => "auto",
      "tl" => "vi",
      "dt" => "t",
      "q" => $q,
    );
    $ch = curl_init();
    $url = 'https://translate.googleapis.com/translate_a/single?';

    curl_setopt($ch, CURLOPT_URL, $url . http_build_query($query));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
      die('');
    }

    $json = @json_decode($response, true);
    return isset($json[0][0][0]) ? $json[0][0][0] : '';
}

if ($input = getenv('POPCLIP_TEXT')) {
    echo googleTrans($input);
}
?>
