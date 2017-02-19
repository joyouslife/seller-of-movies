<?php
namespace jteam\services\movies;

use jteam\core\abstracts\AbstractService;

class AllowSellingService extends AbstractService
{
    public function init()
    {
        $this->app->wp->addFilter(
            'woocommerce_product_class',
            array($this, 'onSetProductClassFilter'),
            10,
            3
        );

        $this->app->wp->addFilter(
            'woocommerce_get_price',
            array($this, 'onSetMoviePriceFilter'),
            10,
            2
        );
    } // end adminInit

    public function onSetProductClassFilter($className, $productType, $postType)
    {
        $conditions = $this->app->service('conditions');

        if ($conditions->isMoviesPostType($postType)) {
            $className = 'WC_Product_Simple';
        }

        return $className;
    } // end onSetProductClassFilter

    public function onSetMoviePriceFilter($price, $product)
    {
        $conditions = $this->app->service('conditions');

        if ($conditions->isMoviesPostType($product->post->post_type)) {
            $movieID = $product->post->ID;

            $priceService = $this->app->service('price', 'movies');
            $price = $priceService->getValue($movieID);
        }

        return $price;
    } // end onSetMoviePriceFilter
}