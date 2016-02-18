<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/UnregisteredGroupsGridCellProvider.inc.php
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UnregisteredGroupsGridCellProvider
 *
 */

import('lib.pkp.classes.controllers.grid.GridCellProvider');
import('lib.pkp.classes.linkAction.request.RedirectAction');

class UnregisteredGroupsGridCellProvider extends GridCellProvider {

	/**
	 * Constructor
	 */
	function UnregisteredGroupsGridCellProvider() {
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
		$unregisteredGroup = $row->getData();
		switch ($column->getId()) {
			case 'name':
				// The action has the label
				return array('label' => $unregisteredGroup->getName());
		}
	}
}

?>
