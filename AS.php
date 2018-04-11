<?php
require_once('CodeHeader.php');
require_once('includes/Element.php');
require_once('includes/AJAXCore.php');
class AjaxC EXTENDS AJAXCore{
  var $strRequestAction = FALSE;
  var $arrValues = FALSE;
  function __construct(){
     /*
     */
  }

  /**
  * primary handler for execution processing
  * @return string    UpdateAdminSubscriber($arrPOST)
  */
  function AjaxCall(){
     if($this->strRequestAction == 'subscribermodify')
        return $this->ModifySubscriber($this->arrValues);
     if($this->strRequestAction == 'subscriberupdate')
        return $this->GetSubscriberUpdateForm($this->arrValues['subscriberid']);
     if($this->strRequestAction == 'postsubscriberupdate')
        return $this->UpdateAdminSubscriber($this->arrValues);
     if($this->strRequestAction == 'getmetabox')
        return $this->GetBlankMetaBox();
  }
}


  $objAjaxCall = new AjaxC();
  $arrValues = filter_var_array($_REQUEST,FILTER_SANITIZE_STRING);
  if(array_key_exists('payload',$arrValues) && trim($arrValues['payload']) != ''){
    $strPayLoad = htmlspecialchars_decode($arrValues['payload']);
    $objAjaxCall->arrValues = json_decode( stripslashes($strPayLoad), TRUE );
    unset($objAjaxCall->arrValues['cash']);
    if(array_key_exists('dir',$objAjaxCall->arrValues) && $objAjaxCall->arrValues['dir'] != ""){
      $objAjaxCall->strRequestAction = $objAjaxCall->arrValues['dir'];
      $caller = $objAjaxCall->AjaxCall();
      if($caller){
          echo $caller;
          exit;
      }
      else{
          echo "0aleNo method return for the request [".$objAjaxCall->strRequestAction."]. ";
          exit;
      }
    }
    else{
      $strRequest = var_export($objAjaxCall->arrValues,TRUE);
      $strValues = var_export($arrValues,TRUE);
      Debug::Debug_er('$objAjaxCall->arrValues ['.$strRequest.'] $strPayLoad ['.$strPayLoad.'] $strValues ['.$strValues.']'.__LINE__,1);
      echo "0aleNo method return for the request [".$objAjaxCall->strRequestAction."] [".__LINE__."]";
      exit;
    }
  }