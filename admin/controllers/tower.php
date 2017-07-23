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
 * Member Database Controller
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
	
}
