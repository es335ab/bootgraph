//check_page-load.js
(function(){

  var memoDisplayArea = document.getElementById('memoDisplayArea');
  if(!localStorage.getItem('memoLength') || localStorage.getItem('memoLength') === '-Infinity' || localStorage.getItem('memoLength') === -Infinity || localStorage.getItem('memoLength') === 'NaN'){
    localStorage.memoLength = 0;
  }

  var appendMemo = function(){
    var keysArr = [];
    for(var i = 0,I = localStorage.length; i < I; i++){
      if(localStorage.key(i) !== 'ip' && localStorage.key(i) !== 'memoLength'){
        keysArr.push(Number(localStorage.key(i)));
      }
    }
    keysArr = keysArr.sort(function(a,b){
      if( a < b ) return 1;
      if( a > b ) return -1;
      return 0;
    });
    if(localStorage.memoLength != 0){
      localStorage.memoLength = Math.max.apply(null, keysArr);
    }

    for(var i = 0,I = localStorage.length; i < I; i++){
      if(localStorage.key(i) !== 'ip' && localStorage.key(i) !== 'memoLength'){
        var appendMemoList = {};
        var memoDataObj = JSON.parse(localStorage.getItem(keysArr[i]));
        if(memoDataObj.memoData.indexOf('\n')){
          alert('えんえぬがある');
          var adjustMemoData = memoDataObj.memoData.replace(/\n/g,'<br>');
        }else if(memoDataObj.memoData.indexOf('\r')){
          alert('えんあーるがある');
          var adjustMemoData = memoDataObj.memoData.replace(/\r/g,'<br>');
        }
        appendMemoList = document.createElement('li');
        appendMemoList.id = 'id' + keysArr[i];
        if(memoDataObj.importantFlag === true){
          appendMemoList.classList.add('importantMemo');
        }else{
          appendMemoList.classList.remove('importantMemo');
        }
        appendMemoList.innerHTML = '<p class="text" title="メモ編集したい場合はここをクリック！">' + adjustMemoData + '</p><textarea class="textCorrection hide">' + memoDataObj.memoData + '</textarea><time>[No' + keysArr[i] + ']' + memoDataObj.date + '</time><i class="iconDelete jsDelete"></i>';

        //memoDisplayArea.insertBefore(appendMemoList,memoDisplayArea.firstChild);
        memoDisplayArea.appendChild(appendMemoList);
      }
    }
  }

  if(localStorage.getItem('ip')){
    console.log('ip is exist in LocalStorage.');
    //idとpassがローカルストレージにセットされている場合、
    //サーバー上のjsonからmemoデータを取得して、localStorageに上書き
    var ipJson = JSON.parse(localStorage.getItem('ip'));
    var i = ipJson['i'];
    var p = ipJson['p'];
    var randomNumber = Math.floor(Math.random()*100000000);
    var recieveData  = {};
    var dataObjKeys;

    var getData = function(){
      $.ajax({
        type: "POST",
        url: "check_page-load.php?" + randomNumber,
        data: {request : i + '|' + p},
        success: function(data, dataType){
          //PHPから返ってきたデータの中からmemoLengthをローカルストレージに保存する。
          recieveData = JSON.parse(data);
          dataObjKeys = Object.keys(recieveData);
          localStorage.setItem('memoLength', recieveData.memoLength);

          for(var i = 0,I = dataObjKeys.length; i < I; i++){
            if(Object.keys(recieveData)[i] !== 'p'){
              localStorage.setItem(Object.keys(recieveData)[i], JSON.stringify(recieveData[dataObjKeys[i]]));
            }
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
          //エラーメッセージの表示
          //alert('Error : ' + errorThrown);
          alert('メモデータとの通信に失敗しました。時間をおいて再度お試しください');
        }
      });
    }
    getData();
  }
  appendMemo();
})();