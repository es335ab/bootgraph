var PATHCONSCRIPT = {};

//localStorage.clear();

PATHCONSCRIPT = {
  convert : function(){

    var macPath = $('#macPath'),
        windowsPath = $('#windowsPath'),
        textareaClass = $('.textarea'),
        pathText,
        selfId,
        replaceText;

    //ファイルパスを置換する処理
    var convertFunction = function(path){
      path.keyup(function(){
        pathText = $(this).val();
        selfId = $(this).attr('id');

        if(selfId == 'macPath'){
          replaceText = (pathText.replace(/\//g, '\\')).replace('smb:','');
          windowsPath.val(replaceText);

        }else if(selfId == 'windowsPath'){
          replaceText = pathText.replace(/\\/g, '/');
          macPath.val('smb:' +replaceText);
        }

      });
    }

    //execute
    convertFunction(macPath);
    convertFunction(windowsPath);
  },

  //ポップアップを開いたり閉じたりする処理
  popup : function(args){
    var openHundler = args.hundler,
        popupArea = $('#popupArea'),
        modalArea = $('#modal'),
        closeHundler = $('#popupArea, .popupClose'),
        allIcon = $('#macIcon, #windowsIcon'),
        allPathList = $('#pathList li'),
        showIcon,
        showPathList;

    var init = function(){
      allIcon.hide();
    },
    openFunction = function(){
      openHundler.click(function(){

        if($(this).attr('id') == 'macPopupOpen'){
          showIcon = $('#macIcon');
          showPathList = $('#pathList .mac');
        }else if($(this).attr('id') == 'windowsPopupOpen'){
          showIcon = $('#windowsIcon');
          showPathList = $('#pathList .windows');
        }

        popupArea.show();
        modalArea.show();
        showIcon.show();
        showPathList.css('display','block');
      });
    },
    closeFunction = function(){
      closeHundler.on('click',function(){
        popupArea.hide();
        modalArea.hide();
        allIcon.hide();
        $('#pathList li').css('display','none');
      });
    }

    //execute
    init();
    openFunction();
    closeFunction();
  },
  savelocalStorage : function(){
    var macPath = $('#macPath'),
        windowsPath = $('#windowsPath'),
        textareaClass = $('.textarea'),
        localStorageLength,
        lengthValue,
        pathText,
        os,
        selfId,
        saveSuccessFlag;

    var localStrageStatusCheck = function(){
      if(localStorage.getItem('pathLength') == 'NAN'){
        localStorage.clear();
      }

      localStorageLength = localStorage.getItem('pathLength');

      if(!localStorageLength){
        localStorage.setItem('pathLength',1);
      }
    },
    textareaChangeEvent = function(path){
      path.change(function(){
        pathText = $(this).val();
        selfId = $(this).attr('id');

        if(pathText == ''){
          return;
        }

        if(selfId == 'macPath'){
          os = 'mac';
        }else if(selfId == 'windowsPath'){
          os = 'windows';
        }

        saveFunction(localStorageLength, pathText, os);

      });
    },
    saveFunction = function(uniqueId, pathText, os){
      var pathText = pathText,
          osData = os,
          duplication;

      if(uniqueId){
        var uniqueId = uniqueId;
      }else{
        var uniqueId = 1;
      }

      //json value
      var insertData = {
        osData : osData,
        urlData : pathText
      }

      var incrementPathLengthValue = function(){
        localStorageLength = parseInt(localStorage.getItem('pathLength')) + 1;
        localStorage.setItem('pathLength',localStorageLength);
      },
      urlSave = function(){

        for(var i = 0; i < localStorage.length; i++){
          var duplicationCheckKey = localStorage.key(i),
              duplicationCheckUrl = JSON.parse(localStorage.getItem(duplicationCheckKey)).urlData;

          if(duplicationCheckUrl == pathText) {
            duplication = 'exist';
          }
        }

        if(duplication !== 'exist'){
          localStorage.setItem(uniqueId, JSON.stringify(insertData));
          incrementPathLengthValue();
          duplication = '';
          saveSuccessFlag = 'saveSuccess';
        }

        return saveSuccessFlag;

      },
      appendNewData = function(){
        //htmlリストにただいま登録されたデータをappendする
        var newRegistKey = (localStorage.getItem('pathLength') - 1 );

        var newRegistOsData = JSON.parse(localStorage.getItem(newRegistKey)).osData,
            newRegistUrlData = JSON.parse(localStorage.getItem(newRegistKey)).urlData,
            appendDom = '<li id="'+ newRegistKey +'" class="' + newRegistOsData + '"><div class="singleDelete">[x]</div><p class="singlePath">' + newRegistUrlData + '</p></li>';

        $('#pathList').prepend(appendDom);
        if($('#noPathList')){
          $('#noPathList').remove();
        }

        saveSuccessFlag = '';
      }

      //execute
      urlSave();
      if(saveSuccessFlag){
        appendNewData();
      }

    }

    //execute
    localStrageStatusCheck();
    textareaChangeEvent(macPath);
    textareaChangeEvent(windowsPath);
  },

  //[×]をクリックで該当データを消す
  deleteLocalStorage : function(){
    var singleDelete = '.singleDelete',
        deleteId;

    var deleteEvent = function(){
      $(document).on('click',singleDelete,function(){
        deleteId = $(this).parent('li').attr('id');

        localStorage.removeItem(deleteId);
        $(this).parent('li').remove();
      });
    }

    //execute
    deleteEvent();
  },

  //localstorageに入っているデータを初期表示させる
  initDisplayLocalstorage : function(){

    if(localStorage.length === 0){
      //localStorageに何も入っていない時にこのfunctionはreturnする
      return;
    }

    var keyNumberList  = [],
        appendDom ='',
        keySingle,
        displayKey,
        displayOsData,
        displayUrlData;

    for (var i = 0; i < localStorage.length; i++) {

      if(localStorage.key(i) !== 'pathLength'){
        keySingle = parseInt(localStorage.key(i));
        keyNumberList.push(keySingle);
      }

    }

    keyNumberList.sort(
      function(a,b){
      if( a < b ) return 1;
        if( a > b ) return -1;
        return 0;
      }
    );

    for (var i = 0; i < keyNumberList.length; i++) {
      displayKey = keyNumberList[i];
      displayOsData = JSON.parse(localStorage.getItem(displayKey)).osData;
      displayUrlData = JSON.parse(localStorage.getItem(displayKey)).urlData;

      appendDom += '<li id="'+ keyNumberList[i] +'" class="' + displayOsData + '"><div class="singleDelete">[x]</div><p class="singlePath">' + displayUrlData + '</p></li>';

    }

    $('#noPathList').remove();
    $('#pathList').append(appendDom);

  },
  initDeleteLocalstorage : function(){

  }
};

window.onload = function(){
  //クライアントでlocalstorageが使えない場合はlocalstrage関連の処理をしない
  if(!localStorage){
    $('.popupOpen').css('display','none');
  }

  //入力したテキストをwindowsパス <=> macパスに変換させる
  PATHCONSCRIPT.convert();

  //ポップアップの表示非表示
  PATHCONSCRIPT.popup({
    hundler : $('#macPopupOpen')
  });
  PATHCONSCRIPT.popup({
    hundler : $('#windowsPopupOpen')
  });

  //localstorageに入っているデータが20件を超えていたら、昔のデータから消す
  PATHCONSCRIPT.initDeleteLocalstorage();

  //localstorageに入っているデータを初期表示させる
  PATHCONSCRIPT.initDisplayLocalstorage();

  //変換したテキストをローカルストレージに保存する
  PATHCONSCRIPT.savelocalStorage();

  //[×]をクリックで該当データを消す
  PATHCONSCRIPT.deleteLocalStorage();

};