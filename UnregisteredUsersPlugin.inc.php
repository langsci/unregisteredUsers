<?php

/**
 * @file plugins/generic/unregisteredUsers/UnregisteredUsersPlugin.inc.php
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING. 
 *
 * @class UnregisteredUsersPlugin
 *
 */

import('lib.pkp.classes.plugins.GenericPlugin');
import('plugins.generic.unregisteredUsers.classes.UnregisteredGroupsDAO');

class UnregisteredUsersPlugin extends GenericPlugin {


	function register($category, $path) {

		if (parent::register($category, $path)) {
			$this->addLocaleData();
			
			if ($this->getEnabled()) {
				HookRegistry::register ('LoadHandler', array(&$this, 'handleLoadRequest'));
				HookRegistry::register('LoadComponentHandler', array($this, 'setupGridHandler'));
			}
			return true;
		}
		return false;

	}

	function handleLoadRequest($hookName, $args) {

		$request = $this->getRequest();
		$press = $request -> getPress();

		// get url path components
		$pageUrl = $args[0];
		$opUrl = $args[1];

		if ($pageUrl=="unregisteredUsers" && $opUrl=="index") {

			define('HANDLER_CLASS', 'UnregisteredUsersHandler');
			define('UNREGISTEREDUSERS_PLUGIN_NAME', $this->getName());
			$this->import('UnregisteredUsersHandler');

			return true;
		}
		return false;
	}

	/**
	 * Permit requests to the grid handler
	 * @param $hookName string The name of the hook being invoked
	 * @param $args array The parameters to the invoked hook
	 */
	function setupGridHandler($hookName, $params) {

		$component =& $params[0];

		if ($component == 'plugins.generic.unregisteredUsers.controllers.grid.UnregisteredUsersGridHandler') {
			// Allow the users grid handler to get the plugin object
			import($component);
			UnregisteredUsersGridHandler::setPlugin($this);
			return true;
		}
		if ($component == 'plugins.generic.unregisteredUsers.controllers.grid.UnregisteredGroupsGridHandler') {
			// Allow the groups grid handler to get the plugin object
			import($component);
			UnregisteredGroupsGridHandler::setPlugin($this);
			return true;
		}
		return false;
	}

	function getDisplayName() {
		return __('plugins.generic.unregisteredUsers.displayName');
	}

	function getDescription() {
		return __('plugins.generic.unregisteredUsers.description');
	}

	function getTemplatePath() {
		return parent::getTemplatePath() . 'templates/';
	}

	function getInstallSchemaFile() {
		return $this->getPluginPath() . '/schema.xml';
	}
}

?>
