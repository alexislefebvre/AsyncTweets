# AsyncTweets

## Demo

http://asynctweets.alexislefebvre.com/demo/

## Goal

The goal of this project is to create an online Twitter reader built with [Symfony2][1]. AyncTweets will retrieve and store your timeline, allowing to read your Twitter timeline even if you're away from <s>the keyboard</s> your Twitter client several days.

## Features

 - Retrieve tweets by using User's Twitter keys
 - Display the tweets with a pagination

### TODO

 - Display tweets since last visit (for example by using a cookie)
 - Add OAuth authentification to identify an user with its Twitter account (may avoid requesting a key for this app)
 - Keep pagination numbers consistent even if tweets are added (new tweets) or removed (old tweets purged)
 - Display tweets on CLI (Command Line Interface)

## Installation

### Requirements:

 - [Twitter keys][2]
 - PHP >= 5.3.3 (required by Symfony2)
 - a database (must be supported by Doctrine2)

### Steps:
 
 1. Clone this repository
 2. Install [Composer][3] (`php -r "readfile('https://getcomposer.org/installer');" | php`)
 3. Install the vendors: `php composer.phar install --prefer-dist --no-dev -vvv --profile` and enter your Twitter keys at the end of the installation wizard (you can still add the keys later by editing the `app/config/parameters.yml` file)
 4. Create the database and create the tables: `php app/console doctrine:schema:update --force --env=prod`
 5. Launch this command to fetch tweets: `php app/console statuses:hometimeline`, try the ` --table` option to see the imported tweets
 6. Open the page with your browser `.../AsyncTweets/web/app_dev.php/`
 7. Add `php app/console statuses:hometimeline` in your crontab (e.g. every hour) to retrieve tweets automatically

### Tests:

`./phpunit.sh` or `phpunit -c app/phpunit.xml.dist`

## Dependencies

 - [symfony/symfony][4] (2.6)
 - [abraham/twitteroauth][5] (0.5.0)
 - [KnpLabs/KnpPaginatorBundle][6] (2.4.*@dev)

[1]: http://symfony.com/
[2]: https://apps.twitter.com/
[3]: https://getcomposer.org/download/
[4]: https://github.com/symfony/symfony
[5]: https://github.com/abraham/twitteroauth
[6]: https://github.com/KnpLabs/KnpPaginatorBundle
