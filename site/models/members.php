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
					'verified_date' 
			);
		}
		
		parent::__construct ( $config );
	}
	
	public function getMembersSubs() {
		$db = JFactory::getDbo ();
		$table = $this->getMembersSubsQuery($db)->__toString();
		$pivotColumn = "member_type"; 
		$groupByColumns = array("district", "tower");
		$aggregationColumns = array("fee" => "sum", "tower" => "count");
		
		$pivot_results = $this->pivot($table, $pivotColumn, $groupByColumns, $aggregationColumns);
		
		return $pivot_results;
	}
	
	private function getMembersSubsQuery($db) {
		
		$query = $this->getBaseQuery($db);
		
		$query->select('mt.name as member_type, mt.fee, t.district_id, d.name as district');
		$query->join('INNER', $db->quoteName ( '#__md_member_type', 'mt' ) . ' ON (' . $db->quoteName ( 'm.member_type_id' ) . ' = ' . $db->quoteName ( 'mt.id' ) . ')');
		$query->join('INNER', $db->quoteName ( '#__md_district', 'd' ) . ' ON (' . $db->quoteName ( 't.district_id' ) . ' = ' . $db->quoteName ( 'd.id' ) . ')');
		$query->order ( 't.district_id, t.place asc' );
		
		return $query;
	}
	
	private function getBaseQuery($db) {
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		$verifiedSubQuery = '(SELECT member_id, max(verified_date) as `verified_date` FROM `#__md_member_verified` group by member_id) v';
		
		// Create the base select statement.
		$query->select ( 'm.*, concat_ws(\', \',place, designation) as tower, concat_ws(\', \',surname, forenames) as name, v.verified_date' );
		$query->from ( $db->quoteName ( '#__md_member', 'm' ) );
		$query->join ( 'LEFT', $db->quoteName ( '#__md_tower', 't' ) . ' ON (' . $db->quoteName ( 'm.tower_id' ) . ' = ' . $db->quoteName ( 't.id' ) . ')' );
		$query->join ( 'LEFT', $verifiedSubQuery . ' ON (' . $db->quoteName ( 'm.id' ) . ' = ' . $db->quoteName ( 'v.member_id' ) . ')' );
		
		if (! JFactory::getUser ()->authorise ( 'member.view', 'com_memberdatabase' )) {
			$query->join('INNER', $db->quoteName('#__md_usertower', 'ut') . ' ON (' . $db->quoteName('m.tower_id') . ' = ' . $db->quoteName('ut.tower_id') . ')');
			$query->where ( 'ut.user_id = ' . $userid );
		}
		
		return $query;
		
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return string An SQL query
	 */
	protected function getListQuery() {
		// Initialize variables.
		$db = JFactory::getDbo ();
		$query = $this->getBaseQuery($db);
		
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
	
	private function pivot($table, $pivotColumn, $groupByColumns, $aggregationColumns) {
		$db = JFactory::getDbo ();
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		$query->select("distinct " . $pivotColumn);
		$query->from("( $table ) as `derivedtable`");
		
		$db->setQuery($query);
		$columns = $db->loadColumn();
		
		$sub_query_str = "select " . join(", ", $groupByColumns);
		
		$column_clauses = array();
		$sub_column_clauses = array();
		$column_names = array();
		
		foreach ($columns as $column) {
			foreach ($aggregationColumns as $field => $aggr_funct) {
				$aggr = "";
				if ($aggr_funct == "sum") {
					$val_to_aggr = "`$field`";
				} else if ($aggr_funct == "count") {
					$val_to_aggr = 1;
				}
				
				$column_name = "$aggr_funct" . "_" . "$column";
				array_push($column_names, $column_name);
				$sub_column_clause = "case($pivotColumn) when '$column' then " . $val_to_aggr . " else 0 END as `$column_name`";
				array_push($sub_column_clauses, $sub_column_clause);
				$column_clause = "sum(`$column_name`) as `$column_name`";
				array_push($column_clauses, $column_clause);
			}
		}
		
		$sub_query_str = $sub_query_str . ", " . join(", ", $sub_column_clauses);
		
		$sub_query_str = "( " . $sub_query_str . " from (" . $table . ") as subquery )";
		
		$query_str = join(", ", $groupByColumns) . ", " . join(", ", $column_clauses);
		$query_str = $query_str . " from " . $sub_query_str . " as `derivedtable`";
		$query_str = $query_str . " group by " . join(", ", $groupByColumns);
		
		error_log("Generated pivot query = " . $query_str);
		
		$query = $db->getQuery ( true );
		
		$query->select($query_str);	
		
		$db->setQuery ( $query );
		$results = $db->loadAssocList ();
		
		$return_value = array(
				"resultset" => $results,
				"pivotcolumns" => $column_names,
				"groupbycolumns" => $groupByColumns
		);
		
		//var_dump($return_value);
		error_log("Result Set from Pivot: " . json_encode($return_value));
		
		return $return_value;
		
		return $query_str;
	}
	
}
