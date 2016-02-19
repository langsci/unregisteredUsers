<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/UnregisteredGroupsGridHandler.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UnregisteredGroupsGridHandler
 *
 */

import('lib.pkp.classes.controllers.grid.GridHandler');
import('plugins.generic.unregisteredUsers.controllers.grid.UnregisteredGroupsGridRow');
import('plugins.generic.unregisteredUsers.controllers.grid.UnregisteredGroupsGridCellProvider');
import('plugins.generic.unregisteredUsers.classes.UnregisteredGroup');
import('plugins.generic.unregisteredUsers.classes.UnregisteredGroupsDAO');

class UnregisteredGroupsGridHandler extends GridHandler {

	static $plugin;

	static function setPlugin($plugin) {
		self::$plugin = $plugin;
	}

	/**
	 * Constructor
	 */
	function UnregisteredGroupsGridHandler() {

		parent::GridHandler();
		$this->addRoleAssignment(
			array(ROLE_ID_MANAGER),
			array('showUsers', 'addUnregisteredGroup', 'editUnregisteredGroup', 'updateUnregisteredGroup', 'delete','manageUsers','deleteUserFromGroup', 'addUserToGroup')
		);
	}

	//
	// Overridden template methods
	//
	/**
	 * @copydoc Gridhandler::initialize()
	 */
	function initialize($request, $args = null) {

		parent::initialize($request);
		$context = $request->getContext();

		// Set the grid details.
		$this->setTitle('plugins.generic.unregisteredUsers.groups.title');
		$this->setEmptyRowText('plugins.generic.unregisteredUsers.groups.noneCreated');

		$unregisteredGroupsDao = new UnregisteredGroupsDAO();

		$this->setGridDataElements($unregisteredGroupsDao->getByContextId($context->getId()));

		// Add grid-level actions
		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');
		$this->addAction(
			new LinkAction(
				'addUnregisteredGroup',
				new AjaxModal(
					$router->url($request, null, null, 'addUnregisteredGroup'),
					__('plugins.generic.unregisteredUsers.addUnregisteredGroup'),
					'modal_add_item'
				),
				__('plugins.generic.unregisteredUsers.addUnregisteredGroup'),
				null,
				__('plugins.generic.unregisteredUsers.tooltip.addUnregisteredGroup')
			)
		);

		// Columns
		$cellProvider = new UnregisteredGroupsGridCellProvider();
		$this->addColumn(new GridColumn(
			'name',
			'plugins.generic.unregisteredUsers.groupName',
			null,
			'controllers/grid/gridCell.tpl', // Default null not supported in OMP 1.1
			$cellProvider
		));
	}

	//
	// Overridden methods from GridHandler
	//

	/**
	 * @copydoc Gridhandler::getRowInstance()
	 */
	function getRowInstance() {
		return new UnregisteredGroupsGridRow();
	}

	/**
	 * An action to add a new group
	 * @param $args array Arguments to the request
	 * @param $request PKPRequest Request object
	 */
	function addUnregisteredGroup($args, $request) {

		return $this->editUnregisteredGroup($args, $request);
	}

	/**
	 * An action to edit a group
	 * @param $args array Arguments to the request
	 * @param $request PKPRequest Request object
	 * @return string Serialized JSON object
	 */
	function editUnregisteredGroup($args, $request) {

		$unregisteredGroupId = $request->getUserVar('unregisteredGroupId');
		$context = $request->getContext();
		$this->setupTemplate($request);

		// Create and present the edit form
		import('plugins.generic.unregisteredUsers.controllers.grid.form.UnregisteredGroupForm');
		$unregisteredUsersPlugin = self::$plugin;

		$unregisteredGroupForm = new UnregisteredGroupForm(self::$plugin, $context->getId(), $unregisteredGroupId);
		$unregisteredGroupForm->initData();

		$json = new JSONMessage(true, $unregisteredGroupForm->fetch($request));

		return $json->getString();
	}

	/**
	 * Update a group
	 * @param $args array
	 * @param $request PKPRequest
	 * @return string Serialized JSON object
	 */
	function updateUnregisteredGroup($args, $request) {

		$unregisteredGroupId = $request->getUserVar('unregisteredGroupId');
		$groupName = $request->getUserVar('name');
		$context = $request->getContext();
		$contextId = $context->getId();
		$this->setupTemplate($request);

		// Create and populate the form
		import('plugins.generic.unregisteredUsers.controllers.grid.form.UnregisteredGroupForm');
		$unregisteredGroupForm = new UnregisteredGroupForm(self::$plugin,$contextId, $unregisteredGroupId);
		$unregisteredGroupForm->readInputData();
		
		// check if group name already exists
		$unregisteredGroupsDAO = new UnregisteredGroupsDAO();
		if (!$unregisteredGroupId) {
			$unregisteredGroupId = -1;
		}
		if ($unregisteredGroupsDAO->nameExists($unregisteredGroupId,$unregisteredGroupForm->getGroupName(),$contextId)) {
			$unregisteredGroupForm->setUniqueName(false);
			$json = new JSONMessage(true, $unregisteredGroupForm->fetch($request));
			return $json->getString();
		}

		// Check the results
		if ($unregisteredGroupForm->validate()) {
			// Save the results
			$unregisteredGroupForm->execute();
 			return DAO::getDataChangedEvent();
		} else {
			// Present any errors
			$json = new JSONMessage(true, $unregisteredGroupForm->fetch($request));
			return $json->getString();
		}
	}

