<?php
namespace jteam\services\users;

use jteam\core\abstracts\AbstractService;

class CustomerService extends AbstractService
{
    private $_skypeInformationKey = 'skype';
    private $_favoritesMoviesKey = 'favorites_movies';

    public function addSkypeInformation($customerID, $value)
    {
        update_user_meta(
            $customerID,
            $this->getSkypeInformationKey(),
            sanitize_text_field($value)
        );
    } // end adminInit

    public function getSkypeInformationKey()
    {
        return $this->_skypeInformationKey;
    } // end getSkypeInformationKey

    public function getFavoritesMoviesKey()
    {
        return $this->_favoritesMoviesKey;
    } // end getFavoritesMoviesKey

    public function getCurrentID()
    {
        return get_current_user_id();
    } // end getCurrentID

    public function addFavoriteMovie($movieID, $customerID = false)
    {
        if (!$customerID) {
            $customerID = $this->getCurrentID();
        }

        $movies = $this->getFavoritesMovies($customerID);

        $hasAlready = in_array($movieID, $movies);

        if (!$hasAlready) {
            $movies[] = $movieID;
        }

        return $this->_updateFavoritesMovies($customerID, $movies);
    } // end addFavoritesMovies

    public function removeFavoriteMovie($movieID, $customerID = false)
    {
        if (!$customerID) {
            $customerID = $this->getCurrentID();
        }

        $movies = $this->getFavoritesMovies($customerID);


        $movies = $arr = array_diff($movies, array($movieID));

        return $this->_updateFavoritesMovies($customerID, $movies);
    } // end removeFavoriteMovie

    private function _updateFavoritesMovies($customerID, $movies)
    {
        $movies = json_encode($movies);

        $result = update_user_meta(
            $customerID,
            $this->getFavoritesMoviesKey(),
            $movies
        );

        return $result;
    } // end _updateFavoritesMovies

    public function getFavoritesMovies($customerID  = false)
    {
        if (!$customerID) {
            $customerID = $this->getCurrentID();
        }

        $value = get_user_meta(
            $customerID,
            $this->getFavoritesMoviesKey(),
            true
        );

        if (!$value) {
           return array();
        }

        $movies = json_decode($value, true);


        return $movies;
    } // end getFavoritesMovies
}