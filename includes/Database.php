<?php
require_once('DBCore.php');
class Database extends DBCore{

  /*******************************
  * Make the constructor and get methods
  ***********************************/


  public static function Get(){
		//==== instantiate or retrieve singleton ====
		static $inst = NULL;
		if( $inst == NULL )
			$inst = new Database();
		return( $inst );
   }

   public function __construct(){
		parent::__construct();
   }

  /*******************************
  * Make the constructor and get methods
  ***********************************/
  /**
  * given a resource return the appropriate data type
  * @param $resResource
  * @param $boolFirstRecordOnly return ONLY the first record
  * @param $intReturnType
  */
   static function CleanQuery($resResource,$boolFirstRecordOnly=FALSE,$intReturnType=2){
    $mixReturn = array();
    if(version_compare(PHP_VERSION, '5.5.0') >= 0){
        if($intReturnType == 1)//num rows
            $resResource->num_rows;
        $resResource->data_seek(0);
        if($intReturnType == 2){//associative array
            if($boolFirstRecordOnly){//we only need ONE record
          $arrResults = $resResource->fetch_array(MYSQLI_ASSOC);
          mysqli_free_result($resResource);
          return $arrResults;
            }
          $arrResults = []; //initialise empty array
          while($row = $resResource->fetch_assoc()){
              $arrResults[] = $row;
          }
          mysqli_free_result($resResource);
          return $arrResults;
        }
        else{
          $arrResults = $resResource->fetch_all(MYSQLI_ASSOC);
          mysqli_free_result($resResource);
          return $arrResults;
        }
    }
    else{
      // fix for warnings
      if(is_resource($resResource))
      {
        if($intReturnType == 1)//num rows
            return mysql_num_rows($resResource);
        for($i = 0; $i < mysql_num_rows($resResource); $i++)
        {
          if($intReturnType == 2){//associative array
            $arrDataRow = mysql_fetch_array($resResource,MYSQL_ASSOC);
            foreach($arrDataRow as $ka=>$va)
              $arrDataRow[$ka] = stripslashes($va);
            if($boolFirstRecordOnly){//we only need ONE record
                mysql_free_result($resResource);
                return $arrDataRow;
            }
            $mixReturn[] = $arrDataRow;
          }
        }
      }
    }
    if(is_resource($resResource))
        mysql_free_result($resResource);
    return $mixReturn;
  }

  /**
  * run a blank query
  * @return bool
  */
  function BlankQuery($strQuery){
   //Debug::Debug_er('Query ['.$strQuery.'] METHOD ['.__METHOD__.'] LINE['.__LINE__.']',1,TRUE);
   if($results = $this->DBQuery($strQuery)){
       return $results;
   }
   else{
       return FALSE;
   }
  }

  /**
  * verify required tables have been created
  * @return bool
  */
  function VerifyRequiredTables(){
    $strQuery = "SHOW TABLES LIKE 'subscribers'";
   if($this->DBQuery($strQuery)){
    $strQuery = "SHOW TABLES LIKE 'subfields'";
    return $this->DBQuery($strQuery);
   }
   else{
       return FALSE;
   }
  }

  /**
  * create the subscribers table
  * @return bool
  */
  function CreateSubscriberTable(){
    $strQuery = 'CREATE TABLE IF NOT EXISTS `subscribers` (
                `subscriberid` int(11) NOT NULL AUTO_INCREMENT,
                `clientid` int(9) NOT NULL,
                `subscribername` varchar(25) NOT NULL,
                `subscriberemail` varchar(65) NOT NULL,
                `subscriberstate` char(2) DEFAULT NULL,
                `subscriberzip` int(5) DEFAULT NULL,
                `subscribercountry` char(2) DEFAULT NULL,
                `subscriberstatus` int(1) NOT NULL,
                `edate` int(12) NOT NULL,
                `udate` int(12) NOT NULL,
                PRIMARY KEY (`subscriberid`)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT=\'hold subscriber core data\' AUTO_INCREMENT=1 ;';
    $this->DBQuery($strQuery);
    //verify our table now
    $strQuery = "SHOW TABLES LIKE 'subscribers'";
    return $this->DBQuery($strQuery);
  }


