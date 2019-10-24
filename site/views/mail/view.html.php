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
 * Users mail view.
 *
 * @since  1.6
 */
class MemberDatabaseViewMail extends JViewLegacy
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
		$this->form = $this->get('Form');

        $document = JFactory::getDocument();
        $document->addStyleSheet('media/jui/css/bootstrap.css');

		parent::display($tpl);
	}

}
