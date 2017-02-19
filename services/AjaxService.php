<?php
namespace jteam\services;

use jteam\core\abstracts\AbstractService;

class AjaxService extends AbstractService
{
   protected function onInit()
   {
       $favoritesService = $this->app->service('favorites', 'movies');

       $this->app->wp->addAction(
           'wp_ajax_seller_of_movies_add_to_favorite',
           array($favoritesService, 'addMovieAjaxAction')
       );

       $this->app->wp->addAction(
           'wp_ajax_seller_of_movies_remove_from_favorite',
           array($favoritesService, 'removeMovieAjaxAction')
       );
   } // end onInit
}