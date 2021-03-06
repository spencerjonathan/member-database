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

if (! class_exists('JToolbarHelper')) {
    require_once JPATH_ADMINISTRATOR . '/includes/toolbar.php';
}

$document = JFactory::getDocument();
$document->addScript('./media/system/js/core-uncompressed.js');
$document->addScriptDeclaration('
Joomla.submitbutton = function( pressbutton, form ) {
	Joomla.submitform( pressbutton, form );
};
');

// $document->addScript('./components/com_memberdatabase/js/typeahead.bundle.js');

/**
 * Newmember View
 *
 * @since 0.0.1
 */
class MemberDatabaseViewNewmember extends JViewLegacy
{

    /**
     * View form
     *
     * @var form
     */
    protected $form = null;

    /**
     * Display the Newmember view
     *
     * @param string $tpl
     *            The name of the template file to parse; automatically searches through the template paths.
     *            
     * @return void
     */
    public function display($tpl = null)
    {

        //debug_print_backtrace();
        // Get the Data
        $this->form = $this->get('Form');
        // This won't work because need to pass pk parameter to model getItem()
        //$this->item = $this->get('Item');

        $app = \JFactory::getApplication();

        $jinput = $app->input;
        $this->token = $jinput->get('token', null, "ALNUM");
        $this->stage = $jinput->get('stage', "initial", 'ALNUM');

        //$app =& JFactory::getApplication();
        
        if ($this->getModel()->hasApplicationBeenSubmitted($this->token)) {
            $app->redirect(JRoute::_('index.php?option=' . $this->option . '&view=newmember&layout=alreadysubmitted', false));
        }

        if ($this->stage != "initial" && ! ($this->getModel()->getPK($this->token))) {

            // Enqueue the redirect message
            $app->enqueueMessage("Could not find record", "error");
            
            error_log("Redirecting to initial page.  Item is : " . json_encode($this->form));
		    
		    $redirect = \JRoute::_(
		        'index.php?option=com_memberdatabase&view=newmember&layout=edit', false
		        );
		    
		    // Execute the redirect
		    $app->redirect($redirect);
        }

        //error_log("Item retrieved: " . json_encode($this->item));

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }

        // Set the toolbar
        //$this->addToolBar();

        // Display the template
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return void
     *
     * @since 1.6
     */
    /* protected function addToolBar()
    {
        // $input = JFactory::getApplication()->input;

        // Hide Joomla Administrator Main menu
        // $input->set('hidemainmenu', true);
        $isNew = ($this->item->id == 0);

        if ($isNew) {
            $title = JText::_('Member Database - New Member');
        } else {
            $title = JText::_('Member Database - Complete Member Details');
        }

        // JToolbarHelper::title($title, 'newmember');
        // JToolbarHelper::save('newmember.save');
        // JToolbarHelper::cancel(
        // 'newmember.cancel',
        // $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE'
        // );

        // echo JToolbar::getInstance()->render();
    } */
}
