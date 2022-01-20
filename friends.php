<?php
    session_start();
    //check that user is logged in before displaying private content
    if(!isset($_SESSION['username'])) {
        echo '<p>Please <a href="https://tracyschuh.com/SUP/sup_login.php">log in</a> to access this page.</p>';
        exit();
    } 
    require_once("./php/includes/connect_vars.php");
    
    $uname = $username = $userEmail = $userZip = $boardStyle = $boardDimensions = $otherInfo = "";
    $uname = $_SESSION['username'];
    $query = "SELECT profile_image, username, email, zip_code, board_story,
            CONCAT ('Board type: ', IFNULL(board_type, 'NA'), '<br>Hull type: ', IFNULL(board_hull, 'NA')) AS 'board_style',
            CONCAT ('Length: ', IFNULL(board_length, 'NA'), '<br>Width: ', IFNULL(board_width, 'NA')) AS 'board_dimensions'
            FROM members WHERE username != '$uname' ORDER BY zip_code LIMIT 10";
    
    $result = mysqli_query($dbc, $query);
    echo "<br><br>
            <table class='friends_table'>
                <tr class='blue_row'>
                <th><button id='hide_friends' type='button' name='hide_friends' onclick='hideFriends();'>x</button>Profile Pic</th>
                <th>User Name</th>
                <th>Email</th>
                <th class='hide1'>Zip Code</th>
                <th class='hide2'>Board / Hull Type</th>
                <th class='hide1'>Board Stats</th>
                <th class='hide2'>Other Info</th>
                </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        $userImage = $row['profile_image'];
        $username = $row['username'];
        $userEmail = $row['email'];
        $userZip = $row['zip_code'];
        $boardStyle = $row['board_style'];
        $boardDimensions = $row['board_dimensions'];
        $otherInfo = $row['board_story'];
        
        echo "<tr class='green_row'>
                <td>$userImage</td>
                <td>$username</td>
                <td>$userEmail</a></td>
                <td class='hide1'>$userZip</td>
                <td class='hide2'>$boardStyle</td>
                <td class='hide1'>$boardDimensions</td>
                <td class='hide2'>$otherInfo</td>
            </tr>";
    }
    echo "</table>";
    
    mysqli_close($dbc);
?>

