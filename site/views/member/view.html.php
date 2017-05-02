<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
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
$document->addScript('./media/system/js/core.js');
//$document->addScript('./components/com_memberdatabase/js/typeahead.bundle.js');

 
/**
 * Member View
 *
 * @since  0.0.1
 */
class MemberDatabaseViewMember extends JViewLegacy
{
	/**
	 * View form
	 *
	 * @var         form
	 */
	protected $form = null;
 
	/**
	 * Display the Member view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		// Get the Data
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->towers = $this->get('towers');
 
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
	}
 
	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolBar()
	{
		$input = JFactory::getApplication()->input;
 
		// Hide Joomla Administrator Main menu
		//$input->set('hidemainmenu', true);
 
		$isNew = ($this->item->id == 0);
 
		if ($isNew)
		{
			$title = JText::_('Member Database - New Member');
		}
		else
		{
			$title = JText::_('Member Database - Edit Member');
		}
 
		JToolbarHelper::title($title, 'member');
		JToolbarHelper::save('member.save');
		JToolbarHelper::cancel(
			'member.cancel',
			$isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE'
		);
		
		//echo JToolbar::getInstance()->render();
	}
}
