<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
 
/**
 * District Table class
 *
 * @since  0.0.1
 */
class MemberDatabaseTableDistrict extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__md_district', 'id', $db);
	}
}
