$(document).ready(function(){
    $("#friends").click(function() {
        $("#showFriends").load("friends.php", function(responseTxt, statusTxt, xhr) { //call script to query database
            /*debug
            if (statusTxt == "success")
                alert("External content loaded successfully");
            */
            if (statusTxt == "error")
                alert("Error" + xhr.status + ": " + xhr.statusTxt);
        });
    });
});

function hideFriends() { //hide friends table
    $("#showFriends").html("");
}


