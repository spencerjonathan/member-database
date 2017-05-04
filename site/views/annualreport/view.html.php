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

		echo '<div id="md-report">';
		echo '<button onclick="window.print()" class="btn">
						<span class="icon-print"></span>Print</button>';
		
		// Display the template
		parent::display ( $tpl );
		
		echo '</div>';
	}
}
