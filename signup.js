function validateUname() {
    var username = $("#username").val();
    var regex = /^[a-zA-Z0-9.]{2,30}$/; 

    if (regex.test(username)) { 
        $("#unameprompt").html("<img src=\"./images/greencheck.png\">");
        return true;
    } else { 
        $("#unameprompt").html("<img src=\"./images/redx.png\">&nbsp;Usernames must be between 2 and 30 characters and contain only letters, numbers, and periods.");
        return false;
    }
} // end validateUname()

function validatePassword1() {
    var password1 = $("#password1").val();
    var regex = /^(?=.*\d)(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/;

    if (regex.test(password1)) {  
        $("#pwd1prompt").html("<img src=\"./images/greencheck.png\">");
        return true;
    } else { 
        $("#pwd1prompt").html("<img src=\"./images/redx.png\">&nbsp;Passwords must be at least 8 characters with at least 1 number and 1 special character.");
        return false;
    }
} // end validatePassword1()

function validatePassword2() {
    var password1 = $("#password1").val();
    var password2 = $("#password2").val();
    
    if ((password1 !== "") && (password2 !== "") && (password1 == password2)) { 
        $("#pwd2prompt").html("<img src=\"./images/greencheck.png\">");
        return true;
    } else { 
        $("#pwd2prompt").html("<img src=\"./images/redx.png\">&nbsp;Passwords must match.");
        return false;
    }
} // end validatePassword2()

function validateEmail () {
    var email = $("#email").val();
    var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    
    if (regex.test(email)) {        
        $("#emailprompt").html("<img src=\"./images/greencheck.png\">");
        return true;
    } else { 
        $("#emailprompt").html("<img src=\"./images/redx.png\">&nbsp;You must enter a valid email address.");
        return false;
    }
} // end validateEmail()

function validateZip() {
    var regex = /^\d{5}$/;
    var zip = $("#zipCode").val();
    var int = parseInt(zip);
       
    if(regex.test(int)) { 
        $("#zipprompt").html("<img src=\"./images/greencheck.png\">");
        return true;
    } else { 
        $("#zipprompt").html("<img src=\"./images/redx.png\">&nbsp;Zip must be five digits.");
        return false;
    }
} // end validateZip()

