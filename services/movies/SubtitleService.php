<?php
namespace jteam\services\movies;

use jteam\core\abstracts\AbstractService;

class SubtitleService extends AbstractService
{
    private $_nonceKey = 'movie_subtitle';
    private $_nonceName = 'movie_subtitle_nonce';
    private $_subtitleName = 'movie_subtitle';

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
            'edit_form_after_title',
            array($this, 'onAppendSubtitleFieldAction')
        );

        $this->app->wp->addAction(
            'save_post',
            array($this, 'onSaveSubtitleFieldAction')
        );
    } // end onAfterScreenInitAction

    public function getValue($postID)
    {
        $subtitle = get_post_meta($postID, $this->_subtitleName, true);

        return $subtitle;
    } // end getValue


    public function onAppendSubtitleFieldAction()
    {
        $post = $this->app->wp->globals('post');

        $value = ($post) ? $this->getValue($post->ID) : '';

        $data = array(
            'nonce' => array(
                'name' => $this->_nonceName,
                'value' => wp_create_nonce($this->_nonceKey)
            ),
            'subtitle' => array(
                'name' => $this->_subtitleName,
                'value' => $value
            )
        );

        echo $this->app->render('backend/movies/subtitle.php', $data);
    } // end onAppendSubtitleFieldAction

    public function onSaveSubtitleFieldAction($postID)
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

        if (!$conditions->hasValueInRequest($this->_subtitleName)) {
            return false;
        }

        $value = $_POST[$this->_subtitleName];

        update_post_meta($postID, $this->_subtitleName, $value);
    } // end onSaveSubtitleFieldAction
}