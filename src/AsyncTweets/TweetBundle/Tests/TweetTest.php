<?php

namespace AsyncTweets\TweetBundle\Tests\Entity;

use AsyncTweets\TweetBundle\Entity\Tweet;
use AsyncTweets\TweetBundle\Entity\User;

class TweetTest extends \PHPUnit_Framework_TestCase
{
    public function testUser()
    {
        $user = new User();
        $user
            ->setId(90556897)
            ->setName('Twitter France')
            ->setScreenName('TwitterFrance')
        ;
        
        $this->assertEquals(
            'Twitter France',
            $user->getName()
        );
        
        $this->assertEquals(
            'TwitterFrance',
            $user->getScreenName()
        );
    }
    
    public function testTweet()
    {
        $now = new \Datetime('now');
        
        $tweet = new Tweet();
        $tweet
            ->setId(565939802152120320)
            ->setCreatedAt($now)
            ->setText('Hello World!')
            ->setRetweetCount(1999)
            ->setFavoriteCount(42)
        ;
        
        $this->assertEquals(
            565939802152120320,
            $tweet->getId()
        );
        
        $this->assertEquals(
            $now,
            $tweet->getCreatedAt()
        );
        
        $this->assertEquals(
            'Hello World!',
            $tweet->getText()
        );
        
        $this->assertEquals(
            1999,
            $tweet->getRetweetCount()
        );
        
        $this->assertEquals(
            42,
            $tweet->getFavoriteCount()
        );
        
        # Bind the Tweet to a User
        $user = new User();
        $user
            ->setId(90556897)
            ->setName('Twitter France')
            ->setScreenName('TwitterFrance')
        ;
        
        $userClone = clone $user;
        
        $tweet
            ->setUser($user)
        ;
        
        $this->assertEquals(
            $userClone,
            $tweet->getUser()
        );
        
        # Count Tweet associated to the User
        $this->assertEquals(
            1,
            count($user->getTweets())
        );
    }
}