  /**
  * drop the subscribers table
  * @return bool
  */
  function DropSubscribersTable(){
    $strQuery = 'DROP TABLE `subscribers`;';
    if($this->DBQuery($strQuery)){
      $strQuery = "SHOW TABLES LIKE 'subscribers'";
      return $this->DBQuery($strQuery);
    }
    else{
      return FALSE;
    }
  }

  /**
  * create the subfields table
  * @return bool
  */
  function CreateSubFields(){
    $strQuery = 'CREATE TABLE IF NOT EXISTS `subfields` (
                `subfieldid` int(11) NOT NULL AUTO_INCREMENT,
                `subfieldtitle` varchar(25) NOT NULL,
                `subfieldtype` int(1) NOT NULL,
                `subfieldvalue` varchar(255) NOT NULL,
                `subscriber` int(11) NOT NULL,
                PRIMARY KEY (`subfieldid`)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT=\'subscriber meta values\' AUTO_INCREMENT=1 ;';
    $this->DBQuery($strQuery);
    //verify our table now
    $strQuery2 = "SHOW TABLES LIKE 'subfields'";
    if($this->DBQuery($strQuery2)){
        return TRUE;
    }
    else{
        Debug::Debug_er('Create failed ['.$strQuery.'] ['.__METHOD__.']',1);
        return FALSE;
    }
  }

  /**
  * drop the subscribers table
  * @return bool
  */
  function DropSubFieldsTable(){
    $strQuery = 'DROP TABLE `subfields`;';
    if($this->DBQuery($strQuery)){
      $strQuery = "SHOW TABLES LIKE 'subfields'";
      return $this->DBQuery($strQuery);
    }
    else{
      return FALSE;
    }
  }

  #REGION Subscriber

