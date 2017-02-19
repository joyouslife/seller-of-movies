<?php
namespace jteam\app;

class WpFacade
{
	private $_pluginMainFile;
	protected $app;

	public function __construct($pluginMainFile, &$app)
	{
		$this->_pluginMainFile = $pluginMainFile;
		$this->app = $app;
	} // end __construct

	public function isBackend()
	{
		return defined('WP_BLOG_ADMIN');
	} // end isBackend

	public function isAjax()
	{
		return defined('DOING_AJAX') && DOING_AJAX;
	} // end isAjax

	public function getPluginPath($fileName = '')
	{
		return plugin_dir_path($this->_pluginMainFile).$fileName;
	} // end getPluginPath

	public function getPluginBasenamePath($path)
	{
		return plugin_basename($path);
	} // end getPluginBasenamePath

	public function getPluginUrl($fileName = '')
	{
		return $this->getUrlByPath($this->_pluginMainFile, $fileName);
	} // end getPluginUrl

	public function getCssUrl($fileName = '')
	{
		return $this->getPluginUrl('assets/styles/'.$fileName);
	} // end getCssUrl

	public function getJsUrl($fileName = '')
	{
		return $this->getPluginUrl('assets/js/'.$fileName);
	} // end getJsUrl

	public function getImagesUrl($fileName = '')
	{
		return $this->getPluginUrl('assets/images/'.$fileName);
	} // end getImagesUrl

	public function getFontsUrl($fileName = '')
	{
		return $this->getPluginUrl('assets/fonts/'.$fileName);
	} // end getFontsUrl

	public function isFileExists($path = '')
	{
		return file_exists($path);
	} // end isFileExists

	public function addAction(
		$hook, $method, $pirority = 10, $paramsCount = 1
	)
	{
		add_action($hook, $method, $pirority, $paramsCount);
	} // end addAction

	public function doAction($actionName)
	{
		if (!is_array($actionName)) {
			$params = array($actionName);
		} else {
			$params = $actionName;
		}

		$args = func_get_args();

		array_shift($args);

		$params = array_merge($params, $args);

		call_user_func_array('do_action', $params);
	} // end doAction

	public function applyFilters($filterName, $value)
	{
		$params = array(
			$filterName,
			$value
		);

		$args = func_get_args();

		$args = array_slice($args, 2);

		$params = array_merge($params, $args);



		return call_user_func_array('apply_filters', $params);
	} // end applyFilters

	public function hasAction($hookName, $callbackMethod = false)
	{
		return has_action($hookName, $callbackMethod);
	} // end hasAction

	public function addFilter(
		$hook, $method, $pirority = 10, $paramsCount = 1
	)
	{
		add_filter($hook, $method, $pirority, $paramsCount);
	} // end addFilter

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

	public function addScriptData($handle, $key, $value)
	{
		return wp_script_add_data($handle, $key, $value);
	} // end addScriptData

	public function isHomePage()
	{
		return is_home() ||  is_front_page();
	} // end isHomePage

	public function registerActivationEvent($pluginMainFile, $method)
	{
		register_activation_hook($pluginMainFile, $method);
	} // end registerActivationEvent

	public function registerDeactivationEvent($pluginMainFile, $method)
	{
		register_deactivation_hook($pluginMainFile, $method);
	} // end registerDeactivationEvent

	public function getLocale()
	{
		return get_locale();
	} // end getLocale

	public function getUrlByPath($filePath, $fileName = '')
	{
		return plugins_url($fileName, $filePath);
	} // end getUrlByPath

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

	public function getOption($optionName, $defaultValue = '')
	{
		return  get_option($optionName, $defaultValue);
	} // end get_option


	public function updateOption($optionName, $newValue, $autoload = true)
	{
		return update_option($optionName, $newValue, $autoload);
	} // end updateOption

	public function sendJsonSuccess($data = null)
	{
		wp_send_json_success($data);
	} // end sendJsonSuccess

	public function sendJsonError($data = null)
	{
		wp_send_json_error($data);
	} // end sendJsonError

	public function sendJson($data)
	{
		wp_send_json($data);
	} // end sendJson

	public function getRoles()
	{
		return get_editable_roles();
	} // end getRoles

