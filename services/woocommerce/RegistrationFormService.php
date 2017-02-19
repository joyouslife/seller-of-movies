<?php
namespace jteam\services\woocommerce;

use jteam\core\abstracts\AbstractService;

class RegistrationFormService extends AbstractService
{
    private $_skypeFieldName;

    protected function onInit()
    {
        $customerService = $this->app->service('customer', 'users');
        $this->_skypeFieldName = $customerService->getSkypeInformationKey();
    } // end onInit

    public function init()
    {
        $this->_initSkypeField();
        $this->_initRedirectAfterRegistration();
    } // end adminInit

    private function _initRedirectAfterRegistration()
    {
        $this->app->wp->addAction(
            'woocommerce_registration_redirect',
            array(&$this, 'onRedirectToFavoritesMoviesPageAction')
        );

    } // end _initRedirectAfterRegistration

    public function onRedirectToFavoritesMoviesPageAction($redirectTo) {
        $favoritesService = $this->app->service('Favorites', 'movies');

        return $favoritesService->getPageUrl();
    } // end onRedirectToFavoritesMoviesPageAction

    private function _initSkypeField()
    {
        $this->app->wp->addAction(
            'woocommerce_register_form',
            array(&$this, 'onAddSkypeFieldAction')
        );

        $this->app->wp->addAction(
            'woocommerce_created_customer',
            array(&$this, 'onSaveSkypeFieldAction')
        );
    } // end _initSkypeField

    public function onAddSkypeFieldAction()
    {
        $vars = array(
            'name' => $this->_skypeFieldName,
            'value' => $this->_getSkypeValue()
        );

        echo $this->app->render('frontend/registration/skype_field.php', $vars);
    } // end onAddSkypeFieldAction

    public function onSaveSkypeFieldAction($customerID)
    {
        if (!$this->_hasSkypeValueInRequest()) {
            return false;
        }

        $customerService = $this->app->service('customer', 'users');
        $value = $this->_getSkypeValue();

        $customerService->addSkypeInformation($customerID, $value);
    } // end onSaveSkypeFieldAction

    private function _hasSkypeValueInRequest()
    {
        return array_key_exists($this->_skypeFieldName, $_POST);
    } // end _hasSkypeValueInRequest

    private function _getSkypeValue()
    {
        $value = '';

        if ($this->_hasSkypeValueInRequest()) {
            $value = esc_attr($_POST[$this->_skypeFieldName]);
        }

        return $value;
    } // end _getSkypeValue
}