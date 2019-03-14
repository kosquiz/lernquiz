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
        });

        $.ajax({
            type: "GET",
            url: "index.php?site=setActive",
            success: function(res){
                console.log(res)
            }
        });

        $.ajax({
            type: "GET",
            url: "index.php?site=getActive",
            success: function(res){
                res = JSON.parse(res);
                var users = "";
                res['users'].forEach(function(user){
                    users += user + " ";
                });
                $('.onlinePoint').empty().html(users)
            }
        });

    }, 500);


    //Set user active

    
   


});

//hide old messages
var siteOpened = new Date();

function submitChat(){
    var $msg = $('#chatInput').val();
    $('#chatInput').val("");
    var $data = {'msg':$msg};
    $.ajax({
        type: "POST",
        data: $data,
        url: "index.php?site=sendChat",
        success: function(res){
            console.log(JSON.parse(res));
        }
    })
}

function buildChat(chat){
    var html = "";
    chat.reverse();
    chat.forEach(function(line){
        var date = new Date(line['Time']);
        if(date<siteOpened)
            return;
        var showDate = date.getHours().pad() + ":" + date.getMinutes().pad();
        html += "[" + showDate + "] " + line['Accounts_Username'] + " | " + line['Message'] + "<br>";
    })
    return html;
}

Number.prototype.pad = function(size) {
    var s = String(this);
    while (s.length < (size || 2)) {s = "0" + s;}
    return s;
}