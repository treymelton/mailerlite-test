<?php
session_start();
error_reporting (E_ALL); // ^ E_WARNING ^ E_PARSE ^ E_COMPILE_ERROR ^ E_NOTICE
ini_set('log_errors', 1);
ini_set('display_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/Logs/error.log');
header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

/*******************************************************************************
********************************** CORE MODULES ********************************
********************************************************************************/

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'Constants.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'Utility.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'Database.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'Element.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'Abstraction.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'DisplayMessages.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'InstallTool.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'SubscriberCore.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'SubFieldCore.php');