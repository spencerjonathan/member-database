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
	 * Display the Hello World view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{
		jimport('joomla.application.component.helper');
		$this->verification_required_since = JComponentHelper::getParams('com_memberdatabase')->get('verification_required_since');
		$date = DateTime::createFromFormat("Y-m-d", $this->verification_required_since);
		$this->year = $date->format("Y");
		
		// Assign data to the view
		$this->status = $this->get('Status');
		
		parent::display($tpl);
	}
}
