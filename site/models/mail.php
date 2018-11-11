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
 * Users mail model.
 *
 * @since  1.6
 */
class MemberDatabaseModelMail extends JModelAdmin
{
	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm	A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_memberdatabase.mail', 'mail', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_memberdatabase.display.mail.data', array());

		$this->preprocessData('com_users.mail', $data);

		return $data;
	}

	/**
	 * Method to preprocess the form
	 *
	 * @param   JForm   $form   A form object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @since   1.6
	 * @throws  Exception if there is an error loading the form.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'user')
	{
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Send the email
	 *
	 * @return  boolean
	 */
	public function send()
	{
		
		$message_body = JFilterInput::getInstance()->clean($message_body, 'string');

        $reply_to_email = MailHelper::cleanAddress($data'reply_to_email']);

        if (!MailHelper::isEmailAddress($reply_to_email)) {
            $app->setUserState('com_memberdatabase.display.mail.data', $data);
		    $this->setError('Email address provided is not valid: ' . $reply_to_email);
		    return false;
        }

		// Get correspondent email address
		
        $db = $this->getDbo();
        $query = $db->getQuery(true)
			->select('t.corresp_email, corresp.email')
			->from('#__md_tower t')
			->leftJoin('#__md_member corresp on corresp.id = t.correspondent_id')
			->where('t.id = ' . (int) $data['tower_id']);

		$db->setQuery($query);
		$row = $db->loadAssoc();

		$to = MailHelper::cleanLine($row['corresp_email'] ? $row['corresp_email'] : $row['email']);
		
		if (!$to) {
		    $app->setUserState('com_memberdatabase.display.mail.data', $data);
		    $this->setError(JText::_('COM_USERS_MAIL_ONLY_YOU_COULD_BE_FOUND_IN_THIS_GROUP'));
		    return false;
		}
		
		// Get the Mailer
		$mailer = JFactory::getMailer();
		$params = JComponentHelper::getParams('com_memberdatabase');

		// Build email message format.
        $mailer->addReplyTo($reply_to_email);
		$mailer->setSender(array($app->get('mailfrom'), $app->get('fromname')));
		$mailer->setSubject($params->get('mailSubjectPrefix') . stripslashes($subject));
		$mailer->setBody($reply_to_email . " sent you a message from the SCACR website\n\n" . $message_body . $params->get('mailBodySuffix'));
		//$mailer->IsHtml($mode);

		$mailer->addRecipient($to);

		// Send the Mail
		$rs = $mailer->Send();

		// Check for an error
		if ($rs instanceof Exception)
		{
			$app->setUserState('com_memberdatabase.display.mail.data', $data);
			$this->setError($rs->getError());

			return false;
		}
		elseif (empty($rs))
		{
			$app->setUserState('com_memberdatabase.display.mail.data', $data);
			$this->setError('The mail could not be sent');

			return false;
		}
		else
		{
			/**
			 * Fill the data (specially for the 'mode', 'group' and 'bcc': they could not exist in the array
			 * when the box is not checked and in this case, the default value would be used instead of the '0'
			 * one)
			 */
/* 			$data['mode']    = $mode;
			$data['subject'] = $subject;
			$data['group']   = $grp;
			$data['recurse'] = $recurse;
			$data['bcc']     = $bcc;
			$data['message'] = $message_body; */
			$app->setUserState('com_memberdatabase.display.mail.data', array());
			$app->enqueueMessage('Message sent!', 'message');

			return true;
		}
	}
}
