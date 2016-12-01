<?php

/**
 * Description of CIniHandler
 * @package Controller
 * @subpackage Helper classes
 * @author Judit Alföldi
 * @access public
 * 
 * The CIniHandler handles the configuration
 * file reading. The class implements a Singleton pattern.
 * 
 */
class CIniHandler
{
    private $filename;
    private $content;
    private static $instance;
    
    public static function GetInstance()
    {
        if(static::$instance === null)
        {
            static::$instance = new static();
        }                
        return static::$instance;
    }        

    /**
     * The constructor of CIniHandler class.
     */       
    private function __construct()    
    {
        $this->filename = 'application/configs/config.ini';
        $this->content = parse_ini_file($this->filename);     
    }
    
    private function __clone() {}
     
    private function __wakeup() {}    
    
    /**
     * 
     * Return the value of specified key.
     * @param string $key The specified key.
     */
    public function GetIni( $key )
    {
        return $this->content[$key];
    }
 }
