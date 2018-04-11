<?php
/**
 * Debug class, basically for handling of exceptions and user-error messages.
 */
class Debug
{
  /**
   * Does nothing, intentionally.
   *
   * @access public
   * @param  void
   * @return void
   */
  public function __construct() {} // end __construct()

  /**
   * Does nothing, intentionally.
   *
   * @access public
   * @param  void
   * @return void
   */
  public function __destruct() {} // end __destruct()

  	/**
	 * Given an error message and a debug level, perform the action on the message
	 * as indicated by the level.
	 *
	 * Valid debug levels (NOT DEBUG_ARG):
	 *   0 = off
	 *   1 = write to log
	 *   2 = write to log and email
	 *   3 = email only
	 *
	 * @access public
	 * @param  string $err  Error Message
	 * @param  int $s       Debug Level
	 * @return void
	 */
	public static function Debug_er($err,$s,$boolBacKTrace = FALSE,$objShowObjectMembers = FALSE)
	{
        $strBackTrace = '';
        $strObjectMembers = '';
        $err .= self::GetMemoryUsage();
        if($boolBacKTrace)
            $strBackTrace = self::FormBackTrace(FALSE);
        if($objShowObjectMembers)
            $strObjectMembers = self::LoadObjectVariables($objShowObjectMembers,FALSE);
        if((int)$s > 0){
    		if(DEBUG_ARG != 1)
    			return;

    		if($s == 1 || $s == 2){
    			Debug::Debug_log($strBackTrace.$err.$strObjectMembers,$s);
            }
    		if($s == 2 || $s == 3)
    		{
    			/**
                 * @internal
    			 * the previous if statement already handles adding to the debug log;
    			 * this should only be concerned with whether the error should be emailed.
    			 */
    			Debug::Send_Mail(ADMIN,"Query Error ",$strBackTrace.$err.$strObjectMembers);
    		}
      }
	} // end Debug_er()


    /**
    * gather the memory usage for this moment and append it to the log
    * @return string
    */
    public static function GetMemoryUsage(){
     $strUsage = "\r\n";
     $intPHPMemory = memory_get_usage(TRUE);
     //add memory usage
      $strUsage .= "memory_get_usage [";
     if ($intPHPMemory < 1024)
      $strUsage .= $intPHPMemory." Bytes";
     elseif ($intPHPMemory < 1048576)
      $strUsage .= round($intPHPMemory/1024,2)." KB";
     else
      $strUsage .= round($intPHPMemory/1048576,2)." MB";
     $strUsage .= "]\r\n";
     $strUsage .= "memory_get_peak_usage [";
     //peak memory
     $intPHPPeakMemory = memory_get_peak_usage (TRUE);
     if ($intPHPPeakMemory < 1024)
       $strUsage .= $intPHPPeakMemory." Bytes";
     elseif ($intPHPPeakMemory < 1048576)
       $strUsage .= round($intPHPPeakMemory/1024,2)." KB";
     else
       $strUsage .= round($intPHPPeakMemory/1048576,2)." MB";
     $strUsage .= "]\r\n";
     return $strUsage;
    }

        //form the backtrace
  public static function FormBackTrace($boolForDisplay = FALSE){
    $arrBackTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    $strBreak = "\r\n";
    $strArguments = '';
    $strBackTrace = '';
    if($boolForDisplay)
      $strBreak = '<br />';
    foreach($arrBackTrace as $ka=>$va){
      $strBackTraceFile = 'File ['.$va['file'].']'.$strBreak;
      if(array_key_exists('args',$va) && is_array($va['args'])){
        foreach($va['args'] as $kb=>$vb){
          if(!is_object($vb))
            $strArguments .= '<pre>'.$vb.'</pre>,';
          else{
            $strObjectVariables = var_export($vb,TRUE);
            $strArguments .= '[OBJECT]'.$strBreak.'<pre>'.$strObjectVariables.'</pre>,'.$strBreak;
          }
        }
      }
      $strBackTrace .= 'Line ['.$va['line'].'] '.$va['class'].'->'.$va['function'].'('.$strArguments.')'.$strBreak;
    }
    return $strBackTraceFile.$strBackTrace;

  }

   public static function LoadObjectVariables($objShowObjectMembers,$boolForDisplay = TRUE){
      $strReturn = '';
        $strObjectVariables = var_export($objShowObjectMembers,TRUE);
      if($boolForDisplay){
        $strReturn .= '<pre>'.$strObjectVariables.'</pre>';
      }
      else{
        $strReturn = $strObjectVariables;
      }
      return $strReturn;
    }

    
  /**
   * Given an error string and a debug level, attempt to open the debug log and
   * append the error string with a timestamp. If the log cannot be written to,
   * send an email to the administrator notifying him of the problem and the
   * error message that was supposed to be written.
   *
   * @access public
   * @param  string $strErrorMessage   Error Message
   * @return bool
   */
  public static function Debug_log($strErrorMessage)
  {
    // switched from m_d_Y to Y_m_d to match string cardinality with date progression
    $handle = (SERVERPATH.DIRECTORY_SEPARATOR.'Logs'.DIRECTORY_SEPARATOR.'DBGLOG_'.date('Y_m_d',time()).'.txt');
    $arrLastError = error_get_last();
    $strLastError = '';
    if(sizeof($arrLastError) > 0)
      $strLastError = var_export($arrLastError,TRUE);
      //check our file for existence
    if(is_file($handle))
      $fh = fopen($handle,'a');
    else
      $fh = fopen($handle,'a+');
    //verify our handle is valid
    if($fh)
    {
      fwrite($fh,"\r\n----------------------[".date('r')."]:----------------------\r\n ".
                 "[".$_SERVER['SCRIPT_NAME']."]: " . $strErrorMessage . "\r\n".
                 '$strLastError ['.$strLastError.']'." \r\n");
      fclose($fh);
    }
    else
    {
      $error = "An error has occured, and the log cannot be written to.";
      if(Debug::Send_Mail(ADMIN,"Query Error ",($error."<br />".$strErrorMessage.'<br />$strLastError ['.$strLastError.']'." \r\n".$handle)))
        ; // do nothing on success
    }  
    return TRUE;
  } // end Debug_log()

  /**
   * Trivial e-mail function.
   * @todo get rid of both this e-mail function and the one in DB2, make into a proper class
   *
   * @access public
   * @param  string $to     E-mail address of recipient
   * @param  string $sub    Subject of the e-mail
   * @param  string $mess   Message body
   * @return bool
   */
  public static function Send_Mail($to,$sub,$mess)
  {
    $headers  = 'MIME-Version: 1.0' . "\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
    $headers .= 'To: '.$to.' <'.$to.'>' . "\n";
    $headers .= 'From: '.SITENAME.'< '.SUPPORT.' >' . "\n";
    if(mail($to,$sub,$mess,$headers))
      return true;
    else
      return false;
  } // end Send_Mail()

} // end class Debug

?>