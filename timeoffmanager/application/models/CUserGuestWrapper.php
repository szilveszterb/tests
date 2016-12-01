<?php

/**
 * Description of CUserGuestWrapper
 * @package Wrapper
 * @subpackage Extended Class
 * @author Judit Alföldi
 * 
 * The CUserGuestWrapper is extended from the CUserWrapper class
 * and it is a filtered class of CUserGuest with public
 * properties to collect the appropriate data which will be
 * used by calendar plugin.
 */
final class CUserGuestWrapper extends CUserWrapper {
    public $operatorList;    

    /**
     * The constructor of the CUserGuestWrapper class which sets the
     * Google based data to the currently signed in user and fills up
     * the operator list.
     * @param string $fullName User's full name from Google login.
     * @param url $imageUrl The profile image url from Google login.
     * @param string $email User's e-mail from Google login.
     * @param type $operatorList
     */
    public function __construct($fullName, $imageUrl, $email, $operatorList)
    {
        parent::__construct($fullName, $imageUrl, $email);
        for($i=0; $i< count($operatorList); $i++)
        {
            $this->operatorList[] = new CUserOperatorWrapper(
                    $operatorList[$i]->fullName, 
                    $operatorList[$i]->imageUrl, 
                    $operatorList[$i]->email, 
                    $operatorList[$i]->background, 
                    $operatorList[$i]->foreground,             
                    $operatorList[$i]->holidayList, 
                    $operatorList[$i]->holidayNum);
        }
    }    
}
