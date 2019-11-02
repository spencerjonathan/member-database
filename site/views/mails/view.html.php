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
 
/**
 * MembersDatabase View
 *
 * @since  0.0.1
 */
class MemberDatabaseViewMails extends JViewLegacy
{
	/**
	 * Display the UserDistricts  view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{

        // Check that user is privilaged to view this form
        if (!(JFactory::getUser()->authorise ( 'mail.view', 'com_memberdatabase' ))) {
            $app = JFactory::getApplication();
            $app->redirect(JUri::root(true));
        }

		// Get application
		$app = JFactory::getApplication();
		$context = "memberdatabase.list.site.mails";

		// Get data from the model
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
 
		$this->state		= $this->get('State');
		$this->filter_order 	= $app->getUserStateFromRequest($context.'filter_order', 'filter_order', 'mod_date', 'cmd');
		$this->filter_order_Dir = $app->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');
		$this->filterForm    	= $this->get('FilterForm');
		$this->activeFilters 	= $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
 
			return false;
		}

		// Display the template
		parent::display($tpl);

	}


}
