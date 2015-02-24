<?php

namespace AsyncTweets\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $tweets = $this->getDoctrine()
            ->getRepository('AsyncTweetsTweetBundle:Tweet')
            ->getWithUsersAndMedias();
        
        $page = $request->query->get('page', 1);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $tweets,
            $page/*page number*/,
            10/*limit per page*/
        );
        
        return $this->render(
            'AsyncTweetsWebsiteBundle:Default:index.html.twig',
            array(
                'page' => $page,
                'pagination' => $pagination
            )
        );
    }
}

