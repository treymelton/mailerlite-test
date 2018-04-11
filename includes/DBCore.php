<?php
require_once(dirname(__FILE__)  .DIRECTORY_SEPARATOR.'Debug.php');


/**
 * DBCore.php
 * Core database class. Handles connections and queries.
 */
class DBCore
{
  protected $db_host;
  protected $db_name;
  protected $db_user;
  protected $db_pass;
  protected $db_link;

  /**
   * DBCore class constructor
   *
   * @param string $host  Host Name of the database connection
   * @param string $name  DB Name
   * @param string $user  DB User Name
   * @param string $pass  DB Password
   */
  function __construct($host = NULL, $name = NULL, $user = NULL, $pass = NULL)
  {                       
    if($host == NULL)
    {
      $this->db_host = DB_HOST;
      $this->db_name = DB_NAME;
      $this->db_user = DB_USER;
      $this->db_pass = DB_PASS;
    }
    else
    {
      $this->db_host = $host;
      $this->db_name = $name;
      $this->db_user = $user;
      $this->db_pass = $pass;
    }

    $this->db_link = NULL;

    $this->DBConnect();
  } // end class constructor

  /**
   * Since serialized objects lose resource data types, re-connect on wakeup.
   */
  function __wakeup()
  {
    $this->DBConnect();
  }

  /**
   * Close database link on class destruct
   */
  function __destruct()
  {
    if($this->db_link !== NULL)
    {
      //mysql_close($this->db_link);
    }
    $this->db_link = NULL;
  } // end function DBDisconnect()

  /**
   * Attempt to connect to DB and select the database.
   * 
   * @return bool
   */
  function DBConnect()
  {
    /**
     * Check for existing link, even though mysql_connect does it for us. If 
     * one exists, we don't waste the connect op and just use that.
     */
    if($this->db_link == NULL)
    {
      if(version_compare(PHP_VERSION, '5.5.0') >= 0){
        $this->db_link = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
      }
      else
        $this->db_link = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
      if($this->db_link == NULL)
      {
        Debug::Debug_er('Could not connect: [' . mysql_errno() . ']  PHP version ['.phpversion().']: ' . mysql_error(), DEBUG_LEVEL);
        die;
      }
    }
    if(version_compare(PHP_VERSION, '5.5.0') < 1 ){
      // db_link will exist here if the script hasn't stopped
      if(mysql_select_db($this->db_name))
      {
        return TRUE;
      }
      else
      {
        Debug::Debug_er("Could not select db : " . $this->db_name. '[' . mysql_errno() . '] $this->db_user ['.$this->db_user.']  PHP version ['.PHP_VERSION.']: ' . mysql_error(), DEBUG_LEVEL);

        mysql_close($this->db_link);
        $this->db_link = NULL;
        return FALSE;
      }
    }

  } // end function DBConnect()

