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
 * Tower Table class
 *
 * @since  0.0.1
 */
class MemberDatabaseTableNewMemberProposer extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__md_new_member_proposer', 'id', $db);
	}
	
	public function store($updateNulls = false)
	{
	    $this->mod_date = $currentDate = date('Y-m-d H:i:s');
	    
	    return parent::store($updateNulls);
	}
}
