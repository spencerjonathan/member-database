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
}
