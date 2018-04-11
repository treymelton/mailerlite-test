<?php
require_once('..'.DIRECTORY_SEPARATOR.'CodeHeader.php');
/*****************************************************************
* basic API endpoint script. More sophisticated checks and structure are in
* order for more complex operations
*****************************************************************/
$strPayload = file_get_contents("php://input");
$arrDataPayload = Utility::Get()->JSONDecode($strPayload);
$arrPOST = filter_var_array($arrDataPayload,FILTER_SANITIZE_STRING);
//verify our token and secret
if(CLIENTTOKEN != $arrPOST['token'] || CLIENTSECRET != $arrPOST['secret'] ){
  $arrResults['result'] = 0;
  $arrResults['message'] = 'Cannot validate communication.';
  echo Utility::Get()->JSONEncode($arrResults);
}
else{
  $arrPOST['clientid'] = CLIENTID;
}
$arrResults = array();
$arrIgnoreVars = array('subscriberstate'=>1,'subscriberzip'=>1,'subscribercountry'=>1);
//typecast this to avoid string validation issues
(int)$arrPOST['subscriberstatus'] = (int)$arrPOST['subscriberstatus'];
if($intSubscriberId = SubscriberCore::Get()->CleanAndInsertSubscriber($arrPOST,$arrIgnoreVars)){
  $arrResults['result'] = 1;
  $arrResults['message'] = 'Subscriber Inserted!';
  //let's load our meta now
  if(array_key_exists('metaid[]',$arrPOST) && sizeof($arrPOST['metaid[]']) > 0){
    //let's go through the attributes and load new ones
    foreach($arrPOST['metaid[]'] as $strNewKey){
        //we have three available fields to fill
        $arrSubField = array('subfieldtitle'=>$arrPOST['title_'.$strNewKey],
                             'subfieldtype'=>$arrPOST['type_'.$strNewKey],
                             'subfieldvalue'=>$arrPOST['value_'.$strNewKey],
                             'subscriber'=>$intSubscriberId);
        if(substr($strNewKey,0,3) != 'new')
          $arrSubField['subfieldid'] = $strNewKey;
        //insert it now
        SubFieldCore::Get()->CleanAndInsertSubField($arrSubField);
    }
  }
}
else{
  $arrResults['result'] = 0;
  $arrResults['message'] = var_export(SubscriberCore::Get()->arrValidationErrors,TRUE);
}
echo Utility::Get()->JSONEncode($arrResults);
?>