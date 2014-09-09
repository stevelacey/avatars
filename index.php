<?php

$username = preg_replace('#^/([A-Za-z0-9_]{1,15}).*#', '$1', $_SERVER['REQUEST_URI']);
$size = isset($_GET['size']) ? $_GET['size'] : 'normal';

$sizes = ['400x400', 'bigger', 'normal', 'mini', 'original'];

if ($username && in_array($size, $sizes)) {
    $profile = file_get_contents("https://twitter.com/${username}");

    if (preg_match('#[^"]+profile_images[^"]+#', $profile, $matches)) {
        $uri = current($matches);

        if ($size != 'original') {
            $uri = preg_replace('#(.*)\.(.*)#', "$1_${size}.$2", $uri);
        }

        header("Location: ${uri}", true, 302);
    }
} else {
    header('HTTP/1.1 404 Not Found');
}

