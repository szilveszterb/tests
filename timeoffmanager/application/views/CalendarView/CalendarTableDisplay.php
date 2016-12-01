<?php

/**
 * Description of CalendarTableDisplay
 * @package View
 * @subpackage Calendar
 * @author Judit Alföldi
 * 
 * The CalendarTableDisplay implements the view part of the MVC 
 * patter and connects to the Calendar Controller class. Its
 * task to include the Calendar specified templates which create a
 * HTML based login form.
 * 
 */
$display = new CalendarTableDisplay();

class CalendarTableDisplay
{    
    /**
     * Constructor of CalendarDisplay which calls the
     * privite class function.
     * @access public
     */     
    public function __construct()
    {
        $this->Body();      
    }

    /**
     * Body function loads the body template and fills up
     * the holiday summary table with data.
     * @access private
     * @param object $currentOperator This object contains 
     * the logged in user's data.
     */       
    private function Body()
    {
        $currentOperator = CUser::getCurrentUser();
        $template_table = new CTemplate();
        $template_table->file = "application/views/CalendarView/templates/table1_template.html";
        $template_table->Display();         

        $template_label = new CTemplate();
        $template_label->file = "application/views/CalendarView/templates/label_template.html";
        $template_label->Display();

        $template_row = new CTemplate();
        $template_row->file = "application/views/CalendarView/templates/row_template.html";        
        for($i=0; $i< count($currentOperator->operatorList); $i++)
        {
            for($j=0; $j< count($currentOperator->operatorList[$i]->holidayList); $j++)
            {
                if($currentOperator->operatorList[$i]->holidayList[$j]->status == EHolidayStatus::Required)
                {
                    $template_row->set('id', $currentOperator->operatorList[$i]->holidayList[$j]->id);
                    $template_row->set('name', $currentOperator->operatorList[$i]->fullName);
                    $template_row->set('reason', $currentOperator->operatorList[$i]->holidayList[$j]->desc);
                    $template_row->set('from', $currentOperator->operatorList[$i]->holidayList[$j]->from);
                    $template_row->set('to', $currentOperator->operatorList[$i]->holidayList[$j]->to);

                    $template_row->Display(); 
                }
            }
        }

        $template_table2 = new CTemplate();
        $template_table2->file = "application/views/CalendarView/templates/table2_template.html";
        $template_table2->Display();  

        $template_label2 = new CTemplate();
        $template_label2->file = "application/views/CalendarView/templates/label_template.html";
        $template_label2->Display();

        $template_row2 = new CTemplate();
        $template_row2->file = "application/views/CalendarView/templates/row_template.html";        
        for($i=0; $i< count($currentOperator->operatorList); $i++)
        {
            for($j=0; $j< count($currentOperator->operatorList[$i]->holidayList); $j++)
            {
                if($currentOperator->operatorList[$i]->holidayList[$j]->status == EHolidayStatus::Approved)
                {
                    $template_row2->set('id', $currentOperator->operatorList[$i]->holidayList[$j]->id);
                    $template_row2->set('name', $currentOperator->operatorList[$i]->fullName);
                    $template_row2->set('reason', $currentOperator->operatorList[$i]->holidayList[$j]->desc);
                    $template_row2->set('from', $currentOperator->operatorList[$i]->holidayList[$j]->from);
                    $template_row2->set('to', $currentOperator->operatorList[$i]->holidayList[$j]->to);

                    $template_row2->Display(); 
                }
            }
        }    

        $template_table3 = new CTemplate();
        $template_table3->file = "application/views/CalendarView/templates/table3_template.html";
        $template_table3->Display();  

        $template_label3 = new CTemplate();
        $template_label3->file = "application/views/CalendarView/templates/label_template.html";
        $template_label3->Display();

        $template_row3 = new CTemplate();
        $template_row3->file = "application/views/CalendarView/templates/row_template.html";        
        for($i=0; $i< count($currentOperator->operatorList); $i++)
        {
            for($j=0; $j< count($currentOperator->operatorList[$i]->holidayList); $j++)
            {
                if($currentOperator->operatorList[$i]->holidayList[$j]->status == EHolidayStatus::Rejected)
                {
                    $template_row3->set('id', $currentOperator->operatorList[$i]->holidayList[$j]->id);
                    $template_row3->set('name', $currentOperator->operatorList[$i]->fullName);
                    $template_row3->set('reason', $currentOperator->operatorList[$i]->holidayList[$j]->desc);
                    $template_row3->set('from', $currentOperator->operatorList[$i]->holidayList[$j]->from);
                    $template_row3->set('to', $currentOperator->operatorList[$i]->holidayList[$j]->to);

                    $template_row3->Display(); 
                }
            }
        }    

        $template_table4 = new CTemplate();
        $template_table4->file = "application/views/CalendarView/templates/table4_template.html";
        $template_table4->Display();  

        $template_label4 = new CTemplate();
        $template_label4->file = "application/views/CalendarView/templates/label_template.html";
        $template_label4->Display();

        $template_row4 = new CTemplate();
        $template_row4->file = "application/views/CalendarView/templates/row_template.html";        
        for($i=0; $i< count($currentOperator->operatorList); $i++)
        {
            for($j=0; $j< count($currentOperator->operatorList[$i]->holidayList); $j++)
            {
                if($currentOperator->operatorList[$i]->holidayList[$j]->status == EHolidayStatus::Pending)
                {
                    $template_row4->set('id', $currentOperator->operatorList[$i]->holidayList[$j]->id);
                    $template_row4->set('name', $currentOperator->operatorList[$i]->fullName);
                    $template_row4->set('reason', $currentOperator->operatorList[$i]->holidayList[$j]->desc);
                    $template_row4->set('from', $currentOperator->operatorList[$i]->holidayList[$j]->from);
                    $template_row4->set('to', $currentOperator->operatorList[$i]->holidayList[$j]->to);

                    $template_row4->Display(); 
                }
            }
        }          

        $template_table5 = new CTemplate();
        $template_table5->file = "application/views/CalendarView/templates/table5_template.html";
        $template_table5->Display();  

        $template_label5 = new CTemplate();
        $template_label5->file = "application/views/CalendarView/templates/label_template.html";
        $template_label5->Display();

        $template_row5 = new CTemplate();
        $template_row5->file = "application/views/CalendarView/templates/row_template.html";        
        for($i=0; $i< count($currentOperator->operatorList); $i++)
        {
            for($j=0; $j< count($currentOperator->operatorList[$i]->holidayList); $j++)
            {
                if($currentOperator->operatorList[$i]->holidayList[$j]->status == EHolidayStatus::Expired)
                {
                    $template_row5->set('id', $currentOperator->operatorList[$i]->holidayList[$j]->id);
                    $template_row5->set('name', $currentOperator->operatorList[$i]->fullName);
                    $template_row5->set('reason', $currentOperator->operatorList[$i]->holidayList[$j]->desc);
                    $template_row5->set('from', $currentOperator->operatorList[$i]->holidayList[$j]->from);
                    $template_row5->set('to', $currentOperator->operatorList[$i]->holidayList[$j]->to);

                    $template_row5->Display(); 
                }
            }
        }
    }
}
