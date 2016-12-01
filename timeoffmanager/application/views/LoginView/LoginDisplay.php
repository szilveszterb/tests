<?php

/**
 * Description of CLoginDisplay
 * @package View
 * @subpackage Login
 * @author Judit Alföldi
 * 
 * The CLoginDisplay implements the view part of the MVC 
 * patter and connects to the Login Controller class. Its
 * task to include the Login specified templates which create a
 * HTML based login form.
 * 
 */
$display = new CLoginDisplay();

class CLoginDisplay 
{
    /**
     * Constructor of CLoginDisplay which calls the
     * privite class functions.
     * @access public
     */
    public function __construct()
    {
        $this->Header();
        $this->Body();
        $this->Footer();        
    }
    
    /**
     * Header function loads the header template.
     * @access private
     */
    private function Header()
	{
		require_once('application/views/LoginView/templates/header_template.html');
	}
		
    /**
     * Body function loads the body template.
     * @access private
     */    
	private function Body()
	{    
        require_once('application/views/LoginView/templates/body_template.html');
	}
	
    /**
     * Footer function loads the footer template.
     * @access private
     */       
	private function Footer()
	{
        require_once('application/views/LoginView/templates/footer_template.html');
	}
}