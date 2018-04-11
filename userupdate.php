<?php
require_once('CodeHeader.php');
$arrPOST = filter_var_array($_POST,FILTER_SANITIZE_STRING);

$arrResults = array();
$arrIgnoreVars = array('subscriberstate'=>1,'subscriberzip'=>1,'subscribercountry'=>1);
//typecast this to avoid string validation issues
(int)$arrPOST['subscriberstatus'] = (int)$arrPOST['subscriberstatus'];

if(SubscriberCore::Get()->CleanAndInsertSubscriber($arrPOST,$arrIgnoreVars))
  DisplayMessages::Get()->AddUserMSG('Subscriber Inserted!', 3);
else
  DisplayMessages::Get()->AddUserMSG('Subscriber NOT Inserted!', 1);
//reload our posted data for repopulation
$_SESSION['POSTDATA'] = $arrPOST;
//send them back to our API page
header('Location: '.SERVERADDRESS);
?>