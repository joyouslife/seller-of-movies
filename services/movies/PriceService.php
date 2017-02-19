<?php
namespace jteam\services\movies;

use jteam\core\abstracts\AbstractService;

class PriceService extends AbstractService
{
    private $_nonceKey = 'movie_price';
    private $_nonceName = 'movie_price_nonce';
    private $_priceName = 'regular_price';

    public function adminInit()
    {
        $this->app->wp->addAction(
            'current_screen',
            array($this, 'onAfterScreenInitAction')
        );

    } // end adminInit

    public function onAfterScreenInitAction()
    {
        $conditions = $this->app->service('conditions');

        if (!$conditions->isMoviesPostTypeAdminPage()) {
            return false;
        }

        $this->app->wp->addAction(
            'add_meta_boxes',
            array($this, 'onAppendPriceFieldAction')
        );

        $this->app->wp->addAction(
            'save_post',
            array($this, 'onSavePriceFieldAction')
        );
    } // end onAfterScreenInitAction

    public function getValue($postID)
    {
        $price = get_post_meta($postID, $this->_priceName, true);

        return $price;
    } // end getValue

    public function onDisplayPriceFieldAction()
    {
        $post = $this->app->wp->globals('post');

        $value = ($post) ? $this->getValue($post->ID) : '';

        $data = array(
            'nonce' => array(
                'name' => $this->_nonceName,
                'value' => wp_create_nonce($this->_nonceKey)
            ),
            'price' => array(
                'name' => $this->_priceName,
                'value' => $value
            ),
            'symbol' => get_woocommerce_currency_symbol()
        );

        echo $this->app->render('backend/movies/price.php', $data);
    } // end onDisplayPriceFieldAction


    public function onAppendPriceFieldAction()
    {
        add_meta_box(
            'price_meta_box',
            'Price',
            array($this, 'onDisplayPriceFieldAction'),
            'movies',
            'side',
            'low'
        );
    } // end onAppendPriceFieldAction

    public function onSavePriceFieldAction($postID)
    {
        if ($this->app->wp->isDoingAutosave()) {
            return false;
        }

        $conditions = $this->app->service('conditions');
        $nonceName = $this->_nonceName;
        $nonceKey = $this->_nonceKey;

        if (!$conditions->isVerifyNonce($nonceName, $nonceKey)) {
            return false;
        }

        if (!$conditions->hasValueInRequest($this->_priceName)) {
            return false;
        }

        $value = $_POST[$this->_priceName];
        $value = trim($value, '.');
        $value = trim($value, ',');

        update_post_meta($postID, $this->_priceName, $value);
    } // end onSavePriceFieldAction
}