  /**
  * given a subscriber object, insert it
  * @param $objSubscriber
  * @return last insert ID
  */
  function InsertSubscriber($objSubscriber){
    $strQuery = 'INSERT INTO subscribers(clientid,
                                    subscribername,
                                    subscriberemail,
                                    subscriberstate,
                                    subscriberzip,
                                    subscribercountry,
                                    subscriberstatus,
                                    edate,
                                    udate
                                    ) VALUES("'.$this->safe($objSubscriber->intSubscriberClient).'",
                                    "'.$this->safe($objSubscriber->strSubscriberName).'",
                                    "'.$this->safe($objSubscriber->strSubscriberEmail).'",
                                    "'.$this->safe($objSubscriber->strSubscriberState).'",
                                    "'.$this->safe($objSubscriber->intSubscriberZip).'",
                                    "'.$this->safe($objSubscriber->strSubscriberCountry).'",
                                    "'.(int)$this->safe($objSubscriber->intSubscriberStatus).'",
                                    "'.$this->safe($objSubscriber->intEDate).'",
                                    "'.$this->safe($objSubscriber->intUDate).'")';
    if($this->DBQuery($strQuery)){
      return $this->getLastInsertID();
    }
    else{
     Debug::Debug_er('Insert FAILED ['.$strQuery.'] ['.__METHOD__.']',1);
     return FALSE;
    }
  }


  /**
  * given a subscriber object, update it
  * @param $objSubscriber
  * @return bool
  */
  function UpdateSubscriber($objSubscriber){
    $strQuery = 'UPDATE subscribers SET ';
    $strQuery .= 'subscribername = "'.$this->safe($objSubscriber->strSubscriberName).'" ,';
    $strQuery .= 'subscriberemail = "'.$this->safe($objSubscriber->strSubscriberEmail).'",';
    $strQuery .= 'subscriberstate = "'.$this->safe($objSubscriber->strSubscriberState).'",';
    $strQuery .= 'subscriberzip = "'.$this->safe($objSubscriber->intSubscriberZip).'",';
    $strQuery .= 'subscribercountry = "'.$this->safe($objSubscriber->strSubscriberCountry).'",';
    $strQuery .= 'subscriberstatus = "'.(int)$this->safe($objSubscriber->intSubscriberStatus).'",';
    $strQuery .= 'udate = "'.$this->safe($objSubscriber->intUDate).'"';
    $strQuery .= ' WHERE subscriberid = '.$this->safe($objSubscriber->intSubscriberId);
    if($this->DBQuery($strQuery)){
       return TRUE;
    }
    else{
       return FALSE;
    }
  }


  /**
  * get all subscribers, or a single subscriber
  * @param $intClientId
  * @param $intSubscriberId
  * @return resource
  */
  function GetSubscribers($intClientId,$intSubscriberId=0){
    $strQuery = 'SELECT * FROM subscribers WHERE clientid ='.$intClientId;
    if((int)$intSubscriberId > 0)
        $strQuery .= ' AND subscriberid = "'.$intSubscriberId.'"';
    if($resResults = $this->DBQuery($strQuery)){
       return $resResults;
    }
    else{
      return FALSE;
    }
  }

  /**
  * get user data by email
  * @param $strEmail
  * @return resource
  */
  function GetUserDataByEmail($strEmail){
    $strQuery = 'SELECT * FROM subscribers';
       $strQuery .= '  WHERE subscriberemail = "'.$this->safe(trim($strEmail)).'"';
    if($resResults = $this->DBQuery($strQuery)){
       return $resResults;
    }
    else{
       //Debug::Debug_er('User Key ['.$strEmail.'] $strQuery ['.$strQuery.'] does not exist in ['.__METHOD__.']',1);
       return FALSE;
    }
  }

  #ENDREGION


  #REGION Subfields

  /**
  * given a user object, insert it
  * @param $objSubField
  * @return last insert ID
  */
  function InsertSubField($objSubField){
    $strQuery = 'INSERT INTO subfields( subfieldtitle,
                                        subfieldtype,
                                        subfieldvalue,
                                        subscriber
                                        ) VALUES("'.$this->safe($objSubField->strSubFieldTitle).'",
                                        "'.$this->safe($objSubField->intSubFieldType).'",
                                        "'.$this->safe($objSubField->strSubFieldValue).'",
                                        "'.$this->safe($objSubField->intSubscriber).'")';
    if($this->DBQuery($strQuery)){
      return $this->getLastInsertID();
    }
    else{
     Debug::Debug_er('Insert FAILED ['.$strQuery.'] ['.__METHOD__.']',1);
     return FALSE;
    }
  }


  /**
  * given a user object, update it
  * @param $objSubField
  * @return bool
  */
  function UpdateSubField($objSubField){
    $strQuery = 'UPDATE subfields SET ';
    $strQuery .= 'subfieldtitle = "'.$this->safe($objSubField->strSubFieldTitle).'" ,';
    $strQuery .= 'subfieldtype = "'.$this->safe($objSubField->intSubFieldType).'",';
    $strQuery .= 'subfieldvalue = "'.$this->safe($objSubField->strSubFieldValue).'"';
    $strQuery .= ' WHERE subfieldid = '.$this->safe($objSubField->intSubFieldId);             
    if($this->DBQuery($strQuery)){
       return TRUE;
    }
    else{
      Debug::Debug_er('Query ['.$strQuery.'] METHOD ['.__METHOD__.'] LINE['.__LINE__.']',1);
       return FALSE;
    }
  }


  /**
  * get all subfields
  * @param $intSubscriberID
  * @param $intSubFieldId
  * @return resource
  */
  function GetSubFields($intSubscriberID,$intSubFieldId=0){
    $strQuery = 'SELECT * FROM subfields WHERE subscriber = '.$intSubscriberID;
    if((int)$intSubFieldId > 0)
        $strQuery .= ' AND subfieldid = "'.$intSubFieldId.'"';
    if($resResults = $this->DBQuery($strQuery)){
       return $resResults;
    }
    else{
      //Debug::Debug_er('Query ['.$strQuery.'] METHOD ['.__METHOD__.'] LINE['.__LINE__.']',1);
      return FALSE;
    }
  }

  #ENDREGION

}//end class
?>