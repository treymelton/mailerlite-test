<?php
require_once('CodeHeader.php');
/* Start Meta */
$strTitle = SITENAME;
$strKeywords = SITENAME;
$strDescription = '';
/* End Meta */
require_once('header.php');
//DisplayMessages::Get()->AddUserMSG('Page Load Initiated', 1);
//DisplayMessages::Get()->AddUserMSG('Page Load Initiated1', 2);
//DisplayMessages::Get()->AddUserMSG('Page Load Initiated2', 3);
//DisplayMessages::Get()->AddUserMSG('Page Load Initiated3', 4);
$arrGET = filter_var_array($_GET,FILTER_SANITIZE_STRING);
if(array_key_exists('loadtables',$arrGET) && $arrGET['loadtables'] == 'true'){
  InstallTool::Get()->InitiateInstall();
}
else{
  if(!($boolFirstRun = Abstraction::Get()->ValidateFirstRun())){
      //give them our first load message
    $strFirstRun = 'Tables not created. Create them ';
    $strFirstRun .= '<a href=index.php?loadtables=true>now?</a>';
    DisplayMessages::Get()->AddUserMSG($strFirstRun, 1);
  }
  else{
    DisplayMessages::Get()->AddUserMSG('API endpoint form ready!', 2);
  }
}
//load our POST options
$arrPOST = Abstraction::Get()->LoadPostSubscriberData();
include(SERVERPATH.DIRECTORY_SEPARATOR.'forms'.DIRECTORY_SEPARATOR.'subscriberform.php');
?>

<div class="row">
  <div class="col-md-12">
    &nbsp;<!-- spacer -->
  </div>
</div>
<div class="row primarycontent">
  <div class="col-md-10 col-lg-offset-1">
    <h1 class="page-header"><?php echo $strTitle; ?></h1>
    <p class="lead"><?php echo $strDescription; ?></p>
    <?php
        echo DisplayMessages::Get()->GetDisplayMSGs();
    ?>
  </div>
  <div class="col-md-11 col-md-offset-1">
    <?php
    if($boolFirstRun){
     ?>
      <div class="row">
        <div class="col-md-11">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#Subscriberupdate">Subscriber Insert Form</a></li>
            <li><a data-toggle="tab" href="#adminupdate">Subscriber Update Form</a></li>
            <li><a data-toggle="tab" href="#apiendpoint">API Endpoint Form</a></li>
          </ul>
          <div class="tab-content">
            <div id="Subscriberupdate" class="tab-pane fade in active">
              <h3>Subscriber Insert Form</h3>
              <p>This is the Subscriber insert form</p>
              <form action="Subscriberupdate.php" method="post" name="uupdate">
                <?php
                    echo $strSubscriberForm;
                ?>
                <button type="submit" class="btn btn-primary" >Insert</button>
              </form>
            </div>
            <div id="adminupdate" class="tab-pane fade">
              <h3>Subscriber Update Form</h3>
              <p>This is the Subscriber update form</p>
              <form action="adminupdate.php" method="post" name="aupdate">
                <?php
                    if($arrSubscriberData = SubscriberCore::Get()->FormSubscriberTableArray())
                        echo Abstraction::Get()->MakeBootStrapTable($arrSubscriberData);
                    else echo DisplayMessages::Get()->GetDisplayMSGs();
                ?>
              </form>
            </div>
            <div id="apiendpoint" class="tab-pane fade">
              <h3>API Endpoint Form</h3>
              <p>This will use Ajax to send the insert to cURL, then return the results to the page.</p>
              <p>Fundamentally it is no different than the local version for subscriber update, except the extra step utilizing cURL.</p>
              <form action="" method="post" name="aendpoint">
                <?php
                    echo $strSubscriberForm;
                ?>
                <button class="btn btn-success" type="button" onclick="GetMetaBox(this.form);">Add Meta Value</button>
                <input type="hidden" name="dir" value="subscribermodify" />      
                <button type="button" class="btn btn-primary" onclick="SubmitSelectedForm(this.form);">Send</button>
              </form>
            </div>
          </div>
        </div>
      </div>
     <?php
    }
    ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    &nbsp;<!-- spacer -->
  </div>
</div>
<?php
require_once('footer.php');
?>