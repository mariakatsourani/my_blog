<?php
function validate_password($password, $repeat_password){
    $errors = array();
    if (mb_strlen($password) < 6 || (mb_strlen($password) > 35)) {// strlen counts bytes not characters
        $errors[] = "Password must contain 6-35 characters.";
    } else {  //if password is valid check if the two match
        if ($password !== $repeat_password) {
            $errors[] = "The passwords don't match.";
        }
    }
    return $errors;
}

function validate_username($username){
    $errors = array();
    if (preg_match("/\\s/", $username) == true) {//further regular expression checks needed
        $errors[] = "Username can not contain spaces.";
    }
    if ((mb_strlen($username) < 4) || (mb_strlen($username) > 25)) {
        $errors[] = "Username must contain 4-25 characters."; //?
    }
    return $errors;
}

function protected_page(){//makes a page visible only to logged in users
    if (!is_loggedin()){
        header("location:index.php");
    }
}

function get_vars_in_string(){
    //echo '<pre>' , print_r($_GET), '</pre>';
    $get_vars = '';
    foreach ($_GET as $key => $value){
        $get_vars.= '&' . $key . '=' . $value;
    }
    return $get_vars;
}

function output_errors($errors){
    return '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>';
}

function output_posts($posts, $db){
    foreach($posts as $i => $post){     //for($i = 0; $i < count($posts); $i++)
        $total_comments = count_comments($posts[$i]['post_id'], $db);
        include 'includes/article.php';
    }
}

//sanitize data from forms

function hash_passwords($db){
    $stm = $db->query("SELECT user_id, password FROM users WHERE LENGTH(password) < 60");
    $result = $stm->fetchAll(PDO::FETCH_NUM);
    //echo '<pre>' , print_r($result) , '</pre>';

    foreach($result as $row){
        //echo "user_id: " . $row[0];
        //echo "pwd: " . $row[1];
        $row[] = password_hash($row[1], PASSWORD_BCRYPT);
        $hashPass = $db->prepare("UPDATE users SET password=:hashed_password WHERE user_id=:user_id");
        $hashPass->bindParam(':hashed_password', $row[2], PDO::PARAM_STR);
        $hashPass->bindParam(':user_id', $row[0], PDO::PARAM_STR);
        if($hashPass->execute()) {
            echo "update complete";
        }else{
            echo "there was a problem";
        }
        //echo "hashed pwd: " . $row[2];
        //echo "<br>";
    }
}