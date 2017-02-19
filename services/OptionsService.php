<?php
namespace jteam\services;

use jteam\core\abstracts\AbstractService;

class OptionsService extends AbstractService
{
    private $_favoritePageOptionsName = 'rewrite_flush';

    public function getFlushRewriteStatus()
    {
        $optionName = $this->_name(
            $this->_favoritePageOptionsName
        );

        return get_option($optionName);
    } // end getFlushRewriteStatus

    public function updateFlushRewriteStatus($value)
    {
        $optionName = $this->_name(
            $this->_favoritePageOptionsName
        );

        return update_option($optionName, $value);
    } // end updateFlushRewriteStatus

    private function _name($name)
    {
        return $this->app->config('option_prefix').$name;
    } // end _name
}