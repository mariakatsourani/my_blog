<?php
include 'core/init.php';
include 'includes/overall/header.php';

$total_posts = count_posts($db);//for pagination
include 'includes/pagination.php';

$posts = get_posts($limit, $db);
output_posts($posts, $db);

?>

    <div id="pagination"><?php echo $pagination_links?></div>

<?php
include 'includes/overall/footer.php';
