<?php




if ( ! function_exists( 'solbegtheme_setup' ) ) :


    function solbegtheme_setup() {

        /**
         * Make theme available for translation.
         * Translations can be placed in the /languages/ directory.
         */
      //  load_theme_textdomain( 'solbegtheme', get_template_directory() . '/languages' );

        /**
         * Add default posts and comments RSS feed links to <head>.
         */
        add_theme_support( 'automatic-feed-links' );

        /**
         * Enable support for post thumbnails and featured images.
         */


        add_theme_support( 'post-thumbnails' );
        add_image_size( 'custom-post-thumbnail', 300, 200, true );

        /**
         * Add support for two custom navigation menus.
         */

        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            )
        );


        register_nav_menus( array(
            'primary'   => __( 'Primary Menu', 'solbegtheme' ),
            'secondary' => __( 'Secondary Menu', 'solbegtheme' ),
          //  'lang' => __( 'Lang', 'solbegtheme' ),
        ) );

        /**
         * Enable support for the following post formats:
         * aside, gallery, quote, image, and video
         */
        add_theme_support( 'post-formats', array( 'aside', 'gallery', 'quote', 'image', 'video' ) );
    }
endif;
add_action( 'after_setup_theme', 'solbegtheme_setup' );



require get_template_directory() . '/inc/function-admin.php';
require get_template_directory() . '/inc/custom-fields.php';
require get_template_directory() . '/inc/posts-filter.php';







function load_solbegtheme_styles_scripts() {
    wp_enqueue_style( 'style', get_stylesheet_uri() );
    wp_enqueue_style( 'solbegtheme', get_template_directory_uri() . '/css/solbegtheme.css', false, '1.0.0', 'all');
    wp_enqueue_script( 'solbegtheme', get_template_directory_uri() . '/js/solbegtheme.js',array('jquery'), '1.0.0', '1.0.0' );
    wp_localize_script('solbegtheme','ajax_url', admin_url('admin-ajax.php'));
}
add_action( 'wp_enqueue_scripts', 'load_solbegtheme_styles_scripts' );






function load_admin_solbegtheme_styles_scripts() {
    wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/css/solbegtheme.admin.css', false, '1.0.0' );
    wp_enqueue_script( 'admin_js', get_template_directory_uri() . '/js/solbegtheme.admin.js',array('jquery'), '1.0.0', '1.0.0' );
}

add_action( 'admin_enqueue_scripts', 'load_admin_solbegtheme_styles_scripts' );





//  localization

add_action( 'after_setup_theme', 'solbegtheme_setup_languages');
function solbegtheme_setup_languages(){
    load_theme_textdomain( 'solbegtheme', get_template_directory() . '/languages' );
}






//
//
//function add_meta_to_posts(){
//    if(is_admin()) {
//        global $post;
//
//        $example = new WP_Query(array('nopaging' => true,'post_type' => 'post'));
//
//
//        while ( $example->have_posts() ) : $example->the_post();
//
//            add_post_meta($post->ID, 'test_field', 'true', true);
//
//        endwhile;
//    }
//}
//
//add_action( 'admin_init', 'add_meta_to_posts', 1 );


