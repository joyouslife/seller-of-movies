<?php
namespace jteam\services\movies;

use jteam\core\abstracts\AbstractService;

class AddToCartService extends AbstractService
{
    public function buttonInit()
    {
        $this->app->wp->addFilter(
            'the_content',
            array($this, 'onDisplayButtonAction')
        );
    } // end buttonInit

    public function onDisplayButtonAction($content)
    {
        $conditions = $this->app->service('conditions');

        if (!$conditions->isMoviesPostType()) {
            return $content;
        }

        $movieID = get_the_ID();

        $checkoutService = $this->app->service('checkoutPage', 'woocommerce');
        $checkoutPageUrl = $checkoutService->getPageUrl();

        $priceService = $this->app->service('price', 'movies');
        $price = $priceService->getValue($movieID);

        if (!$price) {
            return $content;
        }

        $vars = array(
            'price' => wc_price($price),
            'pageUrl' => $checkoutPageUrl.'?add-to-cart='.$movieID,
        );

        $customerService = $this->app->service('customer', 'users');

        if (!$customerService->getCurrentID()) {
            $service = $this->app->service('accountPage', 'woocommerce');
            $vars['pageUrl'] = $service->getPageUrl();
        }

        $button = $this->app->render(
            'frontend/movies/add_to_cart_button.php',
            $vars
        );

        return $content.$button;
    } // end onDisplayButtonAction

    public function hasMovieInCart($movieID)
    {
        return false;
    } // end hasMovieInCart

    public function getPageUrl()
    {
        return '/favorites';
    } // end adminInit
}