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
class MemberDatabaseModelAnnualreport extends JModelList {
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
		parent::__construct ( $config );
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return string An SQL query
	 */
	
	
	public function getTowersByInsuranceGroup() {
		
		// Initialize variables.
		$db = JFactory::getDbo ();
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		$query_from_addition = "";
		$query_where_addition = "";
		
		if (! JFactory::getUser ()->authorise ( 'member.view', 'com_memberdatabase' )) {
			$query_from_addition = ", #__md_usertower ut";
			$query_where_addition = " and ut.tower_id = t.id and ut.user_id = " . $userid;
		}
		
		$query_string = '
		district_id, tower,
		count(*) as total,
		sum(under16) as under16,
		sum(over16) as over16,
		sum(over70) as over70,
		sum(unspecified) as unspecified
		from(
				select district_id, concat_ws(\', \', t.place, t.designation) as tower,
					case(insurance_group) when \'Under 16\' then 1 else 0 END as under16,
					case(insurance_group) when \'16-70\' then 1 else 0 END as over16,
					case(insurance_group) when \'Over 70\' then 1 else 0 END as over70,
					case(insurance_group) when null then 1 else 0 END as unspecified
				from #__md_tower t, #__md_member m ' . $query_from_addition . 
				' where t.id = m.tower_id ' . $query_where_addition . ') as data
				GROUP BY district_id, tower
				order by district_id, tower';
		
		error_log("Insurance Group Query String = " . $query_string);
		// Create the base select statement.
		$query->select ( $query_string );

		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		return $results;
	}
	public function getInsuranceGroups() {
		
		// Initialize variables.
		$db = JFactory::getDbo ();
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		// Create the base select statement.
		$query->select ( 'id, name' );
		$query->from ( $db->quoteName ( '#__md_insurance_group', 'i' ) );
		
		$query->order ( 'id asc' );
		
		$db->setQuery ( $query );
		
		$results = $db->loadAssocList ( id, name );
		
		return $results;
	}
	public function getDistricts() {
		// Initialize variables.
		$db = JFactory::getDbo ();
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		// Create the base select statement.
		$query->select ( 'd.*' )->from ( $db->quoteName ( '#__md_district', 'd' ) )->order ( 'id asc' );
		
		$db->setQuery ( $query );
		
		$results = $db->loadAssocList ( 'id', 'name' );
		
		return $results;
	}
	public function getTowers() {
		// Initialize variables.
		$db = JFactory::getDbo ();
		
		$db->setQuery ( $this->getTowersQuery($db) );
		
		$results = $db->loadObjectList ();
		
		return $results;
	}
	
	public function getTowersAssocArray() {
		// Initialize variables.
		$db = JFactory::getDbo ();
		
		$db->setQuery ( $this->getTowersQuery($db) );
		
		$results = $db->loadAssocList('id', 'name');
		
		return $results;
	}
	
	public function getTowerDetails() {
		$db = JFactory::getDbo ();
		
		$query = $this->getTowersQuery($db);
		QueryHelper::addOnlineCorrespondentsExclusion($db, $query);
		
		$db->setQuery ( $query );
		$results = $db->loadObjectList ();
		
		return $results;
	}
	
	private function getTowersQuery($db) {
		// Initialize variables.
		
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		// Create the base select statement.
		$query->select ( 't.*, cor.title as corresp_title, cor.surname as corresp_surname, cor.forenames as corresp_forenames, cor.telephone as corresp_telephone, cor.email as corresp_email, cap.title as captain_title, cap.surname as captain_surname, cap.forenames as captain_forenames, cap.telephone as captain_telephone, concat_ws(\', \', place, designation) as name' );
		$query->from ( $db->quoteName ( '#__md_tower', 't' ) );
		$query->join ( 'LEFT', $db->quoteName ( '#__md_member', 'cor' ) . ' ON (' . $db->quoteName ( 't.correspondent_id' ) . ' = ' . $db->quoteName ( 'cor.id' ) . ')' );
		$query->join ( 'LEFT', $db->quoteName ( '#__md_member', 'cap' ) . ' ON (' . $db->quoteName ( 't.captain_id' ) . ' = ' . $db->quoteName ( 'cap.id' ) . ')' );
		$query->where ('t.active = 1');
		
		QueryHelper::addDistrictJoin($db, $query);
		QueryHelper::addDataPermissionConstraints($db, $query);
		
		$query->order ( 'district_id, place, designation asc' );
		
		return $query;
	}
	

}
