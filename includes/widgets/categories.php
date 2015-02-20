<div class="widget" id="categories">
    <h3 class="widget_title">Categories</h3>
    <div class="widgets_content">
        <ul>
            <?php
            $categories = get_categories($db);
            $display_categories = '';
            foreach($categories as $category){
                $display_categories .= '<li><a href="by_category.php?category=' . $category . '&">' . $category .'</a></li>';
            }
            echo $display_categories;
            ?>
        </ul>
    </div>
</div>