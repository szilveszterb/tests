<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);
date_default_timezone_set("Europe/Budapest");

//reference the general utility classes
require("application/controllers/helpers/CBaseController.php");
require("application/controllers/helpers/CDatabase.php");
require("application/controllers/helpers/CIdName.php");
require("application/controllers/helpers/CIniHandler.php");
require("application/controllers/helpers/CInterpreter.php");                
require("application/controllers/helpers/CTemplate.php");

//reference the controller classes
require("application/controllers/Calendar.php");
require("application/controllers/Login.php");
require("application/controllers/UserData.php");

//reference the additional model classes
require("application/models/CHolidays.php");
require("application/models/CHolidaysWrapper.php");
require("application/models/CUser.php");
require("application/models/CUserGuest.php");
require("application/models/CUserManager.php");
require("application/models/CUserOperator.php");
require("application/models/CUserWrapper.php");
require("application/models/CUserGuestWrapper.php");
require("application/models/CUserManagerWrapper.php");
require("application/models/CUserOperatorWrapper.php");

//create the controller and execute the action
$interpreter = new CInterpreter($_GET);
$controller = $interpreter->CreateController();
$controller->ExecuteAction();