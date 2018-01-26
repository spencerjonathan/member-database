<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * HelloWorlds Controller
 *
 * @since 0.0.1
 */
class MemberDatabaseControllerInvoice extends JControllerForm {
	/**
	 * Proxy for getModel.
	 *
	 * @param string $name
	 *        	The model name. Optional.
	 * @param string $prefix
	 *        	The class prefix. Optional.
	 * @param array $config
	 *        	Configuration array for model. Optional.
	 *        	
	 * @return object The model.
	 *        
	 * @since 1.6
	 */
	public function getModel($name = 'Invoice', $prefix = 'MemberDatabaseModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel ( $name, $prefix, $config );
		
		return $model;
	}
	
	/**
	 * Method to check if you can add a new record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   12.2
	 */
	protected function allowAdd($data = array())
	{
		
		if ($db_locked == true) {
			$this->setError('Amending invoice details is not permitted because the database is currently locked.');
			$this->setMessage($this->getError(), 'error');
			return false;
		}
		
		$user = JFactory::getUser();
		
		if ($user->authorise('invoice.create', $this->option)) {
			return true;
		}
		
		
	}
	
	
	/**
	 * Method to add an invoice
	 *
	 * @param string $key
	 *        	The name of the primary key of the URL variable.
	 * @param string $urlVar
	 *        	The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *        	
	 * @return boolean True if successful, false otherwise.
	 *        
	 * @since 12.2
	 */
	public function add($key = null, $urlVar = null) {
		
		// Access check.
		if (!$this->allowAdd())
		{
			// Set the internal error and also the redirect error.
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');
			
			$this->setRedirect(
					JRoute::_(
							'index.php?option=' . $this->option . '&view=' . $this->view_list
							. $this->getRedirectToListAppend(), false
							)
					);
			
			return false;
		}
		
		$model = $this->getModel ();
		
		error_log ( "invoice.save function called" );
		$jinput = JFactory::getApplication ()->input;
		
		$towerId = $jinput->post->get ( 'tower-id', null, 'int' );
		$year = $jinput->post->get ( 'year', null, 'int' );
		$userid = JFactory::getUser ()->id;
		
		error_log ( serialize ( $jinput ) );
		
		$members = $jinput->post->get ( 'cid', null, 'array' );
		
		if ($members == null) {
			$this->setError ( "Each invoice must include at least one member!" );
			$this->setMessage($this->getError(), 'error');
			return false;
		}
		
		error_log ( json_encode ( $members ) );
		
		$this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=memberdatabase', false ) );
		
		if (! $model->addInvoice ( $towerId, $year, $userid, $members )) {
			$this->setError ( "Could not save invoice!" );
			$this->setMessage($this->getError(), 'error');
			return false;
		}
		
		$this->setMessage("Invoice created successfully");
		
		return true;
	}
	
	protected function allowEdit($data = array(), $key = 'id') {
			
		$userId = JFactory::getUser ()->id;
		$invoiceId = $data ['id'];
		
		if (JFactory::getUser ()->authorise ( 'invoice.edit', 'com_memberdatabase' )) {
			return true;
		}
		
		error_log ( "User with id " . $userId . " does not have authorisation to modify invoice with id " . $invoiceId, 0 );
		return false;
	}
	
	
	
}
