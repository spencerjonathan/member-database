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

		if ($model->send())
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
