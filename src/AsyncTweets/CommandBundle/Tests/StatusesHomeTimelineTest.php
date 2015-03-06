<?php

namespace AsyncTweets\CommandBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Liip\FunctionalTestBundle\Test\WebTestCase;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

use AsyncTweets\CommandBundle\Command\StatusesHomeTimelineCommand;

/**
 * @see http://symfony.com/doc/current/cookbook/console/console_command.html#testing-commands
 */
class StatusesHomeTimelineTest extends WebTestCase
{
    public function testStatusesHomeTimelineEmpty()
    {
        $this->loadFixtures(array());
        
        $kernel = $this->createKernel();
        $kernel->boot();
        
        $application = new Application($kernel);
        $application->add(new StatusesHomeTimelineCommand());

        $command = $application->find('statuses:hometimeline');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            '--test' => true
        ));

        $this->assertRegExp('/Number of tweets: 3/', $commandTester->getDisplay());
    }
    
    public function testStatusesHomeTimelineWithTweets()
    {
        $this->loadFixtures(array());
        
        /** @see http://symfony.com/doc/current/cookbook/console/console_command.html#testing-commands */
        $kernel = $this->createKernel();
        $kernel->boot();
        
        $application = new Application($kernel);
        $application->add(new StatusesHomeTimelineCommand());
        
        $command = $application->find('statuses:hometimeline');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            '--table' => true,
            '--test' => true
        ));
        
        $display = $commandTester->getDisplay();
        
        $this->assertRegExp('/Number of tweets: 3/', $display);
        
        # Test the first line of the table
        $this->assertRegExp(
            '/| Wed Feb 18 00:01:14 +0000 2015 | '.
                '#image #test http:\/\/ | '.
                'Asynchronous tweets |/',
            $display
        );
        $this->assertRegExp('/(.*)Wed Feb 18 00:01:14 \+0000 2015(.*)/', $display);
    }
}
