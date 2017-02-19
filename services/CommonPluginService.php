<?php
namespace jteam\services;

use jteam\core\abstracts\AbstractService;

class CommonPluginService extends AbstractService
{
    public function init()
    {
        $this->app->service('Movies')->commonInit();
        $this->app->service('rewriteRules')->init();
    } // end init
}