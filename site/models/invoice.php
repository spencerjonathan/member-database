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
 * MemberDatabaseList Model
 *
 * @since 0.0.1
 */
class MemberDatabaseModelInvoice extends JModelAdmin {
	private $towerId = 0;
	
	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param string $type
	 *        	The table name. Optional.
	 * @param string $prefix
	 *        	The class prefix. Optional.
	 * @param array $config
	 *        	Configuration array for model. Optional.
	 *        	
	 * @return JTable A JTable object
	 *        
	 * @since 1.6
	 */
	public function getTable($type = 'Invoice', $prefix = 'MemberDatabaseTable', $config = array()) {
		return JTable::getInstance ( $type, $prefix, $config );
	}
	
	/**
	 * Constructor.
	 *
	 * @param array $config
	 *        	An optional associative array of configuration settings.
	 *        	
	 * @see JController
	 * @since 1.6
	 */
	public function __construct($config = array()) {
		parent::__construct ( $config );
	}
	
	public function getInvoiceData() {
		
		$jinput = JFactory::getApplication ()->input;
		$invoiceId = $jinput->get ( 'id', 1, 'INT' );
		
		if (!isset($invoiceId)) {
			$this->setError("Must specific an invoice number when viewing an invoice");
			return 0;
		}
		
		$data = $this->getItem();
		
		error_log("Item in getInvoiceData = " . serialize($data));
		
		$data->members = $this->getInvoiceMembers($invoiceId);
		
		$data->tower_name = $this->getTowerById($data->tower_id)->name;
		$data->place = $this->getTowerById($data->tower_id)->place;
		
		return $data;
	}
	
	public function getInvoiceMembers($invoiceId) {
		
		if (!isset($invoiceId)) {
			return array();
		}
		
		$db = JFactory::getDbo ();
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		// Create the base select statement.
		$query->select ( 'concat_ws(", ", m.surname, m.forenames) as name, mt.name as member_type, im.long_service, im.fee' )->from ( $db->quoteName ( '#__md_invoicemember', 'im' ) );
		$query->join ( 'INNER', $db->quoteName ( '#__md_member', 'm' ) . ' ON (' . $db->quoteName ( 'm.id' ) . ' = ' . $db->quoteName ( 'im.member_id' ) . ')' );
		$query->join ( 'INNER', $db->quoteName ( '#__md_member_type', 'mt' ) . ' ON (' . $db->quoteName ( 'im.member_type_id' ) . ' = ' . $db->quoteName ( 'mt.id' ) . ')' );
				
		if (! JFactory::getUser ()->authorise ( 'invoice.view', 'com_memberdatabase' )) {
			$query->join ( 'INNER', $db->quoteName ( '#__md_invoice', 'i' ) . ' ON (' . $db->quoteName ( 'im.invoice_id' ) . ' = ' . $db->quoteName ( 'i.id' ) . ')' );
			$query->join ( 'INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (' . $db->quoteName ( 'i.tower_id' ) . ' = ' . $db->quoteName ( 'ut.tower_id' ) . ')' );
			$query->where ( 'ut.user_id = ' . $userid );
		}
		
		$query->where ( 'im.invoice_id = ' . (int) $invoiceId );
		
		$query->order ( 'm.surname, m.forenames asc' );
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		if (count ( $results ) < 1) {
			$this->setError ( 'Could not load invoice members for invoice with id = ' . $invoiceId );
			return;
		}
		
		return $results;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return mixed The data for the form.
	 *        
	 * @since 1.6
	 */
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication ()->getUserState ( 'com_memberdatabase.edit.invoice.data', array () );
		
		if (empty ( $data )) {
			$data = $this->getItem ();
		}
		
		return $data;
	}
	