  /**
   * Given a SQL query, attempt to run it on the DB and return the result,
   * or FALSE on fail. Queries without resultsets will return bool true/false.
   *
   * @param string $query
   * @return resource|bool
   */
  function DBQuery($query, $nonulls = false)
  {
    $result = NULL;
    $rows_matched = NULL;
    $matches = 0;

    // if no connection exists, fail for now. Not sure if this should attempt to
    // connect and run the query instead...
    if(!$this->isConnected())
    {
      Debug::Debug_er("Not connected to database. Exiting...", DEBUG_LEVEL);
      return FALSE;
    }

    // added to handle queries that might want '' instead of null in the query
    if($nonulls === false)
    {
      $pattern = "/('')|(\"\")/i";
      $replacement = 'null';
      $query = preg_replace($pattern, $replacement, $query);
    }

    //Debug::Debug_er('['.$_SERVER['SCRIPT_NAME'].']: '.$query, DEBUG_LEVEL);
    //Debug::Debug_er('value of DB link: [' . $this->db_link . ']', DEBUG_LEVEL);  mysqli_connect_errno()
    if(version_compare(PHP_VERSION, '5.5.0') >= 0){
        $result = mysqli_query($this->db_link, $query);
        if(mysqli_connect_errno())
    {
      Debug::Debug_er("Query failed: \r\n" . $query . "\r\n\r\n[" .
          mysqli_connect_errno() . "]: " . mysqli_connect_error(), DEBUG_LEVEL);
          mysqli_free_result($result);
      return FALSE;
    }

      $strInfo = mysqli_info($this->db_link);
      preg_match('/Rows matched: ([0-9]*)/i', $strInfo, $rows_matched);
      if(array_key_exists(1, $rows_matched))
        $matches = intval($rows_matched[1]);

      if($result && $result instanceof mysqli_result && $result->num_rows > 0)
      {
        return $result;
      }

      // unaffected rows where matches exist is fine
      if($result === TRUE && (mysqli_affected_rows($this->db_link) > 0 || $matches > 0))
      {
        return TRUE;
      }
      // not matching anything and expecting an update/etc. is not
      else if($matches < 0)
      {
       Debug::Debug_er("Error: Query [$query] matched no rows.", DEBUG_LEVEL);
       //return FALSE;
      }
    }
    else{
        $result = mysql_query($query, $this->db_link);

      if(mysql_error())
      {
        Debug::Debug_er("Query failed: \r\n" . $query . "\r\n\r\n[" .
            mysql_errno() . "]: " . mysql_error(), DEBUG_LEVEL);
            //mysql_free_result($result);
        return FALSE;
      }

      $strInfo = mysql_info();
      preg_match('/Rows matched: ([0-9]*)/i', $strInfo, $rows_matched);
      if(array_key_exists(1, $rows_matched))
        $matches = intval($rows_matched[1]);

      if($result && is_resource($result) && mysql_num_rows($result) > 0)
      {
        return $result;
      }

      // unaffected rows where matches exist is fine
      if($result === TRUE && (mysql_affected_rows() > 0 || $matches > 0))
      {
        return TRUE;
      }
      // not matching anything and expecting an update/etc. is not
      else if($matches < 0)
      {
       Debug::Debug_er("Error: Query [$query] matched no rows.", DEBUG_LEVEL);
       //return FALSE;
      }
    }

    return FALSE;
  } // end function DBQuery()

  /**
   * Check whether object has a database link.
   *
   * @return bool
   */
  function isConnected()
  {
    return ($this->db_link !== NULL ? TRUE : FALSE);
  } // end function isConnected()

  /**
   * Attempt to get the ID of the last completed insert on the link. Will return
   * "the ID generated for an AUTO_INCREMENT column by the previous INSERT query
   * on success, 0 if the previous query does not generate an AUTO_INCREMENT
   * value, or FALSE if no MySQL connection was established." -- php.net
   *
   * @return int
   */
  function getLastInsertID()
  {
    if(version_compare(PHP_VERSION, '5.5.0') >= 0){
        return mysqli_insert_id($this->db_link);
    }
    return (mysql_insert_id($this->db_link));
  } // end function getLastInsertID()

  /**
   * Pass database link to calling function. May be needed eventually.
   *
   * @return resource
   */
  function getDBLink()
  {
    return $this->db_link;
  }

  /**
   * Given a string, prepare it for entry into a database and return db-safe
   * string.
   *
   * Currently calls mysql_real_escape_string on the htmlentities()'d string.
   * NOTE: any data which requires entities to be decoded will have to be
   * decoded in the data's containing class.
   *
   * @param string $strvar
   * @return string
   */
  function safe($strvar, $encode = false, $lrn2br = false)
  {
    // needs a flag because we don't want to do this for all strings
    if($lrn2br === true)
      $strvar = nl2br($strvar);

    if($encode === true)
    {
      $strvar = htmlspecialchars($strvar, ENT_COMPAT, 'UTF-8', false);
    }
    if(!get_magic_quotes_gpc())
    {                                                
      if(version_compare(PHP_VERSION, '5.5.0') >= 0)
        $strvar = mysqli_real_escape_string($this->db_link,$strvar);
      else
        $strvar = mysql_real_escape_string($strvar);

      //Debug::Debug_er('Out: [' . $strvar . ']', 1);
    }
    else
    {
      //Debug::Debug_er('In: [' . $strvar . ']', 1);
      if(version_compare(PHP_VERSION, '5.5.0') >= 0)
        $strvar = mysqli_real_escape_string(stripslashes($this->db_link,$strvar));
      else
        $strvar = mysql_real_escape_string(stripslashes($strvar));
      //Debug::Debug_er('Out: [' . $strvar . ']', 1);
    }
    if(trim($strvar) == '')
        $strvar = NULL;
    return $strvar;
  } // end function safe()

} // end class DBCore

?>