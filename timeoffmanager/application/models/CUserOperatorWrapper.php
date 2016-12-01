<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CUserOperatorWrapper
 *
 * @author Judit
 */
final class CUserOperatorWrapper extends CUserWrapper
{
    public $holidayList;
    public $background;
    public $foreground;     
    
    public function __construct($fullName, $imageUrl, $email, $background, $foreground, $holidayList)
    {
        parent::__construct($fullName, $imageUrl, $email);
        $this->background = $background;
        $this->foreground = $foreground;
        for($i=0; $i< count($holidayList); $i++)
        {
            $this->holidayList[] = new CHolidaysWrapper(
                    $holidayList[$i]->id,
                    $holidayList[$i]->from, 
                    $holidayList[$i]->to, 
                    $holidayList[$i]->desc,
                    $holidayList[$i]->status);
        }
    }
}
