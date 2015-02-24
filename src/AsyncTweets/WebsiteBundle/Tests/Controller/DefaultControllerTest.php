<?php

namespace AsyncTweets\WebsiteBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private $client = null;
    private $router = null;
        
    public function setUp()
    {
        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
    }
    
    public function testIndex()
    {
        $this->loadFixtures(array(
            'AsyncTweets\TweetBundle\DataFixtures\ORM\LoadUserData',
            'AsyncTweets\TweetBundle\DataFixtures\ORM\LoadTweetData',
            'AsyncTweets\TweetBundle\DataFixtures\ORM\LoadMediaData',
        ));
        
        $path = $this->router->generate('asynctweets_website_homepage');
        
        $crawler = $this->client->request('GET', $path);
        
        # <body>
        $this->assertEquals(1,
            $crawler->filter('html > body')->count());
        
        # <title>
        $this->assertEquals(1,
            $crawler->filter('title:contains("Home timeline - page 1 - AsyncTweets")')->count());
        
        # 2 navigation blocks
        $this->assertEquals(2,
            $crawler->filter('main.container > div.navigation')->count());
        
        # Tweet
        $this->assertEquals(1,
            $crawler->filter(
                'main.container > div.tweets > div.media > blockquote.media-body'
            )->count());
        
        # Link
        $this->assertEquals(1,
            $crawler->filter(
                'main.container > div.tweets > div.media > blockquote.media-body > '.
                'p > a'
            )->count());
        
        # TODO: Hashtags
        
        # Image
        $this->assertEquals(1,
            $crawler->filter('main.container > div.tweets > div.media')->count());
        
        $this->assertEquals(2,
            $crawler->filter(
                'blockquote.media-body > p')->count());
        
        # User
        $this->assertEquals(1,
            $crawler->filter(
                'main.container > div.tweets > div.media > blockquote.media-body > small > a:contains("Asynchronous tweets")'
            )->count());
    }
}
