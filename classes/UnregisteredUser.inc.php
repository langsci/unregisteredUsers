<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/UnregisteredUser.inc.php
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UnregisteredGroup
 * Data object representing an unregistered user.
 */

class UnregisteredUser extends DataObject {
	/**
	 * Constructor
	 */
	function UnregisteredUser() {
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



	function setFirstName($firstName) {
		return $this->setData('firstName', $firstName);
	}

	function getFirstName() {
		return $this->getData('firstName');
	}




	function setLastName($lastName) {
		return $this->setData('lastName', $lastName);
	}

	function getLastName() {
		return $this->getData('lastName');
	}



	function setEmail($email) {
		return $this->setData('email', $email);
	}

	function getEmail() {
		return $this->getData('email');
	}




	function setOMPUsername($ompUsername) {
		return $this->setData('ompUsername', $ompUsername);
	}

	function getOMPUsername() {
		return $this->getData('ompUsername');
	}



	function setNotes($notes) {
		return $this->setData('notes', $notes);
	}

	function getNotes() {
		return $this->getData('notes');
	}

}

?>
