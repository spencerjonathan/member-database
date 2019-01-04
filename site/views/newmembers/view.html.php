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
 
/**
 * MembersDatabase View
 *
 * @since  0.0.1
 */
class MemberDatabaseViewNewmembers extends JViewLegacy
{
	/**
	 * Display the NewMembers view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{

        // Get application
		$app = JFactory::getApplication();
		
        // Check that user is privilaged to view this form
   		if (!(JFactory::getUser()->authorise ( 'core.admin', 'com_memberdatabase' ))) {
            
            $app->redirect(JUri::root(true));
        }

		$context = "memberdatabase.list.admin.newmember";

		// Get data from the model
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
 
		$this->state		= $this->get('State');
		$this->filter_order 	= $app->getUserStateFromRequest($context.'filter_order', 'filter_order', 'name', 'cmd');
		$this->filter_order_Dir = $app->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');
		$this->filterForm    	= $this->get('FilterForm');
		$this->activeFilters 	= $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
 
			return false;
		}

		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);

		// Set the document
		//$this->setDocument();
	}

	protected function addToolBar()
	{
		$title = JText::_('Members Database Manager - New Members');
 
		if ($this->pagination->total)
		{
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}

		JToolbarHelper::title($title, 'newmember');
		JToolbarHelper::addNew('newmember.add');
		JToolbarHelper::editList('newmember.edit');
		JToolbarHelper::deleteList('', 'newmembers.delete');
	}

    protected function displayProposer($email, $approved) {

        if (!$email) {
            echo '<span class="label label-default">N/A</span>';
        } else {
            if ($approved) {
                // Has said yes!
                echo '<span class="label label-success">';
            } elseif ($approved == 0) {
                // has said no!  Aaaahhh!
                echo '<span class="label label-danger">';
            } else {
                // hasn't responded yet
                echo '<span class="label label-warning">';
            }
            echo $email . '</span>';
        }
    }

}
