<?php
 /**************************************************************************
 * @CLASS SubscriberCore
 * @brief subscriber data management.
 * @REQUIRES:
 *  -Database.php
 *  -Subscriber.php
 *
 **************************************************************************/
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'Subscriber.php');
class SubscriberCore{
   //store our validation errors
   public $arrValidationErrors;
   public static function Get(){
		//==== instantiate or retrieve singleton ====
		static $inst = NULL;
		if( $inst == NULL )
			$inst = new SubscriberCore();
		return( $inst );
  }

  function __construct(){
    //Start on instantiation
  }

  /**
  * given a subsciber id get and return the resource
  * @param $intClientId - ID of the client record
  * @param $intSubscriberId - ID of the subscriber record
  * @param $boolAsArray - return the value as an array for merging
  * @return object || array of objects
  */
  public static function GetSubscribersData($intClientId,$intSubscriberId=0,$boolAsArray=FALSE){
    if($resSubscribers = Database::Get()->GetSubscribers($intClientId,$intSubscriberId)){
      //CleanQuery returns the first record found if this is set to true
      $boolSingleRecord = ((int)$intSubscriberId > 0)? TRUE:FALSE ;
      //We store this for simplicity in use and potential array return option
      $arrSubscribers = Database::Get()->CleanQuery($resSubscribers,$boolSingleRecord);
      if($boolAsArray)
        return $arrSubscribers;
      if($boolSingleRecord){
        $objSubscriber = new Subscriber();
        return $objSubscriber->LoadObjectWithArray($arrSubscribers);
      }
      //make an array of objects
      $arrSubscriberObjects = array();
      foreach($arrSubscribers as $arrSubscriber){
        $objSubscriber= new Subscriber();
        $arrSubscriberObjects[$arrSubscriber['subscriberid']] = $objSubscriber->LoadObjectWithArray($arrSubscriber);
      }
      //array of subfield objects indexed by subfield mysql table id
      return $arrSubscriberObjects;
    }
   return FALSE;
  }

  /**
  * insert or update a subscriber set
  * @param $objSubscriber
  * @return int ID || FALSE
  */
  function InsertSubscriber($objSubscriber){
   if((int)$objSubscriber->intSubscriberId > 0){
     //update the subscriber
     return Database::Get()->UpdateSubscriber($objSubscriber);
   }
   else{
     return Database::Get()->InsertSubscriber($objSubscriber);
   }
  }


   /**
   * given an email verify it is unique
   * @param $strSubscriberEmail
   * @return bool
   */
   function VerifyUniqueEmail($strEmail){
    if(!Database::Get()->GetUserDataByEmail($strEmail)){
      $strDomain = substr($strEmail, strpos($strEmail, "@") + 1);
      if(!Utility::Get()->GetURLHeaderHTTP($strDomainL)){//check for a valid URL
        $this->arrValidationErrors['duplicate'] = array('duplicate'=> 'This email domain seems to be invalid. Please check it and try again.');
        DisplayMessages::Get()->AddUserMSG( 'This email domain seems to be invalid. Please check it and try again.',1);
        return FALSE;
      }
      return TRUE;
    }
    $this->arrValidationErrors['duplicate'] = array('duplicate'=> ' [This email is invalid, banned, or otherwise unavailable. Please try again.].');
    DisplayMessages::Get()->AddUserMSG( ' [This email is invalid, banned, or otherwise unavailable. Please try again.].',1);
    return FALSE;
   }

