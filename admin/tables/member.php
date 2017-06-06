<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
 
/**
 * Member Table class
 *
 * @since  0.0.1
 */
class MemberDatabaseTableMember extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__md_member', 'id', $db);
	}
	
	public function store($updateNulls = false)
	{
		$this->mod_user_id = JFactory::getUser ()->id;
		$this->mod_date = $currentDate = date('Y-m-d H:i:s');
		
		return parent::store($updateNulls);
	}
}
?>