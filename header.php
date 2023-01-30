<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="header">
    <div class="site-branding">
        <?php
        the_custom_logo();
        if (is_front_page() && is_home()) :
            ?>
            <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                      rel="home"><?php bloginfo('name'); ?></a></h1>
        <?php
        else :
            ?>
            <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                     rel="home"><?php bloginfo('name'); ?></a></p>
        <?php
        endif;
        ?>

        <?php
        wp_nav_menu( array(
                'menu'              => 'Lang',
                'theme_location'    => 'lang',
                'depth'             => 2,
                'container'         => 'true',
                'menu_class'        => 'nav nav-list',
             //   'fallback_cb'       => 'wp_bootstrap_navlist_walker::fallback',
              //  'walker'			=> new WP_Bootstrap_Navwalker()
            )
        );
        ?>
</header>