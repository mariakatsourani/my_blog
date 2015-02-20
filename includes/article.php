<article id="<?php echo $posts[$i]['post_id'] ?>">
    <div class="post_date">
        <?php echo $posts[$i]['post_date'] ." " . ' ID: ' . $posts[$i]['post_id'] ?>
    </div>
    <h2 class="post_title">
        <a href="post.php?post_id=<?php echo $posts[$i]['post_id'] ?>"> <?php echo $posts[$i]['post_title'] ?></a>
    </h2>
    <div class="post_content">
        <?php echo substr($posts[$i]['post_content'], 0, 350). " ..." ?>
    </div>
    <div class="post_author">Posted by:
        <a href="by_user.php?username=<?php echo $posts[$i]['username'] ?>">
            <?php echo $posts[$i]['username'] ?>
        </a>
    </div>
    <div class="total_comments">
        <a href="post.php?post_id=<?php echo $posts[$i]['post_id'] ?>#list_comments"><?php
            if ($total_comments == 1){
                echo $total_comments . ' Comment';
            }else{
                echo $total_comments . ' Comments';
            }
            ?>
        </a>
    </div>
    <div class="post_divider"></div>
</article>
