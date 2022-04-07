<?php

namespace CustomElements\RandstadJobPlugin;

/**
 * Jobs plugin
 *
 * @since             1.0.0
 * @package           RandstadJobPlugin
 *
 * @wordpress-plugin
 * Plugin Name:       Randstad Job Plugin
 * Plugin URI:
 * Description:       The event plugin that adds rest functionality
 * Version:           1.0.0
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       job-cpt
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
class Job
{
    /**
     * The custom post type slug
     *
     * @var string
     *
     * @since 1.0.0
     */
    const PLUGIN_NAME = 'jobs';
    const PLUGIN_DOMAIN = 'job-cpt';

    /**
     * The custom post type slug
     *
     * @var string
     *
     * @since 1.0.0
     */
    const POST_TYPE_SLUG = 'job';

    /**
     * The custom taxonomy type slug
     *
     * @var string
     *
     * @since 1.0.0
     */
    const TAXONOMY_CAT_SLUG = 'job-category';
    const TAXONOMY_TAGS_SLUG = 'job-tags';

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

        $label_singular = 'job';
        $label_plural = 'jobs';

        $args = array(
            'description' => '',
            'public' => true,
            'menu_position' => 2,
            'menu_icon' => 'dashicons-calendar',
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
                'menu_name' => 'Offres d\'emploi',
                'add_new' => 'Ajouter un job',
                'add_new_item' => 'Ajouter un nouveau ' . $label_singular,
                'edit' => 'Editer',
                'edit_item' => 'Editer le ' . $label_singular,
                'new_item' => 'Nouveau ' . $label_singular,
                'view' => 'Voir ' . $label_singular,
                'view_item' => 'Voir ' . $label_singular,
                'search_items' => 'Recherche ' . $label_plural,
                'not_found' => 'Pas de ' . $label_singular . ' trouvé',
                'not_found_in_trash' => 'Pas de ' . $label_singular . ' trouvé dans la corbeille',
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
            'name' => _x('Catégories d\'offre', self::TAXONOMY_CAT_SLUG, self::PLUGIN_DOMAIN),
            'singular_name' => _x('Catégorie', self::TAXONOMY_CAT_SLUG, self::PLUGIN_DOMAIN),
            'search_items' => __('Rechercher une catégorie', self::PLUGIN_DOMAIN),
            'all_items' => __('Toutes les catégories', self::PLUGIN_DOMAIN),
            'parent_item' => __('Catégorie parente', self::PLUGIN_DOMAIN),
            'parent_item_colon' => __('Catégorie parente:', self::PLUGIN_DOMAIN),
            'edit_item' => __('Éditer la catégorie', self::PLUGIN_DOMAIN),
            'update_item' => __('Mettre à jour la catégorie', self::PLUGIN_DOMAIN),
            'add_new_item' => __('Ajouter une nouvelle catégorie', self::PLUGIN_DOMAIN),
            'new_item_name' => __('Nom de la nouvelle catégorie', self::PLUGIN_DOMAIN),
            'menu_name' => __('Catégories', self::PLUGIN_DOMAIN),
        );

        $argsCategory = array(
            'hierarchical' => false,
            'labels' => $labelsCategory,
            'label' => __('Catégories', self::PLUGIN_DOMAIN),
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
            'name' => _x('Tags d\'offre', self::TAXONOMY_CAT_SLUG, self::PLUGIN_DOMAIN),
            'singular_name' => _x('Tag', self::TAXONOMY_CAT_SLUG, self::PLUGIN_DOMAIN),
            'search_items' => __('Rechercher un tag', self::PLUGIN_DOMAIN),
            'all_items' => __('Tous les tags', self::PLUGIN_DOMAIN),
            'parent_item' => __('Tag parent', self::PLUGIN_DOMAIN),
            'parent_item_colon' => __('Tag parent:', self::PLUGIN_DOMAIN),
            'edit_item' => __('Éditer le tag', self::PLUGIN_DOMAIN),
            'update_item' => __('Mettre à jour le tag', self::PLUGIN_DOMAIN),
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

$job = new Job();
