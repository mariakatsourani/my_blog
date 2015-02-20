<?php
include 'core/init.php';
include 'includes/overall/header.php';

if(isset($_GET['username']) && !empty($_GET['username'])){
    $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_SPECIAL_CHARS);

    $total_posts = count_posts_by_user($username, $db);
    echo "There are " . $total_posts . " posts by <strong>" . $username . "</strong><br><br>";

    include 'includes/pagination.php';
    $posts = get_posts_by_user($username, $limit, $db);

    output_posts($posts, $db);
    echo '<div id="pagination">' . $pagination_links . '</div>';
}else{
    echo "No username selected.";
}

//pagination
include 'includes/overall/footer.php';