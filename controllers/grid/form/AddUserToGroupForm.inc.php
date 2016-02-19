<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/AddUserToGroupForm.inc.php
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class AddUserToGroupForm
 *
 */

import('lib.pkp.classes.form.Form');

class AddUserToGroupForm extends Form {

	var $contextId;

	var $unregisteredGroupId;

	var $plugin;

	/**
	 * Constructor.
	 */
	function AddUserToGroupForm($unregisteredUsersPlugin, $contextId, $unregisteredGroupId) {

		parent::Form($unregisteredUsersPlugin->getTemplatePath() . 'addUserToGroupForm.tpl');

		$this->unregisteredGroupId=$unregisteredGroupId;
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
		$nonGivenUsers = $unregisteredGroupsDAO->getNonGivenUsers($request->getContext()->getId(),$this->unregisteredGroupId);
		$templateMgr->assign('nonGivenUsers', $nonGivenUsers);
		$templateMgr->assign('unregisteredGroupId', $this->unregisteredGroupId);

		return parent::fetch($request);
	}
}

?>
