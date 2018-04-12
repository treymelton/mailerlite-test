<?php
 /**************************************************************************
 * @CLASS Abstraction
 * @brief Abstract functions
 * @REQUIRES:
 *  -Database.php
 *
 **************************************************************************/
class Abstraction{
  //hold our table ID for local use
   public $strBootstrapTableId = 0;
   public static function Get(){
    //==== instantiate or retrieve singleton ====
    static $inst = NULL;
    if( $inst == NULL )
      $inst = new Abstraction();
    return( $inst );
  }

  function __construct(){
    //Start on instantiation
  }

  /**
  * validate our first run
  * @return bool
  */
  function ValidateFirstRun(){
    if(DB_NAME == ''){
      DisplayMessages::Get()->AddUserMSG('DB_NAME constant not set. Please set it to install tables.', 1);
      return FALSE;
    }
    if(DB_USER == ''){
      DisplayMessages::Get()->AddUserMSG('DB_USER constant not set. Please set it to install tables.', 1);
      return FALSE;
    }
    if(DB_PASS == ''){
      DisplayMessages::Get()->AddUserMSG('DB_PASS constant not set. Please set it to install tables.', 1);
      return FALSE;
    }
    //make sure our query runs
    return Database::Get()->VerifyRequiredTables();
  }

  /**
  * given a name/value pair array create select box options
  * @param $arrDataSet - data we intend to populate the options with
  * @param $varPreviousOption - the previous option selected by record or $_POST
  * @param $strOptionClass - optional CSS 
  * @return string ( HTML )
  */
  function MakeSimpleDropDownOptions($arrDataSet,$varPreviousOption,$strOptionClass=''){
    $strOptions = '';
    foreach($arrDataSet as $varKey=>$varValue){
        $strSelected = ($varPreviousOption == $varKey)? ' SELECTED ':'' ;
        $strOptions .= '<option value="'.$varKey.'" class="'.$strOptionClass.'" '.$strSelected.' >'.$varValue.'</option>';
    }
    return $strOptions;
  }

  /**
  * gather POST and subscriber data from POST submission
  * @return array ( cleaned POST and subscriber array )
  */
  function LoadPostSubscriberData(){
    if(array_key_exists('POSTDATA',$_SESSION) && sizeof($_SESSION['POSTDATA']) > 0){
      $arrPOST = $_SESSION['POSTDATA'];
      unset($_SESSION['POSTDATA']);
    }
    else{
      $arrPOST = filter_var_array($_POST,FILTER_SANITIZE_STRING);
    }
    //check for subscriber attributes
    if(array_key_exists('subscriberid',$arrPOST) && trim($arrPOST['subscriberid']) != ''){
      $arrSubscriberData = SubscriberCore::Get()->GetSubscribersData(CLIENTID,$arrPOST['subscriberid'],TRUE);
      $arrPOST = array_merge($arrPOST,$arrSubscriberData);
    }
    else{
      $objSubscriber = new Subscriber();
      $arrSubscriberData = Subscriber::Get()->LoadArrayWithObject();
      $arrPOST = array_merge($arrSubscriberData,$arrPOST);
    }
    return $arrPOST;
  }


