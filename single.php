<?php

get_header();
?>

    <main id="primary" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content', get_post_type() );


            echo 'Custom fields';
            echo "<br>";
            echo 'title: ' . get_post_meta($post->ID, 'title', true);
            echo "<br>";
            echo 'description: ' .get_post_meta($post->ID, 'description', true);

        endwhile;
        ?>
        <?php  ?>
    </main>

<?php
get_sidebar();
get_footer();
