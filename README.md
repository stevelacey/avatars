# A simple tweetimag.es clone writen in PHP

Tweetimag.es seems to be constantly broken, I figured they're having issues scaling, so I wrote my own.

The script caches images and sets some sensible headers when serving them, if you intend to use this for anything serious I'd recommend throwing Amazon CloudFront or a similar CDN in front of it.

## Examples

 /username

 /username/[bigger,normal,mini,original]
