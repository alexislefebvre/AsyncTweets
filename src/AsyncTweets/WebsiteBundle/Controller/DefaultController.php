<?php

namespace AsyncTweets\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $tweets = $this->getDoctrine()
            ->getRepository('AsyncTweetsTweetBundle:Tweet')
            ->findBy(
                array(),
                array('id' => 'DESC')
            );
        
        return $this->render(
            'AsyncTweetsWebsiteBundle:Default:index.html.twig',
            array(
                'tweets' => $tweets
            )
        );
    }
}

