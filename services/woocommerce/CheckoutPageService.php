<?php
namespace jteam\services\woocommerce;

use jteam\core\abstracts\AbstractService;

class CheckoutPageService extends AbstractService
{
    public function init()
    {
        $this->app->wp->addAction(
            'woocommerce_checkout_fields',
            array(&$this, 'onHideCheckoutFieldsFilter')
        );

        $this->app->wp->addAction(
            'wc_add_to_cart_message',
            array($this, 'onHideAddToCartMessage')
        );

        $this->app->wp->addAction(
            'woocommerce_add_cart_item_data',
            array($this, 'onClearCartBeforeAddingNewItemFilter')
        );
    } // end init


    public function onHideCheckoutFieldsFilter($fields)
    {
        return array();
    } // end onHideCheckoutFieldsFilter

    public function onHideAddToCartMessage($message)
    {
        return false;
    } // end onHideAddToCartMessage

     public function onClearCartBeforeAddingNewItemFilter($cartData)
     {
        $woocommerce = $this->app->wp->globals('woocommerce');
        $woocommerce->cart->empty_cart();

        return $cartData;
    } // end onClearCartBeforeAddingNewItemFilter

    public function getPageUrl()
    {
        $pageID = get_option('woocommerce_checkout_page_id');

        return get_permalink($pageID);
    } // end getPageUrl
}