<?php

namespace AsyncTweets\CommandBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Liip\FunctionalTestBundle\Test\WebTestCase;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

use AsyncTweets\CommandBundle\Command\StatusesReadCommand;

/**
 * @see http://symfony.com/doc/current/cookbook/console/console_command.html#testing-commands
 */
class StatusesReadTest extends WebTestCase
{
    public function testStatusesReadEmpty()
    {
        $this->loadFixtures(array());
        
        $kernel = $this->createKernel();
        $kernel->boot();
        
        $application = new Application($kernel);
        $application->add(new StatusesReadCommand());

        $command = $application->find('statuses:read');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array());

        $this->assertRegExp('/Current page: 1/', $commandTester->getDisplay());
    }
    
    public function testStatusesReadWithTweets()
    {
        $this->loadFixtures(array(
            'AsyncTweets\TweetBundle\DataFixtures\ORM\LoadUserData',
            'AsyncTweets\TweetBundle\DataFixtures\ORM\LoadTweetData',
            'AsyncTweets\TweetBundle\DataFixtures\ORM\LoadMediaData',
        ));
        
        $kernel = $this->createKernel();
        $kernel->boot();
        
        $application = new Application($kernel);
        $application->add(new StatusesReadCommand());
        
        $command = $application->find('statuses:read');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array());
        
        $display = $commandTester->getDisplay();
        
        $this->assertRegExp('/Current page: 1/', $display);
        
        # Test the first line of the table
        $this->assertRegExp(
            '/| Asynchronous  | '.
                'Hello Twitter! #myfirstTweet             | '.
                '2015-02-10 21:19 |/',
            $display
        );
    }
}
