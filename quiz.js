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
    setInterval(function(){
        $.ajax({
            type: "GET",
            url: "index.php?site=getChat",
            success: function(res){
                res = JSON.parse(res);
                var html = buildChat(res['chat']);
                $('#chatBox').empty().html(html);
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

function buildChat(chat){
    var html = "";
    chat.reverse();
    chat.forEach(function(line){
        var date = new Date(line['Time']);
        var showDate = date.getHours() + ":" + date.getMinutes();
        html += "[" + showDate + "] " + line['Accounts_Username'] + " | " + line['Message'] + "<br>";
    })
    return html;
}