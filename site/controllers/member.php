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
 * HelloWorld Controller
 *
 * @package Joomla.Administrator
 * @subpackage com_memberdatabase
 * @since 0.0.9
 */
class MemberDatabaseControllerMember extends JControllerForm {
	protected function allowEdit($data = array(), $key = 'id') {
		$userId = JFactory::getUser ()->id;
		$memberId = $data ['id'];
		
		error_log ( "value of \$data in allowEdit:" . json_encode ( $data ), 0 );
		error_log ( "value of \$user in allowEdit:" . json_encode ( JFactory::getUser () ), 0 );
		
		if (JFactory::getUser ()->authorise ( 'core.manage', 'com_memberdatabase' )) {
			return true;
		}
		
		$db = JFactory::getDbo ();
		
		// Build the database query to get the rules for the asset.
		$query = $db->getQuery ( true )->select ( 'count(*)' )->from ( $db->quoteName ( '#__md_usertower', 'ut' ) )->join ( 'INNER', $db->quoteName ( '#__md_member', 'm' ) . ' ON (' . $db->quoteName ( 'm.tower_id' ) . ' = ' . $db->quoteName ( 'ut.tower_id' ) . ')' )->where ( 'ut.user_id = ' . ( int ) $userId . ' and m.id = ' . ( int ) $memberId );
		
		error_log ( "value of \$query in allowEdit:" . json_encode ( $query ), 0 );
		// Execute the query and load the rules from the result.
		$db->setQuery ( $query );
		$result = $db->loadResult ();
		
		if ($result == 1) {
			return true;
		}
		;
		
		error_log ( "User with id " . $userId . " does not have authorisation to modify member with id " . $memberId, 0 );
		return false;
	}
	
	protected function allowSave($data = array(), $key = 'id') {
		return true;
	}
	protected function allowAdd($data = array(), $key = 'id') {
		return true;
	}
	
	/**
	 * Method to verify a member's detail as being correct.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   12.2
	 */
	public function verify($key = null, $urlVar = null)
	{
		$model = $this->getModel();
		$table = $model->getTable();
		$memberId = $this->input->get->get('id');
		
		error_log("member.verify function called with id = " . $memberId);
		
		// Determine the name of the primary key for the data.
		if (empty($key))
		{
			$key = $table->getKeyName();
		}
		
		// To avoid data collisions the urlVar may be different from the primary key.
		if (empty($urlVar))
		{
			$urlVar = $key;
		}
		
		error_log("member.verify controller has determined that key is " . $key . ".  About to call allowEdit...");
		
		$this->setRedirect(
				JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_list
						. $this->getRedirectToListAppend(), false
						)
				);
		
		// Access check.
		if (!$this->allowEdit(array($key => $memberId), $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');
			
			/* $this->setRedirect(
					JRoute::_(
							'index.php?option=' . $this->option . '&view=' . $this->view_list
							. $this->getRedirectToListAppend(), false
							)
					);
		 */	
			return false;
		}
		
		if ($model->markAsVerified ( $memberId )) {
			$this->setMessage(JText::_('Member successfully verified.'));
			return true;
		} else {
			$this->setError(JText::_('Could not verify member with id: ' . $memberId ));
			$this->setMessage($this->getError(), 'error');
			
			return false;
		}
		
	}
	
	
	/**
	 * Method to save and verify a member's detail as being correct.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   12.2
	 */
	public function saveandverify($key = null, $urlVar = null) {
		error_log("In member.saveandverify");
		if ($this->save($key, $urlVar)) {
			$return = $this->verify($key, $urlVar);
			
			if ($return == true) {
				$this->setMessage(JText::_('Member successfully saved and verified.'));
			}
			return $return;
			
		} else return false;
	}
}
