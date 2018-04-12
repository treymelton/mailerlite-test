<?php
 /**************************************************************************
 * @CLASS AJAXCore
 * @brief Handle all Ajax related functions. This could get rather large
 * due to the unforeseeable
 * @REQUIRES:
 *  -SubscriberCore.php
 *  -SubFieldCore.php
 *  -Element.php
 *
 **************************************************************************/

class AJAXCore{

   public static function Get(){
		//==== instantiate or retrieve singleton ====
		static $inst = NULL;
		if( $inst == NULL )
			$inst = new AJAXCore();
		return( $inst );
  }

  function __construct(){
    //Start on instantiation
  }

  /**
  * before giving a form back, wrap it in a basic CSS wrapper
  * @param $strHTMLCollection collection of form or loose HTML elements
  * @param $strTitle
  * @param $strHeading
  * @return $strHTML
  */
  function MakePopUpFormContainer($strHTMLCollection,$strTitle='',$strHeading=''){
    $objElement = new Element();
    $objPrimaryTable = $objElement->LoadHTMLTemplate('<div class="popup" data-popup="popup-1" id="popupcontent"></div>');
    $objPopupInner = $objElement->AddChildNode($objPrimaryTable,'','div',array('class'=>'popup-inner'));
    if($strTitle != '')
      //add the heading
      $objElement->AddChildNode($objPopupInner,$strTitle,'h1',array('class'=>'page-header pcmt_h1'));
    if($strHeading != '')
      //add the lead
    $objElement->AddChildNode($objPopupInner,$strHeading,'p',array('class'=>'lead pcmt_p'));
    //insert the main data
    $objHTMLContainer = $objElement->AddChildNode($objPopupInner,'','div',array('class'=>'scrollbox'));
    $objElement->AddChildNode($objHTMLContainer,$strHTMLCollection,'div',array());
    //make the close button
    $objCloseButton = $objElement->AddChildNode($objPopupInner,'','p',array());
    $objElement->AddChildNode($objCloseButton,'Close','a',array("data-popup-close"=>"popup-1","href"=>"#",'onclick'=>"CloseDataPopUp('popup-1');"));
    $objElement->AddChildNode($objPopupInner,'X','a',array( "class"=>"popup-close","data-popup-close"=>"popup-1","href"=>"#",'onclick'=>"CloseDataPopUp('popup-1');"));
    return $objElement->CloseDocument();
  }

  /**
  * given a subscriber data set, add the record and return the results
  * @param $arrData
  * @return string
  */
  function ModifySubscriber($arrData){
    //set our token and handshake semi-statically since we don't know where we are or what is available
    $arrData['token'] = CLIENTTOKEN;
    $arrData['secret'] = CLIENTSECRET;
    $arrData['clientid'] = CLIENTID;

    $arrResults = Utility::Get()->MakeQuickCURL(APIENDPATH,$arrData);
    $arrSubscriberData = Utility::Get()->JSONDecode($arrResults['result']);
    if($arrSubscriberData['result'] > 0){
        $strSubscriberData = 'Subscriber Inserted!';
        DisplayMessages::Get()->AddUserMSG($strSubscriberData, 3);
    }
    else{
        $strSubscriberData = 'Subscriber NOT Inserted!';
        $strSubscriberData .= '<pre>';
        $strSubscriberData .= var_export($arrSubscriberData,TRUE);
        $strSubscriberData .= '</pre>';
        DisplayMessages::Get()->AddUserMSG($strSubscriberData, 1);
    }
    return '1frm'.$this->MakePopUpFormContainer(DisplayMessages::Get()->GetDisplayMSGs());
  }

  /**
  * take an ajax request from the local server
  * @param $arrPOST
  * @return string ( HTML )
  */
  function UpdateAdminSubscriber($arrPOST){
    $arrResults = array();
    $arrIgnoreVars = array('subscriberstate'=>1,'subscriberzip'=>1,'subscribercountry'=>1);
    //typecast this to avoid string validation issues
    (int)$arrPOST['subscriberstatus'] = (int)$arrPOST['subscriberstatus'];
    $arrPOST['clientid'] = CLIENTID;
    if($intSubscriberId = SubscriberCore::Get()->CleanAndInsertSubscriber($arrPOST,$arrIgnoreVars)){
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
      return '1frm'.$this->MakePopUpFormContainer('Subscriber Inserted! <a href="Javascript:window.location.reload();">Reload</a>');
    }
    else{
      return '1frm'.$this->MakePopUpFormContainer('Could not insert subscriber ['.DisplayMessages::Get()->GetDisplayMSGs().']');
    }
  }

  /**
  * given a subscriber ID, get the update form
  * @param $intSubscriberId
  * @return string ( HTML )
  */
  function GetSubscriberUpdateForm($intSubscriberId){
    $strForm = '<form action="" method="post" name="updatesubscriber">';
    $arrPOST = SubscriberCore::Get()->GetSubscribersData(CLIENTID,$intSubscriberId,TRUE);
    include(SERVERPATH.DIRECTORY_SEPARATOR.'forms'.DIRECTORY_SEPARATOR.'subscriberform.php');
    $strForm .= $strSubscriberForm;
    //get our meta fields
    if($arrSubFields = SubFieldCore::Get()->GetSubFieldsData($intSubscriberId)){
      foreach($arrSubFields as $objSubField){
          $strForm .= Abstraction::Get()->GetMetaBox($objSubField);
      }
    }
    //add our processing variables
    $strForm .= '<button class="btn btn-success" type="button" onclick="GetMetaBox(this.form);">Add Meta Value</button>';
    $strForm .= '<input type="hidden" name="dir" value="postsubscriberupdate" />';
    $strForm .= '<input type="hidden" name="subscriberid" value="'.$arrPOST['subscriberid'].'" />';
    $strForm .= '<button type="button" onclick="SubmitSelectedForm(this.form)" class="btn btn-primary" >Update!</button>';
    $strForm .= '</form>';
    return '1frm'.$this->MakePopUpFormContainer($strForm);
  }

  /**
  * get a new meta box entry
  * @return string ( HTML )
  */
  function GetBlankMetaBox(){
    $strMetaBox = Abstraction::Get()->GetMetaBox(FALSE);
    return '1apd'.$strMetaBox;
  }

}//end class


?>