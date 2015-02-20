<?php
include 'core/init.php';

if (is_loggedin()){
    logout();
}

header("location:index.php");
