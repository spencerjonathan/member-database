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

if(!class_exists('JToolbarHelper')) {
   require_once JPATH_ADMINISTRATOR . '/includes/toolbar.php';
}

$document = JFactory::getDocument();
$document->addStyleSheet('./components/com_memberdatabase/css/print.css');
 
/**
 * MembersDatabase View
 *
 * @since  0.0.1
 */
class MemberDatabaseViewAnnualreport extends JViewLegacy
{
	/**
	 * Display the Members view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{

		$this->towers = $this->get('Towers');
		$this->members = $this->get('Members');
		$this->districts = $this->get('Districts');

		error_log('towers = ' . json_encode($this->towers));
		error_log('members = ' . json_encode($this->members));
		error_log('districts = ' . json_encode($this->districts));

		// Display the template
		parent::display($tpl);
	}

}
