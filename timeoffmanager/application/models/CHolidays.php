<?php

/**
 * Description of EHolidayStatus
 * @package Holiday
 * @subpackage Holiday helper class
 * @author Judit Alföldi
 * 
 * The EHolidayStatus class defines constants
 * about holiday statuses.
 */
class EHolidayStatus
{
    const Previous  = 0;
	const Required  = 1;
    const Approved  = 2;
    const Rejected  = 3;
    const Pending   = 4;
    const Expired   = 5;
}

/**
 * Description of EHolidayActions
 * @package Holiday
 * @subpackage Holiday helper class
 * @author Judit Alföldi
 * 
 * The EHolidayActions class defines constants
 * about holiday actions.
 */
class EHolidayActions
{
    const Add   = 0;
	const Mod   = 1;
    const Del   = 2;
    const Ready = 3;
}

/**
 * Description of CHolidays
 * @package Holiday
 * @subpackage Base Class
 * @author Judit Alföldi
 * 
 * CHolidays implements the holiday handle class.
 * 
 */
class CHolidays
{
    private $id;
    private $from;
    private $to;
    private $desc;
    private $status;
    private $action;
    
    /**
     * The constructor of CHolidays class which set the
     * main properties of holidays.
     * @param int $id The database identifier of holiday.
     * @param date $from Start date of holiday.
     * @param date $to End date of holiday.
     * @param string $desc Description of holiday.
     * @param int $status Status of holiday.
     * @param int $action Action of holiday.
     */
    public function __construct($id, $from, $to, $desc, $status, $action)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->desc = $desc;
        $this->status = $status;
        $this->action = $action;
    }
    
    /**
     * The setter function of CHolidays class.
     * @param string $property
     * @param string $value
     */    
	function __set($property, $value)
	{
        $this->$property = $value;
	}
 
    /**
     * The getter function of CHolidays class.
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
     * The AddHolidayToDb function adds a holiday to database.
     * @param type $userId The connected user identifier.
     */
    public function AddHolidayToDb($userId)
    {
        $connection = CDatabase::Connect();
        $query = "INSERT INTO time_offs("
                . "user_id, "
                . "to_date_from, "
                . "to_date_to, "
                . "to_status,"
                . "to_desc) values ("
                . "".$userId.", "
                . "'".$this->from."', "
                . "'".$this->to."', "
                . "".$this->status.", "
                . "'".$this->desc."') "
                . "returning to_id;";   
        
        $holidayData = CDatabase::Query($connection, $query);        
        $holidayId = pg_fetch_array($holidayData);
        $this->action = EHolidayActions::Ready; 
        $this->id = $holidayId["to_id"];      
    }
    
    /**
     * The UpdateHolidayInDb function updates a holiday in database.
     */    
    public function UpdateHolidayInDb()
    {
        $connection = CDatabase::Connect();
        $query = "UPDATE time_offs SET "
                . "to_date_from = '" .$this->from . "', "
                . "to_date_to = '" .$this->to . "', "
                . "to_desc = '" .$this->desc . "', "
                . "to_status = " .$this->status . " "
                . "where to_id = " . $this->id. ";";  
        
        CDatabase::Query($connection, $query);     
    }    
    
    /**
     * The DeleteHolidayFromDb function deletes a holiday from database.
     */
    public function DeleteHolidayFromDb()
    {
        $connection = CDatabase::Connect();
        $query = "DELETE FROM time_offs where to_id = ".$this->id.";";           
        CDatabase::Query($connection, $query);     
    }    
    
    /**
     * The GetUsedHolidayNumber function calculates the already used
     * number of days. 
     * @param array $holidayList
     * @return int The used day number. 
     */
    public static function GetUsedHolidayNumber($holidayList)
    {
        $all = 0;
        for($i=0; $i< count($holidayList); $i++)
        {
            if($holidayList[$i]->status != EHolidayStatus::Rejected)
            {
                $diff = abs(strtotime($holidayList[$i]->to) - strtotime($holidayList[$i]->from));

                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                $all = $all + $days + 1;                
            }            
        }        
        return $all;
    }
}
