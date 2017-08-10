<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ('joomla.filesystem.file');

JLoader::import('QueryHelper', JPATH_COMPONENT . "/helpers/");

/**
 * Member Model
 *
 * @since 0.0.1
 */
class MemberDatabaseModelMember extends JModelAdmin {
	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param string $type
	 *        	The table name. Optional.
	 * @param string $prefix
	 *        	The class prefix. Optional.
	 * @param array $config
	 *        	Configuration array for model. Optional.
	 *        	
	 * @return JTable A JTable object
	 *        
	 * @since 1.6
	 */
	public function getTable($type = 'Member', $prefix = 'MemberDatabaseTable', $config = array()) {
		JTable::addIncludePath ( JPATH_ADMINISTRATOR . '/components/com_memberdatabase/tables' );
		return JTable::getInstance ( $type, $prefix, $config );
	}
	
	/**
	 * Method to get the record form.
	 *
	 * @param array $data
	 *        	Data for the form.
	 * @param boolean $loadData
	 *        	True if the form is to load its own data (default case), false if not.
	 *        	
	 * @return mixed A JForm object on success, false on failure
	 *        
	 * @since 1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm ( 'com_memberdatabase.member', 'member', array (
				'control' => 'jform',
				'load_data' => $loadData 
		) );
		
		if (empty ( $form )) {
			return false;
		}
		
		return $form;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return mixed The data for the form.
	 *        
	 * @since 1.6
	 */
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication ()->getUserState ( 'com_memberdatabase.edit.member.data', array () );
		
		if (empty ( $data )) {
			$data = $this->getItem ();
		}
		
