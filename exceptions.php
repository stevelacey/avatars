<?php

class AvatarException extends Exception {
    public $status_code = 404;
    public $status_message = 'Not Found';
}

class AvatarNotFoundException extends Exception {}

class InvalidUriException extends AvatarException {}
class UnsupportedNetworkException extends AvatarException {}
class UnsupportedSizeException extends AvatarException {}
