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
 
/**
 * HelloWorlds Controller
 *
 * @since  0.0.1
 */
class MemberDatabaseControllerMembers extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Members', $prefix = 'MemberDatabaseModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
 
		return $model;
	}
	
	public function requestlink()
	{
		$model = $this->getModel ();
		
		$email = $this->input->post->get ( 'email' );
		
		$model->generateAndSendLink($email);
		
		error_log("requestlink - got email - $email");
		
		echo "requestlink - got email - $email";
		
		//$this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=requestlink', false ) );
	}
}
