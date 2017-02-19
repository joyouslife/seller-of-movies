<?php
namespace jteam\core\abstracts;

abstract class AbstractService
{
    protected $app;

    public function __construct(&$app)
    {
        $this->app = $app;
        $this->onInit();
    } // end __construct

    protected function onInit()
    {
    } // end onInit
}