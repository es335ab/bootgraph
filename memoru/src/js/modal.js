//modal.js
(function(MEMORUJS){
  MEMORUJS.modal = function(){
    this.triggerId = document.getElementById('modalTrigger');
    this.modalId = document.getElementById('modal');
    this.modalTextareaId = document.getElementById('modalTextarea');
    this.triggerText = this.triggerId.getElementsByTagName('b')[0];
    this.jsContentClass = document.getElementsByClassName('jsContent');
    this.headerId = document.getElementById('header');

    this.init();
  }

  var fn = MEMORUJS.modal.prototype;

  //functions
  fn.init = function(){
    var self = this;

    this.triggerId.addEventListener('click',function(){
      self.modalControl(this);
    },false);

    this.modalTextareaId.addEventListener('change',function(){
      this.ios7BugSupportClose();
    },false);
  }

  fn.modalControl = function(obj){
    if(obj.classList.contains('closeModal')){
      this.modalId.classList.add('hide');
      obj.classList.remove('closeModal');
      this.triggerText.innerText = 'メモる';
      this.ios7BugSupportClose();

      return;
    }

    this.modalId.classList.remove('hide');
    obj.classList.add('closeModal');
    this.triggerText.innerText = 'もどる';
    this.modalTextareaId.focus();
    this.ios7BugSupportOpen();
  }

  fn.ios7BugSupportOpen = function(){
    if(MEMORUJS.uaCheck() ==='iPhone' || MEMORUJS.uaCheck() ==='iPad' || MEMORUJS.uaCheck() ==='iPod'){
      for(var i = 0,I = this.jsContentClass.length;i < I; i++){
        this.jsContentClass[i].classList.add('hide');
      }
      this.headerId.classList.add('absolute');
      this.modalId.classList.add('absolute');
    }
  }
  fn.ios7BugSupportClose = function(){
    if(MEMORUJS.uaCheck() ==='iPhone' || MEMORUJS.uaCheck() ==='iPad' || MEMORUJS.uaCheck() ==='iPod'){
      for(var i = 0,I = this.jsContentClass.length;i < I; i++){
        this.jsContentClass[i].classList.remove('hide');
      }
      this.headerId.classList.remove('absolute');
      this.modalId.classList.remove('absolute');
    }
  }

})(MEMORUJS || (MEMORUJS = {}));
