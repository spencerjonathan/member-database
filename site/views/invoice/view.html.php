<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

$document = JFactory::getDocument();
$document->addScript('./media/system/js/core.js');

/**
 * MembersDatabase View
 *
 * @since 0.0.1
 */
class MemberDatabaseViewInvoice extends JViewLegacy {

	protected $form = null;

	/**
	 * Display the Invoice view
	 *
	 * @param string $tpl
	 *        	The name of the template file to parse; automatically searches through the template paths.
	 *        	
	 * @return void
	 */
	
	function display($tpl = null) {
		
		jimport('joomla.application.component.helper');
		$verification_required_since = JComponentHelper::getParams('com_memberdatabase')->get('verification_required_since');
		$date = DateTime::createFromFormat("Y-m-d", $verification_required_since);
		
		$this->year = $date->format("Y");
		
		
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');


		$renewalModel = $this->createModel("MembershipRenewals", "MemberDatabaseModel");		
		$this->towerEmailAssoc = $renewalModel->getTowerEmailAssoc();
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			
			return false;
		}
		
		// Display the template
		parent::display ( $tpl );
		
	}
	
	protected function createModel($name, $prefix = '', $config = array())
	{
		// Clean the model name
		$modelName = preg_replace('/[^A-Z0-9_]/i', '', $name);
		$classPrefix = preg_replace('/[^A-Z0-9_]/i', '', $prefix);
		
		return JModelLegacy::getInstance($modelName, $classPrefix, $config);
	}
}
