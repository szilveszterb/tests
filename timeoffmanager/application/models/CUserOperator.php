<?php

/**
 * Description of CUserGuest
 * @package User
 * @subpackage User helper class
 * @author Judit Alföldi
 * 
 * The EChildrenNumber class defines constants
 * about children number.
 */
class EChildrenNumber
{
    const None          = 0;
	const One           = 1;
	const Two           = 2;
	const ThreeOrMore 	= 3;
}

/**
 * Description of CUserOperator
 * @package User
 * @subpackage Extended Class
 * @author Judit Alföldi
 * 
 * CUserOperator class extends from the CUser class.
 * The user with Operator level can create, modify, delete,
 * drag and drop holiday items. Each Operator is allowed to
 * see its own holidays.
 * 
 */
final class CUserOperator extends CUser{
    private $right;
    private $birthYear;
    private $childrenNumber;
    private $holidayList;    
    private $managerId;
    private $regDate;
    private $background;
    private $foreground;    
    private $holdayNum;
    private $holdayNumPart;
    
    /**
     * The constructor of CUserOperator class which set the
     * main Google properties and fills up the additinal data
     * and the list of holoidays.
     * The additional data are the following:
     * @param string $right The right level of User.
     * @param string $birthYear The birth year of User.
     * @param string $childrenNumber The children number of User.
     * @param string $managerId The chosen manager identifier of User.
     * @param string $regDate The registration date of User.
     * @param string $background The chosen background color of User.
     * @param string $foreground The chosen foreground color of User.
     * @param string $holdayNum The available holidays nuumber of User.
     * @param string $holdayNumPart Number of proportional holidays.
     * @param array $holidayList List of holidays.
     * 
     * @param integer $id The database identifier of the user.
     * @param string $fullName User's full name from Google login.
     * @param url $imageUrl The profile image url from Google login.
     * @param string $email User's e-mail from Google login.
     */
    public function __construct($id, $fullName, $imageUrl, $email)
    {
        parent::__construct($id, $fullName, $imageUrl, $email);
        if($id !== null)
        {
            $data = $this->getAdditionalData();
            $this->setAdditionalData($data["user_birth_year"], $data["user_manager_id"], $data["user_children_number"], $data["user_background_color"], $data["user_foreground_color"], $data["user_reg_date"]);
            CUser::serializeCurrentUser();
        }        
    }
    
    /**
     * The setter function of CUserOperator class.
     * @param string $property
     * @param string $value
     */
	function __set($property, $value)
	{
        $this->$property = $value;
        CUser::serializeCurrentUser();
	}
 
    /**
     * The getter function of CUserOperator class.
     * @param type $property
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
            static::$currentUser = new CUserOperator($id, $fullName, $imageUrl, $email);
        }
        
        CUser::serializeCurrentUser();
    } 
    
    /**
     * The setAdditionalData sets the additional parameter for the currently
     * signed in user.
     * @access public
     * @param string $birthYear The birth year of User.
     * @param string $managerId The chosen manager identifier of User.
     * @param string $childrenNum The children number of User.
     * @param string $background The chosen background color of User.
     * @param string $foreground The chosen foreground color of User.
     * @param string $regDate The registration date of User.
     */
    public function setAdditionalData($birthYear, $managerId, $childrenNum, $background, $foreground, $regDate)
    {
        $this->__set('right', ERight::Operator);
        $this->__set('birthYear', $birthYear);        
        $this->__set('managerId', $managerId);  
        $this->__set('regDate', $regDate); 
        $this->__set('childrenNumber', $childrenNum);
        $this->__set('foreground', $foreground);
        $this->__set('background', $background);                     
        
        if($this->id !== null)
        {
            $this->setHolidayList();
            $this->__set('holdayNumPart', $this->getPartDayOffsNumber());
            $this->__set('holidayNum', $this->getDayOffsNumber());
        }
    }    
    
    /**
     * The getAdditionalData gets the additional parameter for the currently
     * signed in user.
     * @return type
     */
    public function getAdditionalData()
    {
        $connection = CDatabase::Connect();
        $query = "SELECT user_birth_year, "
                . "user_manager_id, "
                . "user_children_number, "
                . "user_reg_date, "
                . "user_background_color, "
                . "user_foreground_color "
                . "from users where users.user_id = ".$this->id." limit 1;";         

        $additionalData = CDatabase::Query($connection, $query);
        return pg_fetch_array($additionalData);        
    }     
    
