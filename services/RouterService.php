<?php
namespace jteam\services;

use jteam\core\abstracts\AbstractService;

class RouterService extends AbstractService
{
    public function init()
    {
        $this->_onUninstallInit();
        $this->_onInstallInit();
        $this->_onCommonInit();
        $this->_onBackendInit();
        $this->_onFrontendInit();
    } // end init

    private function _onUninstallInit()
    {
        if (!defined('WP_UNINSTALL_PLUGIN')) {
            return false;
        }

        $this->app->service('uninstall');
    } // end _onUninstallInit

    private function _onInstallInit()
    {
        $service = $this->app->service('install');
        $method = array(&$service, 'init');

        $this->app->wp->registerActivationEvent(
            $this->app->mainFile,
            $method
        );
    } // end _onInstallInit

    private function _onCommonInit()
    {
        $this->app->service('Internationalization');
        $this->app->service('CommonPlugin')->init();
        $this->app->service('Ajax');
    } // end _onCommonInit

    private function _onFrontendInit()
    {
        if ($this->app->wp->isBackend() && !$this->app->wp->isAjax()) {
            return false;
        }

        $this->app->service('FrontendPlugin')->init();
    } // end _onBackendInit

    private function _onBackendInit()
    {
        if (!$this->app->wp->isBackend() && !$this->app->wp->isAjax()) {
            return false;
        }

        $this->app->service('BackendPlugin')->init();
    } // end _onBackendInit
}