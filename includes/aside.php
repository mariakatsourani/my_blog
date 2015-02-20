<aside>
    <?php
    //session_start();
    include 'includes/widgets/search.php';
    if (is_loggedin()){
        include 'includes/widgets/control_panel.php';
    }else{
        include 'includes/widgets/login.php';
    }
    include 'includes/widgets/about.php';
    include 'includes/widgets/categories.php'
    ?>
</aside>