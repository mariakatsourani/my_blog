<?php
include 'core/init.php';
protected_page();

include 'includes/overall/header.php';

//------------ Validation ------------//
if (isset($_POST['submit'])) {     //check if clicked

    switch ($_POST['submit']) {     //check what was clicked
        case 'Change Username':
            if(empty($_POST['new_username'])){
                $errors[] = "You have to fill in a username";
            }else{
                //check if it exists
                if(user_exists($_POST['new_username'], $db)){
                    $errors[] = "Username already exists";
                }else{
                    $errors = validate_username($_POST['new_username']);
                }
            }
            break;
        case 'Change Password':
            if(empty($_POST['current_password']) || empty($_POST['new_password']) || empty($_POST['repeat_new_password'])){
                $errors[] = "All three password fields are required";
            }else{
                if( !check_password($_SESSION['username'] , $_POST['current_password'], $db) ){//check current_password
                    $errors[] = "This is not your current password.";
                }else{
                    $errors = validate_password($_POST['new_password'], $_POST['repeat_new_password']);
                }

            }
            break;
        case 'Change Subscription':
            if (isset($_POST['email_feed'])){
                $_POST['email_feed'] = 1;
            }else{
                $_POST['email_feed'] = 0;
            }
            //update db
            break;
        default:
            echo "wtf?";
            break;
    }//end switch
}
//------------ Validation ------------//

?>
    <div id="account_settings">
        <form id="username" class="manage" method="post">
            <h3>Change your username</h3><br>
            Your current username is: <strong><?php echo $_SESSION['username'] ?></strong><br>
            <input type="text" name="new_username" placeholder="Enter your new username here..."><br>
            <input type="submit" name="submit" value="Change Username">
        </form>

        <form class="manage" id="password" method="post">
            <h3>Change your password</h3><br>
            <input type="text" name="current_password" placeholder="Enter your current password here..."><br>
            <input type="text" name="new_password" placeholder="Enter your new password here..."><br>
            <input type="text" name="repeat_new_password" placeholder="Enter your new password once more here..."><br>
            <input type="submit" name="submit" value="Change Password">
        </form>

        <form class="manage" id="email_feed" method="post">
            <h3>Manage your email subscription</h3><br>
            Your are currently <strong><?php echo $_SESSION['username'] ?></strong> subscribed to email notifications.<br>
            <input type="checkbox" name="email_feed"><br>
            <input type="submit" name="submit" value="Change Subscription">
        </form>
    </div>

    <div class="errors">
        <?php
        if (empty($errors) && !empty($_POST)) {//no errors
            if ($_POST['submit'] == 'Change Username') {     //check what was clicked
                $update_data = array( 'username' => $_POST['new_username'] );
                if (update_user_info($update_data, $db)) {
                    header("location:account.php");
                }else{echo "Username could not be updated. Try again.";}
            }else if($_POST['submit'] == 'Change Password'){
                $update_data = array( 'password' => $_POST['new_password'] );
                if (update_user_info($update_data, $db)) {
                    echo "Password was successfully updated.";
                }else{echo "Password could not be updated. Try again.";}
            }else if($_POST['submit'] == 'Change Subscription'){
                $update_data = array( 'email_feed' => $_POST['email_feed'] );
                if (update_user_info($update_data, $db)) {
                    echo "Email subscription was successfully updated.";
                }else{echo "Email subscription could not be updated. Try again.";}
            }
        } else if (!empty($errors)) {
            echo output_errors($errors);
        } ?>
    </div>

<?php include 'includes/overall/footer.php';