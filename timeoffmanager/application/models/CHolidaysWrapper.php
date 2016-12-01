<?php

/**
 * Description of CHolidaysWrapper
 * @package Wrapper
 * @subpackage Extended Class
 * @author Judit Alföldi
 * 
 * The CHolidaysWrapper is extended from the CHolidays class
 * and it is a filtered class of CHolidays with public
 * properties to collect the appropriate data which will be
 * used by calendar plugin.
 */
final class CHolidaysWrapper extends CHolidays{
    public $id;
    public $from;
    public $to;
    public $desc;
    public $status;
    
    /**
     * The constructor of CHolidays class which set the
     * main properties of holidays.
     * @param int $id The database identifier of holiday.
     * @param date $from Start date of holiday.
     * @param date $to End date of holiday.
     * @param string $desc Description of holiday.
     * @param int $status Status of holiday.
     */    
    public function __construct($id, $from, $to, $desc, $status)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->desc = $desc;
        $this->status = $status;
    }
}
