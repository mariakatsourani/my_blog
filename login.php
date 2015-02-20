<?php
    include 'core/init.php';

    if (!empty($_POST)){
        $username = $_POST['username'];
        $password = $_POST['password'];

        if( empty($_POST["username"]) || empty($_POST["password"]) ){
            $errors[] = "You must enter a username and a password to log in!";
        }else if( !user_exists($_POST["username"], $db) ){
            $errors[] = "This user does not exist.";
        }else if( !account_activated($_POST["username"], $db) ){
            $errors[] = "Your account is not activated. Resend email?";
        }else if ( !login($_POST["username"], $_POST["password"], $db) ){
            $errors[] = "Wrong combination of username and password.";//pwd is wrong, user doesn't exist
        }
    }else{
        header("location:index.php");
    }

    include 'includes/overall/header.php';

    if (!$errors){
        //header('location:' . $_SERVER['REQUEST_URI']);
        header("location:index.php");    //no errors
    }else{
        echo output_errors($errors);
    }
?>

<?php
include 'includes/overall/footer.php';