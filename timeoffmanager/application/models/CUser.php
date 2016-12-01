<?php

/**
 * Description of ERight
 * @package User
 * @subpackage User helper class
 * @author Judit Alföldi
 * 
 * The ERight class defines the levels of zsers.
 */
class ERight
{
    const Guest     = 1;
	const Operator  = 2;
	const Manager   = 3;
    
	public static function GetStrFromValue($value)
	{
		switch($value)
		{
			case ERight::Guest: 
				return "Guest";
			case ERight::Operator:
                return "Operator";           
			case ERight::Manager:
                return "Manager";                 
			default: 
				return "Unknown right!";
		}
	}            
}

/**
 * Description of CUser
 * @package User
 * @subpackage BaseClass
 * @author Judit Alföldi
 * 
 * The CUser is a base class of users and it contains
 * Google based information about the user and a static
 * property which implemets the currently signed in user.
 */
class CUser {
    protected static $currentUser;
    protected $id;
    protected $fullName;
    protected $imageUrl;
    protected $email;
    
    /**
     * The constructor of the CUser class which sets the
     * Google based data to the currently signed in user.
     * @access public
     * @param string $id - The database based identifier of the user.
     * @param string $fullName User's full name from Google login.
     * @param url $imageUrl The profile image url from Google login.
     * @param string $email User's e-mail from Google login.
     */
    protected function __construct($id, $fullName, $imageUrl, $email)
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->imageUrl = $imageUrl;
        $this->email = $email;
    }
    
    /**
     * The getCurrentUser function returns the currently
     * signed in user class.
     * @access public
     * @static
     * @return object
     */
    public static function getCurrentUser()
    {
        if(static::$currentUser === null)
        {
            static::unserializeCurrentUser();
        }
        return static::$currentUser;
    }
    
    /**
     * The serializeCurrentUser function writes the
     * currently signed in user data to file to transfer
     * the object from page to page.
     * If the php.ini session.save_handler property is file
     * than the object will write next to the session data,
     * otherwise in a local project folder.
     * @access public
     * @static
     */
    public static function serializeCurrentUser()
    {
        session_start();
        $id = session_id();
        session_write_close();        
        if(ini_get("session.save_handler") == "files")
        {
            $path = session_save_path()."/stat_".$id;
        }
        else
        {
            $path = "./public/stat_".$id;
        }
        
        file_put_contents($path, serialize(static::$currentUser), FILE_USE_INCLUDE_PATH);                
    }  
    
    /**
     * The serializeCurrentUser function reads the
     * currently signed in user data to file to transfer
     * the object from page to page.
     * If the php.ini session.save_handler property is file
     * than the object will read from the session data folder,
     * otherwise from a local project folder.
     * @access public
     * @static
     */
    private static function unserializeCurrentUser()
    {
        session_start();
        $id = session_id();
        session_write_close();        
        if(ini_get("session.save_handler") == "files")
        {
            $path = session_save_path()."/stat_".$id;
        }
        else
        {
            $path = "./public/stat_".$id;
        }
        
        static::$currentUser = unserialize(file_get_contents($path, FILE_USE_INCLUDE_PATH));            
    }     
 
    /**
     * isExistUser checks and returns the existance of the user.
     * @access public
     * @param type $email
     * @return boolean
     */
    public static function isExistUser($email)
    {
        $connection = CDatabase::Connect();
        $query = "SELECT user_id from users where user_email = '".$email."' limit 1;";     
        $resultData = CDatabase::Query($connection, $query);   
        if(pg_num_rows($resultData) === 0)
        {
            return false;
        }
        else
        {
            $result = pg_fetch_array($resultData);
            return $result["user_id"];
        }
    }
    
    /**
     * getUserRight returns the right of the user.
     * @access public
     * @param type $id
     * @return type
     */
    public static function getUserRight($id)
    {
        $connection = CDatabase::Connect();
        $query = "SELECT user_right from users where user_id = ".$id." limit 1;";     
        $resultData = CDatabase::Query($connection, $query); 
       
        $result = pg_fetch_array($resultData);
        return $result["user_right"];
    }     
}
