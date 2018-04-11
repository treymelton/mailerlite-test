<?php
/**************************************************
* Class :SubField
 * @brief Well-formed object declaration of the 'SubField' database table.
*
***************************************************/
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'BaseClass.php');


/**
 * @class SubField
 */
class SubField  extends BaseClass
{
    public $intSubFieldId;// int(11) NOT NULL AUTO_INCREMENT
    public $strSubFieldTitle;// varchar(25) NOT NULL
    public $intSubFieldType;// int(1) NOT NULL
    public $strSubFieldValue;// varchar(255) NOT NULL
    public $intSubscriber;// int(11) NOT NULL
    //hold validation errors
    public $arrValidationErrors;

  function __construct(){
  //construct
  }


  public static function Get(){
    //==== instantiate or retrieve singleton ====
    static $inst = NULL;
    if( $inst == NULL )
      $inst = new SubField();
    return( $inst );
   }

  function Validate($action = "select", $ignore_arr = null)
  {
    $err_arr = NULL;
    $var_arr = Array();

    $var_arr['subfieldid']['type'] =     'key';
    $var_arr['subfieldtitle'] =          Array('type' => 'string', 'min' => 1, 'max' => 25);
    $var_arr['subfieldtype']['type'] =   'int';
    $var_arr['subfieldvalue'] =          Array('type' => 'string', 'min' => 1, 'max' => 255);
    $var_arr['subscriber']['type'] =     'key';

    switch($action)
    {
      case 'update':
      case 'select':
        $err_arr = $this->checkTypes($var_arr, $ignore_arr);
        break;
      case 'insert':
        // id will never be checked on insertion
        $ignore_arr['subfieldid'] = true;
        $err_arr = $this->checkTypes($var_arr, $ignore_arr);
        break;
      case 'empty':
      default:
        break;
    }
    if(!is_array($err_arr))
    {
      return TRUE;
    }
    else
    {
      $this->arrValidationErrors = $err_arr;
      $strErrors = var_export($err_arr,TRUE);
      Debug::Debug_er(__CLASS__." class instance failed validation due to following errors: ".$strErrors, DEBUG_LEVEL);
      foreach($err_arr as $strType=>$arrError){
        $strError = var_export($arrError,TRUE);
        DisplayMessages::Get()->AddUserMSG(($strType.' - '.$strError)) ;
      }
      return FALSE;
    }
  }


  /**
  * load local member variables with an array
  * @return $this
  */
  public function LoadObjectWithArray($arrArray){
    $this->intSubFieldId = (int) $arrArray['subfieldid'];
    $this->strSubFieldTitle = (string) $arrArray['subfieldtitle'];
    $this->intSubFieldType = (int) $arrArray['subfieldtype'];
    $this->strSubFieldValue = (string) $arrArray['subfieldvalue'];
    $this->intSubscriber = (int) $arrArray['subscriber'];
    return $this;
  }

  /**
  * given an array sent in JSON format, rehydrate the object
  * @param $arrObject
  * @return $this
  */
  public function LoadObjectWithArrayObject($arrArray){
    foreach($arrArray as $varKey=>$varValue){
      if(property_exists($this,$varKey))
          $this->{$varKey} = $varValue;
    }
    return $this;
  }

  /**
  * update an object with an object
  * @param $objUpdatingObject
  * @return bool
  */
  function UpdateObjectWithObject($objUpdatingObject){
   $arrObjectVars = get_object_vars($objUpdatingObject);
   foreach($arrObjectVars as $strName => $varValue)
      $this->$strName = $varValue;
   return TRUE;
  }

  /**
  * given an array of table data, check for updates
  * @param $arrObjectData
  * return bool
  */
  function UpdateObjectWithArray($arrObjectData){
    if(!empty($arrObjectData['subfieldid']))
      $this->intSubFieldId = (int) $arrObjectData['subfieldid'];
    if(!empty($arrObjectData['subfieldtitle']))
      $this->strSubFieldTitle = (string) $arrObjectData['subfieldtitle'];
    if(!empty($arrObjectData['subfieldtype']))
      $this->intSubFieldType = (int) $arrObjectData['subfieldtype'];
    if(!empty($arrObjectData['subfieldvalue']))
      $this->strSubFieldValue = (string) $arrObjectData['subfieldvalue'];
    if(!empty($arrObjectData['subscriber']))
      $this->intSubscriber = (int) $arrObjectData['subscriber'];
    return TRUE;
  }

  /*
  @brief load an array with the SubField object
  @param $objSubField
  @return array(SubField)
  */
  public function LoadArrayWithObject(){
   $arrArray = array();
   (int) $arrArray['subfieldid'] = $this->intSubFieldId;
   (string) $arrArray['subfieldtitle'] = $this->strSubFieldTitle;
   (int) $arrArray['subfieldtype'] = $this->intSubFieldType;
   (string) $arrArray['subfieldvalue'] = $this->strSubFieldValue;
   (int) $arrArray['subscriber'] = $this->intSubscriber;
   return $arrArray;
  }

}//end class SubField
?>