<?php
namespace jteam\services;

use jteam\core\abstracts\AbstractService;

class JsService extends AbstractService
{
    public function initScriptsForAdminMoviesPostTypePage()
    {
        $this->app->wp->addAction(
            'admin_enqueue_scripts',
            array($this, 'onAddScriptsForAdminMoviesPostTypePage')
        );
    } // end initScriptsForAdminMoviesPostTypePage

    public function initFrontendScripts()
    {
        $this->app->wp->addAction(
            'wp_enqueue_scripts',
            array($this, 'onAddFrontendScriptsAction')
        );
    } // end initFrontendScripts

    public function onAddScriptsForAdminMoviesPostTypePage($hook)
    {
        $conditions = $this->app->service('conditions');

        if (!$conditions->isMoviesPostTypeAdminPage($hook)) {
            return false;
        }

        $this->app->wp->addJs('jquery');

        $this->app->wp->addJs(
            $this->app->getHandler('post-type-page'),
            $this->app->wp->getJsUrl(
                'backend/movies/post_type_page.js'
            ),
            array('jquery'),
            $this->app->version(),
            true
        );
    } // end onAddScriptsForAdminMoviesPostTypePage

    public function onAddFrontendScriptsAction($hook)
    {
        $this->app->wp->addJs('jquery');

        $this->app->wp->addJs(
            $this->app->getHandler('general'),
            $this->app->wp->getJsUrl(
                'frontend/general.js'
            ),
            array('jquery'),
            $this->app->version(),
            true
        );

        $data = array(
            'ajaxUrl' => $this->app->wp->getAdminUrl('admin-ajax.php')
        );

        $this->app->wp->registerJsDataObject(
            $this->app->getHandler('general'),
            'SellerOfMovies',
            $data
        );
    } // end onAddFrontendScriptsAction
}