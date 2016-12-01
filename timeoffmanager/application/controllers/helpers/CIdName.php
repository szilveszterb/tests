<?php

/**
 * Description of CIdName
 * @package Controller
 * @subpackage Helper classes
 * @author Judit Alföldi
 * @access public
 * 
 * The CIdName class helps to fill up drop down boxes.
 * 
 */
class CIdName {
    private $id;
    private $name;
    
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }    
}
