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
 * Users election controller.
 *
 * @since  1.6
 */
class MemberDatabaseControllerElection extends JControllerForm
{
	/**
	 * Save the vote
	 *
	 * @return void
	 *
	 * @since 1.6
	 */
	public function submit()
	{
		// Check for request forgeries.
		$this->checkToken('request');

		$model = $this->getModel('Election');

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

            $app->setUserState('com_memberdatabase.display.election.data', $data);

            $this->setredirect('index.php?option=com_memberdatabase&view=election', $msg, 'warning');
            return false;

		}

		$msg = $model->store($data);
		
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

        if ($errors.length > 0) {
    		$this->setredirect('index.php?option=com_memberdatabase&view=election&layout=error', $msg, 'failure');        
    		return false;
        }

        $app->setUserState('com_memberdatabase.display.election.data', null);
		$this->setredirect('index.php?option=com_memberdatabase&view=election&layout=success', $msg, 'success');
		return true;
		
	}

	/**
	 * Cancel the election submission
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
