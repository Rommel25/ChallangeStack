$('#sendMsg').on('click', function(){
    var msg = $('#chat').val();
    console.log(msg);

    $.post( "/communication/sendMsg/" + $(this).attr("data-commId"), { msg: msg }, function( data ) {
        $('#chat').val('');
        location.reload();
    });
});