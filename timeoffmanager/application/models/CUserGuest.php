<?php

/**
 * Description of CUserGuest
 * @package User
 * @subpackage Extended Class
 * @author Judit Alföldi
 * 
 * The CUserGuest class extends from the CUser class.
 * The user with Guest level can see all employees' 
 * holidays but the modification of the holidays is
 * forbidden.
 */
final class CUserGuest extends CUser{
    private $right;
    private $allOperatorList;
    
    /**
     * The constructor of CUserGuest class which set the
     * main Google properties and fills up the list of
     * operators.
     * 
     * @access protected
     * @param integer $id The database identifier of the user.
     * @param string $fullName User's full name from Google login.
     * @param url $imageUrl The profile image url from Google login.
     * @param string $email User's e-mail from Google login.
     */
    protected function __construct($id, $fullName, $imageUrl, $email)
    {
        parent::__construct($id, $fullName, $imageUrl, $email);
        $this->right = ERight::Guest;
        $connection = CDatabase::Connect();
        $query = "SELECT user_id, "
                . "user_full_name, "
                . "user_url, "
                . "user_email "
                . "from users where users.user_right = ".ERight::Operator.";";   
        
        $operatorsData = CDatabase::Query($connection, $query);               
        $this->allOperatorList = array();
        while($operators = pg_fetch_array($operatorsData))
        {
            $this->allOperatorList[] = new CUserOperator(
                    $operators["user_id"], 
                    $operators["user_full_name"], 
                    $operators["user_url"], 
                    $operators["user_email"]);
        }        
    }    
    
    /**
     * getter function of CUserGuest class.
     * @param string $property
     * @return type
     */
	function __get($property)
	{
		if(isset($this->$property))
		{
			return $this->$property;
		}
	}       
    
    /**
     * The createCurrentUser function creates the currently
     * signed in user object.
     * @static
     * @access public
     * @param integer $id The database identifier of the user.
     * @param string $fullName User's full name from Google login.
     * @param url $imageUrl The profile image url from Google login.
     * @param string $email User's e-mail from Google login.
     */
    public static function createCurrentUser($id, $fullName, $imageUrl, $email)
    {
        if(static::$currentUser === null)
        {
            static::$currentUser = new CUserGuest($id, $fullName, $imageUrl, $email);
        }          
        CUser::serializeCurrentUser();
    }    
}
