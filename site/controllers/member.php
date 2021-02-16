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

JLoader::import('ActionlogsHelper', JPATH_COMPONENT . "/helpers/");

/**
 * HelloWorld Controller
 *
 * @package Joomla.Administrator
 * @subpackage com_memberdatabase
 * @since 0.0.9
 */
class MemberDatabaseControllerMember extends JControllerForm {
	
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
		
		$jinput = JFactory::getApplication ()->input;
		$token = $jinput->get ( 'token', null, 'ALNUM' );
		$token_text = "";
		
		if (isset ( $token )) {
			$token_text = "&token=" . $token;
		}
		
		$list_view = $jinput->get ( 'list_view', null, 'STRING' );
		$list_view_text = "";
		
		if (isset ( $list_view )) {
			$list_view_text = "&list_view=" . $list_view;
		}
		
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
			/*
			 * $this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
			 * $this->setMessage($this->getError(), 'error');
			 */
			
			$this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=member&layout=view&id=' . $recordId . $token_text, false ) );
			
			return false;
		}
		
		// push the new record id into the session.
		$this->holdEditId ( $context, $recordId );
		JFactory::getApplication ()->setUserState ( $context . '.data', null );
		
		$this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend ( $recordId, $urlVar ) . $token_text . $list_view_text, false ) );
		
		return true;
	}
	
	protected function anonymousUserHasPrivilages($memberId) {
		$jinput = JFactory::getApplication ()->input;
		$token = $jinput->get ( 'token', null, 'ALNUM' );
		
		if (isset ( $token )) {
			$db = JFactory::getDbo ();
			
			$currentDate = date ( 'Y-m-d H:i:s' );
			// Build the database query to get the rules for the asset.
			$query = $db->getQuery ( true )
			->select ( 'count(*)' )
			->from ( $db->quoteName ( '#__md_member', 'm' ) )
			->join ( 'INNER', $db->quoteName ( '#__md_member_token', 'memt' ) . ' ON (' . $db->quoteName ( 'm.email' ) . ' = ' . $db->quoteName ( 'memt.email' ) . ')' )
			->where ( 'memt.hash_token = ' . $db->quote($token) . ' and memt.expiry_date >= ' . $db->quote ( $currentDate ) )
			->where ( 'm.id = ' . (int) $memberId);
			
			// Execute the query and load the rules from the result.
			$db->setQuery ( $query );
			$result = $db->loadResult ();
			
			if ($result > 0) {
				return true;
			}
			
			return false;
		}
	}
	
	protected function allowEdit($data = array(), $key = 'id') {
		jimport ( 'joomla.application.component.helper' );
		
		$db_locked = JComponentHelper::getParams ( 'com_memberdatabase' )->get ( 'db_locked' );
		
		error_log ( "member.allowEdit: db_locked is " . $db_locked );
		
		if ($db_locked == true) {
			$this->setError ( 'Editing member details is currently not permitted because the database is currently locked.' );
			$this->setMessage ( $this->getError (), 'error' );
			return false;
		}
		
		$userId = JFactory::getUser ()->id;
		$memberId = $data ['id'];
		
		error_log ( "value of \$data in allowEdit:" . json_encode ( $data ), 0 );
		error_log ( "value of \$user in allowEdit:" . json_encode ( JFactory::getUser () ), 0 );
		
		if (JFactory::getUser ()->authorise ( 'member.edit', 'com_memberdatabase' )) {
			return true;
		}
		
		$db = JFactory::getDbo ();
		
		// Build the database query to get the rules for the asset.
		$query = $db->getQuery ( true )->select ( 'count(*)' )->from ( $db->quoteName ( '#__md_usertower', 'ut' ) )->join ( 'INNER', $db->quoteName ( '#__md_member', 'm' ) . ' ON (' . $db->quoteName ( 'm.tower_id' ) . ' = ' . $db->quoteName ( 'ut.tower_id' ) . ')' )->where ( 'ut.user_id = ' . ( int ) $userId . ' and m.id = ' . ( int ) $memberId );
		
		// Execute the query and load the rules from the result.
		$db->setQuery ( $query );
		$result = $db->loadResult ();
		
		if ($result > 0) {
			return true;
		}
		;
		
		if ($this->anonymousUserHasPrivilages($memberId)) {
			return true;
		}
		
		error_log ( "User with id " . $userId . " does not have authorisation to modify member with id " . $memberId, 0 );
		
		return false;
	}
	
	protected function allowSave($data = array(), $key = 'id') {
		$db_locked = JComponentHelper::getParams ( 'com_memberdatabase' )->get ( 'db_locked' );
		
		error_log ( "member.allowSave: db_locked is " . $db_locked );
		
		if ($db_locked == true) {
			$this->setError ( 'Editing member details is currently not permitted because the database is currently locked.' );
			$this->setMessage ( $this->getError (), 'error' );
			return false;
		}
		
		if ($data ['id']) {
			return $this->allowEdit ( $data, $key );
		} else {
			return $this->allowAdd ( $data, $key );
		}
	}
	protected function allowAdd($data = array(), $key = 'id') {
		$db_locked = JComponentHelper::getParams ( 'com_memberdatabase' )->get ( 'db_locked' );
		
		error_log ( "member.allowAdd: db_locked is " . $db_locked );
		
		if ($db_locked == true) {
			$this->setError ( 'Editing member details is currently not permitted because the database is currently locked.' );
			$this->setMessage ( $this->getError (), 'error' );
			return false;
		}
		
		if (JFactory::getUser ()->authorise ( 'member.create', 'com_memberdatabase' )) {
			return true;
		}
		
		$db = JFactory::getDbo ();
		
		// Build the database query to get the rules for the asset.
		$query = $db->getQuery ( true )->select ( 'count(*)' )->from ( $db->quoteName ( '#__md_usertower', 'ut' ) )->where ( 'ut.user_id = ' . ( int ) $userId . ' and ut.tower_id = ' . ( int ) $data ['tower_id'] );
		
		// Execute the query and load the rules from the result.
		$db->setQuery ( $query );
		$result = $db->loadResult ();
		
		if ($result > 0) {
			return true;
		}
		;
		
		return false;
	}
	
	/**
	 * Method to verify a member's detail as being correct.
	 *
	 * @param string $key
	 *        	The name of the primary key of the URL variable.
	 * @param string $urlVar
	 *        	The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *        	
	 * @return boolean True if successful, false otherwise.
	 *        
	 * @since 12.2
	 */
	public function verify($key = null, $urlVar = null) {
		$model = $this->getModel ();
		$table = $model->getTable ();
		$memberId = $this->input->get->get ( 'id' );
		
		$jinput = JFactory::getApplication ()->input;
		$token = $jinput->get ( 'token', null, 'ALNUM' );
		$token_text = "";
		
		if (isset ( $token )) {
			$token_text = "&token=" . $token;
		}
		
		error_log ( "member.verify function called with id = " . $memberId );
		
		// Determine the name of the primary key for the data.
		if (empty ( $key )) {
			$key = $table->getKeyName ();
		}
		
		// To avoid data collisions the urlVar may be different from the primary key.
		if (empty ( $urlVar )) {
			$urlVar = $key;
		}
		
		error_log ( "member.verify controller has determined that key is " . $key . ".  About to call allowEdit..." );
		
		$this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend () . $token_text, false ) );
		
		// Access check.
		if (! $this->allowEdit ( array (
				$key => $memberId 
		), $key )) {
			$this->setError ( JText::_ ( 'JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED' ) );
			$this->setMessage ( $this->getError (), 'error' );
			
			/*
			 * $this->setRedirect(
			 * JRoute::_(
			 * 'index.php?option=' . $this->option . '&view=' . $this->view_list
			 * . $this->getRedirectToListAppend(), false
			 * )
			 * );
			 */
			return false;
		}
		
		if ($model->markAsVerified ( $memberId )) {
			$this->setMessage ( JText::_ ( 'Member successfully verified.' ) );
			return true;
		} else {
			$this->setError ( JText::_ ( 'Could not verify member with id: ' . $memberId ) );
			$this->setMessage ( $this->getError (), 'error' );
			
			return false;
		}
	}
	
	/**
	 * Method to save and verify a member's detail as being correct.
	 *
	 * @param string $key
	 *        	The name of the primary key of the URL variable.
	 * @param string $urlVar
	 *        	The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *        	
	 * @return boolean True if successful, false otherwise.
	 *        
	 * @since 12.2
	 */
	public function saveandverify($key = null, $urlVar = null) {
		error_log ( "In member.saveandverify" );
		if ($this->save ( $key, $urlVar )) {
			$return = $this->verify ( $key, $urlVar );
			
			if ($return == true) {
				$this->setMessage ( JText::_ ( 'Member successfully saved and verified.' ) );
			}
			return $return;
		} else
			return false;
	}

    public function notify($key = null, $urlVar = null) {

        $model = $this->getModel ();

        $jinput = JFactory::getApplication ()->input;
		$member_id = $jinput->get ( 'id', null, 'INT' );

		error_log ( "In member.notify;  member_id is $member_id" );

        $return = $model->notifyPeopleOfNewMember($member_id);

        if ($return == true) {
				$this->setMessage ( JText::_ ( 'Notifications sent.' ) );
		}

        $this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=newmembers', false ) );

        return $return;
	}
	
	/**
	 * Method to add an attachment to a member's record.
	 *
	 * @param string $key
	 *        	The name of the primary key of the URL variable.
	 * @param string $urlVar
	 *        	The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *        	
	 * @return boolean True if successful, false otherwise.
	 *        
	 * @since 12.2
	 */
	public function addattachment($key = null, $urlVar = null) {
		$model = $this->getModel ();
		$table = $model->getTable ();
		$memberId = ( int ) $this->input->get->get ( 'id' );
		
		$jinput = JFactory::getApplication ()->input;
		$token = $jinput->get ( 'token', null, 'ALNUM' );
		$token_text = "";
		
		if (isset ( $token )) {
			$token_text = "&token=" . $token;
		}
		
		error_log ( "member.addattachment function called with id = " . $memberId );
		
		$file = JRequest::getVar ( 'jform', array (), 'files', 'array' );
		$form = JRequest::getVar ( 'jform', array () );
		
		if ($file ['error'] ['a_file'] != 0) {
			error_log ( "member.addattachment - no file provided" );
		}
		
		/*
		 * error_log ("Input Descr: " . $form['a_description']);
		 *
		 * error_log ("Name: " . $file['name']['a_file']);
		 * error_log ("Name: " . $file['type']['a_file']);
		 * error_log ("Name: " . $file['tmp_name']['a_file']);
		 */
		
		$this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=member&layout=edit&id=' . $memberId . $token_text, false ) );
		
		// Access check.
		if ($this->allowEdit ( array (
				'id' => $memberId 
		), 'id' )) {
			
			$return = $model->addAttachment ( $memberId, $file ['name'] ['a_file'], $file ['type'] ['a_file'], $form ['a_description'], $file ['tmp_name'] ['a_file'] );
			
			if ($return == true) {
				$this->setMessage ( JText::_ ( 'Attachment successfully added.' ) );
			}
			
			return $return;
		} else {
			$this->setError ( JText::_ ( 'JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED' ) );
			$this->setMessage ( $this->getError (), 'error' );
			return false;
		}
	}
	
			
	public function createinvoice($key = null, $urlVar = null) {

        error_log ( "member.createinvoice function called" );
		$jinput = JFactory::getApplication ()->input;	
	    $memberId = $jinput->post->get ( 'member-id', null, 'int' );
	    
	    error_log("In createinvoice - jinput = " . json_encode((array) $jinput));

        $model = $this->getModel ( 'Invoice', 'MemberDatabaseModel', array () );
	
	    $this->setRedirect ( $_SERVER['HTTP_REFERER'] . '&tab=invoices' );
	
	    $return = $model->addInvoiceForMember($memberId);
	    
	    $errors = $model->getErrors();

        if (count($errors) > 0) {
            $this->setMessage ( $errors[0], 'error' );
        } else {
            $this->setMessage ( "Invoice created!", 'success' );
        }
        
        return $return;
        
	}
	
	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param JModelLegacy $model
	 *        	The data model object.
	 * @param array $validData
	 *        	The validated data.
	 *        	
	 * @return void
	 *
	 * @since 12.2
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array()) {
		//error_log ( "validData = " . json_encode ( $validData ) );
		
		$jinput = JFactory::getApplication ()->input;
		
		//error_log("Member - Controller - In postSaveHook");

        ActionlogsHelper::logAction($validData['id'], $validData['surname'] . ", " . $validData['forenames'], "member", "saved");
		
		// If the user has created an new member from the create invoice screen, then send them back there after the new member has been saved.
		if ($jinput->get ( 'list_view', "", 'STRING' ) == "invoice") {
			
			$this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=invoice&layout=create&towerId=' . $validData ['tower_id'], false ) );
		}
	}
}
