<?php

$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

$username = isset($uri[0]) ? $uri[0] : false;
$size = isset($uri[1]) ? $uri[1] : 'normal';

$image = false;

if ($username && $size && preg_match('/^[A-Za-z0-9_]+$/', $username) && in_array($size, array('bigger', 'normal', 'mini', 'original'))) {

  $cache = __DIR__ . '/cache/'.$username.'/'.$size;

  if (!file_exists($cache) || filemtime($cache) < strtotime('yesterday')) {

    $ch = curl_init('https://api.twitter.com/1/users/profile_image?screen_name='.$username.'&size='.$size); 

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch); 

    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
      $mime = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
      $length = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

      if (!is_dir($cache)) {
        mkdir(dirname($cache), 0755, true);
      }

      $image = $response;

      $file = fopen($cache, 'w');
      fwrite($file, $image);
      fclose($file);
    }
  } else {
    $file = fopen($cache, 'r');
    $image = fread($file, filesize($cache));
    fclose($file);

    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    $mime = finfo_file($finfo, $cache);
    $length = strlen($image);

    finfo_close($finfo);
  }
}

if ($image) {
  header('Expires: '.gmdate('D, d M Y H:i:s T', strtotime('+1 Week', filemtime($cache))));
  header('Last-Modified: '.gmdate('D, d M Y H:i:s T', filemtime($cache)));
  header('Content-type: '.$mime);
  header('Content-length: '.$length);

  echo $image;
} else {
  header('HTTP/1.0 404 Not Found');
}
