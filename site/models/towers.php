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

JLoader::import ( 'QueryHelper', JPATH_COMPONENT . "/helpers/" );

/**
 * MemberDatabaseList Model
 *
 * @since 0.0.1
 */
class MemberDatabaseModelTowers extends JModelList {
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
					'place',
					'designation',
					'bells' 
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
		$query = $db->getQuery ( true );
		
		// Create the base select statement.
		$query->select ( 't.*' )->from ( $db->quoteName ( '#__md_tower', 't' ) );
		
		$query = QueryHelper::addDistrictJoin ( $db, $query );
		
		// Filter: like / search
		$search = $this->getState ( 'filter.search' );
		
		if (! empty ( $search )) {
			$like = $db->quote ( '%' . $search . '%' );
			$query->where ( 'place LIKE ' . $like );
		}
		
		$query = QueryHelper::addDataPermissionConstraints ( $db, $query );
		
		// Add the list ordering clause.
		$orderCol = $this->state->get ( 'list.ordering', 'place' );
		$orderDirn = $this->state->get ( 'list.direction', 'asc' );
		
		$query->order ( $db->escape ( $orderCol ) . ' ' . $db->escape ( $orderDirn ) );
		
		return $query;
	}
	public function getTowers() {
		$db = JFactory::getDbo ();
		$userId = JFactory::getUser ()->id;
		
		$query = $db->getQuery ( true );
		
		$query->select ( 't.id, concat_ws(\', \', t.place, t.designation) as tower' )->from ( $db->quoteName ( '#__md_tower', 't' ) );
		
		if (! JFactory::getUser ()->authorise ( 'member.edit', 'com_memberdatabase' )) {
			$query->join ( 'INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (t.id = ut.tower_id)' );
			$query->where ( 'ut.user_id = ' . ( int ) $userId );
		}
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		return $results;
	}

    public function getTowerEmailAssoc() {
        $db = JFactory::getDbo ();
		
		$query = $db->getQuery ( true );
		
		$query->select ( 't.id, case when t.corresp_email is null or trim(t.corresp_email) = "" then trim(ifnull(c.email, "")) else trim(t.corresp_email) end as email' );
        $query->from ( $db->quoteName ( '#__md_tower', 't' ) );
        $query->join("LEFT", $db->quoteName ( '#__md_member', 'c' ) . " on c.id = t.correspondent_id");

        $db->setQuery ( $query );

        return $db->loadAssocList('id', 'email');
    }
}
