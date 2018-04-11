  /**
  * given a child object, remove it's parent
  * @param objThis - element chid to reference parent from
  * @return bool
  */
  function CloseParentBox(objThis){
    $("#"+objThis.id).parent().remove();
    return true;
  }

  function imgloading(open){
      var loadingimage='';
   var loadspace= document.getElementById('loadingimg');
   if (loadspace && typeof(loadspace.style) != "undefined"){
    if (open){                                      
      loadspace.style.display = "block";
    }
    else{
      loadspace.style.display = "none";
    }
   }
  }

  /*
  @brief Make the background fade
  */
  function FadeBackGround(strBoxName){
      $('.maintable').animate({"opacity" : .2,backgroundColor:'transparent'}, 100, function(){
        $("#"+strBoxName).animate({"opacity" : 1}, 500);
      });
  }

  /*
  @brief Make the background full color again
  */
  function ResetBackGroundFade(strBoxName){
   $('.maintable').animate({"opacity" : 1,backgroundColor:'transparent'}, 200, function(){
        $("#"+strBoxName).animate({"opacity" : 0}, 450);
      });
  }

  /**
  * Data popup creation
  */
  function DataPopup(strPopupId,strContent){
    var objNewDiv = '';
    var objBody = document.getElementsByTagName('body');
    if(objBody[0]){
      objNewDiv = document.createElement('div');
      objNewDiv.id = strPopupId+'_1';
      objNewDiv.name = strPopupId+'_1';
      objNewDiv.className = 'popups';
      objBody[0].appendChild(objNewDiv);
      objNewDiv.innerHTML = strContent;
      $('[data-popup="' + strPopupId + '"]').fadeIn(350);
    }
    else    console.log('Body does not exist');
    return true;
  }

  /**
  * close the data popup
  */
  function CloseDataPopUp(strPopupId){
      $('[data-popup="' + strPopupId + '"]').fadeOut(350);
      $( "#"+strPopupId+'_1' ).remove();
      $( '.popups' ).remove();
      ResetBackGroundFade();
      return true;
  }

  /**
  * update the station manager after a station has been removed
  */
  function UpdateHTMLElements(objJSonReturn){
    for(i in objJSonReturn.updateelements){
     var objElement = document.getElementById(objJSonReturn.updateelements[i].elementid);
     if(objElement){
      if(objElement.tagName == 'SELECT'){
        var opts = objElement.options;
        for(var opt, j = 0; opt = opts[j]; j++) {
            if(opt.value == objJSonReturn.updateelements[i].elementcontent) {
                objElement.selectedIndex = j;
                break;
            }
        }
      }
      else if(objElement.type == 'checkbox'){
        if(objElement.value != '')
           objElement.checked = true;
      }
      else if(objElement.type == 'select-multiple'){
        for(var key in objElement){
          objElement[key].selected = true;
        }
      }
      else if(objElement.tagName == 'INPUT' || objElement.tagName == 'TEXTAREA'){
        if(objElement.name != '' && objElement.name != 'undefined' && typeof objElement.name != 'undefined')
         objElement.value =  objJSonReturn.updateelements[i].elementcontent;
      }
      else{
      //assuming this is a div or other element
        objElement.innerHTML = objJSonReturn.updateelements[i].elementcontent;
      }
      //disable it now that it's updated
      if(objJSonReturn.updateelements[i].disabled == 'true')
        objElement.disabled = true;
      else objElement.disabled = false;
     }
     else{
        console.log('element does not exist');
     }
    }
    return true;
  }

  /**
  * load an entire form to ajax handler
  * @param objForm - form to submit
  */
  function SubmitSelectedForm(objForm){
   AjaxCore.objPresentForm = objForm;
    AjaxCore.SendAjaxRequest(objForm);
    return false;
  }

  /**
  * request a subscriber data update form
  * @param intSubscriberId
  * @return bool
  */
  function GetSubscriberUpdateForm(intSubscriberId){
    var strAjaxRequest = 'dir=subscriberupdate';
    strAjaxRequest += '&subscriberid='+intSubscriberId
    AjaxCore.SendAjaxRequest(strAjaxRequest);
    return false;
  }

  /**
  * get a meta box for a subscriber
  * @return bool
  */
  function GetMetaBox(objForm){
    AjaxCore.objPresentForm = objForm;
    var strAjaxRequest = 'dir=getmetabox';
    AjaxCore.SendAjaxRequest(strAjaxRequest);
    return false;
  }