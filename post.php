<?php
include 'core/init.php';

include 'includes/overall/header.php';

$post = get_single_post($_GET['post_id'], $db);
$comments = get_comments($post['post_id'], $db) ?>

<article id="<?php echo $post['post_id'] ?>">
    <div class="post_date"><?php echo $post['post_date'] ." "; echo ' ID: ' . $post['post_id'] ?></div>
    <h2 class="post_title"><?php echo $post['post_title'] ?></h2>
    <div class="post_content">
        <?php echo $post['post_content'] ?>
    </div>
    <div class="post_author">Posted by: <a href="#"><?php echo $post['username'] ?></a></div><br>♒
</article>

 <!-- Comments -->
<div id="list_comments">
    <div class="total_comments">
        <?php if (count($comments) == 1){ echo count($comments) . ' Comment'; }
                else{ echo count($comments) . ' Comments'; } ?>
    </div>
    <?php
        for ($i = 0; $i < count($comments); $i++){
            include 'includes/comment.php';
        }
    ?>
</div>

 <!-- Post Comment -->
<?php if(is_loggedin()){
        if (!empty($_POST)) {
            if (empty($_POST['comment_content'])) {
                $errors[] = "A comment can not be blank.";
            }
            if(mb_strlen($_POST['comment_title']) > 100){
                $errors[] = "The title of a comment cont not be longer than 100 characters.";
            }
            if(mb_strlen($_POST['comment_content']) > 500){
                $errors[] = "The body of a comment cont not be longer than 500 characters.";
            }
        }

    /*if (isset($_GET['success']) && empty($GET['success'])) {
        echo "You 've registered successfully! En activation email has been sent to you!";
    }*/

    ?>

        <div id="post_comment">
            <h4>Post a comment...</h4>
            <form method="post">
                <input type="text" name="comment_title" placeholder="Enter comments' title here... (optional)">
                <input type="text" name="comment_content" placeholder="Enter comment here...">

                <input type="submit" name="submit" value="Post Comment">
            </form>
        </div>

<?php }//end if is_loggedin()

    if (empty($errors) && !empty($_POST)) {//no errors
        $post_comment_data = array(
            'comment_title' => $_POST['comment_title'],
            'comment_content' => $_POST['comment_content'],
            'comment_post_id' => $_GET['post_id'],
            'comment_author_id' => get_user_id($_SESSION['username'], $db)
        );
        if (post_comment($post_comment_data, $db)) {
            header('location:post.php?post_id=' . $_GET['post_id']);
        }
    } else if (!empty($errors)) {
        echo output_errors($errors);
    }

include 'includes/overall/footer.php';