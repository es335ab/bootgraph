//insert_data.js
(function(){
  if(localStorage.getItem('ip')){
    var ipJson = JSON.parse(localStorage.getItem('ip'));
    var i = ipJson['i'];
  }
  var modalInsertMemo = document.getElementById('modalInsertMemo');
  var modalImportantCheck = document.getElementById('modalImportantCheck');
  var modalBtnSubmit = document.getElementById('modalBtnSubmit');
  var pcInsertMemo = document.getElementById('pcInsertMemo');
  var pcImportantCheck = document.getElementById('pcImportantCheck');
  var pcBtnSubmit = document.getElementById('pcBtnSubmit');

  //var randomNumber = Math.floor(Math.random()*100000000);

  var insertDataMethod = function(args){
    this.insertMemoId = args.insertMemoId;
    this.importantCheckId = args.importantCheckId;
    this.btnSubmitId = args.btnSubmitId;
    this.memoCheckFlag = false;
    this.insertCheckFlag = false;
    this.modalId = document.getElementById('modal');
    this.modalTriggerId = document.getElementById('modalTrigger');

    this.insertObj = {};
    this.insertKey;
    this.insertMemoContent;
    this.insertImportantFlag;
    this.insertTime;

    this.init();
  }

  var fn = insertDataMethod.prototype;

  fn.init = function(){
    var self = this;
    this.btnSubmitId.addEventListener('click',function(){
      self.memoContentCheck();

      if(!self.memoCheckFlag) return;

      self.importantCheck();
      self.insertMemoData();

      if(!self.insertCheckFlag){
        self.memoCheckFlag = false;
        return;
      }

      self.appendMemoList();
      self.inputClear();

      self.memoCheckFlag = false;
    },false);
  }

  fn.memoContentCheck = function(){
    if(this.insertMemoId.value === '' || this.insertMemoId.value === null){
      alert('メモを入力していません。');
      return;
    }
    this.insertMemoContent = this.insertMemoId.value;
    this.insertMemoContent = (this.insertMemoContent.replace(/</g, '＜'));
    this.insertMemoContent = (this.insertMemoContent.replace(/>/g, '＞'));
    this.insertMemoContent = (this.insertMemoContent.replace(/\'/g, '’'));
    this.insertMemoContent = (this.insertMemoContent.replace(/\"/g, '”'));

    this.memoCheckFlag = true;
  }

  fn.importantCheck = function(){
    if(this.importantCheckId.checked){
      this.insertImportantFlag = true;
    }else{
      this.insertImportantFlag = false;
    }
  }

  fn.insertMemoData = function(){
    this.insertKey = Number(localStorage.getItem('memoLength')) + 1;

    var now = new Date();
    this.insertTime = (now.getYear() + 1900) + '/' + (now.getMonth() + 1) + '/' + (now.getDate()) + '&nbsp' + (now.getHours()) + ':' + (now.getMinutes());
    console.log(this.insertTime);
    localStorage.setItem('memoLength', this.insertKey);

    this.insertObj = {
      importantFlag : this.insertImportantFlag,
      memoData : this.insertMemoContent,
      date : this.insertTime
    }

    var insertJson = JSON.stringify(this.insertObj);
    localStorage.setItem(this.insertKey, insertJson);

    if(!localStorage.getItem('ip')){
      this.insertCheckFlag = true;
      return;
    }
    console.log('問題なく登録できたらxhr通信でサーバーのjsonも上書きする');
    this.insertCheckFlag = true;
  }

  fn.appendMemoList = function(){
    console.log('ユーザーが入力した内容をappendする');
    var memoDisplayArea = document.getElementById('memoDisplayArea');
    var memoDataObj = JSON.parse(localStorage.getItem(this.insertKey));
    var appendMemoList = appendMemoList = document.createElement('li');
    appendMemoList.id = 'id' + this.insertKey;
    console.log(memoDataObj.importantFlag);
    if(memoDataObj.importantFlag === true){
      appendMemoList.classList.add('importantMemo');
    }else{
      appendMemoList.classList.remove('importantMemo');
    }
    appendMemoList.innerHTML = '<p class="text" title="メモ編集したい場合はここをクリック！">' + memoDataObj.memoData.replace(/\n/g,'<br>') + '</p><textarea class="textCorrection hide">' + memoDataObj.memoData + '</textarea><time>[No' + this.insertKey + ']' + memoDataObj.date + '</time><i class="iconDelete jsDelete"></i>';

    memoDisplayArea.insertBefore(appendMemoList,memoDisplayArea.firstChild);
  }

  fn.inputClear = function(){
    console.log('データ登録が成功したら、ユーザが入力した値とcheckをクリアする');
    this.insertMemoId.value = '';
    this.importantCheckId.checked = false;
    this.modalId.classList.add('hide');
    this.modalTriggerId.classList.remove('closeModal');
    this.modalTriggerId.innerHTML = '<i class="iconPlus"></i><b>メモる</b>';

    this.insertCheckFlag = false;
  }

  //modal内のメモ登録部分にイベントをbindする
  new insertDataMethod({
    insertMemoId : modalInsertMemo,
    importantCheckId : modalImportantCheck,
    btnSubmitId : modalBtnSubmit
  });

  new insertDataMethod({
    insertMemoId : pcInsertMemo,
    importantCheckId : pcImportantCheck,
    btnSubmitId : pcBtnSubmit
  });
})();
