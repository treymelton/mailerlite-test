<?php
/**************************************************
* Class :Subscriber
 * @brief Well-formed object declaration of the 'Subscriber' database table.
 * @Requires:
 *  -BaseClass.php
 *  -Debug.php
 *  -DisplayMessages.php
*
***************************************************/
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'BaseClass.php');


/**
 * @class Subscriber
 */
class Subscriber  extends BaseClass
{
    public $intSubscriberId;// int(11) NOT NULL AUTO_INCREMENT
    public $intSubscriberClient;// int(9) NOT NULL
    public $strSubscriberName;// varchar(25) NOT NULL
    public $strSubscriberEmail;// varchar(65) NOT NULL
    public $strSubscriberState;// char(2) DEFAULT NULL
    public $intSubscriberZip;// int(5) DEFAULT NULL
    public $strSubscriberCountry;// char(2) DEFAULT NULL
    public $intSubscriberStatus;// int(1) NOT NULL
    public $intEDate;// int(12) NOT NULL
    public $intUDate;// int(12) NOT NULL
    //hold validation errors
    public $arrValidationErrors;

  function __construct(){
  //construct
  }


  public static function Get(){
    //==== instantiate or retrieve singleton ====
    static $inst = NULL;
    if( $inst == NULL )
      $inst = new Subscriber();
    return( $inst );
   }

  function Validate($action = "select", $ignore_arr = null)
  {
    $err_arr = NULL;
    $var_arr = Array();

    $var_arr['subscriberid']['type'] =      'key';
    $var_arr['clientid']['type'] =          'key';
    $var_arr['subscribername'] =            Array('type' => 'string', 'min' => 1, 'max' => 25);
    $var_arr['subscriberemail'] =           Array('type' => 'email', 'min' => 5, 'max' => 255);
    $var_arr['subscriberstate'] =           Array('type' => 'string', 'min' => 0, 'max' => 2);
    $var_arr['subscriberzip']['type'] =     'int';//Array('type' => 'int', 'min' => 0, 'max' => 5);
    $var_arr['subscribercountry'] =         Array('type' => 'string', 'min' => 0, 'max' => 2);
    $var_arr['subscriberstatus']['type'] =  Array('type' => 'int', 'min' => 0, 'max' => 5);//'int';
    $var_arr['edate'] =                     Array('type' => 'int', 'min' => 11, 'max' => 12);
    $var_arr['udate'] =                     Array('type' => 'int', 'min' => 11, 'max' => 12);

    switch($action)
    {
      case 'update':
      case 'select':
        $err_arr = $this->checkTypes($var_arr, $ignore_arr);
        break;
      case 'insert':
        // id will never be checked on insertion
        $ignore_arr['subscriberid'] = true;
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
    $this->intSubscriberId = (int) $arrArray['subscriberid'];
    $this->intSubscriberClient = (int) $arrArray['clientid'];
    $this->strSubscriberName = (string) $arrArray['subscribername'];
    $this->strSubscriberEmail = (string) $arrArray['subscriberemail'];
    $this->strSubscriberState = (string) $arrArray['subscriberstate'];
    $this->intSubscriberZip = (int) $arrArray['subscriberzip'];
    $this->strSubscriberCountry = (string) $arrArray['subscribercountry'];
    $this->intSubscriberStatus = (int) $arrArray['subscriberstatus'];
    $this->intEDate = (int) $arrArray['edate'];
    $this->intUDate = (int) $arrArray['udate'];
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
    if(!empty($arrObjectData['subscriberid']))
      $this->intSubscriberId = (int) $arrObjectData['subscriberid'];
    if(!empty($arrObjectData['clientid']))
      $this->intSubscriberClient = (int) $arrObjectData['clientid'];
    if(!empty($arrObjectData['subscribername']))
      $this->strSubscriberName = (string) $arrObjectData['subscribername'];
    if(!empty($arrObjectData['subscriberemail']))
      $this->strSubscriberEmail = (string) $arrObjectData['subscriberemail'];
    if(!empty($arrObjectData['subscriberstate']))
      $this->strSubscriberState = (string) $arrObjectData['subscriberstate'];
    if(!empty($arrObjectData['subscriberzip']))
      $this->intSubscriberZip = (int) $arrObjectData['subscriberzip'];
    if(!empty($arrObjectData['subscribercountry']))
      $this->strSubscriberCountry = (string) $arrObjectData['subscribercountry'];
    if(!empty($arrObjectData['subscriberstatus']))
      $this->intSubscriberStatus = (int) $arrObjectData['subscriberstatus'];
    if(!empty($arrObjectData['edate']))
      $this->intEDate = (int) $arrObjectData['edate'];
    if(!empty($arrObjectData['udate']))
      $this->intUDate = (int) $arrObjectData['udate'];
    return TRUE;
  }

  /*
  @brief load an array with the Subscriber object
  @param $objSubscriber
  @return array(Subscriber)
  */
  public function LoadArrayWithObject(){
   $arrArray = array();
   (int) $arrArray['subscriberid'] = $this->intSubscriberId;
   (int)$arrArray['clientid'] = $this->intSubscriberClient ;
   (string) $arrArray['subscribername'] = $this->strSubscriberName;
   (string) $arrArray['subscriberemail'] = $this->strSubscriberEmail;
   (string) $arrArray['subscriberstate'] = $this->strSubscriberState;
   (int) $arrArray['subscriberzip'] = $this->intSubscriberZip;
   (string) $arrArray['subscribercountry'] = $this->strSubscriberCountry;
   (int) $arrArray['subscriberstatus'] = $this->intSubscriberStatus;
   (int) $arrArray['edate'] = $this->intEDate;
   (int) $arrArray['udate'] = $this->intUDate;
   return $arrArray;
  }

}//end class Subscriber
?>