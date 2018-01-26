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
		JSession::checkToken() or die( JText::_('Invalid Token'));
		
		$model = $this->getModel ();
		$app = JFactory::getApplication();
		
		$email = $this->input->get ( 'email', '', 'STRING');
		
		if (!$model->generateAndSendLink($email)) {
			$app->enqueueMessage("Can't send link:" . $model->getError(), "error");
			return false;
		}
		
		$app->enqueueMessage("Link has been sent to your email account. ", "success");
		return true;
		//$this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=requestlink', false ) );
	}
	
	public function delete($key = null, $urlVar = null) {
		$model = $this->getModel ( 'Members', 'MemberDatabaseModel', array () );
		
		$jinput = JFactory::getApplication ()->input;
		$cids = $jinput->post->get ( 'cid', null, array () );
		
		error_log ( "Members Controller delete cids = " . json_encode ( $cids ) );
		
		if (isset ( $cids )) {
			$key = null;
			foreach ( $cids as $memberId ) {
				if ($model->allowDelete ( $memberId )) {
					if (! $model->delete ( $memberId )) {
						$this->setError ( "One or more members were not deleted due to processing failure" );
						$this->setMessage ( $this->getError (), 'error' );
					} else {
						$this->setMessage ( 'Members successfully deleted' );
					}
				} else {
					$this->setError ( "One or more members were not deleted because you are not permitted to delete them" );
					$this->setMessage ( $this->getError (), 'error' );
				}
			}
		}
		
		$this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=' . $this->view_list, false ) );
	}
}