	/**
	 * Method to get the record form.
	 *
	 * @param array $data
	 *        	Data for the form.
	 * @param boolean $loadData
	 *        	True if the form is to load its own data (default case), false if not.
	 *        	
	 * @return mixed A JForm object on success, false on failure
	 *        
	 * @since 1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm ( 'com_memberdatabase.invoice', 'invoice', array (
				'control' => 'jform',
				'load_data' => $loadData 
		) );
		
		if (empty ( $form )) {
			return false;
		}
		
		return $form;
	}
	
	public function getTower() {
		$jinput = JFactory::getApplication ()->input;
		$towerId = $jinput->get ( 'towerId', 1, 'INT' );
		if (isset($towerId)) {
			return $this->getTowerById($towerId);
		}
	}
	
	public function getTowerById($towerId) {
		
		$db = JFactory::getDbo ();
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		// Create the base select statement.
		$query->select ( 't.id, concat_ws(", ", place, designation) as name, t.place' )->from ( $db->quoteName ( '#__md_tower', 't' ) );
		
		if (! JFactory::getUser ()->authorise ( 'invoice.view', 'com_memberdatabase' )) {
			$query->join ( 'INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (' . $db->quoteName ( 't.id' ) . ' = ' . $db->quoteName ( 'ut.tower_id' ) . ')' );
			$query->where ( 'ut.user_id = ' . $userid );
		}
		
		$query->where ( 't.id = ' . (int) $towerId );
		
		$query->order ( 'place, designation asc' );
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		if (count ( $results ) == 0) {
			$this->setError ( 'Could not load tower details for tower with id = ' . $towerId );
			return;
		}
		
		return $results [0];
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return string An SQL query
	 */
	public function getMembers() {
		// Initialize variables.
		
		$jinput = JFactory::getApplication ()->input;
		$towerId = $jinput->get ( 'towerId', 0, 'INT' );
		
		jimport('joomla.application.component.helper');
		$verification_required_since = JComponentHelper::getParams('com_memberdatabase')->get('verification_required_since');
		if (! $verification_required_since) {
			error_log ( "verification_required_since global configuration option is not set for the MemberDatabase." );
		}
		
		$date = DateTime::createFromFormat ( "Y-m-d", $verification_required_since );
		$year = $date->format ( "Y" );
		
		$db = JFactory::getDbo ();
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		// Create the base select statement.
		$query->select ( 'm.id, m.long_service, mt.name as member_type, concat_ws(\', \',m.surname, m.forenames) as name, mt.fee' )->from ( $db->quoteName ( '#__md_member', 'm' ) );
		$query->join ( 'INNER', $db->quoteName ( '#__md_member_type', 'mt' ) . ' ON (' . $db->quoteName ( 'm.member_type_id' ) . ' = ' . $db->quoteName ( 'mt.id' ) . ')' );
		$query->join ( 'LEFT', '(select imsub.id, member_id, year from #__md_invoicemember imsub INNER JOIN #__md_invoice AS i ON (imsub.invoice_id = i.id) where year = ' . $year . ') as im on m.id = im.member_id' );
		
		if (! JFactory::getUser ()->authorise ( 'invoice.view', 'com_memberdatabase' )) {
			$query->join ( 'INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (' . $db->quoteName ( 'm.tower_id' ) . ' = ' . $db->quoteName ( 'ut.tower_id' ) . ')' );
			$query->where ( 'ut.user_id = ' . (int) $userid );
		}
		
		$query->where ( 'mt.include_in_reports = 1' );
		$query->where ( 'm.tower_id = ' . (int) $towerId );
		$query->where ( 'im.id is null' );
		
		$query->order ( 'surname, forenames asc' );
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		return $results;
	}
	
