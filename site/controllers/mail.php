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
 * Users mail controller.
 *
 * @since  1.6
 */
class MemberDatabaseControllerMail extends JControllerLegacy
{
	/**
	 * Send the mail
	 *
	 * @return void
	 *
	 * @since 1.6
	 */
	public function send()
	{
		// Check for request forgeries.
		$this->checkToken('request');

		$model = $this->getModel('Mail');

        $app    = JFactory::getApplication();
		$data   = $app->input->post->get('jform', array(), 'array');
		
		$form = $model->getForm($data, false);
		$validData = $model->validate($form, $data);
		
		// Check for validation errors.
		if ($validData === false)
		{
		    // Get the validation messages.
		    $errors = $model->getErrors();
		    
		    // Push up to three validation messages out to the user.
		    for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
		    {
		        if ($errors[$i] instanceof \Exception)
		        {
		            $msg = $errors[$i]->getMessage();
		        }
		        else
		        {
		            $msg = $errors[$i];
		        }
		    }

            $app->setUserState('com_memberdatabase.display.mail.data', $data);

            $this->setredirect('index.php?option=com_memberdatabase&view=mail', $msg, 'warning');
            return false;

		}

		if ($model->send($validData))
		{
			$type = 'message';
		}
		else
		{
			$type = 'error';
		}

		$msg = $model->getError();
		$this->setredirect('index.php?option=com_memberdatabase&view=mail', $msg, $type);
	}

	/**
	 * Cancel the mail
	 *
	 * @return void
	 *
	 * @since 1.6
	 */
	public function cancel()
	{
		// Check for request forgeries.
		$this->checkToken('request');
		$this->setRedirect('index.php');
	}
}
