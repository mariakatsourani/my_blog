<?php
include 'core/init.php';
include 'includes/overall/header.php';

if(isset($_GET['search_box']) && !empty($_GET['search_box'])){
    $search = filter_input(INPUT_GET, 'search_box', FILTER_SANITIZE_SPECIAL_CHARS);


    $total_posts = count_posts_by_search($search, $db);
    echo "Search for <strong>" . $search . "</strong> returned " . $total_posts . " results.<br><br>";

    include 'includes/pagination.php';
    $search_results = search_posts($search, $limit, $db);

    output_posts($search_results, $db);
    echo '<div id="pagination">' . $pagination_links . '</div>';
}else{
    echo "Your query returned no results.";
}
include 'includes/overall/footer.php';