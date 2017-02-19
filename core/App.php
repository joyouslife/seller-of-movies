<?php
namespace jteam\core;

use jteam\core\WpFacade;

class App {
    private $_config;

    protected $app;

    public $mainFile;
    public $pluginPath;
    public $textDomain;
    public $wp;
    public $result;

    public function __construct($config, $pluginMainFile)
    {
        $this->app = &$this;
        $this->_config = $config;
        $this->mainFile = $pluginMainFile;
        $this->pluginPath = dirname($pluginMainFile).'/';
        $this->textDomain = $this->config('text_domain');

        $this->_onInitWpFacade();

        $this->service('router')->init();
    } // end __construct

    private function _onInitWpFacade()
    {
        $this->wp = new WpFacade($this);
    } // end _onInitWpFacade

    public function service($name, $path = '')
    {
        $name = ucfirst($name).'Service';

        if ($path) {
            $name = $path.'\\'.$name;
        }

        $className = '\\jteam\\services\\'.$name;

        return new $className($this);
    } // end service

    public function render($template, $vars = array())
    {
        if ($vars) {
            extract($vars);
        }

        $templateFile = $this->getTemplatePath($template);

        ob_start();

        include $templateFile;

        $content = ob_get_clean();

        return $content;
    } // end render

    public function getTemplatePath($template = '')
    {
        return $this->pluginPath.'views/'.$template;
    } // end getTemplatePath

    public function config($key)
    {
        if (!array_key_exists($key, $this->_config)) {
            throw new Exception('Not found key'.$key.' in config array.');
        }

        return $this->_config[$key];
    } // end config

    public function getHandler($name = '')
    {
        return $this->config('handler').$name;
    } // end getHandler

    public function version()
    {
        return $this->config('version');
    } // end version
}