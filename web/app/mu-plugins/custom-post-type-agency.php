<?php

namespace CustomElements\RandstadAgencyPlugin;

/**
 * Jobs plugin
 *
 * @since             1.0.0
 * @package           RandstadAgencyPlugin
 *
 * @wordpress-plugin
 * Plugin Name:       Randstad Agency Plugin
 * Plugin URI:
 * Description:       The event plugin that adds rest functionality
 * Version:           1.0.0
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       agency-cpt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Class that holds all the necessary functionality for the
 * Event custom post type
 *
 * @since  1.0.0
 */
class Agency
{
    /**
     * The custom post type slug
     *
     * @var string
     *
     * @since 1.0.0
     */
    const PLUGIN_NAME = 'agencies';
    const PLUGIN_DOMAIN = 'agency-cpt';

    /**
     * The custom post type slug
     *
     * @var string
     *
     * @since 1.0.0
     */
    const POST_TYPE_SLUG = 'agency';

    /**
     * The custom taxonomy type slug
     *
     * @var string
     *
     * @since 1.0.0
     */
    const TAXONOMY_CAT_SLUG = 'agency-category';
    const TAXONOMY_TAGS_SLUG = 'agency-tags';

    function __construct()
    {
        add_action('init', [$this, 'registerPostType']);
        add_action('init', [$this, 'registerTaxonomy']);
    }

    public function getName()
    {
        return self::POST_TYPE_SLUG;
    }

    /**
     * Register custom post type
     *
     * @since 1.0.0
     */
    public function registerPostType()
    {

        $label_singular = 'agency';
        $label_plural = 'agencies';

        $args = array(
            'description' => '',
            'public' => true,
            'menu_position' => 2,
            'menu_icon' => 'dashicons-list-view',
            'supports' => array(
                'title',
                'revisions',
                'thumbnail',
                'custom-fields',
                'post-formats'
            ),
            'has_archive' => true,
            'show_in_rest' => true,
            'publicly_queryable' => true,
            'labels' => array(
                'name' => esc_html(ucfirst($label_plural), self::PLUGIN_DOMAIN),
                'singular_name' => ucfirst($label_singular),
                'menu_name' => 'Agences',
                'add_new' => 'Ajouter une ' . $label_singular,
                'add_new_item' => 'Ajouter une nouvelle' . $label_singular,
                'edit' => 'Editer',
                'edit_item' => 'Editer le ' . $label_singular,
                'new_item' => 'Nouveau ' . $label_singular,
                'view' => 'Voir ' . $label_singular,
                'view_item' => 'Voir ' . $label_singular,
                'search_items' => 'Recherche ' . $label_plural,
                'not_found' => 'Pas de ' . $label_singular . ' trouv??',
                'not_found_in_trash' => 'Pas de ' . $label_singular . ' trouv?? dans la corbeille',
                'parent' => 'Parent ' . $label_singular,
            ),
        );

        // Wordpress method
        register_post_type(self::POST_TYPE_SLUG, $args);
    }

    /**
     * Register custom tag taxonomy
     *
     * @since 1.0.0
     */
    public function registerTaxonomy()
    {
        // Category taxonomy
        $labelsCategory = array(
            'name' => _x('Cat??gories', self::TAXONOMY_CAT_SLUG, self::PLUGIN_DOMAIN),
            'singular_name' => _x('Cat??gorie', self::TAXONOMY_CAT_SLUG, self::PLUGIN_DOMAIN),
            'search_items' => __('Rechercher une cat??gorie', self::PLUGIN_DOMAIN),
            'all_items' => __('Toutes les cat??gories', self::PLUGIN_DOMAIN),
            'parent_item' => __('Cat??gorie parente', self::PLUGIN_DOMAIN),
            'parent_item_colon' => __('Cat??gorie parente:', self::PLUGIN_DOMAIN),
            'edit_item' => __('??diter la cat??gorie', self::PLUGIN_DOMAIN),
            'update_item' => __('Mettre ?? jour la cat??gorie', self::PLUGIN_DOMAIN),
            'add_new_item' => __('Ajouter une nouvelle cat??gorie', self::PLUGIN_DOMAIN),
            'new_item_name' => __('Nom de la nouvelle cat??gorie', self::PLUGIN_DOMAIN),
            'menu_name' => __('Cat??gories', self::PLUGIN_DOMAIN),
        );

        $argsCategory = array(
            'hierarchical' => false,
            'labels' => $labelsCategory,
            'label' => __('Cat??gories', self::PLUGIN_DOMAIN),
            'show_ui' => true,
            'show_admin_column' => true,
            'update_count_callback' => '_update_post_term_count',
            'show_in_rest' => true,
            'query_var' => true,
        );

        // Wordpress method
        register_taxonomy(self::TAXONOMY_CAT_SLUG, [self::POST_TYPE_SLUG], $argsCategory);

        // Tags taxonomy
        $labelsTags = array(
            'name' => _x('Tags', self::TAXONOMY_CAT_SLUG, self::PLUGIN_DOMAIN),
            'singular_name' => _x('Tag', self::TAXONOMY_CAT_SLUG, self::PLUGIN_DOMAIN),
            'search_items' => __('Rechercher un tag', self::PLUGIN_DOMAIN),
            'all_items' => __('Tous les tags', self::PLUGIN_DOMAIN),
            'parent_item' => __('Tag parent', self::PLUGIN_DOMAIN),
            'parent_item_colon' => __('Tag parent:', self::PLUGIN_DOMAIN),
            'edit_item' => __('??diter le tag', self::PLUGIN_DOMAIN),
            'update_item' => __('Mettre ?? jour le tag', self::PLUGIN_DOMAIN),
            'add_new_item' => __('Ajouter un nouveau tag', self::PLUGIN_DOMAIN),
            'new_item_name' => __('Nom du nouveau tag', self::PLUGIN_DOMAIN),
            'menu_name' => __('Tags', self::PLUGIN_DOMAIN),
        );

        $argsTags = array(
            'hierarchical' => false,
            'labels' => $labelsTags,
            'show_ui' => true,
            'show_admin_column' => true,
            'update_count_callback' => '_update_post_term_count',
            'show_in_rest' => true,
            'query_var' => true,
        );

        // Wordpress method
        register_taxonomy(self::TAXONOMY_TAGS_SLUG, [self::POST_TYPE_SLUG], $argsTags);
    }
}

$agency = new Agency();
