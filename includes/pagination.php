<?php

$posts_per_page = 2;//show 3 posts per page
$last_page = ceil($total_posts/$posts_per_page);
if($last_page < 1){//last page can be 1 or >1
    $last_page = 1;
}
$page_num = 1;//current page
if(isset($_GET['page'])){
    $page_num = preg_replace('#[^0-9]#', '', $_GET['page']);//make sure it is an int
}

if($page_num < 1){//keep page number within range
    $page_num = 1;
}else if($page_num > $last_page){
    $page_num = $last_page;
}

$limit = 'LIMIT '.($page_num-1)*$posts_per_page.','.$posts_per_page;

$pagination_links = '';
$get_vars = get_vars_in_string();
//echo "current GET:   " . $get_vars;

if($last_page != 1){//if we have more than one page
    if($page_num > 1){//if on a page greater than 1 previous pages exist
        $previous_page = $page_num - 1;
        $pagination_links .= '<span id="previous_page"><a href="'. $_SERVER['PHP_SELF'] .'?'. $get_vars . '&page=' . $previous_page . '">Previous</a></span> ';
        /*for($i = $page_num - 4; $i < $page_num; $i++){//left side links to previous pages
            if($i > 0){
                $pagination_links .= '<a href="'. $_SERVER['PHP_SELF'] .'?page=' . $i . '">' . $i . '</a> ';
            }
        }*/
    }
    //$pagination_links .= '<span class="current_page"> ' . $page_num . ' </span>';//current page
    /*for($i = $page_num + 1; $i <= $last_page; $i++){//right side links to next pages
        $pagination_links .= '<a href="'. $_SERVER['PHP_SELF'] .'?page=' . $i . '">' . $i . '</a> ';
        if($i >= $page_num + 4){
            break;
        }
    }*/
    if($page_num < $last_page){//if not on the last page display next link
        $next_page = $page_num + 1;
        $pagination_links.= '<span id="next_page"><a href="'. $_SERVER['PHP_SELF'] .'?'.$get_vars .'&page=' . $next_page . '">Next</a> </span>';
    }
}

//----------- Pagination No2 -----------//
/*try {

    // Find out how many items are in the table
    $total = $dbh->query('
        SELECT
            COUNT(*)
        FROM
            table
    ')->fetchColumn();

    // How many items to list per page
    $limit = 20;

    // How many pages will there be
    $pages = ceil($total / $limit);

    // What page are we currently on?
    $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
        'options' => array(
            'default'   => 1,
            'min_range' => 1,
        ),
    )));

    // Calculate the offset for the query
    $offset = ($page - 1)  * $limit;

    // Some information to display to the user
    $start = $offset + 1;
    $end = min(($offset + $limit), $total);

    // The "back" link
    $prevlink = ($page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

    // The "forward" link
    $nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

    // Display the paging information
    echo '<div id="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results ', $nextlink, ' </p></div>';

    // Prepare the paged query
    $stmt = $dbh->prepare('
        SELECT
            *
        FROM
            table
        ORDER BY
            name
        LIMIT
            :limit
        OFFSET
            :offset
    ');

    // Bind the query params
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Do we have any results?
    if ($stmt->rowCount() > 0) {
        // Define how we want to fetch the results
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $iterator = new IteratorIterator($stmt);

        // Display the results
        foreach ($iterator as $row) {
            echo '<p>', $row['name'], '</p>';
        }

    } else {
        echo '<p>No results could be displayed.</p>';
    }

} catch (Exception $e) {
    echo '<p>', $e->getMessage(), '</p>';
}
*/