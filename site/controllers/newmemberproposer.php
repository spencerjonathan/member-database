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
class MemberDatabaseControllerNewMemberProposer extends JControllerForm {
	
	public function edit($key = null, $urlVar = null) {
		
		return true;
	}

	protected function allowEdit($data = array(), $key = 'id') {
		return true;
	}

	protected function allowSave($data = array(), $key = 'id') {
		$db_locked = JComponentHelper::getParams ( 'com_memberdatabase' )->get ( 'db_locked' );
		
		error_log ( "tower.allowSave: db_locked is " . $db_locked );
		
		if ($db_locked == true) {
			$this->setError ( 'Proposing new members is currently not permitted because the database is currently locked.' );
			$this->setMessage ( $this->getError (), 'error' );
			return false;
		}
		
		return $this->allowEdit($data, $key);
	}

	public function save($key = null, $urlVar = null) {
	    // Check for request forgeries.
	    $this->checkToken('request');
	    
		$saveResult = parent::save($key, $urlVar);

		if (!$saveResult) return $saveResult;
		
		$this->getModel()->promoteToMember();

		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=newmemberproposer&layout=saved', false));

		return true;
	}
	
}
