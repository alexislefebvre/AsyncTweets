<?php

namespace AsyncTweets\TweetBundle\Tests\Entity;

use Liip\FunctionalTestBundle\Test\WebTestCase;

use AsyncTweets\TweetBundle\Entity\Media;
use AsyncTweets\TweetBundle\Entity\Tweet;
use AsyncTweets\TweetBundle\Entity\User;

class TweetRepositoryTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }
    
    public function testTweetRepository()
    {
        $this->loadFixtures(array(
            'AsyncTweets\TweetBundle\DataFixtures\ORM\LoadUserData',
            'AsyncTweets\TweetBundle\DataFixtures\ORM\LoadTweetData',
            'AsyncTweets\TweetBundle\DataFixtures\ORM\LoadMediaData',
        ));
        
        $tweets = $this->em
            ->getRepository('AsyncTweetsTweetBundle:Tweet')
            ->getWithUsers(1)
        ;

        $this->assertCount(1, $tweets);
        
        $tweets = $this->em
            ->getRepository('AsyncTweetsTweetBundle:Tweet')
            ->getWithUsersAndMedias(null, false)
        ;

        $this->assertCount(1, $tweets);
        
        $tweets = $this->em
            ->getRepository('AsyncTweetsTweetBundle:Tweet')
            ->getWithUsersAndMedias(null, true)
        ;

        $this->assertCount(1, $tweets);
        
        $tweets = $this->em
            ->getRepository('AsyncTweetsTweetBundle:Tweet')
            ->countPendingTweets(565258739000049664)
        ;
        
        $this->assertEquals(1, $tweets);
    }
}
