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
                //console.log(res)
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

        $.ajax({
            type: "GET",
            url: "index.php?site=gameTick",
            success: function(res){
                res = JSON.parse(res);
                console.log("gameTick", res);
                buildGameBoard(res);
            }
        })

    }, 1000);

    //button clicks
    $('.question').click(function(){
        var id = $(this).data('id');
        var data = {'id':id};
        console.log(data);
        $.ajax({
            type: "POST",
            data: data,
            url: "index.php?site=uncoverQuestion",
            success: function(res){
                //console.log(JSON.parse(res));
                console.log(res);return;
                res = JSON.parse(res);
                if(res['success']==false){
                    console.log(res['message']);
                }

            }
        })
    })
   


});

//hide old messages
var siteOpened = new Date();

/**
 * GAME
 */

function buildGameBoard(game){
    var board = game['board'];

    for(var key in board){
        var ele = board[key];
        if(ele['hidden'])
            $('.question.number'+ele['pos']).empty().html(ele['value']);
        else
            $('.question.number'+ele['pos']).empty().html(ele['show']);
    }

    var answers = game['answers'];
    if(answers.length == 0){
        for(var i=1; i<=4; i++){
            $('.answer.number'+i).empty();
        }
    }
    for(var key in answers){
        var ele = answer[key];
        $('.answer.number'+ele['pos']).empty().html(ele['show']);
    }

}

/**
 * CHAT
 */
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