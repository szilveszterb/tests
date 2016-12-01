<?php

/**
 * Description of CalendarDisplay
 * @package View
 * @subpackage Calendar
 * @author Judit Alföldi
 * 
 * The CalendarDisplay implements the view part of the MVC 
 * patter and connects to the Calendar Controller class. Its
 * task to include the Calendar specified templates which create a
 * HTML based login form.
 * 
 */
$display = new CalendarDisplay();

class CalendarDisplay
{
    /**
     * Constructor of CalendarDisplay which calls the
     * privite class functions.
     * @access public
     */    
    public function __construct()
    {
        $this->Header();
        $this->Body();          
    }
    
    /**
     * Header function loads the header template which vizualizes
     * a salutation, displays the picture of the user and implements
     * a sign out function which handles the signing out from the 
     * application.
     * @access private
     * @param object $currentOperator This object contains 
     * the logged in user's data.
     */     
    private function Header()
	{
        $picture = "";
        $currentOperator = CUser::getCurrentUser();
        $template_header = new CTemplate();        
		$template_header->file = "application/views/CalendarView/templates/header_template.html";
                     
        $userInfo = "<td align='left' width='70%'><h2>Welcome ".$currentOperator->fullName." (".ERight::GetStrFromValue($currentOperator->right).")!</h2></td>";
        $signOut = "<td><a href='../Login/LoginDisplay' onclick='signOut();'>Sign out</a></td>";

        if(property_exists($currentOperator, "imageUrl"))
        {
            $picture = "<td align='right' rowspan='2' width='30%'><img src='".$currentOperator->imageUrl."' style='width:100px;height:100px;'></td>";
        }        
        else
        {
            $picture = "<td align='right' rowspan='2' width='30%'><img src='../data/icons/no_user.jpg' style='width:100px;height:100px;'></td>";
        }        
        
        $template_header->set('userInfo', $userInfo);
        $template_header->set('signOut', $signOut);
        $template_header->set('picture', $picture);
        $template_header->Display();
	}
	
    /**
     * Body function loads the body template and fills up
     * some necessary data about holiday to help the calendar
     * handle javascript class. The summary table load is also
     * its task but just in Manager level.
     * @access private
     * @param object $currentOperator This object contains 
     * the logged in user's data.
     */       
	private function Body()
	{        
        $currentOperator = CUser::getCurrentUser();
        $template_body = new CTemplate();
        $template_body->file = "application/views/CalendarView/templates/body_template.html";
        $userRight = "<input type='text' id='right' value='".$currentOperator->right."'>";
        $template_body->set('userRight', $userRight);
        if($currentOperator->right == ERight::Operator)
        {
            $holidayNumber = "<input type='text' id='holiday_num' value='".$currentOperator->holidayNum."'>";
            $holidayPartNumber = "<input type='text' id='holiday_part_num' value='".$currentOperator->holdayNumPart."'>";
        }
        else
        {
            $holidayNumber = "<input type='text' id='holiday_num' value='0'>";
            $holidayPartNumber = "<input type='text' id='holiday_part_num' value='0'>";
        }
        $template_body->set('holidayPartNumber', $holidayPartNumber);
        $template_body->set('holidayNumber', $holidayNumber);
        $template_body->Display();
        
        if($currentOperator->right == ERight::Manager)
        {
            require_once('application/views/CalendarView/CalendarTableDisplay.php');
        }
        $this->Footer($currentOperator);
	}
	
    /**
     * Footer function loads the footer template.
     * @access private
     */         
	private function Footer($currentOperator)
	{
        if($currentOperator->right == ERight::Manager)
        {
            require_once('application/views/CalendarView/templates/footer_table_template.html');
        }
        else
        {
            require_once('application/views/CalendarView/templates/footer_template.html');
        }
	}    
}
