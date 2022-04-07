<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, null);
    wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage')
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('styles/main.css'));
}, 20);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ];
    register_sidebar([
            'name' => __('Primary', 'sage'),
            'id' => 'sidebar-primary'
        ] + $config);
    register_sidebar([
            'name' => __('Footer', 'sage'),
            'id' => 'sidebar-footer'
        ] + $config);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
    sage('blade')->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
    /**
     * Add JsonManifest to Sage container
     */
    sage()->singleton('sage.assets', function () {
        return new JsonManifest(config('assets.manifest'), config('assets.uri'));
    });

    /**
     * Add Blade to Sage container
     */
    sage()->singleton('sage.blade', function (Container $app) {
        $cachePath = config('view.compiled');
        if (!file_exists($cachePath)) {
            wp_mkdir_p($cachePath);
        }
        (new BladeProvider($app))->register();
        return new Blade($app['view']);
    });

    /**
     * Create @asset() Blade directive
     */
    sage('blade')->compiler()->directive('asset', function ($asset) {
        return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
    });
});


/**
 * Extras admin assets
 */
// Load Gutenberg Editor Styles
function megademic_gutenberg_editor_styles()
{

}

add_action(
    'admin_enqueue_scripts',
    function () {
        // wp_enqueue_style('sage/admin/editor.css', asset_path('styles/admin/admin.css'), false, null);
        wp_enqueue_style('sage/admin.css', asset_path('styles/admin.css'), false, null);
    }, 100
);

function custom_setup_theme_supported_features()
{
    // Editor styles (with dark mode)
    add_theme_support('editor-styles');
    add_theme_support('dark-editor-style');
    // Default wordpress styles block
    add_theme_support('wp-block-styles');
    // Responsive embed
    add_theme_support('responsive-embeds');
    // Les fonctions vont dans ce hook
    add_theme_support('editor-color-palette',
        array(
            array('name' => 'blue', 'slug' => 'blue', 'color' => '#48ADD8'),
            array('name' => 'pink', 'slug' => 'pink', 'color' => '#FF2952'),
            array('name' => 'green', 'slug' => 'green', 'color' => '#83BD71'),
        )
    );

    // Enable wide mode
    add_theme_support('align-wide');

    // Disable custom color
    add_theme_support('disable-custom-colors');

    // Titles settings
    /*
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => __('small', 'themeLangDomain'),
            'shortName' => __('S', 'themeLangDomain'),
            'size' => 12,
            'slug' => 'small'
        ),
        array(
            'name' => __('regular', 'themeLangDomain'),
            'shortName' => __('M', 'themeLangDomain'),
            'size' => 16,
            'slug' => 'regular'
        ),
        array(
            'name' => __('large', 'themeLangDomain'),
            'shortName' => __('L', 'themeLangDomain'),
            'size' => 36,
            'slug' => 'large'
        ),
        array(
            'name' => __('larger', 'themeLangDomain'),
            'shortName' => __('XL', 'themeLangDomain'),
            'size' => 50,
            'slug' => 'larger'
        )
    ));
    */
}

add_action('after_setup_theme', __NAMESPACE__ . '\\custom_setup_theme_supported_features');


