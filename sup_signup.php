<?php
    session_start();
    require_once("./php/includes/connect_vars.php");
    // variables 
    $uname = $pwd1 = $pwd2 = $email = $zip = "";
    $unameErr = $pwdErr = $emailErr = $zipErr = $output_message = "";
    $valid_uname = $valid_pwd = $valid_email = $valid_zip = 0;
    //regex
    $uname_regex = '/^[a-zA-Z0-9.]{2,30}$/';
    $pwd_regex = '/^(?=.*\d)(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/';
    $email_regex = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
    $zip_regex = '/^\d{5}$/';

    if (isset($_POST['submit'])) {
        $uname = mysqli_real_escape_string($dbc, test_input($_POST['username']));
        $pwd1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
        $pwd2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));
        $hashed_pwd = hash('sha256', $pwd1);
        $email = mysqli_real_escape_string($dbc, test_input($_POST["email"]));
        $zip = mysqli_real_escape_string($dbc, test_input($_POST["zipCode"]));
        //check to see if user name exists already
        $query = "SELECT * FROM members WHERE username = '$uname'";
        $result = mysqli_query($dbc, $query);
        //validate username
        if (mysqli_num_rows($result) !== 0) { //username already exists
            $unameErr =  'Username ' . $uname . ' is already taken. Please choose another';
        } elseif (preg_match($uname_regex, $uname)) { //check input
            $valid_uname = 1;
        } else {
            $unameErr = "Usernames must be between 2 and 30 characters and contain only letters, numbers, and periods";
        }
        //validate password
        if (($pwd1 == $pwd2) && (preg_match($pwd_regex, $pwd1))) {  
            $valid_pwd = 1;
        } else {
            $pwdErr = "Passwords must be at least 8 characters with at least 1 number and 1 special character (!@#$%^&* only) and must match";
        }
        //validate email
        if (preg_match($email_regex, $email)) {
            $valid_email = 1;
        } else {
            $emailErr = "You must enter a valid email address";
        } 
        //validate zip code
        if (preg_match($zip_regex, $zip)) {
            $valid_zip = 1;
        } else {
            $zipErr = "Zip must be five digits";
        } 
        //if input ok, insert into database
        if ($valid_uname && $valid_pwd && $valid_email && $valid_zip) {  
            $timestamp = date("Y-m-d H:i:s");
            $query2 = "INSERT INTO members(username, password, email, zip_code, created_at) VALUES ('$uname', '$hashed_pwd', '$email', '$zip', '$timestamp')";
            $result2 = mysqli_query($dbc, $query2);
            if ($result2 == true) {
                $output_message = "User data was inserted successfully!&nbsp;<a href= 'sup_login.php'>Click here to login</a>";
            } else {
                $output_message = "Database error.";
            }
        }
    } //end if (isset($_POST['submit']))

    function test_input($data) { //cleanse user input
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE HTML>  
<html lang= "en">
    <head>
		<title>SUP Club</title>
		<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="https://tracyschuh.com/SUP/CSS/sup.css">
        <link rel="stylesheet" type="text/css" href="https://tracyschuh.com/SUP/CSS/login_signup.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
        <script src="https://tracyschuh.com/SUP/js/signup.js"></script>
        <script>
            $(function() {
            var triZips = [
                '99301',   
                '99302',
                '99320',
                '99336',
                '99337',
                '99338',
                '99352',
                '99353',
                '99354'
            ];
            $("#zipCode").autocomplete({
                source: triZips
            });
        });
        </script>
    </head>
    <body id = "allbodycontent">
<?php
    require_once("./php/includes/header.php");
?>
        
        <div id = "mainbody">
            <h2>Do You Love SUP Too?</h2>
            <h3>Join our Club!</h3>
                <p class="small">&nbsp;<em>All fields are mandatory</em></p>
                <p class="large_blue"><?= $output_message ?></p>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id= "userInfo" name= "userInfo" autocomplete= "on">
                    <label class="label_signup">Username&nbsp;&nbsp;</label>
                    <input class="input_signup" type="text" name="username" id="username" onblur="validateUname();" value= "<?php if (!empty($uname)) echo $uname; ?>">
                    <span id="unameprompt" class="small">&nbsp;Letters, numbers, and periods only; 2 - 30 characters</span><br>
                    <label class="label_signup"></label><span class="error small"><?php echo $unameErr;?></span>
                    <br>
                    <label class="label_signup">Password&nbsp;&nbsp;</label>
                    <input class="input_signup" type="password" name="password1" id="password1" onblur="validatePassword1();" value= "<?php if (!empty($pwd1)) echo $pwd1; ?>">
                    <span id="pwd1prompt" class="small">&nbsp;Minimum of 8 characters with at least 1 number and 1 special character (!@#$%^&* only)</span><br>
                    <label class="label_signup"></label><span class="error small"><?php echo $pwdErr;?></span>
                    <br>
                    <label class="label_signup">Confirm Password&nbsp;&nbsp;</label>
                    <input class="input_signup" type="password" name="password2" id="password2" onblur="validatePassword2();" value= "<?php if (!empty($pwd2)) echo $pwd2; ?>">
                    <span id="pwd2prompt" class="small"></span><br>
                    <span></span>
                    <br>
                    <label class="label_signup">Email&nbsp;&nbsp;</label>
                    <input class="input_signup" type="text" name="email" id="email" onblur="validateEmail();" value= "<?php if (!empty($email)) echo $email; ?>">
                    <span id="emailprompt" class="small"></span><br>
                    <label class="label_signup"></label><span class="error small"><?php echo $emailErr;?></span>
                    <br>
                    <label class="label_signup">Zip Code&nbsp;&nbsp;</label>
                    <input class="input_signup ui-menu" type="text" name="zipCode" id="zipCode" autocomplete= "off" onblur="validateZip();" value= "<?php if (!empty($zip)) echo $zip; ?>">
                    <span id="zipprompt" class="small"></span><br>
                    <label class="label_signup"></label><span class="error small"><?php echo $zipErr;?></span>
                    <br>
                    <label class="label_signup"></label>
                    <input class="orange_button" type="submit" name="submit" value="Submit">
                </form>
                <br>
                <a href= "https://tracyschuh.com/SUP/sup_login.php" target= "_self">Already Have a Member Account? Click Here.</a><br>
        </div> 
<?php
    require_once("./php/includes/footer.php");
    mysqli_close($dbc); 
?>
                   
    </body>
</html>