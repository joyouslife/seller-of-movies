<?php
namespace jteam\services;

use jteam\core\abstracts\AbstractService;

class BackendPluginService extends AbstractService
{
    public function init()
    {
        $this->app->service('Movies')->adminInit();
    } // end init
}