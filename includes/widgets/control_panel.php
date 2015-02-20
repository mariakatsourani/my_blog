<div class="widget" id="control_panel">
    <h3 class="widget_title">Control Panel</h3>
    <div class="widgets_content">
        Hey,<strong>
            <?php
                if (isset($_SESSION['status']) == 'loggedin'){
                    echo $_SESSION['username'];
                }
            ?> </strong> ! You have
            <?php
            if (isset($_SESSION['status']) == 'loggedin'){
                if (($_SESSION['user_role']) == 1){
                    echo 'admin ';
                }else if(($_SESSION['user_role']) == 2){
                    echo 'blogger ';
                }else{
                    echo 'simple user ';
                }
            }
            ?>privileges.
        <br><a href="logout.php">Log out</a>

        <ul>
            <li><a href="account.php">Account Settings</a></li>
            <li><a href="#">See all your comments here.</a></li>
            <?php if(has_post_privileges()){//bloggers, admins ?>
                    <li><a href="create_post.php">Create a post.</a></li>
                    <li><a href="by_user.php?username=<?php echo $_SESSION['username'] ?>">See all your posts here.</a></li>
            <?php } ?>
            <?php if(is_admin()){//admins ?>
                    <li><a href="#">Burn down the site.</a></li>
            <?php } ?>
        </ul>
    </div>
</div>