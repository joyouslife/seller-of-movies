<?php
namespace jteam\services;

use jteam\core\abstracts\AbstractService;

class ConditionsService extends AbstractService
{
    public function isMoviesPostTypeAdminPage($hook = false)
    {
        $pageData = $this->app->wp->getAdminPageInformationDataObject();

        $checkPostType = $this->isMoviesPostType($pageData->post_type);

        if (!$hook) {
            return $checkPostType;
        }

        $allowableHooks = array(
            'post-new.php',
            'post.php'
        );

        return $checkPostType && in_array($hook, $allowableHooks);
    } // end isMoviesPostTypeAdminPage

    public function isMoviesPostType($postType = false)
    {
        $moviesPostType = $this->app->service('movies')->getPostTypeName();

        if (!$postType) {
            $postType = get_post_type();
        }

        return $moviesPostType == $postType;
    } // end isMoviesPostType

    public function isVerifyNonce($nonceName, $nonceKey)
    {
        return array_key_exists($nonceName, $_POST)
               && wp_verify_nonce($_POST[$nonceName], $nonceKey);
    } // end isVerifyNonce

    public function hasValueInRequest($name)
    {
        return array_key_exists($name, $_POST);
    } // end hasValueInRequest
}