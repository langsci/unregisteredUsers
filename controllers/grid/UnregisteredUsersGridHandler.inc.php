<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/UnregisteredUsersGridHandler.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UnregisteredUsersGridHandler
 *
 */

import('lib.pkp.classes.controllers.grid.GridHandler');
import('plugins.generic.unregisteredUsers.controllers.grid.UnregisteredUsersGridRow');
import('plugins.generic.unregisteredUsers.controllers.grid.UnregisteredUsersGridCellProvider');
import('plugins.generic.unregisteredUsers.classes.UnregisteredUser');
import('plugins.generic.unregisteredUsers.classes.UnregisteredUsersDAO');

class UnregisteredUsersGridHandler extends GridHandler {

	static $plugin;

	static function setPlugin($plugin) {
		self::$plugin = $plugin;
	}

	/**
	 * Constructor
	 */
	function UnregisteredUsersGridHandler() {
		parent::GridHandler();
		$this->addRoleAssignment(
			array(ROLE_ID_MANAGER),
			array('showGroups', 'addUnregisteredUser', 'editUnregisteredUser', 'updateUnregisteredUser', 'delete')
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
		$this->setTitle('plugins.generic.unregisteredUsers.users.title');
		$this->setEmptyRowText('plugins.generic.unregisteredUsers.users.noneCreated');

		$unregisteredUsersDao = new UnregisteredUsersDAO();
		$this->setGridDataElements($unregisteredUsersDao->getByContextId($context->getId()));

		// Add grid-level actions
		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');
		$this->addAction(
			new LinkAction(
				'addUnregisteredUser',
				new AjaxModal(
					$router->url($request, null, null, 'addUnregisteredUser'),
					__('plugins.generic.unregisteredUsers.addUnregisteredUser'),
					'modal_add_item'
				),
				__('plugins.generic.unregisteredUsers.addUnregisteredUser'),
				null,
				__('plugins.generic.unregisteredUsers.tooltip.addUnregisteredUser')
			)
		);


		// Columns
		$cellProvider = new UnregisteredUsersGridCellProvider();
		$this->addColumn(new GridColumn(
			'firstName',
			'plugins.generic.unregisteredUsers.firstName',
			null,
			'controllers/grid/gridCell.tpl', // Default null not supported in OMP 1.1
			$cellProvider
		));
		$this->addColumn(new GridColumn(
			'lastName',
			'plugins.generic.unregisteredUsers.lastName',
			null,
			'controllers/grid/gridCell.tpl', // Default null not supported in OMP 1.1
			$cellProvider
		));
		$this->addColumn(new GridColumn(
			'email',
			'plugins.generic.unregisteredUsers.email',
			null,
			'controllers/grid/gridCell.tpl', // Default null not supported in OMP 1.1
			$cellProvider
		));
		$this->addColumn(new GridColumn(
			'ompUsername',
			'plugins.generic.unregisteredUsers.ompUsername',
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
		return new UnregisteredUsersGridRow();
	}

	/**
	 * An action to add a new user
	 * @param $args array Arguments to the request
	 * @param $request PKPRequest Request object
	 */
	function addUnregisteredUser($args, $request) {

		return $this->editUnregisteredUser($args, $request);
	}

	/**
	 * An action to edit a user
	 * @param $args array Arguments to the request
	 * @param $request PKPRequest Request object
	 * @return string Serialized JSON object
	 */
	function editUnregisteredUser($args, $request) {

		$unregisteredUserId = $request->getUserVar('unregisteredUserId');
		$context = $request->getContext();
		$this->setupTemplate($request);

		// Create and present the edit form
		import('plugins.generic.unregisteredUsers.controllers.grid.form.UnregisteredUserForm');
		$unregisteredUsersPlugin = self::$plugin;

		$unregisteredUserForm = new UnregisteredUserForm(self::$plugin, $context->getId(), $unregisteredUserId);
		$unregisteredUserForm->initData();

		$json = new JSONMessage(true, $unregisteredUserForm->fetch($request));

		return $json->getString();
	}

	/**
	 * Update a user
	 * @param $args array
	 * @param $request PKPRequest
	 * @return string Serialized JSON object
	 */
	function updateUnregisteredUser($args, $request) {

		$unregisteredUserId = $request->getUserVar('unregisteredUserId');
		$context = $request->getContext();
		$this->setupTemplate($request);

		// Create and populate the form
		import('plugins.generic.unregisteredUsers.controllers.grid.form.UnregisteredUserForm');
		$unregisteredUsersPlugin = self::$plugin;
		$unregisteredUserForm = new UnregisteredUserForm(self::$plugin, $context->getId(), $unregisteredUserId);
		$unregisteredUserForm->readInputData();

		// Check the results
		if ($unregisteredUserForm->validate()) {
			// Save the results
			$unregisteredUserForm->execute();
 			return DAO::getDataChangedEvent();
		} else {
			// Present any errors
			$json = new JSONMessage(true, $unregisteredUserForm->fetch($request));
			return $json->getString();
		}
	}

	/**                               
	 * @param $args array
	 * Delete a user
	 * @param $request PKPRequest
	 * @return string Serialized JSON object
	 */
	function delete($args, $request) {

		$unregisteredUserId = $request->getUserVar('unregisteredUserId');
		$context = $request->getContext();

		$unregisteredUsersDao = new UnregisteredUsersDAO();
		$unregisteredUser = $unregisteredUsersDao->getById($unregisteredUserId, $context->getId());

		$unregisteredUsersDao->deleteObject($unregisteredUser);

		return DAO::getDataChangedEvent();
	}

	function showGroups($args, $request){

		$unregisteredUserId = $request->getUserVar('unregisteredUserId');
		$context = $request->getContext();
		$contextId = $context->getId();

		import('plugins.generic.unregisteredUsers.classes.UnregisteredUsersDAO');
		$unregisteredUsersDAO = new UnregisteredUsersDAO();
		$groups = $unregisteredUsersDAO->getGroupsByUser($contextId,$unregisteredUserId);
		$user = $unregisteredUsersDAO->getById($unregisteredUserId, $contextId);
		$userName = $user->getFirstName() . " " . $user->getLastName();

		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('groups', $groups);
		$templateMgr->assign('userName', $userName);

		$json = new JSONMessage(true,  $templateMgr->fetch(self::$plugin->getTemplatePath().'/showGroups.tpl'));
		return $json->getString();

	}

}

?>
