<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/UnregisteredUsersGridRow.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UnregisteredUsersGridRow
 *
 */

import('lib.pkp.classes.controllers.grid.GridRow');

class UnregisteredUsersGridRow extends GridRow {
	/**
	 * Constructor
	 */
	function UnregisteredUsersGridRow() {
		parent::GridRow();
	}

	//
	// Overridden template methods
	//
	/**
	 * @copydoc GridRow::initialize()
	 */
	function initialize($request, $template = null) {

		parent::initialize($request, $template);

		$unregisteredUserId = $this->getId();
		if (!empty($unregisteredUserId)) {
			$router = $request->getRouter();

			// Create the "edit user" action
			import('lib.pkp.classes.linkAction.request.AjaxModal');
			$this->addAction(
				new LinkAction(
					'editUnregisteredUser',
					new AjaxModal(
						$router->url($request, null, null, 'editUnregisteredUser', null, array('unregisteredUserId' => $unregisteredUserId)),
						__('grid.action.edit'),
						'modal_edit',
						true),
					__('grid.action.edit'),
					null,
					__('plugins.generic.unregisteredUsers.tooltip.editUser')
				)
			);

			// Create the "delete user" action
			import('lib.pkp.classes.linkAction.request.RemoteActionConfirmationModal');
			$this->addAction(
				new LinkAction(
					'delete',
					new RemoteActionConfirmationModal(
						__('common.confirmDelete'),
						__('grid.action.delete'),
						$router->url($request, null, null, 'delete', null, array('unregisteredUserId' => $unregisteredUserId)), 'modal_delete'
					),
					__('grid.action.delete'),
					'delete'
				)
			);

			// Create the "show groups" action
			$this->addAction(
				new LinkAction(
					'showGroups',
					new AjaxModal(
						$router->url($request, null, null, 'showGroups', null, array('unregisteredUserId' => $unregisteredUserId)),
						__('plugins.generic.unregisteredUsers.showGroups'),
						'modal_edit',
						true),
					__('plugins.generic.unregisteredUsers.showGroups'),
					null,
					__('plugins.generic.unregisteredUsers.tooltip.showGroups')
				)
			);
		}
	}
}

?>
