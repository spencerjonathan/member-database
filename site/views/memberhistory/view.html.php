<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/* if(!class_exists('JToolbarHelper')) {
   require_once JPATH_ADMINISTRATOR . '/includes/toolbar.php';
} */

JFactory::getDocument()->addStyleDeclaration("
	td, th {
		white-space: nowrap;
	}
");


 
/**
 * MembersDatabase View
 *
 * @since  0.0.1
 */
class MemberDatabaseViewMemberHistory extends JViewLegacy
{
	
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
		
		$model = $this->createModel("Member", "MemberDatabaseModel");
		$this->setModel($model, false);
	}
	
	/**
	 * Display the Members view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{

		/* // Get application
		$app = JFactory::getApplication();
		$context = "memberdatabase.list.admin.memberhistory"; */

		$jinput = JFactory::getApplication ()->input;
		$this->memberId = $jinput->get ( 'memberId', 0, 'INT' );
	
		error_log("Displaying info for memberId: " . $memberId);
		// Get data from the model
		$this->history = $this->getModel('Member')->getHistory($this->memberId);
		/* $this->pagination	= $this->get('Pagination'); */
 
		/* $this->state		= $this->get('State'); */

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
 
			return false;
		}

		// Set the toolbar
		/* $this->addToolBar(); */
 
		// Display the template
		parent::display($tpl);

		// Set the document
		//$this->setDocument();
	}
	
	/**
	 * Method to load and return a model object.
	 *
	 * @param   string  $name    The name of the model.
	 * @param   string  $prefix  Optional model prefix.
	 * @param   array   $config  Configuration array for the model. Optional.
	 *
	 * @return  JModelLegacy|boolean   Model object on success; otherwise false on failure.
	 *
	 * @since   12.2
	 */
	protected function createModel($name, $prefix = '', $config = array())
	{
		// Clean the model name
		$modelName = preg_replace('/[^A-Z0-9_]/i', '', $name);
		$classPrefix = preg_replace('/[^A-Z0-9_]/i', '', $prefix);
		
		return JModelLegacy::getInstance($modelName, $classPrefix, $config);
	}

}
