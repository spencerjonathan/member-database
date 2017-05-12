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
class MemberDatabaseControllerInvoice extends JControllerForm
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
	
	public function getModel($name = 'Invoice', $prefix = 'MemberDatabaseModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		
		return $model;
	}
	
	/**
	 * Method to verify a member's detail as being correct.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   12.2
	 */
	public function save($key = null, $urlVar = null)
	{
		$model = $this->getModel();
		
		error_log("invoice.save function called");
		$jinput = JFactory::getApplication()->input;
		
		$towerId = $jinput->post->get('tower-id', null, 'int');
		$year = $jinput->post->get('year', null, 'int');
		$userid = JFactory::getUser ()->id;
		
		error_log(serialize($jinput));
		
		$members = $jinput->post->get('cid', null, 'array');
		
		if ($members == null) {
			$this->setError("Each invoice must include at least one member!");
			return 0;
		}
		
		error_log(json_encode($members));
		
		if (!$model->addInvoice($towerId, $year, $userid, $members)) {
			$this->setError("Could not save invoice");
			return 0;
		}
		
		return 1;
	}
}
