$(document).ready(function(){


    //SEND CHAT
    $('#chatSubmit').click(function(){
        submitChat();
    })

    $('#chatInput').on('keypress', function(e){
        if(e.which==13){
            submitChat();
        }
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

function submitChat(){
    var $msg = $('#chatInput').val();
    $('#chatInput').val("");
    var $data = {'msg':$msg};
    $.ajax({
        type: "POST",
        data: $data,
        url: "index.php?site=sendChat",
        success: function(res){
            console.log(res, "success");
        }
    })
}