<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * HTML View class for the MemberDatabase Component
 *
 * @since  0.0.1
 */
class MemberDatabaseViewMemberDatabase extends JViewLegacy
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
		
		$model = $this->createModel("Invoices", "MemberDatabaseModel");
		
		error_log(serialize($model));
		
		$this->setModel($model, false);
	}
	
	
	/**
	 * Display the MemberDatabase view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{
		$document = JFactory::getDocument ();
		$document->addStyleSheet ( './components/com_memberdatabase/css/bootstrap.min.css' );
		
		jimport('joomla.application.component.helper');
		$this->verification_required_since = JComponentHelper::getParams('com_memberdatabase')->get('verification_required_since');
		if (!$this->verification_required_since) {
			error_log("verification_required_since global configuration option is not set for the MemberDatabase.");
		}
		
		$date = DateTime::createFromFormat("Y-m-d", $this->verification_required_since);
		$this->year = $date->format("Y");
		
		// Assign data to the view
		$this->status = $this->get('Status');
		
		// Get Invoice list from Invoices Model
		$this->invoices = $this->get('Invoices', 'Invoices');
		
		parent::display($tpl);
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
