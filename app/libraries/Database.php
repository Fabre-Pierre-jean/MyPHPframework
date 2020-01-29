<?php
/**
 * Class Database | file Database.php
 *
 * In this class, we have all mysql methods :
 *
 * Connection to the database in the constructor
 *
 * Disconnection to the database in the destructor
 *
 * Getting the last insert id
 *
 * Execute select method
 *
 * Execute insert / update / delete method
 *
 * @package WebManager - Administrator Project
 * @subpackage Database
 * @author @Afpa Lab Team
 * @copyright  1920-2080 The Afpa Lab Team Group Corporation World Company
 * @version v1.0
 */
Class Database {
    /**
     * private $_hDb is used to store Database instance object
     * @var object
     */
    private $_hDb;

    /**
     * Connect to the database
     */
    function __construct($host, $name, $login, $psw)	{
        // Connection to DB : SERVEUR / LOGIN / PASSWORD / NOM_BDD
        $this->_hDb= new PDO('mysql:host='.$host.';dbname='.$name.';charset=utf8', $login, $psw);
    }

    /**
     * Disconnect from the database
     */
    function __destruct()	{
        $this->_hDb= null;
    }

    /**
     * Get the last id inserted
     */
    public function getLastInsertId()	{
        error_log('getLastInsertId DETAILS = '.$this->_hDb->lastInsertId());
        return $this->_hDb->lastInsertId();
    }

    /**
     * Execute select method
     */
    function getSelectData($spathSQL, $data=array())	{
        // content of SQL file
        $sql= file_get_contents($spathSQL);

        // replace variables @variable from sql by values of the same variables'name
        foreach ($data as $key => $value) {
            $sql = str_replace('@'.$key, $value, $sql);
            error_log("key = " . $key . " | " . "value= " . $value. " | " . "sql = " . $sql);
        }

        error_log("getSelectData = " . $sql);

        // Prepare and execute the request
        $result= [];
        $result["error"]= "";
        try {
            // prepare() protect form SQL injection
            $results_db= $this->_hDb->prepare($sql);
            $results_db->execute();
        }
        catch (PDOException $e) {
            $result["error"]= $e->getMessage();
            error_log("PDOException getSelectData = " . $result["error"]);
        }

        $result= [];
        while ($ligne = $results_db->fetch()) {
            $new_ligne= [];
            foreach ($ligne as $key => $value) {
                if (!(is_numeric($key)))	{
                    error_log("getSelectData DETAILS = " . $key . " => " . $value);
                    $new_ligne[$key]= $value;
                }
            }
            $result[]= $new_ligne;
        }

        return $result;
    }

    /**
     * Execute insert / update / delete method
     */
    function treatData($spathSQL, $data=array())	{
        // content of SQL file
        $sql= file_get_contents($spathSQL);

        foreach ($data as $key => $value) {

            $sql= str_replace('@'.$key, $value, $sql);
        }

        error_log("treatData = " . $sql);

        // Prepare and execute the request
        $result= [];
        $result["error"]= "";
        try {
            // prepare() protect form SQL injection
            $results_db= $this->_hDb->prepare($sql);
            $results_db->execute();
        }
        catch (PDOException $e) {
            $result["error"]= $e->getMessage();
            error_log("PDOException treatData = " . $result["error"]);
        }

        return $result;
    }
//End of class
}

