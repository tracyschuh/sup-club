<?php
    session_start();
    //check that user is logged in before displaying private content
    if(!isset($_SESSION['username'])) {
        echo '<p>Please <a href="https://tracyschuh.com/SUP/sup_login.php">log in</a> to access this page.</p>';
        exit();
    } 
    require_once("./php/includes/connect_vars.php");
    //variables
    $uname = $email = $zip = $boardType = $boardHull = $boardLength = $boardWidth = $boardStory = $profileImage = $user_image = "";
    $email_new = $zip_new = '';
    $emailErr = $zipErr = $imageErr = $email_message = $zip_message = $image_message = "";
    $change_email = $change_zip = $image_type = $image_size = false;
    $image_types = array("image/gif", "image/jpg", "image/jpeg", "image/png");
    $file_name = '';
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

        //extract src value from $profileImage <img> tag
        $image_regex = '/src="([^"]+)/';
        preg_match($image_regex, $profileImage, $match);
        $src = $match[1];
        $name = explode('./uploads/', $src);
        $file_name = $name[1];
    }

    //regex
    $email_regex = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
    $zip_regex = '/^\d{5}$/';

    if (isset($_POST['submit'])) {
        //was email changed? if so, validate
        $email_new = mysqli_real_escape_string($dbc, test_input($_POST["emailAddress"]));
        if ($email_new !== $email) {
            if (preg_match($email_regex, $email_new)) {
                $change_email = true;
            } else {
                $emailErr = "You must enter a valid email address";
            } 
        }
        if ($change_email == true) {  //email changed and input ok so insert into database
            $query2 = "UPDATE members SET email='$email_new' WHERE username='$uname'";
            $result2 = mysqli_query($dbc, $query2);
            if ($result2 == true) {
                $email_message = "<p class='large_blue'>Your email has been successfully updated!&nbsp;</p>";
                $email = $email_new;
            } else {
                $email_message = "<p class='error'>Database error.</p>";
            }
        }
        //was zip changed? if so, validate
        $zip_new = mysqli_real_escape_string($dbc, test_input($_POST["postalCode"]));
        if ($zip_new !== $zip) {
            if (preg_match($zip_regex, $zip_new)) {
                $change_zip = true;
            } else {
                $zipErr = "Zip must be five digits";
            }
        } 
        if ($change_zip == true) {  //zip changed and input ok so insert into database
            $query3 = "UPDATE members SET zip_code='$zip_new' WHERE username='$uname'";
            $result3 = mysqli_query($dbc, $query3);
            if ($result3 == true) {
                $zip_message = "<p class=large_blue>Your zip code has been successfully updated!&nbsp;</p>";
                $zip = $zip_new;
            } else {
                $zip_message = "<p class='error'>Database error.</p>";
            }
        }
    } //end if(isset($_POST['submit']))

    if (isset($_POST['change_image'])) {
        //was image changed? if so, validate
        $user_image = $_FILES['user_image']['name'];
        $file_type = $_FILES['user_image']['type'];
        $file_size = $_FILES['user_image']['size'];
        $file_tmp_name = $_FILES['user_image']['tmp_name'];
                
        if ($user_image !== $file_name) {
            // check file size
            if ($file_size == 0 || $file_size > MAX_UPLOAD_SIZE) {
                $image_size = false;
                $imageErr .= "<p>Images must be less than 50kb</p>";
            } else { 
                $image_size = true; 
            }
            // check file type
            if (!in_array($file_type, $image_types)) {
                $image_type = false;
                $imageErr .= "<p>Images must be .gif, .jpg, .jpeg, or .png</p>";
            } else { 
                $image_type = true; 
            }
        }
    
        if (($image_size == true) && ($image_type == true)) { //image changed and input ok so save image to server and image name to database
            //save image to server
            $target_file = SITE_ROOT_PATH.USER_UPLOAD_DIR.$user_image;
            !file_exists ($target_file) // check for existing file name
                or die ($image_message = "File name already exists. Choose a different name.");
            move_uploaded_file ($file_tmp_name, $target_file)
                or die ($image_message = "File upload failed. Please try again.");
                               
            //save image name (entire tag) to database
            $image_path = '<img src="./uploads/'.$user_image.'">';
            $query4 = "UPDATE members SET profile_image='$image_path' WHERE username='$uname'";
            $result4 = mysqli_query($dbc, $query4);
            if ($result4 == true) {
                $image_message = "<p class='large_blue'>Your profile image has been successfully updated!&nbsp;</p>";
                $profileImage = $image_path;
            } else {
                $image_message = "<p class='error'>Database error.</p>";
            }
        }
    } // end if (isset($_POST['change_image']))

    function test_input($data) { //cleanse user input
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>

<!DOCTYPE html>
<html lang= "en">
    <head>
		<title>SUP Account Profile</title>
		<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="https://tracyschuh.com/SUP/CSS/sup.css">
        <link rel="stylesheet" type="text/css" href="https://tracyschuh.com/SUP/CSS/profile_contact.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
                <br>
                <h2>Board Profile</h2>
                    <p><strong>Fave board type: </strong><?php if (!empty($boardType)) echo $boardType; ?></p>
                    <p><strong>Fave hull type: </strong><?php if (!empty($boardHull)) echo $boardHull; ?></p>
                    <p><strong>Your board length: </strong><?php if (!empty($boardLength)) echo $boardLength; ?></p>
                    <p><strong>Your board width: </strong><?php if (!empty($boardWidth)) echo $boardWidth; ?></p>
                    <p><strong>I'm in the club because: </strong><?php if (!empty($boardStory)) echo $boardStory; ?></p>
                    <button id='edit_board_button' class='orange_button' onclick="location.href='https://tracyschuh.com/SUP/sup_board_profile.php';">Edit Board Profile</button>
            </div>
            <h2>Account Profile</h2>
                <h4><?= $email_message ?></h4>
                <h4><?= $zip_message ?></h4>
                <h4><?= $image_message ?></h4>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id= "userInfo" name= "userInfo" enctype= "multipart/form-data">
                    <label class="label_profile_acct">Username&nbsp;</label>
                    <input class="input_profile" name= "userName" id= "userName" type= "text" size= "20" readonly = "readonly" value= "<?php if (!empty($uname)) echo $uname; ?>">
                    <br>
                    <label class="label_profile_acct">Email&nbsp;</label>
                    <input class="input_profile" type="text" name="emailAddress" id="emailAddress" value= "<?php if (!empty($email)) echo $email; ?>">&nbsp;
                    <span class="output_div error small"><?php echo $emailErr;?></span>
                    <br>
                    <label class="label_profile_acct">Zip Code&nbsp;</label>
                    <input class="input_profile" type="text" name="postalCode" id="postalCode" value= "<?php if (!empty($zip)) echo $zip; ?>">&nbsp;
                    <span class="output_div error small"><?php echo $zipErr;?></span>
                    <br>
                    <label class="label_profile_acct"></label>
                    <input class="orange_button" type="submit" name="submit" value="Save Changes">
                </form>
                <br><br>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id= "pic" name= "pic" enctype= "multipart/form-data">
                    <label class="label_profile_acct">Profile Picture&nbsp;</label>
                    <input class="input_profile" type="file" name="user_image" id="user_image" value= "<?= $file_name ?>">&nbsp;
                    <br>
                    <label class="label_profile_acct"></label>
                    <span class="output_div small">&nbsp;50kb limit (.gif, .jpg, .jpeg, .png only)</span>
                    <br>
                    <span class="output_div error small"><?php echo $imageErr;?></span>
                    <br>
                    <label class="label_profile_acct"></label>
                    <input class="orange_button" type="submit" name="change_image" value="Change Profile Image">
                </form>
        </div> 
<?php
    require_once("./php/includes/footer.php");
    mysqli_close($dbc);
?>
                   
    </body>
</html>
