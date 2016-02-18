<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/DeleteUserFromGroupForm.inc.php
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class DeleteUserFromGroupForm
 *
 */

import('lib.pkp.classes.form.Form');

class DeleteUserFromGroupForm extends Form {


	var $contextId;

	var $unregisteredGroupId;

	var $plugin;

	/**
	 * Constructor.
	 */
	function DeleteUserFromGroupForm($unregisteredUsersPlugin, $contextId, $unregisteredGroupId) {

		parent::Form($unregisteredUsersPlugin->getTemplatePath() . 'deleteUserFromGroupForm.tpl');
		$this->unregisteredGroupId=$unregisteredGroupId;
		$this->contextId=$contextId;
		$this->addCheck(new FormValidatorPost($this));
	}

	//
	// Overridden template methods
	//

	/**
	 * Fetch the form.
	 * @see Form::fetch()
	 */
	function fetch($request) {

		$templateMgr = TemplateManager::getManager($request);
		import('plugins.generic.unregisteredUsers.classes.UnregisteredGroupsDAO');
		$unregisteredGroupsDAO = new UnregisteredGroupsDAO();
		$givenUsers = $unregisteredGroupsDAO->getGivenUsers($this->contextId,$this->unregisteredGroupId);
		asort($givenUsers);
		$templateMgr->assign('givenUsers', $givenUsers);
		$templateMgr->assign('unregisteredGroupId', $this->unregisteredGroupId);

		return parent::fetch($request);
	}
}

?>
