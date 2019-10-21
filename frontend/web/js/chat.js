
let username = $('.js-username').val();
let chat = new WebSocket('ws://localhost:8080');
chat.onmessage = function (e) {
    $('#response').text('');
    let response = JSON.parse(e.data);
    $('#chat').append('<div>'+response.created_at+' &nbsp;<b>' + response.username + '</b>:&nbsp; ' + response.message + '</div>');
};

chat.onopen = function (e) {
    chat.send(JSON.stringify({
            'type': 'hello',
            'username': username
        })
    );
};

$('#send').click(function () {
    chat.send(JSON.stringify({
            'type': 'chat',
            'username': username,
            'message': $('#message').val(),
        })
    );
    $('#message').val('');
});