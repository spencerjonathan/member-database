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
 * HTML View class for the MembershipRenewals Component
 *
 * @since  0.0.1
 */
class MemberDatabaseViewMembershipRenewals extends JViewLegacy
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
		
		$model = $this->createModel("MembershipRenewals", "MemberDatabaseModel");
		$this->setModel($model, false);
	}
	
	
	/**
	 * Display the MembershipRenewals view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{

        if (! JFactory::getUser ()->authorise ( 'core.admin', 'com_memberdatabase' )) {
            JError::raiseError(500, "You are not authorised to perform this action!");

            $app = \JFactory::getApplication();
            $redirect = \JRoute::_(
                        'index.php/login/profile', false
                        );

            // Execute the redirect
            $app->redirect($redirect);    
        } 
		
		jimport('joomla.application.component.helper');
		$this->verification_required_since = JComponentHelper::getParams('com_memberdatabase')->get('verification_required_since');
		if (!$this->verification_required_since) {
			error_log("verification_required_since global configuration option is not set for the MemberDatabase.");
		}
		
		$date = DateTime::createFromFormat("Y-m-d", $this->verification_required_since);
		$this->year = $date->format("Y");

		$this->data = $this->get("TowerData");
		
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
