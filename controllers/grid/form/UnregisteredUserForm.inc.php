<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/UnregisteredGroup.inc.php
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UnregisteredUserForm
 *
 */

import('lib.pkp.classes.form.Form');

class UnregisteredUserForm extends Form {

	var $contextId;

	var $unregisteredUserId;

	var $plugin;

	/**
	 * Constructor
	 */
	function UnregisteredUserForm($unregisteredUsersPlugin, $contextId, $unregisteredUserId = null) {

		parent::Form($unregisteredUsersPlugin->getTemplatePath() . 'editUnregisteredUserForm.tpl');

		$this->contextId = $contextId;
		$this->unregisteredUserId = $unregisteredUserId;
		$this->plugin = $unregisteredUsersPlugin;

		// Add form checks
		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidator($this,'firstName','required', 'plugins.generic.unregisteredUsers.firstNameRequired'));
		$this->addCheck(new FormValidator($this,'lastName', 'required', 'plugins.generic.unregisteredUsers.lastNameRequired'));
	}

	/**
	 * Initialize form data from current group.
	 */
	function initData() {

		if ($this->unregisteredUserId) {
			$unregisteredUsersDao = new UnregisteredUsersDAO();
			$unregisteredUser = $unregisteredUsersDao->getById($this->unregisteredUserId, $this->contextId);
			$this->setData('firstName', $unregisteredUser->getFirstName());
			$this->setData('lastName', $unregisteredUser->getLastName());
			$this->setData('email', $unregisteredUser->getEmail());
			$this->setData('notes', $unregisteredUser->getNotes());
			$this->setData('ompUsername', $unregisteredUser->getOMPUsername());
		}
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('firstName','lastName','email','notes','ompUsername'));
	}

	/**
	 * @see Form::fetch
	 */
	function fetch($request) {

		$templateMgr = TemplateManager::getManager();
		$templateMgr->assign('unregisteredUserId', $this->unregisteredUserId);

		return parent::fetch($request);
	}

	/**
	 * Save form values into the database
	 */
	function execute() {

		$unregisteredUsersDao = new UnregisteredUsersDAO();
		if ($this->unregisteredUserId) {
			// Load and update an existing page
			$unregisteredUser = $unregisteredUsersDao->getById($this->unregisteredUserId, $this->contextId);
		} else {
			// Create a new static page
			$unregisteredUser = $unregisteredUsersDao->newDataObject();
			$unregisteredUser->setContextId($this->contextId);
		}
		$unregisteredUser->setFirstName($this->getData('firstName'));
		$unregisteredUser->setLastName($this->getData('lastName'));
		$unregisteredUser->setEmail($this->getData('email'));
		$unregisteredUser->setNotes($this->getData('notes'));
		$unregisteredUser->setOMPUsername($this->getData('ompUsername'));

		if ($this->unregisteredUserId) {
			$unregisteredUsersDao->updateObject($unregisteredUser);
		} else {
			$unregisteredUsersDao->insertObject($unregisteredUser);
		}
	}
}

?>
