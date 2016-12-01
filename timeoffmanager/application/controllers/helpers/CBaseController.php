<?php

/**
 * Description of CBaseController
 * @package Controller
 * @subpackage BaseClass
 * @author Judit Alföldi
 * @access public
 * 
 * The CBaseController class includes the
 * model and view files and executes the called action.
 * 
 */
class CBaseController {
    protected $args;
    protected $action;
    protected $controller;

    /**
     * The constructor of CBaseController class which calls
     * the called action with arguments.
     * @access public
     * @param string $action
     * @param string $args
     */        
    public function __construct($action, $args) 
    {
        $this->action = $action;
        $this->args = $args;
    }
    
    /**
     * The ExecuteAction function executes
     * the called action with arguments.
     * @access public
     * @param string $action
     * @param string $args
     */       
    public function ExecuteAction()
    {   
        return call_user_func_array(array($this, $this->action), $this->args);
    }
    
    /**
     * The RequireModelAndView function includes
     * the model and view files.
     * @access protected
     */     
    protected function RequireModelAndView()
    {
        require("application/models/" . get_class($this) . "Model/" . get_class($this) . "Model.php");
        require("application/views/" . get_class($this) . "View/" . $this->action . ".php");
    }   
}
