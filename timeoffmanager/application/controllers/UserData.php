<?php
/**
 * Description of UserData
 * @package Controller
 * @subpackage Login
 * @author Judit Alföldi
 * 
 * The UserData class extends from the CBaseController class.
 * If the user uses the application at the first time some
 * additional data will be necessary to define.
 * The followings are essential to calculate the number of 
 * further day offs:
 * - Year of birth
 * - Number of children
 * - Previously used days number at last workplace
 * 
 * The user has to choose a manager who will consider the
 * requests, furthermore a back- and a foreground color
 * have to be defined to visualize customized agenda items.
 * 
 */
class UserData extends CBaseController
{
    /**
     * UserDataDisplay function calls RequireModelAndView
     * which includes the connected model and view files if
     * the user does not exist in the database, otherwise
     * redirects the users to the szabott agenda.
     * @access public
     * @param object $currentOperator This object contains 
     * the logged in user's data.
     */
    public function UserDataDisplay()
    {
        $currentOperator = CUser::getCurrentUser();
        $isExistUserId = $this->isExistUser($currentOperator->email); 
        if($isExistUserId === false)
        {
            $this->RequireModelAndView();
        }
        else
        {
            header("Location: ../Calendar/CalendarDisplay");
        }
	}
    
    /**
     * The SaveData function saves the user's additional data to 
     * the database and redirects the user to the szabott agenda.
     * @access public
     * @param string $yearOfBirth The given year of birth.
     * @param string $managerId The choosen manager's identifier.
     * @param string $childrenNum The given number of children.
     * @param string $foreground The given foreground color.
     * @param string $foreground The given background color.
     * @param object $currentOperator This object contains the 
     * logged in user's data.
     */
    public function SaveData()
    {           
        $yearOfBirth = isset($_POST["year_of_birth"]) ?  $_POST["year_of_birth"] : null;
        $managerId = isset($_POST["managers"]) ?  $_POST["managers"] : null;
        $childrenNum = isset($_POST["children_num"]) ?  $_POST["children_num"] : null;   
        $foreground = isset($_POST["foreground"]) ?  $_POST["foreground"] : null; 
        $background = isset($_POST["background"]) ?  $_POST["background"] : null; 
        
        $currentOperator = CUser::getCurrentUser();
        $currentOperator->setAdditionalData($yearOfBirth, $managerId, $childrenNum, $background, $foreground, date("Y-m-d H:i:s"));
        $userId = $currentOperator->AddUserToDb();
        $currentOperator->__set('id', $userId);           
        if(isset($_POST["used_days"]) && (int)$_POST["used_days"] !== 0)
        {
            $from = date('Y') . "-01-01";
            $to = date('Y-m-d', strtotime($from . " + ".$_POST["used_days"]." days"));            
            $currentOperator->AddHoliday($from, $to, "Holidays at previous workplace.", EHolidayStatus::Previous);
        }
        $currentOperator->setHolidayList();
        
        header("Location: ../Calendar/CalendarDisplay");
    }
    
    /**
     * isExistUser checks and returns the existance of the user.
     * @access public
     * @param string $email User's e-mail from Google login.
     * @return type
     */    
    private function isExistUser($email)
    {
        return CUser::isExistUser($email);
    } 
}
