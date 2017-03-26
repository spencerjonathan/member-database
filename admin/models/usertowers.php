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
class MemberDatabaseModelUserTowers extends JModelList
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
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id',
				'towername',
				'username'
			);
		}
 
		parent::__construct($config);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
 
		// Create the base select statement.
		$query->select('ut.*, u.name, u.username, concat(t.city, \', \', t.designation) as towername')
                ->from($db->quoteName('#__md_usertower', 'ut'))
		->join('INNER', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('ut.user_id') . ' = ' . $db->quoteName('u.id') . ')')
		->join('INNER', $db->quoteName('#__md_tower', 't') . ' ON (' . $db->quoteName('ut.tower_id') . ' = ' . $db->quoteName('t.id') . ')');

		// Filter: like / search
		$search = $this->getState('filter.search');
 
		if (!empty($search))
		{
			$like = $db->quote('%' . $search . '%');
			$query->where('t.city LIKE ' . $like);
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'city');
		$orderDirn 	= $this->state->get('list.direction', 'asc');
 
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
 
		return $query;
	}
}
