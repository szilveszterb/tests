<?php

/**
 * Description of CTemplate
 * @package Controller
 * @subpackage Helper classes
 * @author Judit Alföldi
 * 
 * The CTemplate class handles the variable changing
 * in template files in views and displays the template.
 */
class CTemplate
{
    public $file;
    public $vars = array();
    
    /**
     * CTemplate class set function.
     * @access public
     * @param type $key
     * @param type $value
     */
    public function set($key, $value)
    {
        $this->vars[$key] = $value;
    }
    
    /**
     * The Display function replaces the variableds and
     * displays the loaded tempaltes.
     * @access public
     */      
    public function Display()
    {
        try
        {
            if(file_exists($this->file))
            {
                $output = file_get_contents($this->file);
                foreach ($this->vars as $key => $value)
                {
                    $output = preg_replace("/{".$key."}/", $value, $output);
                }
                
                echo $output;
            }
            else
            {
                throw new Exception("Missing template - " . $this->file);
            }
        }
        catch (Exception $ex)
        {
            echo "Caught exception:" . $ex->getMessage() . "\n";
        }
    }
}
