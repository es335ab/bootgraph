//create_usr-id.js
(function(MEMORUJS){
  MEMORUJS.createUsrId = function(){
    localStorage.clear();
    this.usrIdInput = document.getElementById('usrIdInput');
    this.passInput = document.getElementById('passInput');
    this.usrIdCreate = document.getElementById('usrIdCreate');

    this.init();
  }

  var fn = MEMORUJS.modal.prototype;

  //functions
  fn.init = function(){
    var self = this;

    this.usrIdCreate.addEventListener('click',function(){
      self.modalControl(this);
    },false);
  }
})(MEMORUJS || (MEMORUJS = {}));
