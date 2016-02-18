<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/UnregisteredUsersDAO.inc.php
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UnregisteredUsersDAO
 */

import('lib.pkp.classes.db.DAO');
import('plugins.generic.unregisteredUsers.classes.UnregisteredUser');

class UnregisteredUsersDAO extends DAO {

	function UnregisteredUsersDAO() {
		parent::DAO();
	}

	function getById($unregisteredUserId, $contextId = null) {
		$params = array((int) $unregisteredUserId);
		if ($contextId) $params[] = $contextId;

		$result = $this->retrieve(
			'SELECT * FROM langsci_unregistered_users WHERE user_id = ?'
			. ($contextId?' AND context_id = ?':''),
			$params
		);

		$returner = null;
		if ($result->RecordCount() != 0) {
			$returner = $this->_fromRow($result->GetRowAssoc(false));
		}
		$result->Close();
		return $returner;
	}

	function getByContextId($contextId, $rangeInfo = null) {
		$result = $this->retrieveRange(
			'SELECT * FROM langsci_unregistered_users WHERE context_id = ? ORDER BY last_name',
			(int) $contextId,
			$rangeInfo
		);

		return new DAOResultFactory($result, $this, '_fromRow');
	}

	function getGroupsByUser($contextId,$unregisteredUserId) {

		$result = $this->retrieve(
			'select groups.group_name FROM langsci_unregistered_users_groups comb left join langsci_unregistered_groups groups ON comb.group_id=groups.group_id where comb.user_id='.$unregisteredUserId.' and comb.context_id='.$contextId.' and groups.context_id='.$contextId.' order by groups.group_name'
		);

		if ($result->RecordCount() == 0) {
			$result->Close();
			return null;
		} else {
			$groups = array();
			while (!$result->EOF) {

				$row = $result->getRowAssoc(false);
				$groups[] =  $this->convertFromDB($row['group_name']);
				$result->MoveNext();
			}
			$result->Close();
			return $groups;	
		}
	}

	function insertObject($unregisteredUser) {

		$this->update(
			'INSERT INTO langsci_unregistered_users (context_id, first_name, last_name, email, notes, omp_username)
			VALUES (?,?,?,?,?,?)',
			array(
				(int) $unregisteredUser->getContextId(),
				$unregisteredUser->getFirstName(),
				$unregisteredUser->getLastName(),
				$unregisteredUser->getEmail(),
				$unregisteredUser->getNotes(),
				$unregisteredUser->getOMPUsername()
			)
		);

		$unregisteredUser->setId($this->getInsertId());

		return $unregisteredUser->getId();
	}

	function updateObject($unregisteredUser) {

		$this->update(
			'UPDATE	langsci_unregistered_users
			SET	context_id = ?,
				first_name = ?,
				last_name = ?,
				email = ?,
				notes = ?,
				omp_username = ?
			WHERE	user_id = ?',
			array(
				(int) $unregisteredUser->getContextId(),
				$unregisteredUser->getFirstName(),
				$unregisteredUser->getLastName(),
				$unregisteredUser->getEmail(),
				$unregisteredUser->getNotes(),
				$unregisteredUser->getOMPUsername(),
				(int) $unregisteredUser->getId()
			)
		);
	}

	function deleteById($unregisteredUserId) {
		$this->update(
			'DELETE FROM langsci_unregistered_users WHERE user_id = ?',
			(int) $unregisteredUserId
		);
	}

	function deleteObject($unregisteredUser) {

		$this->deleteById($unregisteredUser->getId());

		// delete references to groups
		$this->update(
			'DELETE FROM langsci_unregistered_users_groups WHERE user_id = ? AND context_id = ?',
			array(
				$unregisteredUser->getId(), 
				$unregisteredUser->getContextId()
			)
		);
	}

	function newDataObject() {
		return new UnregisteredUser();
	}

	function _fromRow($row) {
		$unregisteredUser = $this->newDataObject();
		$unregisteredUser->setId($row['user_id']);
		$unregisteredUser->setFirstName($row['first_name']);
		$unregisteredUser->setLastName($row['last_name']);
		$unregisteredUser->setEmail($row['email']);
		$unregisteredUser->setNotes($row['notes']);
		$unregisteredUser->setOMPUsername($row['omp_username']);
		$unregisteredUser->setContextId($row['context_id']);
		return $unregisteredUser;
	}

	function getInsertId() {
		return $this->_getInsertId('langsci_unregistered_users', 'user_id');
	}

}

?>
