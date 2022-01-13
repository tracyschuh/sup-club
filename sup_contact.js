$(document).ready(function() {
    $('form').on('submit', function(e) {
        e.preventDefault();
        var dataString = $(this).serialize();
        $.ajax({
            url: 'https://tracyschuh.com/SUP/php/process/sup_contact_process.php', //calls PHPMailer script
            type: 'POST',
            data: dataString,
            success: function(data) {
                data = JSON.parse(data);
                if (data.send != 'success') {
                    $("#results_message").html("Oops! It looks like there's an error:<br>" + data.msg);
                } else {
                    swal("Success!", data.msg, "success");
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });
});
