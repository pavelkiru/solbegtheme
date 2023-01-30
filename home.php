<?php

get_header();
?>


    <div class="content">
        <h1 class="title"><?php _e('Home page' , 'solbegtheme');?></h1>
        <p><?php _e('Install WPML, add lang switcher and translate will work', 'solbegtheme');?></p>

        <div class="all_categories">
            <ol>
                <?php
                $categories = get_categories([
                    'orderby' => 'name',
                    'order' => 'ASC'
                ]);

                foreach ($categories as $category) {
                    $link = get_category_link($category->term_id);
                    echo "<li><a href='$link'>$category->name</a></li>";

                }
                ?>
            </ol>
        </div>

<!--         second variant-->

<!--        <div class="all_categories">-->
<!--            <ul>-->
<!--                --><?php
//                $args = array(
//                    'orderby' => 'name',
//                    'order' => 'ASC',
//                    'style' => 'list',
//                    'show_count' => 1,
//                    'hide_empty' => 0,
//                    'title_li' => '',
//                );
//                wp_list_categories($args);
//                ?>
<!--            </ul>-->
<!--        </div>-->


        <div class="search_section">
            <form action="" class="search_section_form">
                <label for="" class="category_label"><?php _('Category', 'solbegtheme')?></label>
                <select name="category" id="category">
                    <option value='all'>all</option>
                    <?php
                        foreach ($categories as $category) {
                            $link = get_category_link($category->term_id);
                            echo "<option value='$category->name'>$category->name</option>";
                        }
                    ?>
                </select>
            </form>

        </div>


        <div class="search_section_content_wr d_fl_cc">
            <div class="search_section_content posts">

            </div>

            <div class="form_loader_img_wr">
                <img
                    src="<?php echo get_stylesheet_directory_uri() . '/images/loading.png' ?>"
                    id="form_loader_img"
                    alt="loading"
                >
            </div>
        </div>

        <div class='pagination' id="pagination"></div>

    </div>
    </div>

<?php
get_sidebar();
get_footer();
