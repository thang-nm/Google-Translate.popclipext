<?php

function option($key, $deault = '') {
  $tl = getenv($key);
  if (empty($tl)) {
    return $default;
  }
  $start = strpos($tl, '(');
  $end = strpos($tl, ')');
  if ($start === false || $end === false) {
    return $default;
  }
  return  substr($tl, $start + 1, $end - $start - 1);
}

function normalize($q) {
  return method_exists('Normalizer', 'normalize')
    ? Normalizer::normalize($q)
    : $q;
}

function googleTrans($q, $tl) {
  $query = array(
    "client" => "gtx",
    "sl" => "auto",
    "tl" => $tl,
    "dt" => "t",
    "q" => normalize($q),
  );
  $ch = curl_init();
  $url = 'https://translate.googleapis.com/translate_a/single?';

  curl_setopt($ch, CURLOPT_URL, $url . http_build_query($query));
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
  // curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:9090');
  $response = curl_exec($ch);
  curl_close($ch);

  if (!$response) {
    die('');
  }

  $json = @json_decode($response, true);
  return isset($json[0][0][0]) ? $json[0][0][0] : '';
}

if ($input = getenv('POPCLIP_TEXT')) {
  echo googleTrans($input, option('POPCLIP_OPTION_TL', 'en'));
}
?>
