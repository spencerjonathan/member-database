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
class MemberDatabaseModelUserTower extends JModelAdmin
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
	public function getTable($type = 'UserTower', $prefix = 'MemberDatabaseTable', $config = array())
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
			'com_memberdatabase.usertower',
			'usertower',
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
			'com_memberdatabase.edit.usertower.data',
			array()
		);
 
		if (empty($data))
		{
			$data = $this->getItem();
		}
 
		return $data;
	}

        public function getTowers()
        {
                $db = JFactory::getDbo();

                $query = $db->getQuery(true)
                ->select('id, place, designation')
                ->from('#__md_tower');
                //->where('user_id = ' . (int) $userId . ' and tower_id = ' . (int) $towerId);

                $db->setQuery($query);


                $results = $db->loadObjectList();

                return $results;
        }

        public function getusers()
        {
                $db = JFactory::getDbo();

                $query = $db->getQuery(true)
                ->select('id, name, username')
                ->from('#__users');
                //->where('user_id = ' . (int) $userId . ' and tower_id = ' . (int) $towerId);

                $db->setQuery($query);

                $results = $db->loadObjectList();

                return $results;
        }


}
