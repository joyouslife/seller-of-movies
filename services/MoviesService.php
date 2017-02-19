<?php
namespace jteam\services;

use jteam\core\abstracts\AbstractService;

class MoviesService extends AbstractService
{
    private $_postTypeKey = 'movies';
    private $_taxonomyKey = 'movies_categories';

    public function commonInit()
    {
        $this->app->wp->addAction('init', array(&$this, 'onRegisterAction'));
    } // end commonInit

    public function adminInit()
    {
        $this->app->service('subtitle', 'movies')->adminInit();
        $this->app->service('price', 'movies')->adminInit();
        $this->app->service('css')->initStylesForAdminMoviesPostTypePage();
        $this->app->service('js')->initScriptsForAdminMoviesPostTypePage();
    } // end adminInit

    public function frontendInit()
    {
        $this->app->service('AllowSelling', 'movies')->init();

        $this->app->service('favorites', 'movies')->pageInit();

        $this->app->service('favorites', 'movies')->buttonInit();
        $this->app->service('AddToCart', 'movies')->buttonInit();
    } // end frontendInit

    public function onRegisterAction()
    {
        $this->_registerPostType();
        $this->_registerTaxonomy();
        $this->app->service('rewriteRules')->flushRules();
    } // end onRegisterAction

    public function _registerPostType()
    {
        $textDomain = $this->app->textDomain;

        $labels = array(
            'name'               => _x('Movies', 'post type general name', $textDomain),
            'singular_name'      => _x('Movie', 'post type singular name', $textDomain),
            'menu_name'          => _x('Movies', 'admin menu', $textDomain),
            'name_admin_bar'     => _x('Movie', 'add new on admin bar', $textDomain),
            'add_new'            => _x('Add New', 'book', $textDomain),
            'add_new_item'       => __('Add New Movie', $textDomain),
            'new_item'           => __('New Movie', $textDomain),
            'edit_item'          => __('Edit Movie', $textDomain),
            'view_item'          => __('View Movie', $textDomain),
            'all_items'          => __('All Movies', $textDomain),
            'search_items'       => __('Search Movies', $textDomain),
            'parent_item_colon'  => __('Parent Movies:', $textDomain),
            'not_found'          => __('No movies found.', $textDomain),
            'not_found_in_trash' => __('No movies found in Trash.', $textDomain)
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_nav_menus'  => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => true,
            'taxonomies'         => array($this->_taxonomyKey),
            'supports'           => array('title', 'editor',  'thumbnail')
        );

        register_post_type($this->_postTypeKey, $args);
    } // end _registerPostType

    private function _registerTaxonomy()
    {
        $labels = array(
            'name'              => 'Categories',
            'singular_name'     => 'Category',
        );

        $args = array(
            'label'                 => '',
            'labels'                => $labels,
            'description'           => '',
            'public'                => true,
            'publicly_queryable'    => null,
            'show_in_nav_menus'     => true,
            'show_ui'               => true,
            'show_in_rest'          => null,
            'rest_base'             => null,
            'hierarchical'          => true,
            'rewrite'               => true,
            'capabilities'          => array(),
            'show_in_quick_edit'    => null,
        );

        register_taxonomy($this->_taxonomyKey, array($this->_postTypeKey), $args);
    } // end _registerTaxonomy

    public function getPostTypeName()
    {
        return $this->_postTypeKey;
    } // end getPostTypeName
}