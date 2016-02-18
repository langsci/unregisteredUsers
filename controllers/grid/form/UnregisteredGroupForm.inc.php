<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/UnregisteredGroupForm.inc.php
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UnregisteredGroupForm
 *
 */
import('lib.pkp.classes.form.Form');

class UnregisteredGroupForm extends Form {

	var $contextId;

	var $unregisteredGroupId;

	var $plugin;

	var $uniqueName;

	/**
	 * Constructor
	 */
	function UnregisteredGroupForm($unregisteredUsersPlugin, $contextId, $unregisteredGroupId = null) {

		parent::Form($unregisteredUsersPlugin->getTemplatePath() . 'editUnregisteredGroupForm.tpl');

		$this->contextId = $contextId;
		$this->unregisteredGroupId = $unregisteredGroupId;
		$this->plugin = $unregisteredUsersPlugin;
		$this->uniqueName = true;

		// Add form checks
		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidator($this, 'name', 'required', 'plugins.generic.unregisteredUsers.groupNameRequired'));
	}

	function setUniqueName($unique) {
		$this->uniqueName = $unique;
	}

	/**
	 * Initialize form data from current group group.
	 */
	function initData() {

		if ($this->unregisteredGroupId) {
			$unregisteredGroupsDao = new UnregisteredGroupsDAO();
			$unregisteredGroup = $unregisteredGroupsDao->getById($this->unregisteredGroupId, $this->contextId);
			$this->setData('name', $unregisteredGroup->getName());
			$this->setData('notes', $unregisteredGroup->getNotes());
		}
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('name','notes'));
	}

	function getGroupName() {
		return $this->getData('name');
	}

	/**
	 * @see Form::fetch
	 */
	function fetch($request) {

		$templateMgr = TemplateManager::getManager();
		$templateMgr->assign('unregisteredGroupId', $this->unregisteredGroupId);
		$templateMgr->assign('uniqueName', $this->uniqueName);

		return parent::fetch($request);
	}

	/**
	 * Save form values into the database
	 */
	function execute() {

		//$unregisteredGroupsDao = DAORegistry::getDAO('UnregisteredGroupsDAO');
		$unregisteredGroupsDao = new UnregisteredGroupsDAO();
		if ($this->unregisteredGroupId) {
			// Load and update an existing page
			$unregisteredGroup = $unregisteredGroupsDao->getById($this->unregisteredGroupId, $this->contextId);
		} else {
			// Create a new static page
			$unregisteredGroup = $unregisteredGroupsDao->newDataObject();
			$unregisteredGroup->setContextId($this->contextId);
		}
		$unregisteredGroup->setName($this->getData('name'));
		$unregisteredGroup->setNotes($this->getData('notes'));

		if ($this->unregisteredGroupId) {
			$unregisteredGroupsDao->updateObject($unregisteredGroup);
		} else {
			$unregisteredGroupsDao->insertObject($unregisteredGroup);
		}
	}
}

?>
