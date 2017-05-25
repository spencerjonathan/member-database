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

// JHtmlBootstrap::loadCss();

$document = JFactory::getDocument ();
//$document->addStyleSheet ( './media/jui/css/bootstrap.min.css' );
$document->addStyleSheet ( './components/com_memberdatabase/css/print.css' );

/**
 * MembersDatabase View
 *
 * @since 0.0.1
 */
class MemberDatabaseViewAnnualreport extends JViewLegacy {
	
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
		
		$model = $this->createModel("Members", "MemberDatabaseModel");
		$this->setModel($model, false);
	}
	
	/**
	 * Display the Members view
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
		
		$this->towers = $this->get ( 'Towers' );
		$this->members = $this->get ( 'Members' );
		$this->districts = $this->get ( 'Districts' );
		$this->association_name = JComponentHelper::getParams('com_memberdatabase')->get('association_name');
		$this->year = $date->format("Y");
		//$this->membersSubs = $this->get('MembersSubs', 'Members');

		echo '<div id="md-report">';
		echo '<button onclick="window.print()" class="btn">
						<span class="icon-print"></span>Print</button>';
		
		// Display the template
		parent::display ( $tpl );
		
		echo '</div>';
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
