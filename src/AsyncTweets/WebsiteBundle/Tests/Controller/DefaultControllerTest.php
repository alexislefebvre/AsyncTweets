<?php

namespace AsyncTweets\WebsiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
        $path = $this->router->generate('asynctweets_website_homepage');
        
        $crawler = $this->client->request('GET', $path);

        $this->assertEquals(1,
            $crawler->filter('html > body')->count());
            
        //~ $this->assertEquals(1,
            //~ $crawler->filter('title:contains("Home timeline - AsyncTweets")')->count());
        
        //~ $this->assertEquals(1,
            //~ $crawler->filter('div.tweets')->count());
        
        //~ $this->assertGreaterThanOrEqual(1,
            //~ $crawler->filter('main.container > div.tweets > div.media')->count());            
    }
}
