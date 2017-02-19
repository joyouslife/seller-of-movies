<?php
namespace jteam\services;

use jteam\core\abstracts\AbstractService;

class InternationalizationService extends AbstractService
{
   protected function onInit()
   {
       $path = $this->app->wp->getPluginPath('languages/');
       $path = $this->app->wp->getPluginBasenamePath($path);

       $this->app->wp->loadPluginTextdomain(
           $this->app->textDomain,
           $path
       );
   } // end onInit
}