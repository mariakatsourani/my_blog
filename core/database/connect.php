<?php
$user = 'root';
$pass = '';
try {
    $db = new PDO('mysql:host=localhost;dbname=my_blog', $user, $pass);
    /*foreach($db->query('SELECT * from users') as $row) {
            echo '<pre>' , print_r($row),  '</pre>';
    }*/
    //return $db;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
}