		return $data;
	}
	public function getTowers() {
		$db = JFactory::getDbo ();
		$userId = JFactory::getUser ()->id;
		
		$query = $db->getQuery ( true );
		
		$query->select ( 't.id, concat_ws(\', \', t.place, t.designation) as tower' )->from ( $db->quoteName ( '#__md_tower', 't' ) );
		
		if (! JFactory::getUser ()->authorise ( 'member.view', 'com_memberdatabase' )) {
			$query->join ( 'INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (t.id = ut.tower_id)' );
			$query->where ( 'ut.user_id = ' . ( int ) $userId );
		}
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		return $results;
	}
	
	public function getAttachments($memberId, $attachmentId = 0) {
		$db = JFactory::getDbo ();
		$userId = JFactory::getUser ()->id;
		
		$query = $db->getQuery ( true );
		
		$query->select('a.*, concat(u.name, " (", u.username, ")") as mod_user');
		$query->from($db->quoteName ( '#__md_member_attachment', 'a' ));
		$query->join('INNER', $db->quoteName ( '#__md_member', 'm' ) . ' ON (a.member_id = m.id)' );
		$query->join('INNER', $db->quoteName ( '#__md_tower', 't' ) . ' ON (m.tower_id = t.id)' );
		$query->join('INNER', $db->quoteName ( '#__users', 'u' ) . ' ON (a.mod_user_id = u.id)' );
		QueryHelper::addDistrictJoin($db, $query);
		
		if ($memberId) {
			$query->where('a.member_id = ' . (int) $memberId);
		} elseif ($attachmentId) {
			$query->where('a.id = ' . (int) $attachmentId);
		} else {
			$this->setError("Must specifiy a memberId or attachmentId when retrieving MemberAttachment details");
			return -1;
		}
		
		$query = QueryHelper::addDataPermissionConstraints($db, $query);
		
		$db->setQuery ( $query );
		$results = $db->loadObjectList ();;
		
		return $results;
	}
	
	public function getHistory($memberId) {
		$db = JFactory::getDbo ();
		$userId = JFactory::getUser ()->id;
		
		$query = $db->getQuery ( true );
		
		$query_string = "(select null as history_id, #__md_member.* 
		from #__md_member 
		where id = $memberId 
		UNION ALL 
		select #__md_member_history.* 
		from #__md_member_history 
		where id = $memberId 
		) mh";
		
		$query->select ( 'mh.*, concat_ws(", ", t.place, t.designation) as tower, mt.name as member_type, u.name as mod_user' );
		$query->from ( $query_string );
		$query->join ( 'LEFT', $db->quoteName ( '#__md_tower', 't' ) . 'ON (mh.tower_id = t.id)' );
		$query->join ( 'LEFT', $db->quoteName ( '#__md_member_type', 'mt' ) . 'ON (mh.member_type_id = mt.id)' );
		$query->join ( 'LEFT', $db->quoteName ( '#__users', 'u' ) . 'ON (mh.mod_user_id = u.id)' );
		
		/*
		 * $query->select ( $query_string );
		 */
		if (! JFactory::getUser ()->authorise ( 'member.view', 'com_memberdatabase' )) {
			$query->join ( 'INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (mh.tower_id = ut.tower_id)' );
			$query->where ( 'ut.user_id = ' . ( int ) $userId );
		}
		
		$query->order("mod_date DESC");
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		return $results;
	}
	public function markAsVerified($memberId) {
		$db = JFactory::getDbo ();
		$userId = JFactory::getUser ()->id;
		$currentDate = date ( 'Y-m-d H:i:s' );
		
		// Create a new query object.
		$query = $db->getQuery ( true );
		
		// Insert columns.
		$columns = array (
				'member_id',
				'user_id',
				'verified_date' 
		);
		
		// Insert values.
		$values = array (
				$memberId,
				$userId,
				$db->quote ( $currentDate ) 
		);
		
		// Prepare the insert query.
		$query->insert ( $db->quoteName ( '#__md_member_verified' ) )->columns ( $db->quoteName ( $columns ) )->values ( implode ( ',', $values ) );
		
		// Set the query using our newly populated query object and execute it.
		$db->setQuery ( $query );
		$result = $db->execute ();
		
		error_log ( "Result from executing query to markAsVerified: " . serialize ( $result ) );
		
		return $result;
	}
	public function addAttachment($memberId, $filename, $type, $description, $tmp_name) {
		$db = JFactory::getDbo ();
		$userId = JFactory::getUser ()->id;
		$currentDate = date ( 'Y-m-d H:i:s' );
		
		$filename = JFile::makeSafe($filename);
		
		// Create a new query object.
		$query = $db->getQuery ( true );
		
		// Insert columns.
		$columns = array (
				'member_id',
				'name',
				'type',
				'description',
				'mod_user_id',
				'mod_date' 
		);
		
		// Insert values.
		// $values = array($memberId, $filename, $type, mysql_real_escape_string(file_get_contents($tmp_name)) ,$userId, $db->quote($currentDate) );
		$values = array (
				$memberId,
				$db->quote ( $filename ),
				$db->quote ( $type ),
				$db->quote( $description ),
				$userId,
				$db->quote ( $currentDate ) 
		);
		
		// Prepare the insert query.
		$query->insert ( $db->quoteName ( '#__md_member_attachment' ) )->columns ( $db->quoteName ( $columns ) )->values ( implode ( ',', $values ) );
		
		// Set the query using our newly populated query object and execute it.
		$db->setQuery ( $query );
		$result = $db->query ();
		
		$key = $db->insertid ();
		
		if ($key) {
			$attachment_location = JComponentHelper::getParams ( 'com_memberdatabase' )->get ( 'attachment_location' );
			$dest = $attachment_location . "/" . $key;
			
			error_log ( "member.addAtachment: copying file from $tmp_name to $dest" );
			
			if (!JFile::copy ( $tmp_name, $dest )) {
				
				// If the file copy was not successful then remove the record from the db table
				$query = $db->getQuery ( true );
				$query->delete ( $db->quoteName ( '#__md_member_attachment' ) );
				$query->where ("id = $key");
				$db->query ();
				
				return false;
			}
		}
		
		error_log ( "Result from executing query to addAtachment: " . $result );
		
		return $result;
	}
}
