<?php

/**
 * Description of CUserWrapper
 * @package Wrapper
 * @subpackage Base Class
 * @author Judit Alföldi
 * 
 * The CUserWrapper is a filtered class of CUser with public
 * properties to collect the appropriate data which will be
 * used by calendar plugin.
 */
class CUserWrapper
{
    public $fullName;
    public $imageUrl;
    public $email;
    
    /**
     * The constructor of the CUserWrapper class which sets the
     * Google based data to the currently signed in user.
     * @param type $fullName
     * @param string $fullName User's full name from Google login.
     * @param url $imageUrl The profile image url from Google login.
     * @param string $email User's e-mail from Google login.
     */
    public function __construct($fullName, $imageUrl, $email)
    {
        $this->fullName = $fullName;
        $this->imageUrl = $imageUrl;
        $this->email = $email;               
    }
}
