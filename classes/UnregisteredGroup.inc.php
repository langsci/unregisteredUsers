<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/UnregisteredGroup.inc.php
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UnregisteredGroup
 * Data object representing an unregistered group.
 */

class UnregisteredGroup extends DataObject {
	/**
	 * Constructor
	 */
	function UnregisteredGroup() {
		parent::DataObject();
	}

	//
	// Get/set methods
	//

	function getContextId(){
		return $this->getData('contextId');
	}

	function setContextId($contextId) {
		return $this->setData('contextId', $contextId);
	}


	function setName($name) {
		return $this->setData('name', $name);
	}

	function getName() {
		return $this->getData('name');
	}


	function setNotes($notes) {
		return $this->setData('notes', $notes);
	}

	function getNotes() {
		return $this->getData('notes');
	}

}

?>
