<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/UnregisteredGroupsGridRow.inc.php
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UnregisteredGroupsGridRow
 *
 */

import('lib.pkp.classes.controllers.grid.GridRow');

class UnregisteredGroupsGridRow extends GridRow {
	/**
	 * Constructor
	 */
	function UnregisteredGroupsGridRow() {
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

		$unregisteredGroupId = $this->getId();
		if (!empty($unregisteredGroupId)) {
			$router = $request->getRouter();

			// Create the "edit group" action
			import('lib.pkp.classes.linkAction.request.AjaxModal');
			$this->addAction(
				new LinkAction(
					'editUnregisteredGroup',
					new AjaxModal(
						$router->url($request, null, null, 'editUnregisteredGroup', null, array('unregisteredGroupId' => $unregisteredGroupId)),
						__('grid.action.edit'),
						'modal_edit',
						true),
					__('grid.action.edit'),
					'edit'
				)
			);

			// Create the "delete group" action
			import('lib.pkp.classes.linkAction.request.RemoteActionConfirmationModal');
			$this->addAction(
				new LinkAction(
					'delete',
					new RemoteActionConfirmationModal(
						__('common.confirmDelete'),
						__('grid.action.delete'),
						$router->url($request, null, null, 'delete', null, array('unregisteredGroupId' => $unregisteredGroupId)), 'modal_delete'
					),
					__('grid.action.delete'),
					'delete'
				)
			);

			// Create the "add user" action
			$this->addAction(
				new LinkAction(
					'addUsers',
					new AjaxModal(
						$router->url($request, null, null, 'manageUsers', null, array('unregisteredGroupId' => $unregisteredGroupId,'modus'=>'add')),
						__('plugins.generic.unregisteredUsers.group.manageUsers.add'),
						'modal_edit',
						true),
					__('plugins.generic.unregisteredUsers.group.manageUsers.add'),
					'addUsers'
				)
			);

			// Create the "add user" action
			$this->addAction(
				new LinkAction(
					'deleteUsers',
					new AjaxModal(
						$router->url($request, null, null, 'manageUsers', null, array('unregisteredGroupId' => $unregisteredGroupId,'modus'=>'delete')),
						__('plugins.generic.unregisteredUsers.group.manageUsers.delete'),
						'modal_edit',
						true),
					__('plugins.generic.unregisteredUsers.group.manageUsers.delete'),
					'deleteUsers'
				)
			);

			// Create the "add users" action
			$this->addAction(
				new LinkAction(
					'showUsers',
					new AjaxModal(
						$router->url($request, null, null, 'showUsers', null, array('unregisteredGroupId' => $unregisteredGroupId)),
						__('plugins.generic.unregisteredUsers.showUsers'),
						'modal_edit',
						true),
					__('plugins.generic.unregisteredUsers.showUsers'),
					'showUsers'
				)
			);
		}
	}
}

?>