    /**
     * The setHolidayList fills up the holiday list with data for
     * the currently signed in user. If the holiday date is expired
     * its status will change to expired and if it is pending its
     * status will change as well.
     */
    public function setHolidayList()
    {
        $connection = CDatabase::Connect();
        $query = "SELECT to_id, "
                . "user_id, "
                . "to_date_from, "
                . "to_date_to, "
                . "to_status, "
                . "to_desc "
                . "from time_offs where time_offs.user_id = ".$this->id.";";         
        
        $holidaysData = CDatabase::Query($connection, $query);
        while($holidays = pg_fetch_array($holidaysData))
        {
            $this->holidayList[] = new CHolidays($holidays["to_id"], $holidays["to_date_from"], $holidays["to_date_to"], 
                    $holidays["to_desc"], $holidays["to_status"], EHolidayActions::Ready);              
            
            if($holidays["to_status"] != EHolidayStatus::Previous)
            {
                $mydate = getdate(date("U"));  
                $currdate = strtotime($mydate["year"] . "-" . $mydate["mon"] . "-" . $mydate["mday"]);
                $fromDate = strtotime($holidays["to_date_from"]);
                $toDate = strtotime($holidays["to_date_to"]);                
                if(($currdate >= $fromDate) && ($currdate <= $toDate))     
                {
                    if($holidays["to_status"] == EHolidayStatus::Approved)
                    {
                        $this->ModifyHoliday("id", $holidays["to_id"], $holidays["to_date_from"], $holidays["to_date_to"],
                                $holidays["to_desc"], EHolidayStatus::Pending);
                    }
                }
                else if($currdate > $toDate)
                {
                    $this->ModifyHoliday("id", $holidays["to_id"], $holidays["to_date_from"], $holidays["to_date_to"],
                            $holidays["to_desc"], EHolidayStatus::Expired);
                }
            }            
        }
    }

    /**
     * The getDayOffsNumber function counts the available
     * holiday number for the currently signed in user.
     * It makes allowance for proportional holidays and if
     * its number is lower than the available holidays, it
     * will be the number of day offs.
     * @access public
     * @return float
     */
    public function getDayOffsNumber()
    {        
        $allHolidays = $this->getDayOffsByAge($this->getAge()) + $this->getPlusDayOffsByChildren($this->childrenNumber);
        $usedHolidays = CHolidays::GetUsedHolidayNumber($this->holidayList);
        $remainHolidays = $allHolidays - $usedHolidays;        
        
        $time = strtotime($this->regDate);
        $year = date("Y",$time);               
        if($year == date('Y'))
        {
            $partHolidayNumber = $this->getPartDayOffsNumber();              
            if($remainHolidays > $partHolidayNumber)
            {
                $holidayNumber = $partHolidayNumber;                
            }
            else
            {
                $holidayNumber = $remainHolidays; 
            }
        }
        else
        {
            $holidayNumber = $remainHolidays;
        }
        
        return $holidayNumber;
    } 
    
    /**
     * The getPartDayOffsNumber function counts the proportional
     * number of holidays.
     * @access public
     * @return float
     */
    public function getPartDayOffsNumber()
    {
        $allHolidays = $this->getDayOffsByAge($this->getAge()) + $this->getPlusDayOffsByChildren($this->childrenNumber);
        $daysleft = round((((strtotime('31 Dec '.date('Y'))-strtotime($this->regDate))/24)/60)/60);        
        return (round($allHolidays / 365 * $daysleft));
    }
    
    /**
     * The getAge function defines the user's age.
     * @access private
     * @return int
     */
    private function getAge()
    {
        return (int)date('Y') - (int)$this->birthYear;
    }
    
    /**
     * The getDayOffsByAge function returns the holiday
     * number which is determined by age.
     * @access private
     * @param type $age
     * @return int
     */
    private function getDayOffsByAge($age)
    {
        switch ($age)
        {
            case $age < 25:
                return 20;
            case $age >= 25 && $age < 28:
                return 21;
            case $age >= 28 && $age < 31:
                return 22;
            case $age >= 31 && $age < 33:
                return 23;
            case $age >= 33 && $age < 35:
                return 24;
            case $age >= 35 && $age < 37:
                return 25;
            case $age >= 37 && $age < 39:
                return 26;
            case $age >= 39 && $age < 41:
                return 27;   
            case $age >= 41 && $age < 43:
                return 28;  
            case $age >= 43 && $age < 45:
                return 29;                 
            default:
                return 30;                
        }
    }
    
