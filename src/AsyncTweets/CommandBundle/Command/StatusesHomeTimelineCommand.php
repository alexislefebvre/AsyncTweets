<?php

namespace AsyncTweets\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Helper\ProgressBar;

use Abraham\TwitterOAuth\TwitterOAuth;

use AsyncTweets\TweetBundle\Entity\User;
use AsyncTweets\TweetBundle\Entity\Tweet;
use AsyncTweets\TweetBundle\Entity\Media;

class StatusesHomeTimelineCommand extends ContainerAwareCommand
{
    protected $em;
    
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output); //initialize parent class method
        
        $this->container = $this->getContainer();
        
        // This loads Doctrine, you can load your own services as well
        $this->em = $this->getContainer()->get('doctrine')
            ->getManager();
    }
    
    protected function configure()
    {
        parent::configure();
        
        $this
            ->setName('statuses:hometimeline')
            ->setDescription('Fetch home timeline')
            # http://symfony.com/doc/2.3/cookbook/console/console_command.html#automatically-registering-commands
            ->addOption('table', null, InputOption::VALUE_NONE, 'Display a table with tweets')
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
        
        /** @see https://dev.twitter.com/rest/reference/get/statuses/home_timeline */
        $parameters = array(
            'count' => 200,
            'exclude_replies' => true
        );
        
        # Get the last tweet
        $lastTweet = $this->em
            ->getRepository('AsyncTweetsTweetBundle:Tweet')
            /** @see http://doctrine-orm.readthedocs.org/en/latest/reference/working-with-objects.html#by-simple-conditions */
            ->findOneBy(
                # Conditions
                array(),
                # Orderings
                array('id' => 'DESC'),
                # Limit
                1,
                # Offset
                0
            )
        ;
        
        # And use it in the request if it exists
        if ($lastTweet)
        {
            $parameters['since_id'] = $lastTweet->getId();
            
            $comment = 'since_id parameter = '.$parameters['since_id'];
        }
        else
        {
            $comment = 'no since_id parameter';
        }
        
        $output->writeln('<comment>'.$comment.'</comment>');
        
        $content = $connection->get('statuses/home_timeline', $parameters);
        
        if (! is_array($content))
        {
            $formatter = $this->getHelper('formatter');
            
            $errorMessages = array('Error!', 'Something went wrong, $content is not an array.');
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            $output->writeln(print_r($content, true));
            return 1;
        }
        
        $numberOfTweets = count($content);
        
        $output->writeln('<comment>Number of tweets: '.$numberOfTweets.'</comment>');
        
        if ($numberOfTweets == 0)
        {
            $output->writeln('<comment>No new tweet.</comment>');
            return 0;
        }
        
        # Entity Manager
        $em = $this->em;
        
        # Display
        if ($input->getOption('table'))
        {
            $table = $this->getHelper('table');
            $table
                ->setHeaders(array('Datetime', 'Text excerpt', 'Name'))
            ;
            
            $rows = array();
        }
        
        # Iterate through the $content with the oldest tweet first
        #  in order to add the oldest tweet first
        //~ array_reverse($content);
        
        $progress = new ProgressBar($output, $numberOfTweets);
        $progress->setBarCharacter('<comment>=</comment>');
        $progress->start();
        
        foreach ($content as $tweetTmp)
        {
            $userTmp = $tweetTmp->user;
            
            # User
            $user = $this->em
                ->getRepository('AsyncTweetsTweetBundle:User')
                ->findOneById($userTmp->id)
            ;
            
            if (! $user)
            {
                $user = new User();
                
                # Only set the id when adding the user
                $user
                    ->setId($userTmp->id)
                ;
            }
            
            # Update these fields
            $user
                ->setName($userTmp->name)
                ->setScreenName($userTmp->screen_name)
                ->setProfileImageUrl($userTmp->profile_image_url)
            ;
            
            $em->persist($user);
            
            # Tweet
            $tweet = $this->em
                ->getRepository('AsyncTweetsTweetBundle:Tweet')
                ->findOneById($tweetTmp->id)
            ;
            
            if (! $tweet)
            {
                $tweet = new Tweet();
                $tweet
                    ->setId($tweetTmp->id)
                    ->setCreatedAt(new \Datetime($tweetTmp->created_at))
                    ->setText($tweetTmp->text)
                    ->setRetweetCount($tweetTmp->retweet_count)
                    ->setFavoriteCount($tweetTmp->favorite_count)
                    ->setUser($user)
                ;
                
                if (
                    (isset($tweetTmp->entities))
                    &&
                    (isset($tweetTmp->entities->media))
                )
                {
                    foreach ($tweetTmp->entities->media as $mediaTmp)
                    {
                        if ($mediaTmp->type !== 'photo')
                        {
                            continue;
                        }
                        
                        $media = new Media();
                        $media
                            ->setId($mediaTmp->id)
                            ->setMediaUrlHttps($mediaTmp->media_url)
                            ->setUrl($mediaTmp->url)
                            ->setDisplayUrl($mediaTmp->display_url)
                            ->setExpandedUrl($mediaTmp->expanded_url)
                        ;
                        
                        $tweet->addMedia($media);
                        
                        $em->persist($media);
                    }
                }
            }
            
            $em->persist($tweet);
            $em->flush();
            
            if ($input->getOption('table'))
            {
                $rows[] = array(
                    $tweetTmp->created_at,
                    mb_substr($tweetTmp->text, 0, 20),
                    $userTmp->name
                );
            }
            
            $progress->advance();
        }
        
        $progress->finish();
        $output->writeln('');
        
        if ($input->getOption('table'))
        {
            $table
                ->setRows($rows)
            ;
            
            $table->render($output);
        }
    }
}
