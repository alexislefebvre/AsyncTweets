AsyncTweets
===========

Goal
----

The goal of this project is to create a PHP Twitter reader. AyncTweets will retrieve and store your timeline, allowing to read tweets even if you're away from <s>the keyboard</s> your Twitter client.

Installation
------------

 1. Clone this repository
 2. Install [Composer][1]
 3. Install the vendors: `php composer.phar install --prefer-dist -vvv --profile`
 4. Edit the `app/config/parameters.yml` file and add your [Twitter keys][2]
 5. Launch the only available command yet: `https://apps.twitter.com/`

[1]: https://getcomposer.org/download/
[2]: https://apps.twitter.com/
