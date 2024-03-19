<?php

// enqueue theme styles
add_action('wp_enqueue_scripts', 'theme_vite_styles');
function theme_vite_styles()
{
    if (!is_wp_error(wp_remote_get('http://localhost:5173/wp-content/themes/vite/@vite/client'))) {
        add_action('wp_footer', 'add_vite_dev_server_script');
        function add_vite_dev_server_script(){
    ?>
        <script type="module" src="http://localhost:5173/wp-content/themes/vite/@vite/client"></script>
        <script type="module" src="http://localhost:5173/wp-content/themes/vite/assets/scripts/main.js"></script>
    <?php
        }

        add_action('wp_head', 'add_vite_dev_stylesheet');
        function add_vite_dev_stylesheet() {
            ?>
            <link rel="stylesheet" href="http://localhost:5173/wp-content/themes/vite/assets/styles/main.scss">
            <?php
        }
    } else {
        wp_enqueue_style('theme-styles', get_stylesheet_directory_uri() . '/dist/styles.css' );
        wp_enqueue_script('theme-scripts', get_stylesheet_directory_uri() . '/dist/scripts.js' );
    }
}

// add theme support for editor styles
add_action('after_setup_theme', 'theme_vite_setup');
function theme_vite_setup()
{
    add_theme_support('wp-block-styles');
}

// preconect google fonts
add_action('wp_head', 'theme_vite_preconnect', 1);
function theme_vite_preconnect()
{
    echo '<link rel="preconnect" href="https://fonts.googleapis.com/">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>';
}

// enqueue google fonts
add_action('wp_enqueue_scripts', 'theme_vite_fonts');
function theme_vite_fonts()
{
    wp_enqueue_style('theme-vite-fonts', 'https://fonts.googleapis.com/css2?family=Figtree:wght@300;700;900&display=swap', false);
}
