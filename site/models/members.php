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
class MemberDatabaseModelMembers extends JModelList
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
				'tower',
				'name',
				'email'
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
		$userid = JFactory::getUser()->id;
		$query = $db->getQuery(true);
 
		// Create the base select statement.
		$query->select('m.*, concat_ws(\', \',place, designation) as tower, concat_ws(\', \',surname, forenames) as name')
                ->from($db->quoteName('#__md_member', 'm'))
		->join('INNER', $db->quoteName('#__md_usertower', 'ut') . ' ON (' . $db->quoteName('m.tower_id') . ' = ' . $db->quoteName('ut.tower_id') . ')')
		->join('LEFT', $db->quoteName('#__md_tower', 't') . ' ON (' . $db->quoteName('m.tower_id') . ' = ' . $db->quoteName('t.id') . ')')
		->where('ut.user_id = ' . $userid);

		// Filter: like / search
		$search = $this->getState('filter.search');
 
		if (!empty($search))
		{
			$like = $db->quote('%' . $search . '%');
			$query->where('concat_ws(\', \',surname, forenames) LIKE ' . $like);
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'surname, forenames');
		$orderDirn 	= $this->state->get('list.direction', 'asc');
 
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
 
		return $query;
	}
}
