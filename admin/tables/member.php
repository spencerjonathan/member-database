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
		$this->mod_user_id = $this->createModel('member', 'MemberDatabaseModel')->getCurrentUserId();
		
		//$this->mod_user_id = JFactory::getUser ()->id;
		$this->mod_date = $currentDate = date('Y-m-d H:i:s');
		
		return parent::store($updateNulls);
	}
	
	public function load($keys = null, $reset = true) {
		$result = parent::load($keys, $reset);
		
		// A bit mucky - but here we override the mod_user_id with the name or email address of the mod_user
		$this->mod_user_id = $this->getModUser($this->mod_user_id);
		
		return $result;
	}
	
	protected function createModel($name, $prefix = '', $config = array()) {
		// Clean the model name
		$modelName = preg_replace ( '/[^A-Z0-9_]/i', '', $name );
		$classPrefix = preg_replace ( '/[^A-Z0-9_]/i', '', $prefix );
		
		return JModelLegacy::getInstance ( $modelName, $classPrefix, $config );
	}
	
	private function getModUser($mod_user_id) {
		error_log("In getModUser with mod_user_id = " . $mod_user_id);
		
		$db = JFactory::getDbo ();
		
		$query = $db->getQuery(true);
		
		if ($mod_user_id > 0) {
			$query->select('concat("User: ", name, " (", username, ")") as name')
			->from("#__users u")
			->where("u.id = " . (int) $mod_user_id);
		} else {
			$query->select('concat("Member: ", email) as name')
			->from("#__md_member_token memt")
			->where("memt.id = " . (int) $mod_user_id * -1);
		}
		
		$db->setQuery ( $query );
		$name = $db->loadResult ();
		
		return $name;
	}
	
}
?>