  /**
  * given an subscriber object, validate and insert/update it
  * @param $arrPOST
  * @param $arrIgnoreFields
  * @return bool || int (id)
  */
  function CleanAndInsertSubscriber($arrPOST,$arrIgnoreFields=array()){
    /**************************************************************
    ********** set this statically for demo **********************
    **************************************************************/
    $arrPOST['clientid'] = CLIENTID;
    //load our object
    $objSubscriber = new Subscriber();
    $objSubscriber->UpdateObjectWithArray($arrPOST);
    if($objSubscriber->intSubscriberId > 0){
      //get previous record for comparison ( filled with CLIENTID for demo purposes )
      $objPreviousRecord = $this->GetSubscribersData($objSubscriber->intSubscriberClient,$objSubscriber->intSubscriberId);
      //if we're updating, we need to verify the email is unique
      if($objPreviousRecord->strSubscriberEmail != $objSubscriber->strSubscriberEmail){
        if(!$this->VerifyUniqueEmail($objSubscriber->strSubscriberEmail))
          return FALSE;
      }
      //set our udate
      $objSubscriber->intUDate = time();
      $objSubscriber->intEDate = time();
      if($objSubscriber->Validate('update',$arrIgnoreFields)){
        if($this->InsertSubscriber($objSubscriber))
            return $objSubscriber->intSubscriberId;
      }
    }
    else{
      //set our udate
      $objSubscriber->intUDate = time();
      $objSubscriber->intEDate = time();
      if($objSubscriber->Validate('insert',$arrIgnoreFields)){
         if(!$this->VerifyUniqueEmail($objSubscriber->strSubscriberEmail))
           return FALSE;
         if(($objSubscriber->intSubscriberId = $this->InsertSubscriber($objSubscriber))){
           return $objSubscriber->intSubscriberId;
         }
      }
    }
    //store our validation errors locally for accessibility
    $this->arrValidationErrors = $objSubscriber->arrValidationErrors;
    return FALSE;
  }

  /**
  * get all users and form the data array for entry into a bootstrap searchable table
  * @param $arrTableData
  *     -['tabledescription'] = ['tabledescription']
  *     -['tableheader']
  *         -['headerkey'] = ['headername']
  *     -['tabledata'][unique key]
  *         -['headerkey'] = ['value']
  *         -['linkvalue'] = ['linkvalue'] || ['onclickvalue'] = ['onclickvalue']
  * @return array
  */
  function FormSubscriberTableArray(){
    //get our status array
    $arrSubscriberStatus = Utility::Get()->CreateStatusArray();
    /**************************************************************
    ********** set CLIENTID statically for demo **********************
    **************************************************************/
    //get our subscribers
    if(!$arrSubscribers = $this->GetSubscribersData(CLIENTID)){
      DisplayMessages::Get()->AddUserMSG('No Subscribers at this time. <a data-toggle="tab" href="#userupdate">Try This</a>', 1);
      return FALSE;
    }
    //make our primary data arrary
    $arrTableArray = array('tabledescription'=>'Subscribers');
    $arrTableArray['tableheader'] = array('intSubscriberId'=>'ID',
                                          'strSubscriberName'=>'Name',
                                          'strSubscriberEmail'=>'Email',
                                          'intSubscriberStatus'=>'status',
                                          'intEDate'=>'Entry Date');
    //make our data table
    $arrTableArray['tabledata'] = array();
    foreach($arrSubscribers as $objSubscriber){
      //make our subscriber row
      $arrTableArray['tabledata'][$objSubscriber->{'intSubscriberId'}] = array();
      foreach($arrTableArray['tableheader'] as $strKey=>$strValue){
        $arrTableArray['tabledata'][$objSubscriber->{'intSubscriberId'}][$strKey] = array();
        $arrTableArray['tabledata'][$objSubscriber->{'intSubscriberId'}][$strKey]['linkvalue'] = 'Javascript:GetSubscriberUpdateForm('.$objSubscriber->{'intSubscriberId'}.')';
        $arrTableArray['tabledata'][$objSubscriber->{'intSubscriberId'}][$strKey]['tooltip'] = 'Update Subscriber';
        if($strKey == 'intSubscriberStatus'){
          //insert our status instead of its numeric key
          $arrTableArray['tabledata'][$objSubscriber->{'intSubscriberId'}][$strKey]['value'] = $arrSubscriberStatus[$objSubscriber->{$strKey}];


        }
        else if($strKey == 'intEDate'){
          //adjust our entry date to human readable date format
          $arrTableArray['tabledata'][$objSubscriber->{'intSubscriberId'}][$strKey]['value'] = date('m-d-Y',$objSubscriber->{$strKey});
        }
        else{
            $arrTableArray['tabledata'][$objSubscriber->{'intSubscriberId'}][$strKey]['value'] = $objSubscriber->{$strKey};
        }
      }
    }
    //all done, give it back for display
    return $arrTableArray;
  }

}//end class


?>