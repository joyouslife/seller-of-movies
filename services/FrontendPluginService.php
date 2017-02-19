<?php
namespace jteam\services;

use jteam\core\abstracts\AbstractService;

class FrontendPluginService extends AbstractService
{
    public function init()
    {
        $this->app->service('movies')->frontendInit();
        $this->app->service('RegistrationForm', 'woocommerce')->init();
        $this->app->service('CheckoutPage', 'woocommerce')->init();

        $this->app->service('css')->initFrontendStyles();
        $this->app->service('js')->initFrontendScripts();
    } // end init
}

