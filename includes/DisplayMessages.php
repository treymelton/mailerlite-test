<?php
 /**************************************************************************
 * @CLASS DisplayMessages
 * @brief General display message handling.
 * @REQUIRES:
 *  -none
 *
 **************************************************************************/
class DisplayMessages{
   public $intReportcount = 0;//message count

   public static function Get(){
		//==== instantiate or retrieve singleton ====
		static $inst = NULL;
		if( $inst == NULL )
			$inst = new DisplayMessages();
		return( $inst );
  }

  function __construct(){
    //Start on instantiation
  }


  /**
  * add a message to the general display array
  * @param $strMessage - message to be added to the array
  * @param $intType - type of message for labeling
  * @return bool
  */
  function AddUserMSG($strMessage, $intType = 1) {
  	if (array_key_exists('msgs', $_SESSION) && is_array($_SESSION['msgs']))
  		$_SESSION['msgs'][] = array($strMessage, $intType);
  	else {
  		$_SESSION['msgs'] = array();
  		$_SESSION['msgs'][] = array($strMessage, $intType);
  	}
  }

  /**
  * clear user messages
  * @return bool
  */
  function ClearDisplayMSGs() {
  	$_SESSION['msgs'] = array();
    return TRUE;
  }

  /**
  * get display messages from session
  * @return string ( messages )
  */
  function GetDisplayMSGs() {
  	$arrRepeatErrors = array();
  	$arrMesseges = '';
  	if (array_key_exists('msgs', $_SESSION) && sizeof($_SESSION['msgs']) > 0) {
  		foreach ($_SESSION['msgs'] as $intCount => $arrMessage) {
  			if (!in_array($arrMessage[0], $arrRepeatErrors)) {
  				$arrMesseges .= $this->ReportToUser($arrMessage[1],
                                                    '[' . ($intCount + 1) . ']' . $arrMessage[0]);
  				//lets add it to the array now that it's stored.
  				$arrRepeatErrors[] = $arrMessage[0];
  			}
  		}
  	}
    //clean up now
    $this->ClearDisplayMSGs();
    //give it back
    return $arrMesseges;
  }

  function ReportToUser($intMessageType, $strMessage) {
  	//type 1 == Error
  	//type 2 == Info
  	//type 3 == Success
  	//type 4 == System
  	$arrMessageTypes = array();
  	$arrMessageTypes[1] = 'Error';
  	$arrMessageTypes[2] = 'Info';
  	$arrMessageTypes[3] = 'Success';
  	$arrMessageTypes[4] = 'System';
    //set our message variable
    $strMessageOut = '';
  	if ($intMessageType == 1)
  	  $strMessageOut .= '<div class="alert alert-danger" >
  			<p> ' . $strMessage . '</p><div class="btn btn-danger pull-right" onclick="CloseParentBox(this);" id="close' . $this->intReportcount . '">Close</div><hr>
  		</div>';
  	if ($intMessageType == 2)
  	  $strMessageOut .= '<div class="alert alert-warning">
  			<p> ' . $strMessage . '</p><div class="btn btn-danger pull-right" onclick="CloseParentBox(this);" id="close' . $this->intReportcount . '">Close</div><hr>
  		</div>';
  	if ($intMessageType == 3)
  	  $strMessageOut .= '<div class="alert alert-success">
  			<p> ' . $strMessage . '</p><div class="btn btn-danger pull-right" onclick="CloseParentBox(this);" id="close' . $this->intReportcount . '">Close</div><hr>
  		</div>';
  	if ($intMessageType == 4)
  	  $strMessageOut .= '<div class="bg-primary text-primary">
  			<p> ' . $strMessage . '</p><div class="btn btn-danger pull-right" onclick="CloseParentBox(this);" id="close' . $this->intReportcount . '">Close</div><hr>
  		</div>';
  	$this->intReportcount++;
  	return $strMessageOut;
  }
}//end class


?>