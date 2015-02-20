<?php
function create_post($new_post_data, $db){
    //get category ids
    $categories = array();
    foreach($new_post_data['categories'] as $category){
        $categories[$category] = get_category_id($category, $db);
    }

    //insert into posts
    //$user_id = get_user_by_id($_SESSION['username'], $db);//get user id
    $insertPostStm = $db->prepare("INSERT INTO posts (post_title, post_content, post_author_id)
                                    VALUES (:post_title, :post_content, :post_author_id)");
    $insertPostStm->bindParam(":post_title", $new_post_data['post_title'], PDO::PARAM_STR);
    $insertPostStm->bindParam(":post_content", $new_post_data['post_content'], PDO::PARAM_STR);
    $insertPostStm->bindParam(":post_author_id", $_SESSION['user_id'], PDO::PARAM_INT);
    if($insertPostStm->execute()){
        $inserted_post_id = $db->lastInsertId();
        echo "Posted successfully";
    }else{
        echo "insertPostStm failed.";
    }
    //insert into posts_categories
    foreach($categories as $category_id){
        $insertCategoriesStm = $db->prepare("INSERT INTO posts_categories (post_id, category_id)
                                              VALUES (:post_id, :category_id)");
        $insertCategoriesStm->bindParam(":post_id", $inserted_post_id, PDO::PARAM_INT);
        $insertCategoriesStm->bindParam(":category_id", $category_id, PDO::PARAM_INT);
        if($insertCategoriesStm->execute()){
            echo "Categories inserted.";
        }else{
            echo "insertCategoriesStm failed.";
            echo "insertCategoriesStm failed.";
        }
    }
}

function get_category_id($category, $db){
    $getCategoryIdStm = $db->prepare("SELECT category_id FROM categories WHERE category_name = :category");
    $getCategoryIdStm->bindParam(":category", $category, PDO::PARAM_STR);
    if($getCategoryIdStm->execute()){
        return $getCategoryIdStm->fetchColumn(0);
    }else{
        echo "get_category_id query failed";
    }
}

function count_posts_by_user($username, $db){
    $countPostsUserStm = $db->prepare("SELECT post_id, post_title, post_content, post_date, username FROM posts
                                        INNER JOIN users ON posts.post_author_id=users.user_id
                                        WHERE username = :username");
    $countPostsUserStm->bindParam(":username", $username, PDO::PARAM_STR);
    if($countPostsUserStm->execute()){
        return $countPostsUserStm->rowCount();
    }else{
        echo "count_posts_by_user query failed miserably.";
    }
}

function get_posts_by_user($username, $limit, $db){//should have a $limit!!!
    $postsByUserStm = $db->prepare("SELECT post_id, post_title, post_content, post_date, username FROM posts
                                    INNER JOIN users ON posts.post_author_id=users.user_id
                                    WHERE username = :username
                                    ORDER BY post_date DESC $limit");
    $postsByUserStm->bindParam(':username', $username, PDO::PARAM_STR);
    if($postsByUserStm->execute()){
        return $postsByUserStm->fetchAll(PDO::FETCH_ASSOC);
    }else{
        echo "get_posts_by_user query failed.";
    }
}

function count_posts_by_search($search, $db){
    $search = "%$search%";
    $countPostsSearchStm = $db->prepare("SELECT NULL FROM posts
                                  WHERE post_title LIKE :search
                                  OR post_content LIKE :search");
    $countPostsSearchStm->bindParam(":search", $search, PDO::PARAM_STR);
    if($countPostsSearchStm->execute()){
        return $countPostsSearchStm->rowCount();
    }else{
        echo "count_posts_by_search query failed miserably.";
    }
}

function search_posts($search, $limit, $db){
    $search = "%$search%";
    $searchStm = $db->prepare("SELECT post_id, post_title, post_content, post_date, username FROM posts
                                  INNER JOIN users ON posts.post_author_id=users.user_id
                                  WHERE post_title LIKE :search
                                  OR post_content LIKE :search
                                  ORDER BY post_id DESC $limit");
    $searchStm->bindParam(":search", $search, PDO::PARAM_STR);
    if($searchStm->execute()){
        return $searchStm->fetchAll(PDO::FETCH_ASSOC);
    }else{
        echo "search_posts query failed";
    }
}

function get_categories($db){
    $getCategoriesStm = $db->prepare("SELECT category_name FROM categories");
    if ($getCategoriesStm->execute()){
        return $getCategoriesStm->fetchAll(PDO::FETCH_COLUMN, 0);//one dimensional array
    }else{
        echo "Execution of get_categories query failed.";
    }
}

function count_posts_by_category($category, $db){
    $countPostsCategoryStm = $db->prepare("SELECT NULL FROM posts
                                    INNER JOIN posts_categories ON posts.post_id=posts_categories.post_id
                                    INNER JOIN categories ON categories.category_id=posts_categories.category_id
                                    WHERE category_name=:category_name");
    $countPostsCategoryStm->bindParam(":category_name", $category, PDO::PARAM_STR);
    if($countPostsCategoryStm->execute()){
        return $countPostsCategoryStm->rowCount();
    }else{
        echo "count_posts_in_category query failed miserably.";
    }
}

function get_posts_by_category($category, $limit, $db){//should have a $limit!!!
    $postsByCategoryQuery ="SELECT posts.post_id, post_title, post_content, post_date, username FROM posts
                            INNER JOIN users ON posts.post_author_id=users.user_id
                            INNER JOIN posts_categories ON posts.post_id=posts_categories.post_id
                            INNER JOIN categories ON categories.category_id=posts_categories.category_id
                            WHERE categories.category_name=:category
                            ORDER BY post_date DESC $limit";
    $postsByCategoryStm = $db->prepare($postsByCategoryQuery);
    $postsByCategoryStm->bindParam(":category", $category, PDO::PARAM_STR);

    if ($postsByCategoryStm->execute()){
        return $postsByCategoryStm->fetchAll(PDO::FETCH_ASSOC);
    }else{
        echo "Execution of get_posts_by_category query failed.";
    }
}

function get_posts($limit, $db){
    $getPostsQuery ="SELECT post_id, post_title, post_content, post_date, username FROM posts
                                  INNER JOIN users ON posts.post_author_id=users.user_id
                                  ORDER BY post_date DESC $limit";
    $getPostsStm = $db->prepare($getPostsQuery);
    if ($getPostsStm->execute()){
        return $getPostsStm->fetchAll(PDO::FETCH_ASSOC);
    }else{
        echo "Execution of get_posts query failed.";
    }
}

function get_single_post($post_id, $db){
    $getPostStm = $db->prepare("SELECT post_id, post_title, post_content, post_date, username FROM posts
                                  INNER JOIN users ON posts.post_author_id=users.user_id
                                  WHERE post_id = :post_id");
    $getPostStm->bindParam(':post_id', $post_id, PDO::PARAM_INT);

    if ($getPostStm->execute()){
        return $getPostStm->fetch(PDO::FETCH_ASSOC);
    }else{
        echo "Execution of get_post query failed.";
    }
}

function count_posts($db){
    $countPostsStm = $db->prepare("SELECT NULL FROM posts");
    if($countPostsStm->execute()){
        return $countPostsStm->rowCount();
    }else{
        echo "count_posts query failed miserably.";
    }
}

// -------------------------- Comments -------------------------- //
function get_comments($post_id, $db){
    $getCommentsStm = $db->prepare("SELECT comment_id, comment_title, comment_content, comment_date, post_id, username FROM comments
                                    INNER JOIN users ON comments.comment_author_id=users.user_id
                                    INNER JOIN posts ON comments.comment_post_id=posts.post_id
                                    WHERE post_id = :post_id
                                    ORDER BY comment_date DESC");
    $getCommentsStm->bindParam(':post_id', $post_id, PDO::PARAM_INT);

    if ($getCommentsStm->execute()){
        return $getCommentsStm->fetchAll(PDO::FETCH_ASSOC);
    }else{
        echo "Execution of get_comments query failed.";
    }
}

function count_comments($post_id, $db){
    $countCommentsStm = $db->prepare("SELECT comment_id FROM comments WHERE comment_post_id = :post_id");
    $countCommentsStm->bindParam(':post_id' , $post_id , PDO:: PARAM_INT);

    if($countCommentsStm->execute()){
        return $countCommentsStm->rowCount();
    }else{
        echo "count_comments query execution failed!";
    }
}

function post_comment($post_comment_data, $db){
    //echo '<pre>',print_r($post_comment_data), '</pre>';
    $postCommentStm = $db->prepare("INSERT INTO comments (comment_title, comment_content, comment_date,
                                    comment_post_id, comment_author_id)
                                    VALUES (:comment_title, :comment_content, CURRENT_TIMESTAMP(),
                                    :comment_post_id ,:comment_author_id)");
    $postCommentStm->bindParam(':comment_title', $post_comment_data['comment_title'], PDO::PARAM_STR);
    $postCommentStm->bindParam(':comment_content', $post_comment_data['comment_content'], PDO::PARAM_STR);
    $postCommentStm->bindParam(':comment_post_id', $post_comment_data['comment_post_id'], PDO::PARAM_STR);
    $postCommentStm->bindParam(':comment_author_id', $post_comment_data['comment_author_id'], PDO::PARAM_STR);

    if($postCommentStm->execute()){
        return true;
    }else{
        echo "post_comment query execution failed!";
        return false;
    }
}