  /**
  * given a group of data, load the headnig and rows from the data given
  * @param $arrTableData
  *     -['tabledescription'] = ['tabledescription']
  *     -['tableheader']
  *         -['headerkey'] = ['headername']
  *     -['tabledata'][unique key]
  *         -['headerkey'] = ['value']
  *         -['linkvalue'] = ['linkvalue'] || ['onclickvalue'] = ['onclickvalue']
  * @return string
  */
  function MakeBootStrapTable($arrTableData,$strTableOrder='asc'){
    $objElement = new Element();
    $this->strBootstrapTableId = 'table_'.rand(100,10000);
    $objPrimaryTable = $objElement->LoadHTMLTemplate('<div class="panel panel-default"></div>');
    if(trim($arrTableData['tabledescription']) != '')
     $objElement->AddChildNode($objPrimaryTable,trim($arrTableData['tabledescription']),'div',array('class'=>'panel-heading'));
    //make the wrapper
    $objTableBodyWrapper = $objElement->AddChildNode($objPrimaryTable,'','div',array('class'=>'dataTable_wrapper'));
    //make the primary table now
    $objTableBodyTable = $objElement->AddChildNode($objTableBodyWrapper,'','table',array('class'=>'table table-striped table-bordered table-hover ','role'=>'grid','id'=>$this->strBootstrapTableId));
    //make the primary table now
    $objTableBodyTableHead = $objElement->AddChildNode($objTableBodyTable,'','thead',array());
    //make the primary table now
    $objTableBodyTableHeadRow = $objElement->AddChildNode($objTableBodyTableHead,'','tr',array());
    //make the th values
    foreach($arrTableData['tableheader'] as $strKeys=>$varHeaderValue){
      if(is_array($varHeaderValue))
       $objElement->AddChildNode($objTableBodyTableHeadRow,$varHeaderValue[1],'th',array('class'=>'sorting','tabindex'=>0,'aria-controls'=>'dataTables-example','rowspan'=>1,'colspan'=>1,'aria-label'=>$varHeaderValue[1].': activate to sort column descending'));
      else
       $objElement->AddChildNode($objTableBodyTableHeadRow,$varHeaderValue,'th',array('class'=>'sorting','tabindex'=>0,'aria-controls'=>'dataTables-example','rowspan'=>1,'colspan'=>1,'aria-label'=>$varHeaderValue.': activate to sort column descending'));
    }
    $objTableBodyTableBody = $objElement->AddChildNode($objTableBodyTable,'','tbody',array());
    //make the row data now
    $strRowClass = '';
    foreach($arrTableData['tabledata'] as $strDataKeys=>$arrDataValues){
      $strRowClass = ($strRowClass == 'odd')? 'even': 'odd';
      if(array_key_exists('rowclass',$arrDataValues) && $arrDataValues['rowclass'] != '')
      $strRowClass = $arrDataValues['rowclass'];
      $objTableBodyTableBodyRow = $objElement->AddChildNode($objTableBodyTableBody,'','tr',array('class'=>$strRowClass.' gradeA','role'=>'row'));
      foreach($arrTableData['tableheader'] as $strKeys=>$strHeaderValue){
        $strToolTip = '';
        //fill our values
        $strCellData = $arrDataValues[$strKeys]['value'];
        $arrCellAttributes = array('class'=>'sorting_1');
        if(array_key_exists('linkbadge',$arrDataValues[$strKeys]) && $arrDataValues[$strKeys]['linkbadge'] != '')
            $strCellData = '<i class="'.$arrDataValues[$strKeys]['linkbadge'].'"></i>&nbsp;&nbsp;'.$strCellData;
        if(array_key_exists('tooltip',$arrDataValues[$strKeys]) && $arrDataValues[$strKeys]['tooltip'] != ''){
        //ALWAYS required for tooltip/popover in tables
            $arrCellAttributes['data-container'] = "body";
      // tooltip toggle
      $arrCellAttributes['data-toggle'] = "tooltip";
      $arrCellAttributes['html'] = "true";
      // use html in popover
      $arrCellAttributes['data-html'] = "true";
      // popover/tooltip timer show options
      //$arrCellAttributes['data-delay'] = '{"show": 99, "hide": 900}';
      // popover  toggle
      $arrCellAttributes['data-toggle'] = "popover";
      $arrCellAttributes['data-trigger'] = "focus";
      // tooltip
           $arrCellAttributes['title'] = $arrDataValues[$strKeys]['tooltip'];


        }
        if(array_key_exists('linkvalue',$arrDataValues[$strKeys]) && $arrDataValues[$strKeys]['linkvalue'] != ''){
           $strCellData = '<a href="'.$arrDataValues[$strKeys]['linkvalue'].'" '.$strToolTip.' >'.$strCellData.'</a>';
        }
        else if(array_key_exists('checkbox',$arrDataValues[$strKeys]) &&  $arrDataValues[$strKeys]['checkbox'] != ''){
           $strChecked = ($strCellData === TRUE)? ' CHECKED ': '';
           $strCellData = '<input type="checkbox" '.$strChecked.' '.$strToolTip;
           if($arrDataValues[$strKeys]['ajaxlinkcall'] != '')
            $strCellData .= ' onclick="'.$arrDataValues[$strKeys]['ajaxlinkcall'].'" ';
           $strCellData .=' />';
        }
        else if(array_key_exists('href',$arrDataValues[$strKeys]) &&  $arrDataValues[$strKeys]['href'] != ''){
          $strClass = '';
          if(trim($arrDataValues[$strKeys]['linkclass']) != '')
            $strClass = $arrDataValues[$strKeys]['linkclass'];
          $strCellData = '<a class="'.$strClass.'" href="'.$arrDataValues[$strKeys]['href'].'" '.$strToolTip.' target="_blank" >'.$strCellData;

          $strCellData .= '</a>';
        }
        else if(array_key_exists('onclickvalue',$arrDataValues[$strKeys]) &&  $arrDataValues[$strKeys]['onclickvalue'] != '')
            $strCellData = '<button onclick="'.$arrDataValues[$strKeys]['onclickvalue'].'"  type="button" class="'.$arrDataValues[$strKeys]['linkclass'].'" '.$strToolTip.' >'.$strCellData.'</button>';
        else if(array_key_exists('formlinkvalue',$arrDataValues[$strKeys]) &&  $arrDataValues[$strKeys]['formlinkvalue'] != '' && $arrDataValues[$strKeys]['formlinkclass'] != '')
          $strCellData = '<a href="?formid='.$arrDataValues[$strKeys]['formlinkvalue'].'" class="'.$arrDataValues[$strKeys]['formlinkclass'].'" '.$strToolTip.' >'.$strCellData.'</a>';
        else if(array_key_exists('ajaxlinkcall',$arrDataValues[$strKeys]) &&  $arrDataValues[$strKeys]['ajaxlinkcall'] != '' && $arrDataValues[$strKeys]['formlinkclass'] != '')
          $strCellData = '<a class="'.$arrDataValues[$strKeys]['formlinkclass'].'" onclick="'.$arrDataValues[$strKeys]['ajaxlinkcall'].'" '.$strToolTip.' > '.$strCellData.' </a>';
        else{
          if(array_key_exists('linkclass',$arrDataValues[$strKeys]) &&  $arrDataValues[$strKeys]['linkclass'] != "")
            $strCellData = '<b class="'.$arrDataValues[$strKeys]['linkclass'].'"  '.$strToolTip.' >'.$strCellData.'</b>';
          else
             $strCellData = '<b  '.$strToolTip.'>'.$strCellData.'</b>';
        }
         $objElement->AddChildNode($objTableBodyTableBodyRow,$strCellData,'td',$arrCellAttributes);
      }
    }
    $strSortScript = "\r\n".'$(document).ready(function() {'."\r\n";
    $strSortScript .= ' if($(\'#'.$this->strBootstrapTableId.'\')){'."\r\n";
    $strSortScript .= '     $(\'#'.$this->strBootstrapTableId.'\').DataTable({'."\r\n";
    $strSortScript .= '            responsive: true,'."\r\n";
    $strSortScript .= '            order: [[ 0, "'.$strTableOrder.'" ]],'."\r\n";
    $strSortScript .= '     });'."\r\n";
    $strSortScript .= ' }'."\r\n";
    $strSortScript .= '});'."\r\n";
    $objElement->AddChildNode($objPrimaryTable,$strSortScript,'script',array());
    return $objElement->CloseDocument();
  }

