<?php

if (preg_match('#^/(\w+)/([A-Za-z0-9_]{1,15})#', $_SERVER['REQUEST_URI'], $matches)) {
    list($uri, $network, $username) = $matches;
    $size = isset($_GET['size']) ? $_GET['size'] : 'normal';

    $networks = ['twitter'];
    $sizes = ['400x400', 'bigger', 'normal', 'mini', 'original'];

    if ($username && in_array($network, $networks) && in_array($size, $sizes)) {
        $profile = file_get_contents("https://twitter.com/${username}");

        if (preg_match('#[^"]+profile_images[^"]+#', $profile, $matches)) {
            $avatar = current($matches);

            if ($size != 'original') {
                $avatar = preg_replace('#(.*)\.(.*)#', "$1_${size}.$2", $avatar);
            }

            header('Expires: ' . date('D, d M Y H:i:s T', strtotime('+1 Week')));
            header('Last-Modified: ' . date('D, d M Y H:i:s T'));
            header("Location: ${avatar}", true, 302);
        }
    } else {
        header('HTTP/1.1 404 Not Found');
    }
} else {
    header('HTTP/1.1 404 Not Found');
}
