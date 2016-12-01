<?php
/**
 * Description of Calendar
 * @package Controller
 * @subpackage Calendar
 * @author Judit Alföldi
 * 
 * The Calendar class extends from the CBaseController class.
 * This class handles the vizualization and all types of 
 * modification of the calendar plugin.
 * 
 * The calendar is customized based on the user's right.
 * In Operator level the user can add, modify, delete, 
 * drag and drop his/her own calendar items.
 * 
 * In Manager level the manager can adjust those request
 * statuses which belong to him/her.
 * 
 * In Guest level the guest can view all holiday records.
 * 
 */
class Calendar extends CBaseController
{
    /**
     * CalendarDisplay function calls RequireModelAndView
     * which includes the connected model and view files to 
     * visualize the calendar.
     * @access public
     */    
    public function CalendarDisplay()
    {
		$this->RequireModelAndView();
	}
    
    /**
     * CalendarTableDisplay function calls RequireModelAndView
     * which includes the connected model and view files to 
     * visualize the summary table under agenda but just in
     * Manager level.
     * @access public
     */      
    public function CalendarTableDisplay()
    {
		$this->RequireModelAndView();
	}    
    
    /**
     * FillUpCalendar function fills up the calendar
     * with right based data.
     * @access public
     * @param object $currentOperator This object contains 
     * the logged in user's data.
     */   
    public function FillUpCalendar()
    {
        $currentUser = CUser::getCurrentUser();        
        if($currentUser->right == ERight::Operator)
        {            
            $currentUserWrapper = new CUserOperatorWrapper(
                    $currentUser->__get("fullName"), 
                    $currentUser->__get("imageUrl"), 
                    $currentUser->__get("email"), 
                    $currentUser->__get("background"), 
                    $currentUser->__get("foreground"), 
                    $currentUser->__get("holidayList"));
        }
        elseif ($currentUser->right == ERight::Guest)
        {
            $currentUserWrapper = new CUserGuestWrapper(
                    $currentUser->__get("fullName"), 
                    $currentUser->__get("imageUrl"), 
                    $currentUser->__get("email"), 
                    $currentUser->__get("allOperatorList"));            
        }
        else
        {
            $currentUserWrapper = new CUserManagerWrapper(
                    $currentUser->__get("fullName"), 
                    $currentUser->__get("imageUrl"), 
                    $currentUser->__get("email"), 
                    $currentUser->__get("operatorList"));             
        }        
        echo(json_encode((array)$currentUserWrapper));
    }
    
    /**
     * AddCalendarItem function adds a holiday item
     * to the signed in user's holiday list.
     * @access public
     * @param string $from The start date of holiday.
     * @param string $to The end date of holiday.
     * @param string $desc The description of holiday.
     * @param object $currentOperator This object contains 
     * the logged in user's data.
     */     
    public function AddCalendarItem()
    {
        $from = isset($_POST["from"]) ?  $_POST["from"] : null;
        $to = isset($_POST["to"]) ?  $_POST["to"] : null;
        $desc = isset($_POST["desc"]) ?  $_POST["desc"] : null;  
        
        $currentOperator = CUser::getCurrentUser();      
        $currentOperator->AddHoliday($from, $to, $desc, EHolidayStatus::Required); 
        $this->FillUpCalendar();
    }
          
    /**
     * ModifyCalendarItem function modifyes a holiday item
     * and refreshes the summary table but just in Manager level.
     * @access public
     * @param string $id The identifier of edited holiday.
     * @param string $from The start date of holiday.
     * @param string $to The end date of holiday.
     * @param string $desc The description of holiday.
     * @param string $status The status of holiday.
     * @param object $currentOperator This object contains 
     * the logged in user's data.
     */      
    public function ModifyCalendarItem()
    {
        $id = isset($_POST["id"]) ?  $_POST["id"] : null; 
        $from = isset($_POST["from"]) ?  $_POST["from"] : null;
        $to = isset($_POST["to"]) ?  $_POST["to"] : null;
        $desc = isset($_POST["desc"]) ?  $_POST["desc"] : null;
        $status = isset($_POST["status"]) ?  $_POST["status"] : null;
        
        $currentOperator = CUser::getCurrentUser();      
        $currentOperator->ModifyHoliday("id", $id, $from, $to, $desc, $status);
        if($currentOperator->right == ERight::Manager)
        {
            require_once ('application/views/CalendarView/CalendarTableDisplay.php"');
        }
    }   
         
    /**
     * DragAndDropCalendarItem function updates a holiday item
     * start, end date and description.
     * @access public
     * @param string $id The identifier of edited holiday.
     * @param string $from The start date of holiday.
     * @param string $desc The description of holiday.
     * @param object $currentOperator This object contains 
     * the logged in user's data.
     */        
    public function DragAndDropCalendarItem()
    {
        $id = isset($_POST["id"]) ?  $_POST["id"] : null; 
        $from = isset($_POST["from"]) ?  $_POST["from"] : null;
        $desc = isset($_POST["desc"]) ?  $_POST["desc"] : null; 
        
        $currentOperator = CUser::getCurrentUser();      
        $currentOperator->DragAndDropHoliday("id", $id, $from, $desc); 
    }     
    
    /**
     * DeleteCalendarItem function deletes a holiday item.
     * @access public
     * @param string $id The identifier of edited holiday.
     * @param object $currentOperator This object contains 
     * the logged in user's data.
     */       
    public function DeleteCalendarItem()
    {
        $id = isset($_POST["id"]) ?  $_POST["id"] : null;  
        
        $currentOperator = CUser::getCurrentUser();      
        $currentOperator->DeleteHoliday("id", $id); 
        $this->FillUpCalendar();
    }      
}
