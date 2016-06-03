CueNation player
==================================
Cue files service. CueNation API adapter.


# How to install

* `git pull origin master` -- always points to the latest development commit (no need to guess branch name)
* `composer install`

# How to test

`php vendor/bin/phpunit` -- add `--group=unit` or `...functional` to differentiate between types. Services which 
communicate over network return predefined responses (useful for functional tests). Integration tests are coming in the 
Gateway API.

# API

### Cue categories
Return all cue-categories

**Request**

`GET /cue-categories`

**Response**

**NOTE** `If-None-Match` header is present `data` section is empty

```
{
    "data": [
        {
            "id": "53f24f73cb88814e672ce4f0",
            "name": "#goldrushRADIO",
            "host": "with Ben Gold",
            "link": "http://cuenation.com/?page=cues&folder=goldrushradio"
        }
    ],
    "meta": {
        "ETag": "this is a test ETag"
    }
}
```