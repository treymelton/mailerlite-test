<?php
 /**************************************************************************
 * @CLASS SubFieldCore
 * @brief subfield data management.
 * @REQUIRES:
 *  -Database.php
 *  -SubField.php
 *
 **************************************************************************/
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'SubField.php');
class SubFieldCore{

   public static function Get(){
		//==== instantiate or retrieve singleton ====
		static $inst = NULL;
		if( $inst == NULL )
			$inst = new SubFieldCore();
		return( $inst );
  }

  function __construct(){
    //Start on instantiation
  }

  /**
  * given a subsciber id, or a specific subfield value get and return the resource
  * @param $intSubscriberId - ID of the subscriber record
  * @param $intSubFieldId - specific field ID
  * @return object || array of objects
  */
  public static function GetSubFieldsData($intSubscriberId,$intSubFieldId=0){
    if($resSubFields = Database::Get()->GetSubFields($intSubscriberId,$intSubFieldId)){
      //CleanQuery returns the first record found if this is set to true
      $boolSingleRecord = ((int)$intSubFieldId > 0)? TRUE:FALSE ;
      //We store this for simplicity in use and potential array return option
      $arrSubFields = Database::Get()->CleanQuery($resSubFields,$boolSingleRecord);
      if($boolSingleRecord){
        $objSubField = new SubField();
        return $objSubField->LoadObjectWithArray($arrSubFields);
      }
      //make an array of objects
      $arrSubFieldObjects = array();
      foreach($arrSubFields as $arrSubField){
        $objSubField= new SubField();
        $arrSubFieldObjects[$arrSubField['subfieldid']] = $objSubField->LoadObjectWithArray($arrSubField);
      }
      //array of subfield objects indexed by subfield mysql table id
      return $arrSubFieldObjects;
    }
   return FALSE;
  }

  /**
  * insert or update a subfield set
  * @param $objSubField
  * @return int ID || FALSE
  */
  function InsertSubField($objSubField){
   if((int)$objSubField->intSubFieldId > 0){
     //update the subfield
     return Database::Get()->UpdateSubField($objSubField);
   }
   else{
     return Database::Get()->InsertSubField($objSubField);
   }
  }



  /**
  * given an post form: validate and insert/update it
  * @param $arrPOST
  * @param $arrIgnoreFields
  * @return bool || int (id)
  */
  function CleanAndInsertSubField($arrPOST,$arrIgnoreFields=array()){
    //load our object
    $objSubField = new SubField();
    $objSubField->UpdateObjectWithArray($arrPOST);
    //see if we're updating or not
    if($objSubField->intSubFieldId > 0){
      if($objSubField->Validate('update',$arrIgnoreFields)){
        if($this->InsertSubField($objSubField))
            return $objSubField->intSubFieldId;
      }
    }
    else{
      if($objSubField->Validate('insert',$arrIgnoreFields)){
         if(($objSubField->intSubFieldId = $this->InsertSubField($objSubField))){
           return $objSubField->intSubFieldId;
         }
      }
    }
    return FALSE;
  }

}//end class


?>