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
		sum(over25) as over25,
		sum(over70) as over70,
		sum(over80) as over80,
		sum(unspecified) as unspecified
		from(
				select district_id, concat_ws(\', \', t.place, t.designation) as tower,
					case(insurance_group) when \'Under 16\' then 1 else 0 END as under16,
					case(insurance_group) when \'16-24\' then 1 else 0 END as over16,
					case(insurance_group) when \'25-69\' then 1 else 0 END as over25,
					case(insurance_group) when \'70-79\' then 1 else 0 END as over70,
					case(insurance_group) when \'80 and over\' then 1 else 0 END as over80,
					case when insurance_group is null or insurance_group = "" then 1 else 0 END as unspecified
				from #__md_tower t, #__md_member m, #__md_member_type mt ' . $query_from_addition . 
				' where t.id = m.tower_id and m.member_type_id = mt.id and mt.include_in_reports = 1 ' . $query_where_addition . ') as data
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


	public function getNewMembers($from_date = '2017-01-01') {
		// Initialize variables.
		$db = JFactory::getDbo ();
		
        $query = $db->getQuery( true );

        jimport('joomla.application.component.helper');
        $this->verification_required_since = JComponentHelper::getParams('com_memberdatabase')->get('verification_required_since');
        if (!$this->verification_required_since) {
            error_log("verification_required_since global configuration option is not set for the MemberDatabase.");
        }

        $date = DateTime::createFromFormat("Y-m-d", $this->verification_required_since);
        $year = $date->format("Y") - 1;

        error_log("New Members since $year");

        $from_date = "$year" . "-01-01";

        $query->select("m.id, mt.name as type, forenames, surname, date_elected as member_since, concat_ws(', ', place, designation) as tower, d.name as district") 
            ->from($db->quoteName ( "#__md_member", 'm'))
            ->join('INNER', $db->quoteName('#__md_tower', 't') . "on m.tower_id = t.id")
//            ->join('INNER', $db->quoteName('#__md_district', 'd') . "on t.district_id = d.id")
            ->join('INNER', $db->quoteName('#__md_member_type', 'mt') . "on m.member_type_id = mt.id")
            ->where("m.date_elected >= '$from_date'")
            ->order("d.name, date_elected");

        QueryHelper::addDistrictJoin($db, $query);
		QueryHelper::addDataPermissionConstraints($db, $query);

		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		return $results;
	}
    
	
	public function getTowerDetails($towerid = null) {
		$db = JFactory::getDbo ();
		
		$query = $this->getTowersQuery($db);
		//QueryHelper::addOnlineCorrespondentsExclusion($db, $query);
		
        if (!is_null($towerid)) {
            $query->where("t.id = $towerid");
        }
    
		$db->setQuery ( $query );
		$results = $db->loadObjectList ();
		
		return $results;
	}
	
	private function getTowersQuery($db) {
		// Initialize variables.
		
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		// Create the base select statement.
		$query->select ( 't.*, t.corresp_email as tower_email, cor.title as corresp_title, cor.surname as corresp_surname, cor.forenames as corresp_forenames, cor.telephone as corresp_telephone, cor.email as corresp_email, cap.title as captain_title, cap.surname as captain_surname, cap.forenames as captain_forenames, cap.telephone as captain_telephone, concat_ws(\', \', place, designation) as name' );
		$query->from ( $db->quoteName ( '#__md_tower', 't' ) );
		$query->join ( 'LEFT', $db->quoteName ( '#__md_member', 'cor' ) . ' ON (' . $db->quoteName ( 't.correspondent_id' ) . ' = ' . $db->quoteName ( 'cor.id' ) . ')' );
		$query->join ( 'LEFT', $db->quoteName ( '#__md_member', 'cap' ) . ' ON (' . $db->quoteName ( 't.captain_id' ) . ' = ' . $db->quoteName ( 'cap.id' ) . ')' );
		$query->where ('t.active = 1');
		
		QueryHelper::addDistrictJoin($db, $query);
		QueryHelper::addDataPermissionConstraints($db, $query);
		
		$query->order ( 'district_id, if(place like "Unattached%", 1, 0), place, designation asc' );
		
		return $query;
	}
	

}
