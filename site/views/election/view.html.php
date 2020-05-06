<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Users election view.
 *
 * @since  1.6
 */
class MemberDatabaseViewElection extends JViewLegacy
{
	/**
	 * @var object form object
	 */
	protected $form;

    protected $captchaEnabled = false;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{

		// Get data from the model
		error_log("About to call get Form - layout is " . $this->getLayout());
		
		if ($this->getLayout() == "default") {
		
		    $app = \JFactory::getApplication();

		    $this->form = $this->get('Form');
		    $this->item = $this->get('Item');
		    
		    error_log("In display.  Item is " . $this->item);

		    if (!$this->item) {
		    	$app->redirect(JRoute::_('index.php?option=' . $this->option . '&view=election&layout=error', false));
		    	return false;
		    }
		    
		    if ($this->getModel()->hasVoteBeenSubmitted($this->item->member_id)) {
                $app->redirect(JRoute::_('index.php?option=' . $this->option . '&view=election&layout=alreadysubmitted', false));
            }
		    
		    $this->form->setValue("member_id", null, $this->item->member_id);
		    $this->form->setValue("hash_token", null, $this->item->token);
        }
        //$document = JFactory::getDocument();
        //$document->addStyleSheet('media/jui/css/bootstrap.css');

		parent::display($tpl);
	}

}