	function manageUsers($args, $request){

		$unregisteredGroupId = $request->getUserVar('unregisteredGroupId');
		$modus = $request->getUserVar('modus');
		$context = $request->getContext();
		$this->setupTemplate($request);
		$unregisteredUsersPlugin = self::$plugin;

		// Create and present the edit form
		if ($modus=="delete") {
			import('plugins.generic.unregisteredUsers.controllers.grid.form.DeleteUserFromGroupForm');
			$manageUsersForm = new DeleteUserFromGroupForm(self::$plugin, $context->getId(), $unregisteredGroupId);
		} else {
			import('plugins.generic.unregisteredUsers.controllers.grid.form.AddUserToGroupForm');
			$manageUsersForm = new AddUserToGroupForm(self::$plugin, $context->getId(), $unregisteredGroupId);
		}

		$json = new JSONMessage(true, $manageUsersForm->fetch($request));

		return $json->getString();

	}

	function showUsers($args, $request){

		$unregisteredGroupId = $request->getUserVar('unregisteredGroupId');
		$context = $request->getContext();
		$contextId = $context->getId();

		import('plugins.generic.unregisteredUsers.classes.UnregisteredGroupsDAO');
		$unregisteredGroupsDAO = new UnregisteredGroupsDAO();
		$users = $unregisteredGroupsDAO->getUsersByGroup($contextId,$unregisteredGroupId);

		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('users', $users);
		$json = new JSONMessage(true,  $templateMgr->fetch(self::$plugin->getTemplatePath().'/showUsers.tpl'));
		return $json->getString();
	}

	function addUserToGroup($args, $request) {

		$unregisteredGroupId = $request->getUserVar('unregisteredGroupId');
		$selectedUserId = $request->getUserVar('userId');
		$context = $request->getContext();
		$this->setupTemplate($request);

		// Create and populate the form
		import('plugins.generic.unregisteredUsers.controllers.grid.form.AddUserToGroupForm');
		$unregisteredUsersPlugin = self::$plugin;
		$addUserToGroupForm = new AddUserToGroupForm(self::$plugin, $context->getId(), $unregisteredGroupId);

		// Check the results
		if ($addUserToGroupForm->validate()) {
			// Save the results

			import('plugins.generic.unregisteredUsers.classes.UnregisteredGroupsDAO');
			$unregisteredGroupsDAO = new UnregisteredGroupsDAO();
			$unregisteredGroupsDAO->insertUserIntoGroup($selectedUserId,$unregisteredGroupId, $context->getId());
 			return DAO::getDataChangedEvent();
		} else {
			// Present any errors
			$json = new JSONMessage(true, $addUserToGroupForm->fetch($request));
			return $json->getString();
		}
	}

	function deleteUserFromGroup($args, $request) {

		$unregisteredGroupId = $request->getUserVar('unregisteredGroupId');
		$selectedUserId = $request->getUserVar('userId');
		$context = $request->getContext();
		$this->setupTemplate($request);

		// Create and populate the form
		import('plugins.generic.unregisteredUsers.controllers.grid.form.DeleteUserFromGroupForm');
		$unregisteredUsersPlugin = self::$plugin;
		$deleteUserFromGroupForm = new DeleteUserFromGroupForm(self::$plugin, $context->getId(), $unregisteredGroupId);

		// Check the results
		if ($deleteUserFromGroupForm->validate()) {
			// Save the results

			import('plugins.generic.unregisteredUsers.classes.UnregisteredGroupsDAO');
			$unregisteredGroupsDAO = new UnregisteredGroupsDAO();
			$unregisteredGroupsDAO->deleteUserFromGroup($selectedUserId,$unregisteredGroupId, $context->getId());

 			return DAO::getDataChangedEvent();
		} else {
			// Present any errors
			$json = new JSONMessage(true, $deleteUserFromGroupForm->fetch($request));
			return $json->getString();
		}
	}

	/**                               
	 * @param $args array
	 * Delete a group
	 * @param $request PKPRequest
	 * @return string Serialized JSON object
	 */
	function delete($args, $request) {

		$unregisteredGroupId = $request->getUserVar('unregisteredGroupId');
		$context = $request->getContext();

		$unregisteredGroupsDao = new UnregisteredGroupsDAO();
		$unregisteredGroup = $unregisteredGroupsDao->getById($unregisteredGroupId, $context->getId());

		$unregisteredGroupsDao->deleteObject($unregisteredGroup);

		return DAO::getDataChangedEvent();
	}
}

?>
