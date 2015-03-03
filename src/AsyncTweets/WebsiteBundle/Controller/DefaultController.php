<?php

namespace AsyncTweets\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    public function indexAction(Request $request, $lastTweetId = null)
    {
        $tweets = $this->getDoctrine()
            ->getRepository('AsyncTweetsTweetBundle:Tweet')
            ->getWithUsersAndMedias($lastTweetId);
        
        $lastTweetId = null;
        
        if (count($tweets) > 0)
        {
            $lastTweetId = $tweets[count($tweets) - 1]->getId();
        }
        
        $numberOfTweets = $this->getDoctrine()
            ->getRepository('AsyncTweetsTweetBundle:Tweet')
            ->countPendingTweets($lastTweetId);
        
        $response = new Response();
        
        $response = $this->render(
            'AsyncTweetsWebsiteBundle:Default:index.html.twig',
            array(
                'tweets' => $tweets,
                'lastTweetId' => $lastTweetId,
                'numberOfTweets' => $numberOfTweets,
            )
        );
        
        if (
            (! is_null($lastTweetId))
            &&
            # Only update the cookie if the last Tweet Id is bigger than
            #  the one in the cookie
            ($lastTweetId > $request->cookies->get('lastTweetId'))
        )
        {
            $nextYear = new \Datetime('now');
            $nextYear->add(new \DateInterval('P1Y'));
            
            # Set last Tweet Id
            $cookie = new Cookie('lastTweetId', $lastTweetId, $nextYear);
            $response->headers->setCookie($cookie);
        }
        
        return $response;
    }
    
    public function resetCookieAction(Request $request)
    {
        /** @see http://www.craftitonline.com/2011/07/symfony2-how-to-set-a-cookie/ */
        $response = new RedirectResponse(
            $this->generateUrl('asynctweets_website_homepage')
        );    
        
        # Reset last Tweet Id
        $cookie = new Cookie('lastTweetId', null);
        $response->headers->setCookie($cookie);
        
        return $response;
    }
}

