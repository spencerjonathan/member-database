<?php
abstract class QueryHelper {
	public function addDataPermissionConstraints($db, $query) {
		$jinput = JFactory::getApplication ()->input;
		$token = $jinput->get ( 'token', null, 'STRING' );
		
		if (isset ( $token )) {
			$currentDate = date ( 'Y-m-d H:i:s' );
			//$hashedToken = JUserHelper::hashPassword($token);
			error_log("Token received = $token");
			//error_log("Hashed Token is received = $hashedToken");
			
			$query->join ( 'INNER', $db->quoteName ( '#__md_member_token', 'memt' ) . ' ON (' . $db->quoteName ( 'memt.email' ) . ' = ' . $db->quoteName ( 'm.email' ) . ') ' );
			$query->where ( 'memt.expiry_date >= ' . $db->quote($currentDate) );
			$query->where ( 'memt.hash_token = ' . $db->quote($token) );
			
		} else {
			$userid = JFactory::getUser ()->id;
			
			if (! JFactory::getUser ()->authorise ( 'member.view', 'com_memberdatabase' )) {
				$query->join ( 'LEFT', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (' . $db->quoteName ( 't.id' ) . ' = ' . $db->quoteName ( 'ut.tower_id' ) . " and ut.user_id = $userid)" );
				$query->join ( 'LEFT', $db->quoteName ( '#__md_userdistrict', 'ud' ) . ' ON (' . $db->quoteName ( 'd.id' ) . ' = ' . $db->quoteName ( 'ud.district_id' ) . " and ud.user_id = $userid)" );
				$query->where ( '(ut.user_id is not null or ud.user_id is not null)' );
			}	
		}
		
		return $query;
	}
	public function addMemberTypeJoin($db, $query) {
		$query->select ( 'mt.name as member_type, mt.fee' );
		$query->join ( 'INNER', $db->quoteName ( '#__md_member_type', 'mt' ) . ' ON (' . $db->quoteName ( 'm.member_type_id' ) . ' = ' . $db->quoteName ( 'mt.id' ) . ')' );
		
		return $query;
	}
	public function addDistrictJoin($db, $query) {
		$query->select ( 't.district_id, d.name as district' );
		$query->join ( 'INNER', $db->quoteName ( '#__md_district', 'd' ) . ' ON (' . $db->quoteName ( 't.district_id' ) . ' = ' . $db->quoteName ( 'd.id' ) . ')' );
		
		return $query;
	}
	public function pivot($table, $pivotColumn, $groupByColumns, $aggregationColumns) {
		$db = JFactory::getDbo ();
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		$query->select ( "distinct " . $pivotColumn );
		$query->from ( "( $table ) as `derivedtable`" );
		
		$db->setQuery ( $query );
		$columns = $db->loadColumn ();
		
		$sub_query_str = "select " . join ( ", ", $groupByColumns );
		
		$column_clauses = array ();
		$sub_column_clauses = array ();
		$column_names = array ();
		
		foreach ( $columns as $column ) {
			foreach ( $aggregationColumns as $field => $aggr_funct ) {
				$aggr = "";
				if ($aggr_funct == "sum") {
					$val_to_aggr = "`$field`";
				} else if ($aggr_funct == "count") {
					$val_to_aggr = 1;
				}
				
				$column_name = "$aggr_funct" . "_" . "$column";
				array_push ( $column_names, $column_name );
				$sub_column_clause = "case($pivotColumn) when '$column' then " . $val_to_aggr . " else 0 END as `$column_name`";
				array_push ( $sub_column_clauses, $sub_column_clause );
				$column_clause = "sum(`$column_name`) as `$column_name`";
				array_push ( $column_clauses, $column_clause );
			}
		}
		
		$sub_query_str = $sub_query_str . ", " . join ( ", ", $sub_column_clauses );
		
		$sub_query_str = "( " . $sub_query_str . " from (" . $table . ") as subquery )";
		
		$query_str = join ( ", ", $groupByColumns ) . ", " . join ( ", ", $column_clauses );
		$query_str = $query_str . " from " . $sub_query_str . " as `derivedtable`";
		$query_str = $query_str . " group by " . join ( ", ", $groupByColumns );
		
		error_log ( "Generated pivot query = " . $query_str );
		
		$query = $db->getQuery ( true );
		
		$query->select ( $query_str );
		
		$db->setQuery ( $query );
		$results = $db->loadAssocList ();
		
		$return_value = array (
				"resultset" => $results,
				"pivotcolumns" => $column_names,
				"groupbycolumns" => $groupByColumns 
		);
		
		// var_dump($return_value);
		error_log ( "Result Set from Pivot: " . json_encode ( $return_value ) );
		
		return $return_value;
		
		return $query_str;
	}
}

?>