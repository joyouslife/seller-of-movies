<?php
namespace jteam\services\movies;

use jteam\core\abstracts\AbstractService;

class FavoritesService extends AbstractService
{
    public function buttonInit()
    {
        $this->app->wp->addFilter(
            'the_content',
            array($this, 'onDisplayButtonAction')
        );
    } // end buttonInit

    public function pageInit()
    {
        $this->app->wp->addAction(
            'pre_get_posts',
            array($this, "onChangeQueryForFavoritesPageAction")
        );

        $this->app->wp->addFilter(
            'get_the_archive_title',
            array($this, "onChangeArchiveTitleFilter")
        );
    } // end pageInit

    public function onChangeArchiveTitleFilter($title)
    {
        return __('Favorites Movies', $this->app->textDomain);
    } // end onChangeArchiveTitleFilter

    public function isFavoritePage()
    {
        return $this->app->wp->getQueryVar('display_favorites');
    } // end isFavoritePage

    public function onChangeQueryForFavoritesPageAction( $query  ) {
        if (!$this->isFavoritePage() || !$query->is_main_query()) {
            return false;
        }

        $customerService = $this->app->service('customer', 'users');
        $favorites = $customerService->getFavoritesMovies();

        if (!$favorites) {
            $idForNotFoundResult = 99999999;
            $favorites = array($idForNotFoundResult);
        }

        $query->set('post__in', $favorites);
    } // end onChangeQueryForFavoritesPageAction

    public function onDisplayButtonAction($content)
    {
        $conditions = $this->app->service('conditions');

        if (!$conditions->isMoviesPostType()) {
            return $content;
        }

        $movieID = get_the_ID();

        if ($this->isFavoritePage()) {
            $button = $this->_fetchRemoveFromFavoritesButton($movieID);
        } elseif ($this->isFavoriteMovie($movieID)) {
            $button = $this->_fetchInFavoritesButton();
        } else {
            $button = $this->_fetchAddToFavoritesButton($movieID);
        }

        return $content.$button;
    } // end onDisplayButtonAction

    private function _fetchInFavoritesButton()
    {
        $vars = array(
            'pageUrl' => $this->getPageUrl()
        );

        $button = $this->app->render(
            'frontend/movies/in_favorite_button.php',
            $vars
        );

        return $button;
    } // end _fetchInFavoritesButton

    private function _fetchAddToFavoritesButton($movieID)
    {
        $vars = array(
            'movieID' => $movieID,
            'pageUrl' => '#'
        );

        $customerService = $this->app->service('customer', 'users');

        if (!$customerService->getCurrentID()) {
            $service = $this->app->service('accountPage', 'woocommerce');
            $vars['pageUrl'] = $service->getPageUrl();
        }

        $button = $this->app->render(
            'frontend/movies/add_to_favorite_button.php',
            $vars
        );

        return $button;
    } // end _fetchAddToFavoritesButton

    private function _fetchRemoveFromFavoritesButton($movieID)
    {
        $vars = array(
            'movieID' => $movieID,
        );

        $button = $this->app->render(
            'frontend/movies/remove_from_favorite_button.php',
            $vars
        );

        return $button;
    } // end _fetchRemoveFromFavoritesButton

    public function isFavoriteMovie($movieID)
    {
        $customerService = $this->app->service('customer', 'users');
        $favorites = $customerService->getFavoritesMovies();

        return in_array($movieID, $favorites);
    } // end isFavoriteMovie

    public function getPageUrl()
    {
        return $this->app->wp->getSiteUrl('favorites/');
    } // end adminInit

    public function addMovieAjaxAction()
    {
        if (!$this->_hasMovieIdInRequest()) {
            $this->app->wp->sendJsonError();
        }

        $movieID = intval($_POST['movie_id']);

        $customerService = $this->app->service('customer', 'users');
        $result = $customerService->addFavoriteMovie($movieID);

        if (!$result) {
            $this->app->wp->sendJsonError();
        }

        $data = array(
            'message' => __('Successfully added!', $this->app->textDomain),
            'button' => $this->_fetchInFavoritesButton(),
            'movie_id' => $movieID
        );

        $this->app->wp->sendJsonSuccess($data);
    } // end addMovieAjaxAction

    public function removeMovieAjaxAction()
    {
        if (!$this->_hasMovieIdInRequest()) {
            $this->app->wp->sendJsonError();
        }

        $movieID = intval($_POST['movie_id']);

        $customerService = $this->app->service('customer', 'users');
        $result = $customerService->removeFavoriteMovie($movieID);

        if (!$result) {
            $this->app->wp->sendJsonError();
        }

        $data = array(
            'message' => __('Successfully removed!', $this->app->textDomain)
        );

        $this->app->wp->sendJsonSuccess($data);
    } // end removeMovieAjaxAction

    private function _hasMovieIdInRequest()
    {
        return array_key_exists('movie_id', $_POST)
               && intval($_POST['movie_id']);
    } // end _hasMovieIdInRequest
}