	public function addInvoice($towerId, $year, $userId, $members) {
		$db = JFactory::getDbo ();
		$userId = JFactory::getUser ()->id;
		$currentDate = date ( 'Y-m-d H:i:s' );
		
		// Create a new query object.
		$query = $db->getQuery ( true );
		
		// Insert columns.
		$columns = array (
				'tower_id',
				'year',
				'created_by_user_id',
				'created_date' 
		);
		
		// Insert values.
		$values = array (
				(int) $towerId,
				(int) $year,
				(int) $userId,
				$db->quote ( $currentDate ) 
		);
		
		// Prepare the insert query.
		$query->insert ( $db->quoteName ( '#__md_invoice' ) )->columns ( $db->quoteName ( $columns ) )->values ( implode ( ',', $values ) );
		
		// Set the query using our newly populated query object and execute it.
		$db->setQuery ( $query );
		$result = $db->execute ();
		
		if (! $result) {
			error_log ( "Result from executing dml to addInvoice: " . serialize ( $result ) );
			
			return 0;
		}
		
		$query = $db->getQuery ( false );
		
		$query->select ( 'LAST_INSERT_ID()' );
		$db->setQuery ( $query );
		$invoiceId = (int) $db->loadResult ();
		
		error_log ( "Result from getting last insert id: " . $invoiceId );
		
		foreach ( $members as $member ) {
			if (! $this->addMemberToInvoice ( $invoiceId, $member )) {
				return 0;
			}
		}
		
		return $invoiceId;
	}
	protected function addMemberToInvoice($invoiceId, $memberId) {
		error_log ( "in addMemberToInvoice(" . $invoiceId . ", " . $memberId . ")" );
		$db = JFactory::getDbo ();
		
		// Create a new query object.
		$query = $db->getQuery ( true );
		
		// Insert values.
		
		$dml = '#__md_invoicemember (invoice_id, member_id, member_type_id, long_service, fee) 
		SELECT ' . (int) $invoiceId . ', m.id, m.member_type_id, m.long_service, if(m.long_service="No", mt.fee, 0) as fee 
		from #__md_member m 
		INNER JOIN #__md_member_type mt on m.member_type_id = mt.id 
		where m.id = ' . (int) $memberId;
		
		$query->insert ( $dml );
		
		// Set the query using our newly populated query object and execute it.
		$db->setQuery ( $query );
		$result = $db->execute ();
		
		if (! $result) {
			error_log ( "Result from executing dml to addMemberToInvoice: " . serialize ( $result ) );
			
			return 0;
		}
		
		return $result;
	}
	
	public function allowDelete($invoiceId) {
		
		// First check that the invoice isn't paid
		
		$db    = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		
		$query->select('count(*)');
		$query->from('#__md_invoice i');
		$query->where ( 'invoice_id = ' . (int) $invoiceId );
		$query->where( 'paid = true' );
		
		$db->setQuery($query);
		$result = $db->getResult();
		
		// should only ever be 0 or 1, but checking for > to be safe
		if ($result > 0) {
			return false;
		}
		
		// If the user has delete privilage then they can delete
		
		if (JFactory::getUser ()->authorise ( 'invoice.delete', 'com_memberdatabase' )) {
			return true;
		}
		
		// If the user has write access to the tower then they can delete
		
		$userid = JFactory::getUser()->id;
		
		$query = $db->getQuery(true);
		
		$query->select('count(*)');
		$query->from('#__md_invoice i');
		$query->join('INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (i.tower_id = ut.tower_id)');
		$query->where ( 'i.invoice_id = ' . (int) $invoiceId );
		$query->where( 'ut.user_id = ' . (int) $userid );
		
		$db->setQuery($query);
		$result = $db->getResult();
		
		// should only ever be 0 or 1, but checking for > to be safe
		if ($result > 0) {
			return true;
		}
		
		return false;
		
	}
	
	public function delete(&$invoiceId) {
		
		error_log("In invoice.delete: invoiceId = " . $invoiceId);
		
		// Initialize variables.
		$db    = JFactory::getDbo();
		$userid = JFactory::getUser()->id;
		$query = $db->getQuery(true);
		
		$query->delete($db->quoteName('#__md_invoicemember'));
		$query->where ( 'invoice_id = ' . (int) $invoiceId );
		
		$db->setQuery($query);
		$db->execute();
		
		$query = $db->getQuery(true);
		
		$query->delete($db->quoteName('#__md_invoice'));
		$query->where ( 'id = ' . (int) $invoiceId );
		
		$db->setQuery($query);
		$db->execute();
		
		return true;
		
	}
	
}
