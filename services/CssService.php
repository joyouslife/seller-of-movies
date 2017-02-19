<?php
namespace jteam\services;

use jteam\core\abstracts\AbstractService;

class CssService extends AbstractService
{
    public function initStylesForAdminMoviesPostTypePage()
    {
        $this->app->wp->addAction(
            'admin_enqueue_scripts',
            array($this, 'onAddStylesForAdminMoviesPostTypePageAction')
        );
    } // end initStylesForAdminMoviesPostTypePage

    public function initFrontendStyles()
    {
        $this->app->wp->addAction(
            'wp_print_styles',
            array($this, 'onAddFrontendStylesAction')
        );
    } // end initFrontendStyles

    public function onAddStylesForAdminMoviesPostTypePageAction($hook)
    {
        $conditions = $this->app->service('conditions');

        if (!$conditions->isMoviesPostTypeAdminPage($hook)) {
            return false;
        }

        $this->app->wp->addCss(
            $this->app->getHandler('post-type-page'),
            $this->app->wp->getCssUrl(
                'backend/movies/post_type_page.css'
            ),
            array(),
            $this->app->version()
        );
    } // end onAddStylesForAdminMoviesPostTypePageAction

    public function onAddFrontendStylesAction()
    {
        $this->app->wp->addCss(
            $this->app->getHandler('styles'),
            $this->app->wp->getCssUrl('frontend/styles.css'),
            array(),
            $this->app->version()
        );
    } // end onAddFrontendStylesAction
}