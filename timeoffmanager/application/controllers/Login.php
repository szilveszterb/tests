<?php

/**
 * Description of Login
 * @package Controller
 * @subpackage Login
 * @author Judit Alföldi
 * 
 * The Login class extends from the CBaseController class.
 * The application login page is avaliable throw the
 * [host]/timeoffmanager/Display/LoginDisplay url.
 * The Login class is responsible for to diplay the
 * login page, support the Google authentication and
 * route the users to the appropriate next page.
 * 
 */
class Login extends CBaseController
{
    /**
     * LoginDisplay function calls RequireModelAndView
     * which includes the connected model and view files.
     * @access public
     */
    public function LoginDisplay()
    {
		$this->RequireModelAndView();
	}
    
    /**
     * Route function redirect the users to the proper next page
     * which depends on the users' right.
     * @access public
     * @param string $name User's full name from Google login.
     * @param url $url The profile image url from Google login.
     * @param string $email User's e-mail from Google login.
     * @return string
     */
    public function Route()
    {               
        $name = isset($_POST["name"]) ?  $_POST["name"] : null;
        $url = isset($_POST["url"]) ?  $_POST["url"] : null;
        $email = isset($_POST["email"]) ?  $_POST["email"] : null;
        
        $isExistUserId = $this->isExistUser($_POST["email"]);  
        if($isExistUserId === false)
        {           
            CUserOperator::createCurrentUser(null, $name, $url, $email);
            echo 'http://localhost/timeoffmanager/UserData/UserDataDisplay';            
        }
        else
        {
            $userRight = $this->getUserRight($isExistUserId);            
            switch($userRight)
            {                
                case $userRight == ERight::Guest: 
                    CUserGuest::createCurrentUser($isExistUserId, $name, $url, $email);                               
                    echo '../Calendar/CalendarDisplay';
                    exit();
                case $userRight == ERight::Operator:
                    CUserOperator::createCurrentUser($isExistUserId, $name, $url, $email);
                    echo '../Calendar/CalendarDisplay';
                    exit();
                case $userRight == ERight::Manager: 
                    CUserManager::createCurrentUser($isExistUserId, $name, $url, $email);        
                    echo '../Calendar/CalendarDisplay';
                    exit();
                default: 
                    return "Unknown right level!";
            }                         
        }        
    }
    
    /**
     * isExistUser checks and returns the existance of the user.
     * @access public
     * @param type $email User's e-mail from Google login.
     * @return type
     */
    private function isExistUser($email)
    {
        return CUser::isExistUser($email);
    }  
    
    /**
     * getUserRight returns the right of the user.
     * @access public
     * @param type $id
     * @return type
     */
    private function getUserRight($id)
    {
        return CUser::getUserRight($id);
    }      
}