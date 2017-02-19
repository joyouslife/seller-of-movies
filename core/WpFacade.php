<?php
namespace jteam\core;

class WpFacade
{
	protected $app;
	private $_pluginMainFile;

	public function __construct(&$app)
	{
		$this->app = $app;
		$this->_pluginMainFile = $this->app->mainFile;
	} // end __construct

	public function isBackend()
	{
		return defined('WP_BLOG_ADMIN');
	} // end isBackend

	public function isAjax()
	{
		return defined('DOING_AJAX') && DOING_AJAX;
	} // end isAjax

    public function registerActivationEvent($pluginMainFile, $method)
    {
        register_activation_hook($pluginMainFile, $method);
    } // end registerActivationEvent

    public function addAction(
        $hook, $method, $pirority = 10, $paramsCount = 1
    )
    {
        add_action($hook, $method, $pirority, $paramsCount);
    } // end addAction

    public function addFilter(
        $hook, $method, $pirority = 10, $paramsCount = 1
    )
    {
        add_filter($hook, $method, $pirority, $paramsCount);
    } // end addFilter

    public function addCss(
        $handle, $src = false, $deps = array(),
        $ver = false, $media = false
    )
    {
        wp_enqueue_style($handle, $src, $deps, $ver, $media);
    } // end addCss

    public function addJs(
        $handle, $src = false, $deps = array(),
        $ver = false, $inFooter = false
    )
    {
        wp_enqueue_script($handle, $src, $deps, $ver, $inFooter);
    } // end addJs

    public function registerJsDataObject($handle, $name, $data = array())
    {
        wp_localize_script($handle, $name, $data);
    } // end registerJsDataObject

    public function getPluginUrl($fileName = '')
    {
        return $this->getUrlByPath($this->_pluginMainFile, $fileName);
    } // end getPluginUrl

    public function getUrlByPath($filePath, $fileName = '')
    {
        return plugins_url($fileName, $filePath);
    } // end getUrlByPath

    public function getCssUrl($fileName = '')
    {
        return $this->getPluginUrl('static/css/'.$fileName);
    } // end getCssUrl

    public function getJsUrl($fileName = '')
    {
        return $this->getPluginUrl('static/js/'.$fileName);
    } // end getJsUrl

    public function getImagesUrl($fileName = '')
    {
        return $this->getPluginUrl('static/images/'.$fileName);
    } // end getImagesUrl

    public function addMenuPage(
        $pageTitle, $menuTitle, $capability, $menuSlug,
        $function = '', $iconUrl = '', $position = null
    )
    {
        $page = add_menu_page(
            $pageTitle,
            $menuTitle,
            $capability,
            $menuSlug,
            $function,
            $iconUrl,
            $position
        );

        return $page;
    } // end addMenuAction

    public function addSubMenuPage(
        $parentSlug, $pageTitle, $menuTitle, $capability,
        $menuSlug, $function = ''
    )
    {
        $page = add_submenu_page(
            $parentSlug,
            $pageTitle,
            $menuTitle,
            $capability,
            $menuSlug,
            $function
        );

        return $page;
    } // end addSubMenuPage

    public function getOption($optionName, $defaultValue = '')
    {
        return  get_option($optionName, $defaultValue);
    } // end get_option

    public function updateOption($optionName, $newValue, $autoload = true)
    {
        return update_option($optionName, $newValue, $autoload);
    } // end updateOption

    public function deleteOption($optionName)
    {
        return delete_option($optionName);
    } // end deleteOption

    public function getSiteUrl($path = '', $scheme = null)
    {
        return site_url($path, $scheme);
    } // end getSiteUrl;

    public function addQueryArg()
    {
        $args = func_get_args();

        return call_user_func_array('add_query_arg', $args);
    } // end addQueryArg

    public function isMobile()
    {
        return wp_is_mobile();
    } // end isMobile

    public function isRtl()
    {
        return is_rtl();
    } // end isMobile

    public function getPluginPath($fileName = '')
    {
        return plugin_dir_path($this->_pluginMainFile).$fileName;
    } // end getPluginPath

    public function getPluginBasenamePath($path)
    {
        return plugin_basename($path);
    } // end getPluginBasenamePath

    public function loadPluginTextdomain($domain, $languagesFolderPath = false)
    {
        load_plugin_textdomain($domain, false, $languagesFolderPath);
    } // end loadPluginTextdomain

    public function getAdminUrl($path, $scheme = 'admin')
    {
        $url = admin_url($path, $scheme);
        return $this->prepareUrl($url);
    } // end getAdminUrl

    public function prepareUrl($url)
    {
        $data = parse_url($url);

        if (array_key_exists('scheme', $data)) {
            $url = str_replace($data['scheme'].':', '', $url);
        }

        return $url;
    } // end prepareUrl

    public function sendJsonSuccess($data = null)
    {
        wp_send_json_success($data);
    } // end sendJsonSuccess

    public function sendJsonError($data = null)
    {
        wp_send_json_error($data);
    } // end sendJsonError

    public function getAdminPageInformationDataObject()
    {
        return get_current_screen();
    } // end getAdminPageInformationDataObject

    public function isDoingAutosave()
    {
        return defined('DOING_AUTOSAVE') && DOING_AUTOSAVE;
    } // end isDoingAutosave

    public function globals($name)
    {
        if (!array_key_exists($name, $GLOBALS)) {
            return false;
        }

        return $GLOBALS[$name];
    } // end globals

    public function updateUserMeta($userID, $metaKey, $value, $prevValue = '')
    {
        return update_user_meta($userID, $metaKey, $value, $prevValue);
    } // end updateUserMeta

    public function addRewriteRule($regex, $redirect, $after)
    {
        add_rewrite_rule($regex, $redirect, $after);
    } // end addRewriteRule

    public function addRewriteTag($tagname, $regex, $query = '')
    {
        add_rewrite_tag($tagname, $regex, $query);
    } // end addRewriteTag

    public function getQueryVar($key, $default = '')
    {
        return get_query_var($key, $default);
    } // end getQueryVar
}