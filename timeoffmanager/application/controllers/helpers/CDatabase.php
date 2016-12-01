<?php

/**
 * Description of CDatabase
 * @package Controller
 * @subpackage Helper classes
 * @author Judit Alföldi
 * 
 * The CDatabase class handles all functions
 * about database. The class implements a Singleton
 * pattern.
 */
class CDatabase
{
    private static $database;
    public $connection;        
    private $config;

    /**
     * The construcor of CDatabase class which loads
     * the connection string parameters from a configuration
     * file and creates a database connection.
     * @throws Exception
     */
    private function CDatabase() 
    {
        $this->config = CIniHandler::GetInstance();
        $databaseName = $this->config->GetIni("dbDatabase");
        $serverName = $this->config->GetIni("dbHost");
        $databaseUser = $this->config->GetIni("dbUser");
        $databasePassword = $this->config->GetIni("dbPwd");
        $databasePort = $this->config->GetIni("dbPort");
        
        $this->connection = pg_connect("host=$serverName dbname=$databaseName port=$databasePort user=$databaseUser password=$databasePassword");
        if($this->connection)
        {
            if(pg_dbname($this->connection) === false)
            {
                throw new Exception("Cannot find: " . $databaseName);
            }
        }
        else
        {
            throw new Exception("Cannot connect to database.");
        }        
    }
    
    private function __clone() {}
     
    private function __wakeup() {}       
    
    /**
     * The Connect function creates a static
     * instance of database it it does not exist.
     * before.
     * @return object
     */
    public static function Connect()
    {
        if(static::$database === null)
        {
            static::$database = new CDatabase();
        }

        return static::$database->connection;
    }        
    
    /**
     * The Query function runs queries.
     * @param string $connection
     * @param string $query
     * @return string $result
     * @throws Exception
     */
    public static function Query($connection, $query)
    {
        $result = pg_query($connection, $query);
        if($result === false)
        {
            throw new Exception("Cannot run the query. The error is: " . pg_last_error($connection));
        }
        else
        {
            return $result;
        }                  
    }
       
    /**
     * The CloseConnection function closes the connection.
     * @return boolean
     * @throws Exception
     */
    public function CloseConnection()
    {
		if($this->connection)
		{			
			if(pg_close($this->connection) == true)
			{
				return true;
			}
			else
			{
				throw new Exception("Cannot close the database connection. The error is: " . pg_last_error($this->connection));
			}
		}
    }
}