  /**
  * get a meta box row for insert
  * @param $objData - previous data if it exists
  * @return string ( HTML )
  */
  function GetMetaBox($objData=NULL){
    if(!is_object($objData)){
        $objData = new SubField();
        $objData->intSubFieldId = 'new_'.mt_rand(10,time());
    }
    $strMetaBox = '<div class="row rowmarginoffset">';
    $strMetaBox .= '<div class="col-md-4">';
    $strMetaBox .= '<label for="title_'.$objData->intSubFieldId.'">Title:</label>';
    $strMetaBox .= '<input type="hidden" name="metaid[]" value="'.$objData->intSubFieldId.'" />';
    $strMetaBox .= '<input type="text" class="form-control" id="title_'.$objData->intSubFieldId.'" name="title_'.$objData->intSubFieldId.'" placeholder="Title" required value="'.$objData->strSubFieldTitle.'" />';
    $strMetaBox .= '</div>';
    $strMetaBox .= '<div class="col-md-4">';
    $strMetaBox .= '<label for="type_'.$objData->intSubFieldId.'">Type:</label>';
    $strMetaBox .= '<select name="type_'.$objData->intSubFieldId.'" id="type_'.$objData->intSubFieldId.'" class="form-control" >
                    <option value="">Select</option>';
    $strMetaBox .=  Abstraction::Get()->MakeSimpleDropDownOptions(Utility::Get()->CreateDataTypeArray(),$objData->intSubFieldType);
    $strMetaBox .= '</select>';
    $strMetaBox .= '</div>';
    $strMetaBox .= '<div class="col-md-4">';
    $strMetaBox .= '<label for="value_'.$objData->intSubFieldId.'">Value:</label>';
    $strMetaBox .= '<input type="text" class="form-control" id="value_'.$objData->intSubFieldId.'" name="value_'.$objData->intSubFieldId.'" placeholder="Value" required value="'. $objData->strSubFieldValue.'" />';
    $strMetaBox .= '</div>';
    $strMetaBox .= '</div>';

    return $strMetaBox;
  }

}//end class


?>