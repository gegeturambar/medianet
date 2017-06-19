
jQuery(document).ready(function(){

    var Utils = {};
    Utils.types = ["fail","success","notice"];

    $("#password").change(function () {
        let str = $(this).val();
        var regex = /^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/;
        res = regex.test(str);
        if(!res) {
            showMsg("Mot de passe non compatible","fail");
        }
    });

    function hideAllMsg(){
        if(Utils.types) {
            Utils.types.forEach(function (item) {
                hideMsg(item);
            });
        }
    }

    $("#mail").change(function () {
        hideAllMsg();
        let str = $(this).val();
        var regex = /^[\w|.]*@s2hgroup.com$/;
        res = regex.test(str);
        if(!res) {
            showMsg("Mail non compatible","fail");
        }
    });

   function showMsg(msg,type){
       hideAllMsg();
       let selector = '#msg_'+type;
       $(selector).text(msg);
       selector = '#div_msg_'+type;
       $(selector).removeClass('hide');
       $(selector).show();
   }

    function hideMsg(type){
        let selector = '#msg_'+type;
        $(selector).text("");
        selector = '#div_msg_'+type;
        $(selector).addClass('hide');
        $(selector).hide();
    }


});