    /**
     * The getPlusDayOffsByChildren function returns the plus
     * holiday number which is determined by children number.
     * @access private
     * @param integer $childrenNumber
     * @return int
     */
    private function getPlusDayOffsByChildren($childrenNumber)
    {
        switch ($childrenNumber)
        {
            case $childrenNumber == EChildrenNumber::One:
                return 2;
            case $childrenNumber == EChildrenNumber::Two:
                return 4;
            case $childrenNumber == EChildrenNumber::ThreeOrMore:
                return 7;                
            default:
                return 0;                
        }
    }
    
    /**
     * The AddUserToDb function adds a user to database.
     * @access public
     * @return integer The database user identifier of the user.
     */
    public function AddUserToDb()
    {
        $connection = CDatabase::Connect();
        $query = "INSERT INTO users("
                . "user_right, "
                . "user_full_name, "
                . "user_url, "
                . "user_email, "
                . "user_birth_year, "
                . "user_manager_id, "
                . "user_children_number, "
                . "user_background_color, "
                . "user_foreground_color "
                . ") values ("
                . "".ERight::Operator.", "
                . "'".$this->fullName."', "
                . "'".$this->imageUrl."', "
                . "'".$this->email."', "
                . "".$this->birthYear.", "
                . "".$this->managerId.", "
                . "".$this->childrenNumber.", "
                . "'".$this->background."', "
                . "'".$this->foreground."') "
                . "returning user_id;";   
        
        $userIdData = CDatabase::Query($connection, $query);
        $userId = pg_fetch_array($userIdData);
        return $userId["user_id"];
    }
    
    /**
     * The AddUserToDb function adds a holiday to a list and calls
     * the function which handles the holidays.
     * @access public
     * @return integer The database user identifier of the user.
     */    
    public function AddHoliday($from, $to, $desc, $status)
    {
        $this->holidayList[] = new CHolidays(null, $from, $to, $desc, $status, EHolidayActions::Add);
        $this->HandleHolidays();
    }
    
    /**
     * The DragAndDropHoliday function calculates an end date
     * and sets the modified holiday properties and changed the
     * action property to Mod.
     * @access public
     * @param string $key The wanted key.
     * @param string $value The wanted keys value.
     * @param string $from The start date of holiday.
     * @param string $desc The description of holiday.
     */
    public function DragAndDropHoliday($key, $value, $from, $desc)
    {
        for($i=0; $i < count($this->holidayList); $i++)
        {
            if($this->holidayList[$i]->$key == $value)
            {
                $this->holidayList[$i]->action = EHolidayActions::Mod;
                $diffDays = floor((strtotime($this->holidayList[$i]->to) - strtotime($this->holidayList[$i]->from))/(60*60*24));
                $to = date('Y-m-d', strtotime($from . " + ".$diffDays." days"));
                $this->holidayList[$i]->from = $from;
                $this->holidayList[$i]->to = $to;
                $this->holidayList[$i]->desc = $desc;
            }
        }
        $this->HandleHolidays();        
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
        for($i=0; $i < count($this->holidayList); $i++)
        {
            if($this->holidayList[$i]->$key == $value)
            {
                $this->holidayList[$i]->action = EHolidayActions::Mod;
                $this->holidayList[$i]->from = $from;
                $this->holidayList[$i]->to = $to;
                $this->holidayList[$i]->desc = $desc;
                $this->holidayList[$i]->status = $status;
            }
        }
        $this->HandleHolidays();        
    }    
        
    /**
     * The DeleteHoliday function changed the action property to Del.
     * @access public
     * @param string $key The wanted key.
     * @param string $value The wanted keys value.
     */
    public function DeleteHoliday($key, $value)
    {
        for($i=0; $i < count($this->holidayList); $i++)
        {
            if($this->holidayList[$i]->$key == $value)
            {
                $this->holidayList[$i]->action = EHolidayActions::Del;
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
        for($i=0; $i < count($this->holidayList); $i++)
        {
            if($this->holidayList[$i]->action == EHolidayActions::Add)
            {
                $this->holidayList[$i]->AddHolidayToDb($this->id);
            }
            elseif($this->holidayList[$i]->action == EHolidayActions::Mod)
            {
                $this->holidayList[$i]->UpdateHolidayInDb();
            }              
            elseif($this->holidayList[$i]->action == EHolidayActions::Del)
            {
                $this->holidayList[$i]->DeleteHolidayFromDb();
                unset($this->holidayList[$i]);
                $this->holidayList = array_values($this->holidayList);
            }         
        }  
        $this->holidayNum = $this->getDayOffsNumber();
        CUser::serializeCurrentUser();
    }  
}
