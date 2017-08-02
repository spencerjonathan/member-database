<?php

abstract class QueryHelper {
	public function addDataPermissionConstraints($db, $query) {
		$userid = JFactory::getUser ()->id;
		
		if (! JFactory::getUser ()->authorise ( 'member.view', 'com_memberdatabase' )) {
			$query->join('LEFT', $db->quoteName('#__md_usertower', 'ut') . ' ON (' . $db->quoteName('m.tower_id') . ' = ' . $db->quoteName('ut.tower_id') . " and ut.user_id = $userid)");
			$query->join('LEFT', $db->quoteName('#__md_userdistrict', 'ud') . ' ON (' . $db->quoteName('t.district_id') . ' = ' . $db->quoteName('ud.district_id') . " and ud.user_id = $userid)");
			$query->where ( '(ut.user_id is not null or ud.user_id is not null)');
		}
		
		return $query;
	}
}

?>