var conn = new WebSocket('ws://localhost:8091'); // you can change this host and port, for your host and port (remeber change port on app/ratchet/server.php )

conn.onopen = function(e) {
    $('#messages').append('<p>Connection established! Chat work!</p>');
};

conn.onclose = function(e){
    conn.send('Closed!'); // TODO: this not working
}

conn.onmessage = function(e) {
    $('#messages').append('<p>'+ e.data + '</p>');
    console.log(e.data);
    $('#messages').scrollTop(1000);
};

$(document).on('click', '#addMessage', function(){
    var newMessage = $('input[name=message]').val();
    conn.send(newMessage);
    $('#messages').append('<p>'+ newMessage +'</p>');
    $('#messages').scrollTop(1000);
    $('input[name=message]').val('');
});

$(document).on('click', '#addLogin', function(){
    var newMessage = $('input[name=message]').val() +' has joined';
    conn.send(newMessage);
    $('input[name=message]').val('');
    $('input[name=message]').attr('placeholder', 'Type here...');
    $('#addMessage').removeClass('hidden');
    $('#addLogin').addClass('hidden');
});

// window.onunload = function (e) {
//     conn.send('has disconnected!'); // TODO
// };