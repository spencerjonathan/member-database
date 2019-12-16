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
 * Mails Controller
 *
 * @since  0.0.1
 */
class MemberDatabaseControllerMembershipRenewals extends JControllerAdmin
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
	public function getModel($name = 'MembershipRenewals', $prefix = 'MemberDatabaseModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
 
		return $model;
	}

    public function sendinvoice()
    {

        // Check for request forgeries.
		//$this->checkToken('request');

        $towerId = $this->input->get('towerId', null, "INT");
        
        $model = $this->getModel();

        $type = $model->sendinvoice($towerId);

		$msg = $model->getError();
		$this->setredirect('index.php?option=com_memberdatabase&view=membershiprenewals', $msg, $type);

    }
}
