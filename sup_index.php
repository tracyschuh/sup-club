<?php
    session_start();
    //check that user is logged in before displaying private content
    if(!isset($_SESSION['username'])) {
        echo '<p>Please <a href="https://tracyschuh.com/SUP/sup_login.php">log in</a> to access this page.</p>';
        exit();
    } 
    require_once("./php/includes/connect_vars.php");
    //populate acct profile
    $uname = $email = $zip = $boardType = $boardHull = $boardLength = $boardWidth = $boardStory = '';
    $uname = $_SESSION['username'];
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
	<title>SUP Home</title>
	<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="https://tracyschuh.com/SUP/CSS/sup.css">
	<link rel="stylesheet" type="text/css" href="https://tracyschuh.com/SUP/CSS/index.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://tracyschuh.com/SUP/js/friends.js"></script>
	<script src="https://tracyschuh.com/SUP/js/navbar.js"></script>
    </head>
    <body id = "allbodycontent">
        
<?php
    require_once("./php/includes/header.php");
    require_once("./php/includes/member_menu.php");
?>
        
        <div id = "mainbody">
            <div id="left_sidebar_index">
                <?= $profileImage ?>
            </div>
            <div id="right_sidebar_profile">
                <h2>Account Profile:</h2>
                    <p><strong>Username: </strong><?php if (!empty($uname)) echo $uname; ?></p>
                    <p><strong>Email: </strong><?php if (!empty($email)) echo $email; ?></p>
                    <p><strong>Zip Code: </strong><?php if (!empty($zip)) echo $zip; ?></p>
                    <button id="edit_acct_button" class="orange_button" type="button" onclick="location.href='https://tracyschuh.com/SUP/sup_account_profile.php';">Edit Account Profile</button>
                <br><br>
                <h2>Board Profile</h2>
                    <p><strong>Fave board type: </strong><?php if (!empty($boardType)) echo $boardType; ?></p>
                    <p><strong>Fave hull type: </strong><?php if (!empty($boardHull)) echo $boardHull; ?></p>
                    <p><strong>Your board length: </strong><?php if (!empty($boardLength)) echo $boardLength; ?></p>
                    <p><strong>Your board width: </strong><?php if (!empty($boardWidth)) echo $boardWidth; ?></p>
                    <p><strong>I'm in the club because: </strong><?php if (!empty($boardStory)) echo $boardStory; ?></p>
                    <button id="edit_board_button" class="orange_button" type="button" onclick="location.href='https://tracyschuh.com/SUP/sup_board_profile.php';">Edit Board Profile</button><br><br>
            </div>
            <h1>You Love SUP Too!</h1>
                <h2 class="welcome_blue">Welcome <?= $uname ?>!</h2>
                <br>
            <h1 id="connect" class="small_margin">Connect with Other Members</h1>
                <div class="container">
                    <div class="left_quad">
                        <h3>Find Friends</h3>
                        <button id="friends" class="orange_button" type="button" name="friends">Find Friends</button>  
                    </div>
                    <div class="middle_quad">
                        <h3>Find an Upcoming Event</h3>
                        <button class="orange_button" onclick="location.href='https://tracyschuh.com/SUP/sup_events.php';">Go!</button>
                    </div>
                    <div class="right_quad">
                    <h3>Group Chat</h3>
                        <input id="input" placeholder="message">&nbsp;<span id="hide_chat" onclick='hideChat();'>x</span>
                        <div id="box"></div>
                    </div>
                </div>
                <div id="showFriends"></div>
        </div>
        <script src=https://cdn.pubnub.com/sdk/javascript/pubnub.4.37.0.min.js></script>
        <script src="https://tracyschuh.com/SUP/js/chat.js"></script>
        <script> 
            var username = '<?= $uname ?>: ';
            chat();
        </script>
        
<?php
    require_once("./php/includes/footer.php");
    mysqli_close($dbc);
?>
                   
    </body>
</html>
