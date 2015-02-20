<?php
function update_user_info($update_data, $db){
    if(isset($update_data['password'])){
        $update_data['password'] = password_hash($update_data['password'], PASSWORD_BCRYPT);
    }
    $fields= '';
    foreach($update_data as $key => $value){
        $fields .= $key . "=" . "'" . $value . "'";
    }
    $updateQuery = "UPDATE users SET $fields WHERE username= :username";
try{
    $updateDetailsStm = $db->prepare($updateQuery);
    $updateDetailsStm->bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);

    if ($updateDetailsStm->execute()){
        if(isset($update_data['username'])){
            $_SESSION['username'] = $update_data['username'];
        }
        return true;
    }else {
        return false;
    }
}
catch(Exception $e) {
    echo 'Exception -> ';
    var_dump($e->getMessage());
}

}

function get_privileges($user_id, $db){
    $getUserPrivStm = $db->prepare("SELECT user_role_id FROM users WHERE user_id = :user_id");
    $getUserPrivStm->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    if($getUserPrivStm->execute()){
        return $getUserPrivStm->fetchColumn(0);
    }else{
        echo "get_privileges failed to execute";
    }
}

function get_username($user_id, $db){
    $getUsernameStm = $db->prepare("SELECT username FROM users WHERE user_id = :user_id");
    $getUsernameStm->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    if($getUsernameStm->execute()){
        return $getUsernameStm->fetchColumn(0);
    }else{
        echo "get_username failed to execute";
    }
}

function get_user_id($username, $db){
    $getUserIdStm = $db->prepare("SELECT user_id FROM users WHERE username = :username");
    $getUserIdStm->bindParam(":username", $username, PDO::PARAM_STR);
    if($getUserIdStm->execute()){
        return $getUserIdStm->fetchColumn(0);
    }else{
        echo "get_user_id failed to execute";
    }
}

function check_password($username , $password, $db){
    $getPasswordStm = $db->prepare("SELECT password FROM users WHERE username = :username");
    $getPasswordStm->bindParam(':username' , $username , PDO:: PARAM_STR);

    if($getPasswordStm->execute()){
        if($getPasswordStm->rowCount() == 1){
            $stored_password = $getPasswordStm->fetchColumn(0);
            //return (password_verify($password, $stored_password)) ? true : false;
            if ( password_verify($password, $stored_password) ){
                return true;
            }else{
                return false;
            }
        }
    }else{ echo "Password match query execution failed!"; }
}

/*function login($username , $password, $db){
    if (check_password($username , $password, $db)){
        $loginStm = $db->prepare("SELECT user_id, username, password, user_role_id FROM users
                      WHERE username = :username AND password = :password");
        $loginStm->bindParam(':username' , $username , PDO:: PARAM_STR);
        $loginStm->bindParam(':password' , $stored_password, PDO:: PARAM_STR);
        if($loginStm->execute()){
            if($loginStm->rowCount() == 1){
                $_SESSION['status'] = "loggedin";
                //$_SESSION['user_id'] = $loginStm->fetchColumn(0);
                $_SESSION['username'] = $username;
                $_SESSION['user_role'] = $loginStm->fetchColumn(3);// 3 is the index of field in the statement
                return true;
            }else{ return false; }
        }else{ echo "Login query execution failed!"; }
    }
}*/

function login($username , $password, $db){
    $getPasswordStm = $db->prepare("SELECT password FROM users WHERE username = :username");
    $getPasswordStm->bindParam(':username' , $username , PDO:: PARAM_STR);

    if($getPasswordStm->execute()){
        if($getPasswordStm->rowCount() == 1){
            $stored_password = $getPasswordStm->fetchColumn(0);
            if ( password_verify($password, $stored_password) ){
                $loginStm = $db->prepare("SELECT user_id, username, password, user_role_id FROM users
                              WHERE username = :username AND password = :password");
                $loginStm->bindParam(':username' , $username , PDO:: PARAM_STR);
                $loginStm->bindParam(':password' , $stored_password, PDO:: PARAM_STR);
                if($loginStm->execute()){
                    if($loginStm->rowCount() == 1){
                        $_SESSION['status'] = "loggedin";
                        $_SESSION['user_id'] = $loginStm->fetchColumn(0);
                        //$_SESSION['username'] = $username;
                        //$_SESSION['user_role'] = $loginStm->fetchColumn(3);// 3 is the index of field in the statement
                        $_SESSION['user_role'] = get_privileges($_SESSION['user_id'], $db);
                        $_SESSION['username'] = get_username($_SESSION['user_id'], $db);
                        return true;
                    }else{ return false; }
                }else{ echo "Login query execution failed!"; }
            }
        }
    }else{ echo "Password match query execution failed!"; }
}

function user_exists($username, $db){
    $userExistsStm = $db->prepare("SELECT username FROM users WHERE username = :username");
    $userExistsStm->bindParam(':username' , $username , PDO:: PARAM_STR);

    if($userExistsStm->execute()){
        if($userExistsStm->rowCount() == 1){
            return true;
        }else{
            return false;
        }
    }else{
        echo "User exists match query execution failed!";
    }
}

function email_exists($email, $db){
    $emailExistsStm = $db->prepare("SELECT email FROM users WHERE email = :email");
    $emailExistsStm->bindParam(':email' , $email , PDO:: PARAM_STR);

    if($emailExistsStm->execute()){
        if($emailExistsStm->rowCount() == 1){
            return true;
        }else{
            return false;
        }
    }else{
        echo "Email exists match query execution failed!";
    }
}

function account_activated($username, $db){
    $activatedStm = $db->prepare("SELECT activated FROM users WHERE username = :username");
    $activatedStm->bindParam(':username', $username, PDO::PARAM_STR);

    if($activatedStm->execute()){
        $active = $activatedStm->fetchColumn(0);
        if($active == 1) {
            return true;
        }else{
            return false;
        }
    }else{
        echo "Activated query failed.";
    }
}

function register_user($registration_data, $db){
    $registration_data['password'] = password_hash($registration_data['password'], PASSWORD_BCRYPT);

    $fields = implode(", ", array_keys($registration_data));
    $values = ":" . implode(", :", array_keys($registration_data));

    $registerQuery = "INSERT INTO users ($fields) VALUES ($values)";

    $registerStm = $db->prepare($registerQuery);
    foreach ($registration_data as $key => &$value) {  //pass $value as a reference to the array item
        $registerStm->bindParam($key, $value);  // bind the variable to the statement
    }

    if ($registerStm->execute()){
        return true;
    }else {
        echo "Registration query failed.";
        return false;
    }
}

function logout(){
    $_SESSION = array();
    session_destroy();
}

function is_loggedin(){
    return (isset($_SESSION['status']) == 'loggedin') ? true : false;
}

function has_post_privileges(){
    return (($_SESSION['user_role'] == 2) || ($_SESSION['user_role'] == 1)) ? true : false;
}

function is_simple_user(){
    return ($_SESSION['user_role'] == 3)  ? true : false;
}

function is_blogger(){
    return ($_SESSION['user_role'] == 2)  ? true : false;
}

function is_admin(){
    return ($_SESSION['user_role'] == 1) ? true : false;
}