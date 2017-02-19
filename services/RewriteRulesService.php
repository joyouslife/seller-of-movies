<?php
namespace jteam\services;

use jteam\core\abstracts\AbstractService;

class RewriteRulesService extends AbstractService
{
    public function init()
    {
        $this->app->wp->addAction(
            'init',
            array($this, 'onInitAction')
        );
    } // end initScriptsForAdminMoviesPostTypePage

    public function onInitAction()
    {
        $this->_favoritesPageRule();
    } // end onInitAction

    private function _favoritesPageRule()
    {
        $postType = $this->app->service('movies')->getPostTypeName();

        $this->app->wp->addRewriteRule(
            '^favorites/?$',
            'index.php?post_type='.$postType.'&display_favorites=1',
            'top'
        );

        $this->app->wp->addRewriteTag('%display_favorites%', '([^&]+)');
    } // _homePageRule

    public function flushRules()
    {
        $optionsService = $this->app->service('options');

        $status = $optionsService->getFlushRewriteStatus();

        if ($status) {
           return false;
        }

        flush_rewrite_rules(false);

        $optionsService->updateFlushRewriteStatus(true);
    } // end flushRules
}