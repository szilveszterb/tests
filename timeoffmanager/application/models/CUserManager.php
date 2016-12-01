<?php

/**
 * Description of CUserManager
 * @package User
 * @subpackage Extended Class
 * @author Judit Alföldi
 * 
 * CUserManager class extends from the CUser class.
 * The user with Manager level can modify each status
 * of holiday item which belongs to an Operator who 
 * selected the current user as manager.
 */
final class CUserManager extends CUser {
    private $right;
    private $operatorList;
    
    /**
     * The constructor of CUserManager class which set the
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
        $this->right = ERight::Manager;
        $connection = CDatabase::Connect();
        $query = "SELECT user_id,"
                . "user_full_name, "
                . "user_url, "
                . "user_email "
                . "from users where users.user_manager_id = ".$id." "
                . "and users.user_right = ".ERight::Operator.";";   
        
        $operatorsData = CDatabase::Query($connection, $query);
        
        $this->operatorList = array();
        while($operators = pg_fetch_array($operatorsData))
        {
            $this->operatorList[] = new CUserOperator(
                    $operators["user_id"], 
                    $operators["user_full_name"], 
                    $operators["user_url"], 
                    $operators["user_email"]);
        } 
    } 
    
    /**
     * The setter function of CUserManager class.
     * @param string $property
     * @param string $value
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
            static::$currentUser = new CUserManager($id, $fullName, $imageUrl, $email);
        } 
        
        CUser::serializeCurrentUser();
    }
    
    /**
     * The ModifyHoliday function sets the modified holiday
     * properties and changed the action property to Mod.
     * @access public
     * @param string $key The wanted key.
     * @param string $value The wanted keys value.
     * @param string $from The start date of holiday.
     * @param string $to The end date of holiday
     * @param string $desc The description of holiday.
     * @param string $status The status of holiday.
     */    
    public function ModifyHoliday($key, $value, $from, $to, $desc, $status)
    {
        for($i=0; $i < count($this->operatorList); $i++)
        {
            for($j=0; $j < count($this->operatorList[$i]->holidayList); $j++)
            {
                if($this->operatorList[$i]->holidayList[$j]->$key == $value)
                {
                    $this->operatorList[$i]->holidayList[$j]->action = EHolidayActions::Mod;
                    $this->operatorList[$i]->holidayList[$j]->from = $from;
                    $this->operatorList[$i]->holidayList[$j]->to = $to;
                    $this->operatorList[$i]->holidayList[$j]->desc = $desc;
                    $this->operatorList[$i]->holidayList[$j]->status = $status;
                }
            }
        }
        $this->HandleHolidays();        
    }
    
    /**
     * The HandleHolidays function handles the holidays based
     * on the action property.
     * @access private
     */    
    private function HandleHolidays()
    {
        for($i=0; $i < count($this->operatorList); $i++)
        {
            for($j=0; $j < count($this->operatorList[$i]->holidayList); $j++)
            {
                if($this->operatorList[$i]->holidayList[$j]->action == EHolidayActions::Mod)
                {
                    $this->operatorList[$i]->holidayList[$j]->UpdateHolidayInDb();
                }                      
            }   
        }
        CUser::serializeCurrentUser();
    }      
    
    /**
     * The getAllManagers function return all users who
     * has Manager level to fill up the manager drop down
     * box in 'Additional information' page.
     * @return \CIdName
     */
    public static function getAllManagers()
    {
        $connection = CDatabase::Connect();
        $query = "SELECT user_id, user_full_name from users where user_right = ".ERight::Manager.";";     
        $resultData = CDatabase::Query($connection, $query);
        
        $resultArray = array();
        while($result = pg_fetch_array($resultData))
        {
            $resultArray[] = new CIdName($result["user_id"], $result["user_full_name"]);
        }
        return $resultArray;            
    }    
}
