<?php

require_once __DIR__ . '/exceptions.php';

$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
$size = isset($_GET['size']) ? $_GET['size'] : 'normal';

$networks = array('twitter');
$sizes = array('400x400', 'bigger', 'normal', 'mini', 'original');

try {
    if (!preg_match('#^/(\w+)/([A-Za-z0-9_]{1,15})#', $uri, $matches)) {
        throw new InvalidUriException();
    }

    list($uri, $network, $username) = $matches;

    if (!in_array($network, $networks)) {
        throw new UnsupportedNetworkException();
    } elseif (!in_array($size, $sizes)) {
        throw new UnsupportedSizeException();
    }

    $profile = file_get_contents("https://twitter.com/{$username}");

    if (!preg_match('#[^"]+profile_images[^"]+#', $profile, $matches)) {
        throw new AvatarNotFoundException();
    }

    $avatar = current($matches);

    if ($size != 'original') {
        $avatar = preg_replace('#(.*)\.(.*)#', "$1_{$size}.$2", $avatar);
    }

    header('Expires: ' . date('D, d M Y H:i:s T', strtotime('+1 Week')));
    header('Last-Modified: ' . date('D, d M Y H:i:s T'));
    header("Location: {$avatar}", true, 302);
} catch (AvatarException $e) {
    header("HTTP/1.1 {$e->status_code} {$e->status_message}");
}
