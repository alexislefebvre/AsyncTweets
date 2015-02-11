<?php

namespace AsyncTweets\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Abraham\TwitterOAuth\TwitterOAuth;

class StatusesHomeTimelineCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('statuses:hometimeline')
            ->setDescription('Fetch home timeline')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        
        $connection = new TwitterOAuth(
            $container->getParameter('twitter_consumer_key'),
            $container->getParameter('twitter_consumer_secret'),
            $container->getParameter('twitter_token'),
            $container->getParameter('twitter_token_secret')
        );
        $content = $connection->get("statuses/home_timeline");
        
        # Display
        $table = $this->getHelper('table');
        $table
            ->setHeaders(array('Datetime', 'Text excerpt', 'Username'))
        ;
        
        $rows = array();
        
        foreach ($content as $tweet)
        {
            $rows[] = array(
                $tweet->created_at,
                mb_substr($tweet->text, 0, 20),
                $tweet->user->name
            );
        }
        
        $table
            ->setRows($rows)
        ;
        
        $table->render($output);
    }
}
