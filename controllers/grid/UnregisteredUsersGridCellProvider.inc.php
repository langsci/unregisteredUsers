<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/UnregisteredUsersGridCellProvider.inc.php
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UnregisteredUsersGridCellProvider
 *
 */

import('lib.pkp.classes.controllers.grid.GridCellProvider');
import('lib.pkp.classes.linkAction.request.RedirectAction');

class UnregisteredUsersGridCellProvider extends GridCellProvider {

	/**
	 * Constructor
	 */
	function UnregisteredUsersGridCellProvider() {
		parent::GridCellProvider();
	}

	//
	// Template methods from GridCellProvider
	//

	/**
	 * Extracts variables for a given column from a data element
	 * so that they may be assigned to template before rendering.
	 * @param $row GridRow
	 * @param $column GridColumn
	 * @return array
	 */
	function getTemplateVarsFromRowColumn($row, $column) {
		$unregisteredUser = $row->getData();
		switch ($column->getId()) {
			case 'firstName':
				// The action has the label
				return array('label' => $unregisteredUser->getFirstName());
			case 'lastName':
				// The action has the label
				return array('label' => $unregisteredUser->getLastName());
			case 'email':
				// The action has the label
				return array('label' => $unregisteredUser->getEmail());
			case 'ompUsername':
				// The action has the label
				return array('label' => $unregisteredUser->getOMPUsername());
		}
	}
}

?>
