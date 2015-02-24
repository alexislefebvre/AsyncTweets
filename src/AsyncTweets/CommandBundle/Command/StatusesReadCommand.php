<?php

namespace AsyncTweets\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use AsyncTweets\TweetBundle\Entity\Tweet;

class StatusesReadCommand extends ContainerAwareCommand
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
            ->setName('statuses:read')
            ->setDescription('Read home timeline')
            ->addArgument('page', InputArgument::OPTIONAL, 'Page')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $page = $input->getArgument('page');
        
        if ($page < 1) {$page = 1;}
        
        $output->writeln('Current page: <comment>'.$page.'</comment>');
        
        # Get the tweets
        $tweets = $this->em
            ->getRepository('AsyncTweetsTweetBundle:Tweet')
            ->getWithUsers();
        
        $paginator = $this->container->get('knp_paginator');
        $pagination = $paginator->paginate(
            $tweets,
            $page/*page number*/,
            10/*limit per page*/
        );
        
        $table = $this->getHelper('table');
        $table
            ->setHeaders(array(
                # Add spaces to use all the 80 columns,
                #  even if name or texts are short
                sprintf('%-13s', 'Name'),
                sprintf('%-40s', 'Text'),
                sprintf('%-16s', 'Datetime'),
            ))
        ;
        
        foreach ($pagination as $tweet)
        {
            $table->addRows(array(
                array(
                    '<info>'.
                        # Add <info> in order to close <info> before
                        #  each new line
                        str_replace("\n", "</info>\n<info>",
                            wordwrap($tweet->getUser()->getName(), 13, "\n")
                        ).
                    '</info>',
                    
                    '<comment>'.
                    # Add <info> in order to close <info> before
                    #  each new line
                    str_replace("\n", "</comment>\n<comment>",
                        wordwrap($tweet->getText(), 40, "\n")
                    ).
                    '</comment>',
                    
                    $tweet->getCreatedAt()->format('Y-m-d H:i'),
                ),
                # empty row
                array('', '', ''))
            );
        }
        
        $table->render($output);
    }
}
