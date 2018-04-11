<?php
 /**************************************************************************
 * @CLASS InstallTool
 * @brief Install the application o the local server
 * @REQUIRES:
 *  -Database.php
 *
 **************************************************************************/
class InstallTool{            

   public static function Get(){
		//==== instantiate or retrieve singleton ====
		static $inst = NULL;
		if( $inst == NULL )
			$inst = new InstallTool();
		return( $inst );
  }

  function __construct(){
    //Start on instantiation
  }

  /**
  * initiate the installation protocol
  * @return bool
  */
  function InitiateInstall(){          
    DisplayMessages::Get()->AddUserMSG('Begin create tables!', 2);
    if(Database::Get()->CreateSubscriberTable()){
      DisplayMessages::Get()->AddUserMSG('Subscriber table created!', 3);
      //check for next table
      //we'll truncate the fields table here, but without matching subscribers,
      // the relation to subscribers is broken catastrophically
      if(Database::Get()->CreateSubFields()){
        DisplayMessages::Get()->AddUserMSG('Sub-Fields table created!', 3);
        header('Location:'.SERVERADDRESS);
        exit;
      }
      else{
        $strFirstRun = 'Sub-Fields Not table created!';
        if(Database::Get()->DropSubscribersTable())
          $strFirstRun .= ' Also cannot remove subscribers table. It will need to be removed manually.<br />';
        $strFirstRun .= '<a href=index.php?loadtables=true>Try again?</a>';
        DisplayMessages::Get()->AddUserMSG($strFirstRun, 1);
        header('Location:'.SERVERADDRESS);
        exit;
      }
    }
    else{
        //give them our first load message
        Database::Get()->DropSubscribersTable();
        $strFirstRun = 'Subscribers table not created. ';
        $strFirstRun .= '<a href=index.php?loadtables=true>Try again?</a>';
        DisplayMessages::Get()->AddUserMSG($strFirstRun, 1);
        header('Location:'.SERVERADDRESS);
        exit;
    }
  }

}//end class


?>