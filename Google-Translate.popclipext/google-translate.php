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
  if (method_exists('Normalizer', 'normalize')) {
    return Normalizer::normalize($q);
  }

  $q = str_replace('"', '\\"', $q);
  $command = sprintf('perl -MUnicode::Normalize -Mutf8 -CS -e \'binmode(STDOUT, ":utf8"); print NFC("%s")\'', $q);
  $result = shell_exec($command);
  return $result ? $result : $q;
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

  return @json_decode($response, true);
}

if ($input = getenv('POPCLIP_TEXT')) {
  $json = googleTrans($input, option('POPCLIP_OPTION_TL', 'en'));
  if (!isset($json[0])) {
    die('');
  }
  
  echo array_reduce($json[0], function($carry, $item) {
    if (isset($item[0])) {
      $carry .= $item[0];
      return $carry;
    }
  }, '');
}
?>
