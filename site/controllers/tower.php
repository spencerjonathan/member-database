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

/**
 * tower Database Controller
 *
 * @package Joomla.Administrator
 * @subpackage com_memberdatabase
 * @since 0.0.9
 */
class MemberDatabaseControllerTower extends JControllerForm {
	
	public function history($key = null, $urlVar = null) {
		
		$towerId = $this->input->get->get ( 'id' );
		
		$this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=towerhistory&towerId=' . $towerId, false ) );
		
		return false;
		
	}
	
	/**
	 * Method to edit an existing record.
	 *
	 * @param string $key
	 *        	The name of the primary key of the URL variable.
	 * @param string $urlVar
	 *        	The name of the URL variable if different from the primary key
	 *        	(sometimes required to avoid router collisions).
	 *
	 * @return boolean True if access level check and checkout passes, false otherwise.
	 *
	 * @since 12.2
	 */
	public function edit($key = null, $urlVar = null) {
		$model = $this->getModel ();
		$table = $model->getTable ();
		$cid = $this->input->post->get ( 'cid', array (), 'array' );
		$context = "$this->option.edit.$this->context";
		
		// Determine the name of the primary key for the data.
		if (empty ( $key )) {
			$key = $table->getKeyName ();
		}
		
		// To avoid data collisions the urlVar may be different from the primary key.
		if (empty ( $urlVar )) {
			$urlVar = $key;
		}
		
		// Get the previous record id (if any) and the current record id.
		$recordId = ( int ) (count ( $cid ) ? $cid [0] : $this->input->getInt ( $urlVar ));
		// $checkin = property_exists($table, 'checked_out');
		
		// Access check.
		if (! $this->allowEdit ( array (
				$key => $recordId
		), $key )) {
			
			 $this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
			 $this->setMessage($this->getError(), 'error');
			
			
			//$this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=tower&layout=view&id=' . $recordId, false ) );
			
			return false;
		}
		
		// push the new record id into the session.
		$this->holdEditId ( $context, $recordId );
		JFactory::getApplication ()->setUserState ( $context . '.data', null );
		
		$this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend ( $recordId, $urlVar ), false ) );
		
		return true;
	}
	protected function allowEdit($data = array(), $key = 'id') {
		jimport ( 'joomla.application.component.helper' );
		
		$db_locked = JComponentHelper::getParams ( 'com_memberdatabase' )->get ( 'db_locked' );
		
		error_log ( "tower.allowEdit: db_locked is " . $db_locked );
		
		if ($db_locked == true) {
			$this->setError ( 'Editing tower details is currently not permitted because the database is currently locked.' );
			$this->setMessage ( $this->getError (), 'error' );
			return false;
		}
		
		$userId = JFactory::getUser ()->id;
		$towerId = $data ['id'];
		
		error_log ( "value of \$data in allowEdit:" . json_encode ( $data ), 0 );
		error_log ( "value of \$user in allowEdit:" . json_encode ( JFactory::getUser () ), 0 );
		
		if (JFactory::getUser ()->authorise ( 'tower.edit', 'com_memberdatabase' )) {
			return true;
		}
		
		$db = JFactory::getDbo ();
		
		// Build the database query to get the rules for the asset.
		$query = $db->getQuery ( true )->select ( 'count(*)' )->from ( $db->quoteName ( '#__md_usertower', 'ut' ) )->join ( 'INNER', $db->quoteName ( '#__md_tower', 't' ) . ' ON (' . $db->quoteName ( 't.id' ) . ' = ' . $db->quoteName ( 'ut.tower_id' ) . ')' )->where ( 'ut.user_id = ' . ( int ) $userId . ' and t.id = ' . ( int ) $towerId );
		
		// Execute the query and load the rules from the result.
		$db->setQuery ( $query );
		$result = $db->loadResult ();
		
		if ($result == 1) {
			return true;
		}
		
		error_log ( "User with id " . $userId . " does not have authorisation to modify tower with id " . $towerId, 0 );
		
		return false;
	}
	protected function allowSave($data = array(), $key = 'id') {
		$db_locked = JComponentHelper::getParams ( 'com_memberdatabase' )->get ( 'db_locked' );
		
		error_log ( "tower.allowSave: db_locked is " . $db_locked );
		
		if ($db_locked == true) {
			$this->setError ( 'Editing tower details is currently not permitted because the database is currently locked.' );
			$this->setMessage ( $this->getError (), 'error' );
			return false;
		}
		
		return $this->allowEdit($data, $key);
	}
	
}
