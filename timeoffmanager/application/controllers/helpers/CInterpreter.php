<?php

/**
 * Description of CInterpreter
 * @package Controller
 * @subpackage Helper classes
 * @author Judit Alföldi
 * 
 * The CInterpreter class handles the URL mapping.
 * It splits the given URL to controller - action -
 * argument parts and it it is success it tries to
 * initialize the controller itself.
 */
class CInterpreter
{
    private $controller;
    private $action;
    private $args;
    private $urlValues;
    
    /**
     * The constructor of CInterpreter class which connects
     * the URL controller - action - argument parts.
     * @param string $urlValues
     */    
    public function __construct($urlValues)
    {
        $this->urlValues = $urlValues;
        if($this->urlValues["controller"] == "")
        {
            $this->controller = "show";
        }
        else
        {
            $this->controller = $this->urlValues["controller"];
        }
        
        if($this->urlValues["action"] == "")
        {
            $this->action = "index";            
        }
        else
        {
            $this->action = $this->urlValues["action"];
        }
        
        if(!isset($this->urlValues["args"]))
        {
            $this->args = "";            
        }
        else
        {
            $this->args = $this->urlValues["args"];
        }        
    }
    
    /**
     * The CreateController function initialize
     * the called controller.
     * @access public
     */       
    public function CreateController()
    {
        try
        {
            if(class_exists($this->controller))
            {
                $parents = class_parents($this->controller);
                if(in_array("CBaseController", $parents))
                {
                    if(method_exists($this->controller, $this->action))
                    {
                        $args = explode("/", $this->args);                        
                        return new $this->controller($this->action, $args);
                    }
                    else
                    {
                        return new Exception("Bad Method: " . $this->urlValues);
                    }
                }
                else
                {
                    return new Exception("Bad Controller: " . $this->urlValues);
                }
            }
            else
            {
                return new Exception("Bad Controller: " . $this->urlValues);
            }            
        }
        catch (Exception $ex)
        {
            echo "Caught exception:" . $ex->getMessage() . "\n";
        }                
    }
}
