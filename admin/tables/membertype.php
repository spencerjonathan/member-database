<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
 
/**
 * MemberType Table class
 *
 * @since  0.0.1
 */
class MemberDatabaseTableMemberType extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__md_member_type', 'id', $db);
	}
}
