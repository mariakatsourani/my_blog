<!-- individual comment -->
<div class="comment" id="<?php echo $comments[$i]['comment_id'] ?>">
    <div class="comment_author">
        ↛ Posted by&nbsp;
        <span class="username">
            <a href="by_user.php?username=<?php echo $comments[$i]['username']?>"><?php echo $comments[$i]['username'] ?></a>
        </span>
    </div>
    <div class="comment_date">
        &nbsp;on&nbsp;<span class="date"><?php echo $comments[$i]['comment_date'] ?></span>
    </div>
    <div class="comment_title"><?php echo $comments[$i]['comment_title'] ?></div>
    <div class="comment_content"><?php echo $comments[$i]['comment_content'] ?></div>
    <?php if(is_loggedin()){
        if ( ($_SESSION['username'] == $comments[$i]['username']) || ($_SESSION['user_role'] == 1) ){ ?>
            <div class="delete_comment"><a href="<?php echo $_SERVER['REQUEST_URI']?>?delete_comment=true">Delete Comment</a></div>
<?php   }
    }?>
</div>
