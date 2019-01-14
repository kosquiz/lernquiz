$(document).ready(function(){


    //SEND CHAT
    $('#chatSubmit').click(function(){
        var $msg = $('#chatInput').val();

        var $data = {'msg':$msg};
        $.ajax({
            type: "POST",
            data: $data,
            url: "index.php?site=sendChat",
            success: function(res){
                console.log(res, "success");
            }
        })
    })



    //REFRESH CHAT
    setTimeout(function(){
        $.ajax({
            type: "GET",
            url = "index.php?site=getChat",
            success: function(res){
                console.log(res, "chat");
                $('#chatBox').empty().html(res['chat']);
            }
        })
    }, 500);


});