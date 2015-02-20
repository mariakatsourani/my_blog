<?php
include 'core/init.php';
include 'includes/overall/header.php';

if(isset($_GET['category']) && !empty($_GET['category'])){
    $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_SPECIAL_CHARS);

    $total_posts = count_posts_by_category($category, $db);
    echo $total_posts . " posts were found about <strong>" . $category . "</strong><br><br>";

    include 'includes/pagination.php';
    $posts = get_posts_by_category($category, $limit, $db);

    output_posts($posts, $db);
    echo '<div id="pagination">' . $pagination_links . '</div>';
}else{
    echo "No category selected.";
}

include 'includes/overall/footer.php';
