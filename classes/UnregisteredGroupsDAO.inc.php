<?php

/**
 * @file plugins/generic/unregisteredUsers/classes/UnregisteredGroupsDAO.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UnregisteredGroupsDAO
 */

import('lib.pkp.classes.db.DAO');
import('plugins.generic.unregisteredUsers.classes.UnregisteredGroup');

class UnregisteredGroupsDAO extends DAO {

	function UnregisteredGroupsDAO() {
		parent::DAO();
	}

	function getById($unregisteredGroupId, $contextId = null) {
		$params = array((int) $unregisteredGroupId);
		if ($contextId) $params[] = $contextId;

		$result = $this->retrieve(
			'SELECT * FROM langsci_unregistered_groups WHERE group_id = ?'
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
			'SELECT * FROM langsci_unregistered_groups WHERE context_id = ? ORDER BY group_name',
			(int) $contextId,
			$rangeInfo
		);

		return new DAOResultFactory($result, $this, '_fromRow');
	}

	function getUsersByGroup($contextId,$unregisteredGroupId) {

		$result = $this->retrieve(
			'select comb.user_id, users.first_name, users.last_name,users.email, users.omp_username FROM langsci_unregistered_users_groups comb left join langsci_unregistered_users users ON comb.user_id=users.user_id where comb.group_id='.$unregisteredGroupId.' and comb.context_id='.$contextId.' and users.context_id='.$contextId.' order by users.last_name'
		);

		if ($result->RecordCount() == 0) {
			$result->Close();
			return null;
		} else {
			$users = array();
			$rowcount=0;
			while (!$result->EOF) {
				$row = $result->getRowAssoc(false);
				$users[$rowcount]['firstName'] = $this->convertFromDB($row['first_name']);
				$users[$rowcount]['lastName'] = $this->convertFromDB($row['last_name']);
				$users[$rowcount]['email'] = $this->convertFromDB($row['email']);
				$users[$rowcount]['ompUsername'] = $this->convertFromDB($row['omp_username']);
				$rowcount++;
				$result->MoveNext();
			}
			$result->Close();
			return $users;	
		}
	}

	function getGivenUsers($contextId,$unregisteredGroupId) {

		$result = $this->retrieve(
			' select comb.user_id, users.first_name, users.last_name FROM langsci_unregistered_users_groups comb left join langsci_unregistered_users users ON comb.user_id=users.user_id where comb.group_id='.$unregisteredGroupId.' and comb.context_id='.$contextId.' and users.context_id='.$contextId . ' order by users.last_name'
		);

		if ($result->RecordCount() == 0) {
			$result->Close();
			return null;
		} else {
			$users = array();
			while (!$result->EOF) {
				$row = $result->getRowAssoc(false);
				$users[$this->convertFromDB($row['user_id'])] = $this->convertFromDB($row['first_name'])." " .$this->convertFromDB($row['last_name']);
				$result->MoveNext();
			}
			$result->Close();
			return $users;	
		}
	}

	function getNonGivenUsers($contextId,$unregisteredGroupId) {

		$result = $this->retrieve(
			'select user_id, first_name, last_name from langsci_unregistered_users where context_id='.$contextId.' and user_id not in (select users.user_id from langsci_unregistered_users users left join langsci_unregistered_users_groups comb on users.user_id=comb.user_id where users.context_id='.$contextId.' and comb.context_id='.$contextId.' and comb.group_id='.$unregisteredGroupId.') order by last_name'
		);

		if ($result->RecordCount() == 0) {
			$result->Close();
			return null;
		} else {
			$users = array();
			while (!$result->EOF) {
				$row = $result->getRowAssoc(false);
				$users[$this->convertFromDB($row['user_id'])] = $this->convertFromDB($row['first_name'])." " .$this->convertFromDB($row['last_name']);
				$result->MoveNext();
			}
			$result->Close();
			return $users;
		}
	}

	function insertUserIntoGroup($userId,$groupId, $contextId) {

		$this->update(
			'INSERT INTO langsci_unregistered_users_groups (user_id, group_id, context_id) VALUES (?,?,?)',
			array(
				$userId,
				$groupId,
				$contextId
			)
		);
	}

	function insertObject($unregisteredGroup) {

		$this->update(
			'INSERT INTO langsci_unregistered_groups (context_id, group_name, notes) VALUES (?,?,?)',
			array(
				(int) $unregisteredGroup->getContextId(),
				$unregisteredGroup->getName(),
				$unregisteredGroup->getNotes()
			)
		);

		$unregisteredGroup->setId($this->getInsertId());

		return $unregisteredGroup->getId();
	}

	function updateObject($unregisteredGroup) {

		$this->update(
			'UPDATE	langsci_unregistered_groups
			SET	context_id = ?,
				group_name = ?,
				notes = ?
			WHERE	group_id = ?',
			array(
				(int) $unregisteredGroup->getContextId(),
				$unregisteredGroup->getName(),
				$unregisteredGroup->getNotes(),
				(int) $unregisteredGroup->getId()
			)
		);
	}

	function deleteById($unregisteredGroupId) {
		$this->update(
			'DELETE FROM langsci_unregistered_groups WHERE group_id = ?',
			(int) $unregisteredGroupId
		);
	}

	function deleteUserFromGroup($userId,$groupId, $contextId) {

		$this->update(
			'DELETE FROM langsci_unregistered_users_groups WHERE group_id = ? AND user_id = ? AND context_id = ?',
			array(
				(int) $groupId,
				$userId, 
				$contextId
			)
		);
	}

	function deleteObject($unregisteredGroup) {

		$this->deleteById($unregisteredGroup->getId());

		$this->update(
			'DELETE FROM langsci_unregistered_users_groups WHERE group_id = ? AND context_id = ?',
			array(
				$unregisteredGroup->getId(), 
				$unregisteredGroup->getContextId()
			)
		);
	}


	function newDataObject() {
		return new UnregisteredGroup();
	}

	function _fromRow($row) {
		$unregisteredGroup = $this->newDataObject();
		$unregisteredGroup->setId($row['group_id']);
		$unregisteredGroup->setName($row['group_name']);
		$unregisteredGroup->setNotes($row['notes']);
		$unregisteredGroup->setContextId($row['context_id']);

		return $unregisteredGroup;
	}

	function nameExists($groupId, $groupName,$contextId) {

		$result = $this->retrieve(
			'SELECT group_id from langsci_unregistered_groups where group_name="'.$groupName.'" AND context_id='. $contextId . ' AND not group_id=' . $groupId
		);

		if ($result->RecordCount() == 0) {
			$result->Close();
			return false;
		} else {
			$result->Close();
			return true;
		}
	}

	function getInsertId() {
		return $this->_getInsertId('langsci_unregistered_groups', 'group_id');
	}

	function getLocaleFieldNames() {
		return array();
	}

}

?>
