<?php
    include 'core/init.php';
    include 'includes/overall/header.php';

    if (is_loggedin()){
        echo "You have to log out to register a new account.";
    }else {//ends at the bottom of the page before including footer
        if (!empty($_POST)) {       //------------ Validation ------------//
            $required_fields = array('username', 'email', 'first_name', 'password', 'repeat_password');
            foreach ($_POST as $key => $value) {
                if (empty($value) && in_array($key, $required_fields)) {
                    $errors[] = "Fields with asterisk are required.";
                    break;
                }
            }

            // not using else if statements in order to get a list of errors
            if (empty($errors)) {
                if (user_exists($_POST['username'], $db)) {
                    $errors[] = "Username " . $_POST['username'] . " already exists.";
                } else { //if username is unique check its validity
                    $errors = validate_username($_POST['username']);
                }

                if (email_exists($_POST['email'], $db)) {
                    $errors[] = "This email address is already in use.";
                } else { //if email is unique check its validity
                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {//browser doesn't allow invalid emails
                        $errors[] = "Enter a valid email address.";
                    }
                    if (mb_strlen($_POST['email']) > 255) {
                        $errors[] = "Email can not contain more than 255 characters.";
                    }
                }

                if ((mb_strlen($_POST['first_name']) < 2) || (mb_strlen($_POST['first_name']) > 40)) {
                    $errors[] = "First name must contain 2-40 characters.";
                }

                if (!empty($_POST['last_name'])) {  //if last name exists then should be minimum 2 char long
                    if ((mb_strlen($_POST['last_name']) < 2) || (mb_strlen($_POST['last_name']) > 50)) {
                        $errors[] = "Last name must contain 2-50 characters.";
                    }
                }

                if (mb_strlen($_POST['password']) < 6 || (mb_strlen($_POST['password']) > 35)) {// strlen counts bytes not characters
                    $errors[] = "Password must contain 6-35 characters.";
                } else {  //if password is valid check if the two match
                    if ($_POST['password'] !== $_POST['repeat_password']) {
                        $errors[] = "The passwords don't match.";
                    }
                }

            }
        } //------------ Validation ------------//

        //if already registered display msg else display form
        if (isset($_GET['success']) && empty($GET['success'])) {
            echo "You 've registered successfully! En activation email has been sent to you!";
        } else {//ends at the bottom of the page
            ?>
            <div id="registration_wrapper">
                <h3>Register here!</h3>

                <form id="registration" method="post" action="">
                    <label>Username*:</label><br>
                    <input type="text" name="username" value="<?php if (!empty($_POST['username'])) {
                        echo $_POST['username'];
                    } ?>" placeholder="Username"/><br>
                    <label>Email*:</label><br>
                    <input type="email" name="email" value="<?php if (!empty($_POST['username'])) {
                        echo $_POST['email'];
                    } ?>" placeholder="E-mail"/><br>
                    <label>First Name*:</label><br>
                    <input type="text" name="first_name" value="<?php if (!empty($_POST['username'])) {
                        echo $_POST['first_name'];
                    } ?>" placeholder="First Name"/><br>
                    <label>Last Name:</label><br>
                    <input type="text" name="last_name" value="<?php if (!empty($_POST['username'])) {
                        echo $_POST['last_name'];
                    } ?>" placeholder="Last Name"/><br>
                    <label>Password*:</label><br>
                    <input type="password" name="password" placeholder="Password"/><br>
                    <label>Repeat Password*:</label><br>
                    <input type="password" name="repeat_password" placeholder="Repeat password"/><br>

                    <input type="checkbox" name="email_feed" value="0"><label id="checkbox">Subscribe to email feed by clicking
                        the
                        box</label><br>

                    <input type="submit" name="submit" value="Register"/>
                </form>

                <div class="errors">
                    <?php if (empty($errors) && !empty($_POST)) {//no errors
                        if (isset($_POST['email_feed'])){
                            $_POST['email_feed'] = 1;
                        }else{
                            $_POST['email_feed'] = 0;
                        }
                        $registration_data = array(
                            'username' => $_POST['username'],
                            'email' => $_POST['email'],
                            'first_name' => $_POST['first_name'],
                            'last_name' => $_POST['last_name'],
                            'password' => $_POST['password'],
                            'email_feed' => $_POST['email_feed']
                        );
                        if (register_user($registration_data, $db)) {
                            header("location:register.php?success");
                        }
                    } else if (!empty($errors)) {
                        echo output_errors($errors);
                    } ?>
                </div>
            </div>
        <?php
        }//end else if already registered
    }//end else is_loggedin
    include 'includes/overall/footer.php';