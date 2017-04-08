<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * MemberDatabaseList Model
 *
 * @since  0.0.1
 */
class MemberDatabaseModelAnnualreport extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */

	public function getMembers()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$userid = JFactory::getUser()->id;
		$query = $db->getQuery(true);
 
		// Create the base select statement.
		$query->select('m.tower_id, concat_ws(\', \',surname, forenames) as name')
                ->from($db->quoteName('#__md_member', 'm'))
		->join('INNER', $db->quoteName('#__md_usertower', 'ut') . ' ON (' . $db->quoteName('m.tower_id') . ' = ' . $db->quoteName('ut.tower_id') . ')')
		->where('ut.user_id = ' . $userid)
		->order('surname, forenames asc');

		$db->setQuery($query);

		$results = $db->loadObjectList();

		return $results;
	}

	public function getDistricts()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$userid = JFactory::getUser()->id;
		$query = $db->getQuery(true);
 
		// Create the base select statement.
		$query->select('d.*')
                ->from($db->quoteName('#__md_district', 'd'))
		->order('id asc');

		$db->setQuery($query);

		$results = $db->loadAssocList('id', 'name');

		return $results;
	}

	public function getTowers()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$userid = JFactory::getUser()->id;
		$query = $db->getQuery(true);
 
		// Create the base select statement.
		$query->select('t.*, cor.title as corresp_title, cor.surname as corresp_surname, cor.forenames as corresp_forenames, cor.telephone as corresp_telephone, cap.title as captain_title, cap.surname as captain_surname, cap.forenames as captain_forenames, cap.telephone as captain_telephone') 
                ->from($db->quoteName('#__md_tower', 't'))
		->join('INNER', $db->quoteName('#__md_usertower', 'ut') . ' ON (' . $db->quoteName('t.id') . ' = ' . $db->quoteName('ut.tower_id') . ')')
		->join('LEFT', $db->quoteName('#__md_member', 'cor') . ' ON (' . $db->quoteName('t.correspondent_id') . ' = ' . $db->quoteName('cor.id') . ')')
		->join('LEFT', $db->quoteName('#__md_member', 'cap') . ' ON (' . $db->quoteName('t.captain_id') . ' = ' . $db->quoteName('cap.id') . ')')
		->where('ut.user_id = ' . $userid)
		->order('district_id, place, designation asc');

		$db->setQuery($query);

		$results = $db->loadObjectList();

		return $results;
	}
}