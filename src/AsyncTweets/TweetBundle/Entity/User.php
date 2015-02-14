<?php

namespace AsyncTweets\TweetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 */
class User
{
    /**
     * @var integer
     */
    private $id;
    
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $screen_name;
    
    /**
     * @var string
     */
    private $profile_image_url;
    
    /**
     * @var ArrayCollection
     */
    private $tweets;
    
    public function __construct()
    {
        $this->tweets = new ArrayCollection();
    }
    
    /**
     * Set id
     *
     * @param bigint $id
     * @return User 
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    
    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set screen_name
     *
     * @param string $screenName
     * @return User
     */
    public function setScreenName($screenName)
    {
        $this->screen_name = $screenName;

        return $this;
    }
    
    /**
     * Get screen_name
     *
     * @return string 
     */
    public function getScreenName()
    {
        return $this->screen_name;
    }
    
    /**
     * Set profile_image_url
     *
     * @param string $profileImageUrl
     * @return User
     */
    public function setProfileImageUrl($profileImageUrl)
    {
        $this->profile_image_url = $profileImageUrl;

        return $this;
    }
    
    /**
     * Get profile_image_url
     *
     * @return string 
     */
    public function getProfileImageUrl()
    {
        return $this->profile_image_url;
    }
        
    /**
     * Set tweets
     *
     * @param ArrayCollection $tweets
     * @return User
     */
    public function setTweets($tweets)
    {
        $this->tweets = $tweets;
        
        return $this;
    }
    
    /**
     * Get tweets
     *
     * @return ArrayCollection 
     */
    public function getTweets()
    {
        return $this->tweets;
    }
    
    /**
     * Add a tweet
     *
     * @return User
     */
    public function addTweet(Tweet $tweet)
    {
        $this->tweets[] = $tweet;
        return $this;
    }
}
