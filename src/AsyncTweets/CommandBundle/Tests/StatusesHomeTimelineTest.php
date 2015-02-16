<?php

namespace AsyncTweets\CommandBundle\Tests\Entity;

use AsyncTweets\TweetBundle\Entity\Tweet;

class StatusesHomeTimelineTest extends \PHPUnit_Framework_TestCase
{
    public function testReadTweet()
    {
        $tweetJSON = file_get_contents(
            dirname(__FILE__).'/data/tweet_with_hashtag_link_and_image.json');
        
        $tweet = json_decode($tweetJSON);
        
        $this->assertEquals(
            'Fri Feb 13 16:02:45 +0000 2015', 
            $tweet->created_at
        );
        
        $this->assertEquals(
            566266232190418946,
            $tweet->id
        );
        
        $this->assertEquals(
            '566266232190418946',
            $tweet->id_str
        );
        
        $this->assertEquals(
            'Twitter France',
            $tweet->user->name
        );
        
        $this->assertEquals(
            'TwitterFrance',
            $tweet->user->screen_name
        );
        
        $tweetObject = new Tweet();
        $tweetObject
            ->setId($tweet->id)
            ->setCreatedAt(new \Datetime($tweet->created_at))
            ->setRetweetCount($tweet->retweet_count)
            ->setFavoriteCount($tweet->favorite_count)
        ;
        
        $this->assertEquals(
            566266232190418946,
            $tweetObject->getId()
        );
        
        $this->assertEquals(
            new \Datetime('Fri Feb 13 16:02:45 +0000 2015'),
            $tweetObject->getCreatedAt()
        );
        
        $this->assertEquals(
            68,
            $tweetObject->getRetweetCount()
        );
        
        $this->assertEquals(
            34,
            $tweetObject->getFavoriteCount()
        );
        
    }
}
