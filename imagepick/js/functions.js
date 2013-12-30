var FRONTJS = {};

FRONTJS = {
  //input type="file"に入れた画像データをDataURLで表示させる
  readImageFile : function(){
    var readImageFileId = document.getElementById('readImageFile'),//$('#readImageFile')だとfiles[0]がエラーになる
        dragDropAreaId = document.getElementById('dragDropArea'),
        stageId = document.getElementById('stage'),
        imageDisplayCanvasId = document.getElementById('imageDisplayCanvas'),
        maskMeasureID = document.getElementById('maskMeasure'),
        selectedRangeID = document.getElementById('selectedRange'),
        infoSecsId = document.getElementById('infoSecs'),
        imageFile,
        imageUrl,
        canvasContext,
        fileTypeCheck = '',
        fileLoaderObject;

    var fileChangeEvent = function(){
      readImageFileId.addEventListener('change',function(){
        imageFile = this.files[0];

        //アップロードされたファイルの拡張子が画像化どうか確認
        fileTypeCheckFunction(imageFile);

        //アップロードされたファイルが画像形式じゃなかったら処理を終える
        if(fileTypeCheck === ''){
          alert('File format is not in the image.');
          this.value = '';
          return false;
        }

        //複数の画像をアップしようとした輩がいたら、できない旨警告させていただく
        if(this.files.length > 1){
          alert('This site will display only representative image.');
        }

        //アップロードされたファイルを読み込んでDataURLで表示させる
        fileLoaderObject = new FileReader();
        fileLoaderObject.onload = onFileLoad;

        fileLoaderObject.readAsDataURL(imageFile);

        //アップロードされたファイルの形式判定フラグをでデフォルトにする
        fileTypeCheck = '';

        //information背kションを表示にする
        infoSecsId.style.display = 'block';

      },false);
    },
    imageUrlLoadEvent = function(){

      /*
      クロスドメインの画像をcanvasで読み込むところまではできたけど、
      読み込んだ画像にマウス処理しようとするとセキュリティエラーになるため実装中断
      惜しいだけに非常に悲しい
      */

      $('#urlSearchBtn').on('click', function(){
        imageUrl = $('#urlSearchText').val();

        //入力された画像URLを読み込んでDataURLで表示させる
        fileLoaderObject = new FileReader();

        onFileLoad('',imageUrl);

        //アップロードされたファイルの形式判定フラグをでデフォルトにする
        fileTypeCheck = '';
      });
    },

    //Generic functions
    fileTypeCheckFunction = function(imageFile){
      if(imageFile.type === 'image/png' || imageFile.type === 'image/jpeg' || imageFile.type === 'image/gif'){
        fileTypeCheck = 'fileIsImage';

        return fileTypeCheck;
      }
    },
    onFileLoad = function(e , inputImageUrl){
      var getImageSize,
          dataUrlValue;

      if(e){
        dataUrlValue = e.target.result;//file apiの画像ファイルをdataURLに変換する
      }else{
        dataUrlValue = inputImageUrl;//URL入力の画像ファイルをdataURLに変換する
      }

      getImage = new Image();
      getImage.src = dataUrlValue;

       //canvasのサイズ属性をアップロードした画像と同じにする
      imageDisplayCanvasId.setAttribute('width',getImage.width);
      imageDisplayCanvasId.setAttribute('height',getImage.height);

      //length計測用のdivをcanvasと同じサイズにする
      maskMeasureID.style.width = getImage.width + 'px';
      maskMeasureID.style.height = getImage.height + 'px';

      canvasContext = imageDisplayCanvasId.getContext('2d');

      //画像を読み込んだらcanvasに画像を表示する
      getImage.onload = function() {
        canvasContext.drawImage(getImage, 0, 0, getImage.width, getImage.height);
      };

      stageId.style.display = 'none';
    }

    //execute
    fileChangeEvent();
    imageUrlLoadEvent();
  },

  colorPickFunction : function(){
    var canvasOffset = $('#imageDisplayCanvas').offset(),
        imageDisplayCanvasId = document.getElementById('imageDisplayCanvas'),
        colorInfoPanelId = document.getElementById('colorInfoPanel'),
        canvasX = 0,
        canvasY = 0,
        imageData = {},
        pixel = [],
        rgba = '',
        hex = '',
        preview = $('#preview'),
        colorInfoR = $('#colorInfoR'),
        colorInfoG = $('#colorInfoG'),
        colorInfoB = $('#colorInfoB'),
        colorInfoHex = $('#colorInfoHex');

    var colorValueGet = function(e){
      // マウスカーソルのピクセル色情報取得
      canvasX = Math.floor(e.pageX - canvasOffset.left);
      canvasY = (Math.floor(e.pageY - canvasOffset.top) + 13);

      imageData = canvasContext.getImageData(canvasX, canvasY, 1, 1);
      pixel = imageData.data;
      rgba = 'rgba(' + pixel[0] + ',' + pixel[1] + ',' + pixel[2] + ',' + pixel[3] + ')';
      hex = pixel[0].toString(16) + pixel[1].toString(16) + pixel[2].toString(16);
    }

    canvasContext = imageDisplayCanvasId.getContext('2d');

    $('#imageDisplayCanvas').on('mousemove', function(e) {
        colorValueGet(e);

        // 取得した色情報を画面に渡す
        preview.css({backgroundColor: rgba});
        colorInfoR.html(pixel[0]);
        colorInfoG.html(pixel[1]);
        colorInfoB.html(pixel[2]);
        colorInfoHex.html(hex);
        colorInfoPanelId.style.backgroundColor = '#' + hex;

    });

    $('#imageDisplayCanvas').on('click',function(e){
      colorValueGet(e);
      alert('hex:#' + hex + ' RGB:' + pixel[0] + ',' + pixel[1] +',' + pixel[2]);
    });
  },

  measureLengthFunction : function(){
    var maskMeasureArea = $('#maskMeasure'),
        selectedRange = $('#selectedRange'),
        gridW = $('#gridW'),
        gridH = $('#gridH'),
        gridX = $('#gridX'),
        gridY = $('#gridY'),
        selectedRangeCssProp = {},
        clickingFlag = false,
        startLocateX,
        startLocateY,
        presentLocateX,
        presentLocateY,
        leftValue = 0,
        topValue = 0,
        widthValue = 0,
        heightValue = 0;

    var startLocateGet = function(event){
      startLocateX = event.pageX - 30;
      startLocateY = event.pageY - 69;
      clickingFlag = true;

      gridX.html(startLocateX);
      gridY.html(startLocateY);
      gridW.html(0);
      gridH.html(0);

      return(startLocateX, startLocateY);
    },
    operateMaskArea = function(event){
      presentLocateX = event.pageX - 30;
      presentLocateY = event.pageY - 69;

      if(presentLocateX < startLocateX){
        leftValue = presentLocateX;
      }else{
        leftValue = startLocateX;
      }

      if(presentLocateY < startLocateY){
        topValue = presentLocateY;
      }else{
        topValue = startLocateY;
      }

      widthValue = Math.abs(presentLocateX - startLocateX);
      heightValue = Math.abs(presentLocateY - startLocateY);

      gridW.html(widthValue);
      gridH.html(heightValue);

      selectedRange.css('left' , leftValue + 'px');
      selectedRange.css('top' , topValue + 'px');
      selectedRange.css('width' , widthValue + 'px');
      selectedRange.css('height' , heightValue + 'px');

    },
    endLocateGet = function(event){
      clickingFlag = false;
    };

    //event
    maskMeasureArea.on('mousedown',function(){
      startLocateGet(event);
    });

    maskMeasureArea.on('mousemove',function(){
      if(clickingFlag === false) return;

      operateMaskArea(event);

    });

    maskMeasureArea.on('mouseup',function(){
      endLocateGet(event);
    });


  },

  oldIeCheckFunction : function(){
    var userAgent = window.navigator.userAgent.toLowerCase(),
        oldIeArray = ['msie 5.','msie 6.','msie 7.','msie 8.'];
        oldIeFlag = 'oldIeFalse';

    for (var i = 0, I = oldIeArray.length; i < I; i++) {
      if(userAgent.indexOf(oldIeArray[i]) != -1){
        oldIeFlag = 'oldIeTrue';
      }
    }

    return oldIeFlag;
  },

  toggleSwitchFunction : function(){
    var toggleSwitch = $('#toggleSwitch'),
        infoSec = $('.infoSec'),
        maskMeasure = $('#maskMeasure');

    var toggleSwitchFunction = function(){
      toggleSwitch.children('li').click(function(){
        clickEvent($(this));
      });
    },
    clickEvent = function(obj){
      toggleSwitch.children('li').removeClass('current');
      obj.addClass('current');

      infoSec.addClass('hide');
      infoSec.eq(obj.index()).removeClass('hide');

      if(obj.index() === 1){
        maskMeasure.removeClass('hide');
      }else{
        maskMeasure.addClass('hide');
      }
    }

    toggleSwitchFunction();
  },

  infoSecsMove : function(){
    var infoSecs = $('#infoSecs'),
        topPixel = infoSecs.css('top'),
        topNumberInput = 0,
        leftNumberInput = 0,
        topNumberOutput = 0,
        leftNumberOutput = 0,
        mouseMovingRangeInitX = 0,
        mouseMovingRangeInitY = 0,
        mouseMovingRangeX = 0,
        mouseMovingRangeY = 0,
        topPixel = '',
        leftPixel = '',
        clickFlag = false;

    var mouseMoveFunction = function(){
      infoSecs.mousedown(function(e){
        clickFlag = true;

        mouseMovingRangeInitX = e.pageX;
        mouseMovingRangeInitY = e.pageY;

        leftNumberInput = Number(infoSecs.css('left').replace('px',''));
        topNumberInput = Number(infoSecs.css('top').replace('px',''));

      });

      $('body').mousemove(function(e){
        if(clickFlag == false) return;

        mouseMovingRangeX = mouseMovingRangeInitX - e.pageX;
        mouseMovingRangeY = mouseMovingRangeInitY - e.pageY;

        leftNumberOutput = Number(leftNumberInput - mouseMovingRangeX);
        topNumberOutput = Number(topNumberInput - mouseMovingRangeY);

        infoSecs.css('left',leftNumberOutput +'px');
        infoSecs.css('top',topNumberOutput +'px');
      });

      infoSecs.mouseup(function(){
        clickFlag = false;
      });
    },

    leftFixedInit = function(){
      leftNumber = (window.innerWidth - 254);
      infoSecs.css('left', leftNumber + 'px');
      infoSecs.css('display','block');
    }

    //init
    leftFixedInit();

    //eventInit
    mouseMoveFunction();
  },

  adStyleFixed : function(){
    $('#google_ads_frame2').contents().find('.al').css('font-size', '12px');
  }


};

window.onload = function(){
  //IE8以下のブラウザでは使えないっす表記をする
  FRONTJS.oldIeCheckFunction();

  if(oldIeFlag === 'oldIeTrue'){
    $('#header').remove();
    $('#content').remove();
    $('body').append('<p>こちらのツールはモダンブラウザかInternet Explorer9以上で使用できます。</p>')

    return false;
  }

  //input type="file"に入れた画像データをDataURLで表示させる
  FRONTJS.readImageFile();

  //canvasのオンマウスで色をピックアップする
  FRONTJS.colorPickFunction();

  //lengthを計る
  FRONTJS.measureLengthFunction();

  //タブでインフォメーションセクションを切り替える
  FRONTJS.toggleSwitchFunction();

  //インフォメーションセクションをdrag&dropで移動させる
  FRONTJS.infoSecsMove();

  //sdsenceの文字サイズを指定
  FRONTJS.adStyleFixed();
};