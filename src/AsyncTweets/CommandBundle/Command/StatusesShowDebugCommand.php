<?php

namespace AsyncTweets\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Helper\ProgressBar;

use Abraham\TwitterOAuth\TwitterOAuth;

use AsyncTweets\TweetBundle\Entity\Tweet;
use AsyncTweets\TweetBundle\Entity\User;

class StatusesShowDebugCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        parent::configure();
        
        $this
            ->setName('statuses:show:debug')
            ->setDescription('Fetch one tweet (for debugging)')
            ->addArgument('id', InputArgument::REQUIRED, 'Tweet id')
            ->addArgument('format', InputArgument::REQUIRED, 'Output format')
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
        
        /** @see https://dev.twitter.com/rest/reference/get/statuses/show/%3Aid */
        $parameters = array(
            'exclude_replies' => true
        );
        
        $content = $connection->get('statuses/show/'.$input->getArgument('id'),
            $parameters);
        
        if (empty($content))
        {
            $output->writeln('<comment>No tweet.</comment>');
            return 0;
        }
        
        if ($input->getArgument('format') === 'printr')
        {
            $out = print_r($content, true);
        }
        elseif ($input->getArgument('format') === 'printruser')
        {
            $out = print_r($content->user, true);
        }
        elseif ($input->getArgument('format') === 'json')
        {
            $out = json_encode($content, true);
        }
        
        $output->writeln($out);
        
        return 0;
    }
}
