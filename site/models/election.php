<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//JLoader::import('ElectionsHelper', JPATH_ADMINISTRATOR . '/path/to/test.php');

/**
 * Users mail model.
 *
 * @since  1.6
 */
class MemberDatabaseModelElection extends JModelAdmin
{
	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm	A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_memberdatabase.election', 'election', array('control' => 'jform', 'load_data' => $loadData));

		error_log("In Election getForm");

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	public function getTable($type = 'Election', $prefix = 'MemberDatabaseTable', $config = array()) {
		//JTable::addIncludePath ( JPATH_ADMINISTRATOR . '/components/com_memberdatabase/tables' );
		return JTable::getInstance ( $type, $prefix, $config );
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

        $id = (int) $this->getState($this->getName() . '.id');
        error_log("loadForm - id = " . $id);

        if ($id > 0) {
    		$data = $this->getItem ();
        } else {
            $data = JFactory::getApplication()->getUserState('com_memberdatabase.display.election.data', array());
        }

        error_log("Election loadFormData: " . json_encode((array) $data));

		return $data;
	}

	/**
	 * Method to preprocess the form
	 *
	 * @param   JForm   $form   A form object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @since   1.6
	 * @throws  Exception if there is an error loading the form.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'user')
	{
		parent::preprocessForm($form, $data, $group);
	}

}
