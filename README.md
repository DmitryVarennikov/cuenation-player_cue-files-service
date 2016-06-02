CueNation player
==================================
Cue files service
----------------------------------


# How to install

* `git pull origin master` -- always points to the latest development commit (no need to guess branch name)
* `composer install`

# How to test

`php vendor/bin/phpunit` -- add `--group=unit` or `...functional` to differentiate between types. Services which 
communicate over network return predefined responses (useful for functional tests). Integration tests are coming in the 
Gateway API.

# API

* `GET /cue-categories` -- return all cue-categories