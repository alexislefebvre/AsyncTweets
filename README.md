AsyncTweets
===========

## Goal

The goal of this project is to create a PHP Twitter reader built with [Symfony2][1]. AyncTweets will retrieve and store your timeline, allowing to read your Twitter timeline even if you're away from <s>the keyboard</s> your Twitter client several days.

## Installation

### Requirements:

 - [Twitter keys][2]
 - PHP >=5.3.3 (required by Symfony2)

### Steps:
 
 1. Clone this repository
 2. Install [Composer][3] (`php -r "readfile('https://getcomposer.org/installer');" | php`)
 3. Install the vendors: `php composer.phar install --prefer-dist -vvv --profile` and enter your Twitter keys at the end of the installation wizard (you can still add the keys later by editing the `app/config/parameters.yml` file)
 4. Launch the tests: `./phpunit.sh` or `phpunit -c app/phpunit.xml.dist`
 5. Launch the only available command yet: `php app/console statuses:hometimeline`, try the ` --printr` and ` --printruser` options

## Dependencies

 - [symfony/symfony][4] (2.6)
 - [abraham/twitteroauth][5] (0.5.0)

[1]: http://symfony.com/
[2]: https://apps.twitter.com/
[3]: https://getcomposer.org/download/
[4]: https://github.com/symfony/symfony
[5]: https://github.com/abraham/twitteroauth
