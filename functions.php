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


function vb_filter_posts() {

    if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'bobz' ) )
        die('Permission denied');

    /**
     * Default response
     */
    $response = [
        'status'  => 500,
        'message' => 'Something is wrong, please try again later ...',
        'content' => false,
        'found'   => 0
    ];

    $tax  = sanitize_text_field($_POST['params']['tax']);
    $term = sanitize_text_field($_POST['params']['term']);
    $page = intval($_POST['params']['page']);
    $qty  = intval($_POST['params']['qty']);

    /**
     * Check if term exists
     */
    if (!term_exists( $term, $tax) && $term != 'all-terms') :
        $response = [
            'status'  => 501,
            'message' => 'Term doesn\'t exist',
            'content' => 0
        ];

        die(json_encode($response));
    endif;

    if ($term == 'all-terms') :

        $tax_qry[] = [
            'taxonomy' => $tax,
            'field'    => 'slug',
            'terms'    => $term,
            'operator' => 'NOT IN'
        ];

    else :

        $tax_qry[] = [
            'taxonomy' => $tax,
            'field'    => 'slug',
            'terms'    => $term,
        ];

    endif;

    /**
     * Setup query
     */
    $args = [
        'paged'          => $page,
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $qty,
        'tax_query'      => $tax_qry
    ];

    $qry = new WP_Query($args);

    ob_start();
    if ($qry->have_posts()) :
        while ($qry->have_posts()) : $qry->the_post(); ?>

            <article class="loop-item">
                <header>
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                </header>
                <div class="entry-summary">
                    <?php the_excerpt(); ?>
                </div>
            </article>

        <?php endwhile;

        /**
         * Pagination
         */
        vb_ajax_pager($qry,$page);

        $response = [
            'status'=> 200,
            'found' => $qry->found_posts
        ];

    else :
        $response = [
            'status'  => 201,
            'message' => 'No posts found'
        ];
    endif;

    $response['content'] = ob_get_clean();

    die(json_encode($response));

}
add_action('wp_ajax_do_filter_posts', 'vb_filter_posts');
add_action('wp_ajax_nopriv_do_filter_posts', 'vb_filter_posts');





add_action('wp_ajax_search_form_function', 'search_form_callback');
add_action('wp_ajax_nopriv_search_form_function', 'search_form_callback');

function search_form_callback() {

    header('Content-Type: application/json');

    $category = 'all';

    if(isset($_GET['category'])) {
        $category = sanitize_text_field($_GET['category']);
    }

    $result = array();

    if($category === 'all') {
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1

        );
    }
    else {
        $args = array(
            'post_type' => 'post',
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => $category,
                ),
            ),
            'posts_per_page' => -1

        );
    }


    $search_form_query = new WP_Query($args);

    while( $search_form_query->have_posts()) {

        $search_form_query->the_post();
        $cat = get_the_category();

        $result[] = array(
            'id'            => get_the_ID(),
            'title'         => get_the_title(),
            'permalink'     => get_the_permalink(),
            'featuredImgUrl'=> get_the_post_thumbnail_url(get_the_ID(), 'custom-post-thumbnail'),
            'theExcerpt'    => get_the_excerpt(),
            'alt'           => get_post_meta(get_the_ID(), '_wp_attachment_image_alt', true),
            'category'      => get_the_category(get_the_ID())

        );
    };
    echo json_encode($result);
    wp_die();
}





















