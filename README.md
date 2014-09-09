# Avatars

A pretty basic replacement for Twitter's `GET users/profile_image/:screen_name`,
stick this in front of Varnish and it'll cache and serve 302's to Twitter avatar
URI's.

http://avatars.stevelacey.net/twitter/SirPatStew?size=original

## Examples

    /twitter/screen_name

    /twitter/screen_name?size={400x400,bigger,normal,mini,original}
