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
 * MemberDatabaseList Model
 *
 * @since 0.0.1
 */
class MemberDatabaseModelMembers extends JModelList {
	/**
	 * Constructor.
	 *
	 * @param array $config
	 *        	An optional associative array of configuration settings.
	 *        	
	 * @see JController
	 * @since 1.6
	 */
	public function __construct($config = array()) {
		if (empty ( $config ['filter_fields'] )) {
			$config ['filter_fields'] = array (
					'id',
					'tower',
					'name',
				/* 'email', */
				'verified_date' 
			);
		}
		
		parent::__construct ( $config );
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return string An SQL query
	 */
	protected function getListQuery() {
		// Initialize variables.
		$db = JFactory::getDbo ();
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		$verifiedSubQuery = '(SELECT member_id, max(verified_date) as `verified_date` FROM `#__md_member_verified` group by member_id) v';
		
		// Create the base select statement.
		$query->select ( 'm.*, concat_ws(\', \',place, designation) as tower, concat_ws(\', \',surname, forenames) as name, v.verified_date' );
		$query->from ( $db->quoteName ( '#__md_member', 'm' ) )->join ( 'LEFT', $db->quoteName ( '#__md_tower', 't' ) . ' ON (' . $db->quoteName ( 'm.tower_id' ) . ' = ' . $db->quoteName ( 't.id' ) . ')' );
		$query->join ( 'LEFT', $verifiedSubQuery . ' ON (' . $db->quoteName ( 'm.id' ) . ' = ' . $db->quoteName ( 'v.member_id' ) . ')' );
		
		if (! JFactory::getUser ()->authorise ( 'core.manage', 'com_memberdatabase' )) {
			$query->join('INNER', $db->quoteName('#__md_usertower', 'ut') . ' ON (' . $db->quoteName('m.tower_id') . ' = ' . $db->quoteName('ut.tower_id') . ')');
			$query->where ( 'ut.user_id = ' . $userid );
		}
		
		// Filter: like / search
		$search = $this->getState ( 'filter.search' );
		
		if (! empty ( $search )) {
			$like = $db->quote ( '%' . $search . '%' );
			$query->where ( 'concat_ws(\', \',surname, forenames, place, designation) LIKE ' . $like );
		}
		
		// Add the list ordering clause.
		error_log("State = " . serialize($this->state));
		
		$orderCol = $this->state->get ( 'list.ordering', 'surname, forenames' );
		$orderDirn = $this->state->get ( 'list.direction', 'asc' );
		
		$query->order ( $db->escape ( $orderCol ) . ' ' . $db->escape ( $orderDirn ) );
		
		return $query;
	}
	
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   The field to order on.
	 * @param   string  $direction  The direction to order on.
	 *
	 * @return  void.
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('site');
		
		$itemid = $app->input->get('id', 0, 'int') . ':' . $app->input->get('Itemid', 0, 'int');
		
		// Optional filter text
		$search = $app->getUserStateFromRequest('com_memberdatabase.member.list.' . $itemid . '.filter-search', 'filter-search', '', 'string');
		$this->setState('list.filter', $search);
		
		// Filter.order
		$orderCol = $app->getUserStateFromRequest('com_memberdatabase.member.list.' . $itemid . '.filter_order', 'filter_order', '', 'string');
		
		$this->setState('list.ordering', $orderCol);
		
		$listOrder = $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
		
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}
		
		$this->setState('list.direction', $listOrder);
		
		parent::populateState($ordering, $direction);
	}
	
}
