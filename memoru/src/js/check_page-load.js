//check_page-load.js
(function(){
  if (localStorage.getItem('ip') === null) {
    console.log('idとpassが登録されていない。処理を抜けます');
    return;
  }

  var ipJson = JSON.parse(localStorage.getItem('ip'));
  var i = ipJson['i'];
  var p = ipJson['p'];
  var randomNumber = Math.floor(Math.random()*100000000);
  var recieveData  = {};
  var memoDisplayArea = document.getElementById('memoDisplayArea');
  var dataObjKeys;

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
        if(Object.keys(recieveData)[i] !== 'p' && Object.keys(recieveData)[i] !== 'memoLength'){
          var appendMemoList = {};

          appendMemoList = document.createElement('li');
          appendMemoList.id = 'id' + Object.keys(recieveData)[i];
          console.log(Object.keys(recieveData)[i]);
          if(recieveData[dataObjKeys[i]].importantFlag === true){
            appendMemoList.classList.add('importantMemo');
          }else{
            appendMemoList.classList.remove('importantMemo');
          }
          appendMemoList.innerHTML = '<p class="text" title="メモ編集したい場合はここをクリック！">' + recieveData[dataObjKeys[i]].memoData + '</p><textarea class="textCorrection hide">' + recieveData[dataObjKeys[i]].memoData + '</textarea><time>' + recieveData[dataObjKeys[i]].date + '</time><i class="iconDelete jsDelete"></i>';

          memoDisplayArea.insertBefore(appendMemoList,memoDisplayArea.firstChild);
        }
      }
    },
    error: function(XMLHttpRequest, textStatus, errorThrown){
      //エラーメッセージの表示
      //alert('Error : ' + errorThrown);
      alert('メモデータとの通信に失敗しました。時間をおいて再度お試しください');
    }
  });



})();
