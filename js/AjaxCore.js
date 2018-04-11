var AjaxCore = {
    //make our common vars AjaxCore.SendAjaxRequest
    strFormName:'',
    boolViewCart:false,
    objPresentForm:null,

    /**
    *   make an ajax call
    *   @param varAjaxContent - Various content types
    *       -string curl formed string
    *       -form data direct POST/GET
    *   @return bool
    */
    SendAjaxRequest:function(varAjaxContent){
      var arrPayLoad = {};
      imgloading(true);
      FadeBackGround('');
      //check to see if it's a string
      if(typeof varAjaxContent == 'string')
        arrPayLoad = this.ConvertCurlToJson(varAjaxContent);

      //check to see if it's an array
      else if(varAjaxContent instanceof Object)
        arrPayLoad = this.ConvertFormToJson(varAjaxContent);

      //check to see if it's an object
      else if(variable.constructor === Array)
        arrPayLoad = this.ConvertFormToJson(varAjaxContent);
      //can't make empty requests
      if( arrPayLoad.length < 1 ){
        console.log( 'Ajax request failed.');
        return false;
      }
      //send our content
    	jQuery.ajax({
    		type: "post",
            url: "/AS.php",
            data: { action: 'AjaxHandler', payload: arrPayLoad},
    		success: function(varAjaxReturn){
          			return AjaxCore.HandleAjaxReturns(varAjaxReturn);
    		}
    	}); //close jQuery.ajax(
    },

    /**
    *   prepare a curl string for JQuery delivery
    *   @param strCURLData
    *   @return JQuery ready JSON object
    */
    ConvertCurlToJson:function(strCurlData){
      var arrPayLoad = {};
      var arrCurlArray = strCurlData.split("&");
      //make our array
      for(i=0;i<arrCurlArray.length;i++){
       var arrDataPair = arrCurlArray[i].split("=");
       arrPayLoad[arrDataPair[0]] = arrDataPair[1];
      }
      //give back our json object
      return JSON.stringify(arrPayLoad);
    },

    /**
    *   prepare form data for JQuery delivery
    *   @param objForm
    *   @return JQuery ready JSON object
    */
    ConvertFormToJson:function(objForm){
      //go through the form and assemble our payload
      var arrPayLoad = {};
      if(objForm.elements && objForm.elements.length > 0){
          for(i=0;i<objForm.elements.length;i++){
            if(objForm.elements[i].type == 'select-multiple'){
              arrPayLoad[objForm.elements[i].name] = {};
              var intTrack = 0;
              for(e=0;e<objForm.elements[i].length;e++){
                if(objForm.elements[i][e].selected){
                    arrPayLoad[objForm.elements[i].name][intTrack] = objForm.elements[i][e].value;
                    intTrack++;
                }
              }
            }
            else{
              if(objForm.elements[i].type != 'button'){
                if(objForm.elements[i].type != 'checkbox' && objForm.elements[i].type != 'radio'){
                  if(objForm.elements[i].name.indexOf('[') >= 0){
                    //this is a multiple input selection
                    if(typeof arrPayLoad[objForm.elements[i].name] !== 'undefined'){
                      if(arrPayLoad[objForm.elements[i].name] instanceof Array){
                        arrPayLoad[objForm.elements[i].name][arrPayLoad[objForm.elements[i].name].length] = objForm.elements[i].value;
                      }
                      else{
                        var varArrayParts = arrPayLoad[objForm.elements[i].name];
                        arrPayLoad[objForm.elements[i].name] = [];
                        arrPayLoad[objForm.elements[i].name][0] = varArrayParts;
                        arrPayLoad[objForm.elements[i].name][1] = objForm.elements[i].value;    
                      }
                    }
                    else{
                        arrPayLoad[objForm.elements[i].name] = [];
                        arrPayLoad[objForm.elements[i].name][0] = objForm.elements[i].value
                    }
                  }
                  else{
                    arrPayLoad[objForm.elements[i].name] = objForm.elements[i].value;
                  }
                }
                else{
                  if(objForm.elements[i].checked)
                    arrPayLoad[objForm.elements[i].name] = objForm.elements[i].value;
                }
              }
            }
          }
      }
      //give back our json object
      return JSON.stringify(arrPayLoad);
    },

    /**
    *   Handle ajax request returns
    *   @param varAjaxReturn
    *   @return bool
    */
    HandleAjaxReturns:function(varAjaxReturn){
      //check to see if it's a string
      if(typeof varAjaxReturn == 'string')
        varAjaxReturn = this.ConvertStringToJson(varAjaxReturn);
        if(varAjaxReturn.intIntent == 0){
              if(varAjaxReturn.strHandlerKey == "ale"){
              //varAjaxReturn.varResponse == should be alerted to user
                alert(varAjaxReturn.varResponse );
                ResetBackGroundFade();
              }
              if(varAjaxReturn.strHandlerKey == "alu"){
              //varAjaxReturn.varResponse == should be alerted to user
                 alert(varAjaxReturn.varResponse );
                 location.reload();
              }
              if(varAjaxReturn.strHandlerKey == "red"){
                 window.location = varAjaxReturn.varResponse ;
              }
              if(varAjaxReturn.strHandlerKey == "log"){
                 console.log(varAjaxReturn.varResponse);
                 ResetBackGroundFade();
              }
          }
          if(varAjaxReturn.intIntent == 1){
              if(varAjaxReturn.strHandlerKey == "frm"){
                  DataPopup('popup-1',varAjaxReturn.varResponse );
      	      }
              if(varAjaxReturn.strHandlerKey == "ude"){
                //update the HTML now
                var objJSonReturn = jQuery.parseJSON(varAjaxReturn.varResponse );
                UpdateHTMLElements(objJSonReturn);

              }
              if(varAjaxReturn.strHandlerKey == "apd"){
              //append an existing html element.innerHTML
                var objParentElement = this.objPresentForm;
                if(objParentElement){
                   var objNewDiv = document.createElement('div');
                   objNewDiv.innerHTML = varAjaxReturn.varResponse ;
                   objParentElement.appendChild(objNewDiv);
                }
                ResetBackGroundFade();
              }
          }
          //close our loading image
          imgloading(false);
    },///HandleAjaxReturns

    /**
    *   given a string of data, parse it into a JSON array
    *   @param strAjaxReturn
    *   @return JSON array
    */
    ConvertStringToJson:function(varAjaxReturn){
      var objJsonObject = {};
      objJsonObject.intIntent = varAjaxReturn.slice(0,1);//output to screen 0, or update a variable = 1
      objJsonObject.strHandlerKey = varAjaxReturn.slice(1,4);//code execution key
      objJsonObject.varResponse = varAjaxReturn.slice(4,varAjaxReturn.length );//actual info to update or insert
      return objJsonObject;
    }
  };//end class