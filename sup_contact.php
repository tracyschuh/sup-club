<?php
    session_start();
    //check that user is logged in before displaying private content
    if(!isset($_SESSION['username'])) {
        echo '<p>Please <a href="https://tracyschuh.com/SUP/sup_login.php">log in</a> to access this page.</p>';
        exit();
    } 
    require_once("./php/includes/connect_vars.php");
    //variables
    $uname = $email = $zip = $boardType = $boardHull = $boardLength = $boardWidth = $boardStory = $profileImage = $output_message = $subject = $msg = "";
    $uname = ($_SESSION['username']);
       
    //populate profile sidebar
    $query = "SELECT * FROM members WHERE username = '$uname'";
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $email = $row['email'];
        $zip = $row['zip_code'];
        $boardType = $row['board_type'];
        $boardHull = $row['board_hull'];
        $boardLength = $row['board_length'];
        $boardWidth = $row['board_width'];
        $boardStory = $row['board_story'];
        $profileImage = $row['profile_image'];
    }

?>

<!DOCTYPE html>
<html lang= "en">
    <head>
	<title>SUP Contact Us</title>
	<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="https://tracyschuh.com/SUP/CSS/sup.css">
        <link rel="stylesheet" type="text/css" href="https://tracyschuh.com/SUP/CSS/profile_contact.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script src="https://tracyschuh.com/SUP/js/navbar.js"></script>
    </head>
    <body id = "allbodycontent">
        
<?php
    require_once("./php/includes/header.php");
    require_once("./php/includes/member_menu.php");
?>
        
        <div id = "mainbody">
        <div id="left_sidebar_profile">
            <?= $profileImage ?>
        </div>
        <div id="right_sidebar_profile">
            <h2>Account Profile:</h2>
                <p><strong>Username: </strong><?php if (!empty($uname)) echo $uname; ?></p>
                <p><strong>Email: </strong><?php if (!empty($email)) echo $email; ?></p>
                <p><strong>Zip Code: </strong><?php if (!empty($zip)) echo $zip; ?></p>
                <button id='edit_acct_button' class='orange_button' onclick="location.href='https://tracyschuh.com/SUP/sup_account_profile.php';">Edit Account Profile</button>
            <br><br>
            <h2>Board Profile</h2>
                <p><strong>Fave board type: </strong><?php if (!empty($boardType)) echo $boardType; ?></p>
                <p><strong>Fave hull type: </strong><?php if (!empty($boardHull)) echo $boardHull; ?></p>
                <p><strong>Your board length: </strong><?php if (!empty($boardLength)) echo $boardLength; ?></p>
                <p><strong>Your board width: </strong><?php if (!empty($boardWidth)) echo $boardWidth; ?></p>
                <p><strong>I'm in the club because: </strong><?php if (!empty($boardStory)) echo $boardStory; ?></p>
                <button id='edit_board_button' class='orange_button' onclick = "location.href='https://tracyschuh.com/SUP/sup_board_profile.php';">Edit Board Profile</button>
        </div>
            <h2>Contact Us</h2>
                <h4 class="error" id="results_message"></h4>
                <form method="post" action="#" id= "contact" name= "contact" enctype= "multipart/form-data">
                    <label class="label_profile_acct">Username&nbsp;</label>
                    <input class="input_profile" name= "userName" id= "userName" type= "text" size= "20" readonly = "readonly" value= "<?php if (!empty($uname)) echo $uname; ?>">&nbsp;&nbsp;
                    <br><br>
                    <label class="label_profile_acct">Email&nbsp;</label>
                    <input class="input_profile" type="text" name="emailAddress" id="emailAddress" readonly = "readonly" value= "<?php if (!empty($email)) echo $email; ?>">&nbsp;&nbsp;
                    <br><br>
                    <label class="label_profile_acct">Subject&nbsp;</label>
                    <input class="input_profile" type="text" name="subject" id="subject" required>&nbsp;&nbsp;
                    <br><br>
                    <label class="label_contact">Message&nbsp;</label>
                    <textarea class="input_profile" name="message" id="message" rows="4" cols="50" maxlength = "500"></textarea>&nbsp;&nbsp;
                    <br><br>
                    <label class="label_profile_acct"></label><input class="orange_button" type="submit" id="send" name="send" value="Send Message">&nbsp;&nbsp;
                </form>
                <br><br>                
        </div> 
        <script src="https://tracyschuh.com/SUP/js/sup_contact.js"></script>
<?php
    require_once("./php/includes/footer.php");
    mysqli_close($dbc);
?>
                   
    </body>
</html>
