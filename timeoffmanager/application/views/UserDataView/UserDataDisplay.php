<?php

/**
 * Description of CLoginDisplay
 * @package View
 * @subpackage UserData
 * @author Judit Alföldi
 * 
 * The CUserDataDisplay implements the view part of the MVC 
 * patter and connects to the UserData Controller class. Its
 * task to include the UserData specified templates which create a
 * HTML based additional information form.
 * 
 * @param class $vm Instance of the UserData Model.
 * 
 */
$display = new CUserDataDisplay();

class CUserDataDisplay {
    private $vm;
    
    /**
     * Constructor of CUserDataDisplay which calls the
     * privite class functions and initialize model class.
     * @access public
     */    
    public function __construct()
    {
        $this->vm = new CUserDataModel(); 
        $this->Header();
        $this->Body();
        $this->Footer();        
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
        $currentOperator = CUser::getCurrentUser();
        $template_header = new CTemplate();        
		$template_header->file = "application/views/UserDataView/templates/header_template.html";
                     
        $userInfo = "<td align='left' width='70%'><h2>Welcome ".$currentOperator->fullName." (Operator)!</h2></td>";
        $signOut = "<td><a href='../Login/LoginDisplay' onclick='signOut();'>Sign out</a></td>";
        if(property_exists($currentOperator, "imageUrl") && isset($currentOperator->imageUrl))
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
     * the managers drop down box with names and ids.
     * @access private
     */     
	private function Body()
	{
        $managers = $this->vm->getAllManagers();
        $managerComboBox = "<select name='managers' id ='managerComboBox'>";
        $managerComboBox .= "<option> </option>";
        foreach ($managers as $manager)
        {
            $managerComboBox .= "<option value='".$manager->getId()."'>".$manager->getName()."</option>";
        }
        $managerComboBox .= "</select>";
        
        $template_body = new CTemplate();
        $template_body->file = "application/views/UserDataView/templates/body_template.html";	
        $template_body->set('managerComboBox', $managerComboBox);
        $template_body->Display();
	}
	
    /**
     * Footer function loads the footer template.
     * @access private
     */       
	private function Footer()
	{
        require_once('application/views/UserDataView/templates/footer_template.html');
	}
}
