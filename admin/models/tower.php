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
 * HelloWorld Model
 *
 * @since  0.0.1
 */
class MemberDatabaseModelTower extends JModelAdmin
{
	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Tower', $prefix = 'MemberDatabaseTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
 
	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed    A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm(
			'com_memberdatabase.tower',
			'tower',
			array(
				'control' => 'jform',
				'load_data' => $loadData
			)
		);
 
		if (empty($form))
		{
			return false;
		}
 
		return $form;
	}
 
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState(
			'com_memberdatabase.edit.tower.data',
			array()
		);
 
		if (empty($data))
		{
			$data = $this->getItem();
		}
 
		return $data;
	}
	
	public function getHistory($towerId) {
		$db = JFactory::getDbo ();
		$userId = JFactory::getUser ()->id;
		
		$query = $db->getQuery ( true );
		
		
		$query_string =
		"(select null as history_id, #__md_tower.*
		from #__md_tower
		where id = $towerId
		UNION ALL
		select #__md_tower_history.*
		from #__md_tower_history
		where id = $towerId
		order by mod_date DESC) th";
		
		$query->select('th.*, d.name as district, concat_ws(", ", capt.title, capt.forenames, capt.surname) as captain, concat_ws(", ", corresp.title, corresp.forenames, corresp.surname) as correspondent, u.name as mod_user');
		$query->from($query_string);
		$query->join('LEFT', $db->quoteName ( '#__md_member', 'capt' ) . 'ON (th.captain_id = capt.id)' );
		$query->join('LEFT', $db->quoteName ( '#__md_member', 'corresp' ) . 'ON (th.correspondent_id = corresp.id)' );
		$query->join('LEFT', $db->quoteName ( '#__md_district', 'd' ) . 'ON (th.district_id = d.id)' );
		$query->join('LEFT', $db->quoteName ( '#__users', 'u' ) . 'ON (th.mod_user_id = u.id)' );
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		return $results;
	}
}
