<?php
    include 'core/init.php';
    include 'includes/overall/header.php';

    //check if logged in and privileges
    if(!is_loggedin() || is_simple_user()){
        header("location:index.php");
    }else {//ends at the bottom of the page
        if (!empty($_POST)) {
            $required_fields = array('post_title', 'post_content');
            foreach ($_POST as $key => $value) {
                if (empty($value) && in_array($key, $required_fields)) {
                    $errors[] = "All fields are required.";
                    break;
                }
            }
            if(empty($_POST['category'])){
                $errors[] = "You must enter at least one category.";
            }

            if (empty($errors)) {//perform this check only if all required fields have data
                if ((mb_strlen($_POST['post_title']) < 2) || (mb_strlen($_POST['post_title']) > 150)) {
                    $errors[] = "Posts' title must contain 2-150 characters.";
                }
                if ((mb_strlen($_POST['post_title']) < 2) || (mb_strlen($_POST['post_title']) > 150)) {
                    $errors[] = "Posts' title must contain 2-150 characters.";
                }
            }
        }

        //if post created display msg else display form
        if (isset($_GET['success']) && empty($GET['success'])) {
            echo "Your post has been inserted successfully.";
        } else {//ends at the bottom of the page
            ?>

            <form id="new_post" method="post">
                <input type="text" name="post_title" value="<?php if (isset($_POST['post_title'])) echo $_POST['post_title']; ?>" placeholder="Enter the title here...(required field)">

                <div id="list_categories">
                    <br>Choose the categories, in which the post will appear...<br><br>
                    <?php
                    $categories = get_categories($db);
                    foreach ($categories as $category) {
                        echo '<span class="categories">' . $category . '</span>';
                        echo '<input type="checkbox" name="category[]" value="' . $category . '">';
                    }

                    ?>
                </div>
                <textarea name="post_content" placeholder="Type the post here...(required field)">
                    <?php if (isset($_POST['post_content'])) echo $_POST['post_content']; ?>
                </textarea><br>

                <input type="submit" name="submit" value="Submit post">
            </form>

            <div id="errors">
                <?php if (empty($errors) && !empty($_POST)) {//no errors
                    $new_post_data = array(
                        'post_title' => $_POST['post_title'],
                        'categories' => $_POST['category'],
                        'post_content' => $_POST['post_content']
                    );
                    if (create_post($new_post_data, $db)) {
                        header("location:create_post.php?success");
                    }
                } else if (!empty($errors)) {
                    echo output_errors($errors);
                } ?>
            </div>

        <?php
            //var_dump($_POST);
        }//end if display form
    }//end if not redirected
    include 'includes/overall/footer.php';