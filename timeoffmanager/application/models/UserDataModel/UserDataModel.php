<?php

/**
 * Description of CUserDataModel
 * @package Model
 * @subpackage UserData
 * @author Judit Alföldi
 * 
 * The CUserDataModel implements the model part of the MVC 
 * patter and connects to the Login UserData class.
 * 
 */
class CUserDataModel
{
    /**
     * getAllManagers function returns the manager names
     * and ids to fill up the the manager drop down box
     * in the 'Additional data' page.
     * @access public
     * @return type
     */
    public function getAllManagers()
    {
        return CUserManager::getAllManagers();
    }
}