	public function isPluginActive($pluginMainFilePath)
	{
		if ($this->isMultiSite()) {
			$activPlugins = $this->getSiteOption('active_sitewide_plugins');
			$result = array_key_exists($pluginMainFilePath, $activPlugins);
			if ($result) {
				return true;
			}
		} else {
			$activPlugins = $this->getOption('active_plugins');
			$result = in_array($pluginMainFilePath, $activPlugins);
		}

		return $result;
	} // end isPluginActive

	public function isMultiSite()
	{
		return is_multisite();
	} // end isMultiSite

	public function getSiteOption(
		$optionName, $defaultValue = false, $useCache = true
	)
	{
		return get_site_option($optionName, $defaultValue , $useCache);
	} // end getSiteOption

	public function loadPluginTextdomain($domain, $languagesFolderPath = false)
	{
		load_plugin_textdomain($domain, false, $languagesFolderPath);
	} // end loadPluginTextdomain

	public function getCurrentUserId()
	{
		return get_current_user_id();
	} // end getCurrentUserId

	public function getUserData($userID)
	{
		return get_userdata($userID);
	} // end getUserData

	public function getCurrentUserRoles($userID)
	{
		$userData = $this->getUserData($userID);

		return $userData->roles;
	} // end getCurrentUserRoles

	public function isPage($postParam = '')
	{
		return is_page($postParam);
	} // end isPage

	public function getPostID()
	{
		return get_the_ID ();
	} // end getPostID

	public function getPageID()
	{
		return get_the_ID ();
	} // end getPageID

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

	public function registerSidebar($args = array())
	{
		return register_sidebar($args);
	} // end registerSidebar

	public function addShortcode($tag , $method)
	{
		return add_shortcode($tag, $method);
	} // end addShortcode

	public function getUserByEmail($email)
	{
		return $this->getUserBy('email', $email);
	} // end getUserByEmail

	public function getUserBy($field, $value)
	{
		return get_user_by($field, $value);
	} // end getUserBy

	public function getUsers($params = array())
	{
		return get_users($params);
	} // end getUsers

	public function insertUser($userData)
	{
		return wp_insert_user($userData) ;
	} // end insertUser

	public function isError($dataObject)
	{
		return is_wp_error($dataObject);
	} // end isError

	public function addUserMeta($userID, $metaKey, $value, $unique = false)
	{
		return add_user_meta($userID, $metaKey, $value, $unique);
	} // end addUserMeta

	public function updateUserMeta($userID, $metaKey, $value, $prevValue = '')
	{
		return update_user_meta($userID, $metaKey, $value, $prevValue);
	} // end updateUserMeta

	public function addManyUserMeta($userID, $metaValues, $unique = false)
	{
		if (!is_array($metaValues)) {
			throw new \Exception('$metaValues param most be an array');
		}

		$result = array();

		foreach ($metaValues as $key => $value) {
			$result[] = $this->addUserMeta($userID, $key, $value, $unique);
		}

		return $result;
	} // end addManyUserMeta

	public function updateManyUserMeta($userID, $metaValues, $prevValue = false)
	{
		if (!is_array($metaValues)) {
			throw new \Exception('$metaValues param most be an array');
		}

		$result = array();

		foreach ($metaValues as $key => $value) {
			$result[] = $this->updateUserMeta($userID, $key, $value, $prevValue);
		}

		return $result;
	} // end updateManyUserMeta

	public function getSiteUrl($path = '', $scheme = null)
	{
		return site_url($path, $scheme);
	} // end getSiteUrl;

	public function mail(
		$to, $subject, $message, $headers = '', $attachments = ''
	)
	{
		return wp_mail($to, $subject, $message, $headers, $attachments);
	} // end mail

	public function redirect($location, $status = 302)
	{
		wp_redirect($location, $status);
		exit;
	} // end redirect

	public function getPermalink($id = 0, $leavename = false)
	{
		return get_permalink($id , $leavename);
	} // end getPermalink

	public function signon($credentials, $secureCookie = false)
	{
		return wp_signon($credentials, $secureCookie);
	} // end signon

	public function isSuperAdmin($userID = false)
	{
		return is_super_admin($userID);
	} // end isSuperAdmin

	public function setUserPassword($password, $userID)
	{
		wp_set_password($password, $userID);
	} // end setUserPassword
}