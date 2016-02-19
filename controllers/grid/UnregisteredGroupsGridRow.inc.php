<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/UnregisteredGroupsGridRow.inc.php
 *
 * Copyright (c) 2016 Language Science Press
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
					null,
					__('plugins.generic.unregisteredUsers.tooltip.editGroup') 
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
					'addUser',
					new AjaxModal(
						$router->url($request, null, null, 'manageUsers', null, array('unregisteredGroupId' => $unregisteredGroupId,'modus'=>'add')),
						__('plugins.generic.unregisteredUsers.group.manageUsers.add'), // title in the form
						'modal_edit',
						true),
					__('plugins.generic.unregisteredUsers.group.manageUsers.add'), // title in the settings area
					null, 
					__('plugins.generic.unregisteredUsers.tooltip.addUser') // tooltip
				)
			);
//LinkAction($id, &$actionRequest, $title = null, $image = null, $toolTip = null) {
			// Create the "add user" action
			$this->addAction(
				new LinkAction(
					'removeUser',
					new AjaxModal(
						$router->url($request, null, null, 'manageUsers', null, array('unregisteredGroupId' => $unregisteredGroupId,'modus'=>'delete')),
						__('plugins.generic.unregisteredUsers.group.manageUsers.remove'),
						'modal_edit',
						true),
					__('plugins.generic.unregisteredUsers.group.manageUsers.remove'),
					null,
					__('plugins.generic.unregisteredUsers.tooltip.removeUser')
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
					null,
					__('plugins.generic.unregisteredUsers.tooltip.showUsers')
				)
			);
		}
	}
}

?>
