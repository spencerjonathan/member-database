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

//jimport ('joomla.filesystem.file');

//JLoader::import('QueryHelper', JPATH_COMPONENT . "/helpers/");

/**
 * Newmember Model
 *
 * @since 0.0.1
 */
class MemberDatabaseModelNewmember extends JModelAdmin {
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

	public function getTable($type = 'Newmember', $prefix = 'MemberDatabaseTable', $config = array()) {
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
		
		$jinput = JFactory::getApplication ()->input;
		//$token = $jinput->get ( 'token', null, 'CMD' );
		$stage = $jinput->get ( 'stage', "initial", 'ALNUM' );
		//$id = $jinput->get ( 'id', null, 'INT' );
		
		$form_name = 'com_memberdatabase.newmember_' . $stage;
		$form_file = 'newmember_' . $stage;
		
		/* if ($id) {
		    $form_name = 'com_memberdatabase.newmember_final';
		    $form_file = 'newmember_final';
		} elseif ($token) {
		    $form_name = 'com_memberdatabase.newmember_main';
		    $form_file = 'newmember_main';
		} else {
		    $form_name = 'com_memberdatabase.newmember_initial';
		    $form_file = 'newmember_initial';
		} */
		
		$form = $this->loadForm ( $form_name, $form_file, array (
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
	 * @since 0.1.288
	 * 
	 */
	
	protected function loadFormData() {
	    // Check the session for previously entered form data.
	    $data = JFactory::getApplication ()->getUserState ( 'com_memberdatabase.edit.newmember.data', array () );
	    
	    if (empty ( $data )) {
	        $jinput = JFactory::getApplication ()->input;
	        $token = $jinput->get ( 'token', null, 'ALNUM' );
	        
	        $pk = $this->getPK($token);
	        
	        $data = $this->getItem ($pk);
	    }
	    
	    return $data;
	}
	
	protected function getPK($token) {
	    $db = JFactory::getDbo ();
	    
	    $query = $db->getQuery ( true )
	    ->select ( 'm.id' )
	    ->from ( $db->quoteName ( '#__md_new_member', 'm' ) )
	    ->join ( 'INNER', $db->quoteName ( '#__md_member_token', 'memt' ) . ' ON (' . $db->quoteName ( 'm.email' ) . ' = ' . $db->quoteName ( 'memt.email' ) . ')' )
	    ->where ( 'memt.hash_token = ' .  $db->quote ( $token ) );
	   
	    $db->setQuery ( $query );
	    return $db->loadResult ();
	}
	
	public function saveProposers($data) {
	    error_log("In newmember::saveProposers().  Data = " . json_encode($data));
	    
	    
	}
	
	public function generateAndSendLink($email) {
	        
	    // Set the confirmation token.
	    $token = JApplicationHelper::getHash(JUserHelper::genRandomPassword());
	    
	    // 2) store the hash and email address
	    if (!EmailHelper::storeToken($email, $token)) {
	        $this->setError(JText::sprintf('Could not store unique token'), 500);
	        return false;
	    }
	    
	    // 3) send the email
	    $link = 'index.php?option=com_memberdatabase&view=newmember&layout=edit&token=' . $token;
	    
	    $config = JFactory::getConfig();
	    $mode = $config->get('force_ssl', 0) == 2 ? 1 : (-1);
	    $link_text = JRoute::_($link, false, $mode);
	    $body = JText::sprintf(
	        'Use this link %s to access your SCACR membership account record',
	        $link_text
	        );
	    
	    $subject = 'Link To Your Membership Record';
	    
	    //$send = EmailHelper::sendEmail($email, $subject, $body);
	    //if ( $send !== true ) {
	    //    $this->setError(JText::sprintf('Could not send email to %s', $email), 500);
	    //    return false;
	    //} else {
	    //    return true;
	    //}
	    
	}
	
	
}
