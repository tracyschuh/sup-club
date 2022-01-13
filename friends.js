$(document).ready(function(){
    $("#friends").click(function() {
        $("#showFriends").load("friends.php", function(responseTxt, statusTxt, xhr) { //file must be in same folder to work with load() otherwise it's blocked for security reasons
            /*debug
            if (statusTxt == "success")
                alert("External content loaded successfully");
            */
            if (statusTxt == "error")
                alert("Error" + xhr.status + ": " + xhr.statusTxt);
        });
    });
});

function hideFriends() {
    $("#showFriends").html("");
}


