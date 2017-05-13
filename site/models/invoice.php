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
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Invoice', $prefix = 'MemberDatabaseTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
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
		$this->towerId = $jinput->get ( 'towerId', 1, 'INT' );
		
		$db = JFactory::getDbo ();
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		// Create the base select statement.
		$query->select ( 't.id, concat_ws(", ", place, designation) as name' )->from ( $db->quoteName ( '#__md_tower', 't' ) );
		
		if (! JFactory::getUser ()->authorise ( 'core.manage', 'com_memberdatabase' )) {
			$query->join ( 'INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (' . $db->quoteName ( 't.id' ) . ' = ' . $db->quoteName ( 'ut.tower_id' ) . ')' );
			$query->where ( 'ut.user_id = ' . $userid );
		}
		
		$query->where ( 't.id = ' . $this->towerId );
		
		$query->order ( 'place, designation asc' );
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		if (count ( $results ) == 0) {
			$this->setError ( 'Could not load tower details for tower with id = ' . $this->towerId );
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
		$db = JFactory::getDbo ();
		$userid = JFactory::getUser ()->id;
		$query = $db->getQuery ( true );
		
		// Create the base select statement.
		$query->select ( 'm.id, mt.name as member_type, concat_ws(\', \',m.surname, m.forenames) as name, mt.fee' )->from ( $db->quoteName ( '#__md_member', 'm' ) );
		$query->join ( 'INNER', $db->quoteName ( '#__md_member_type', 'mt' ) . ' ON (' . $db->quoteName ( 'm.member_type_id' ) . ' = ' . $db->quoteName ( 'mt.id' ) . ')' );
		
		if (! JFactory::getUser ()->authorise ( 'core.manage', 'com_memberdatabase' )) {
			$query->join ( 'INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (' . $db->quoteName ( 'm.tower_id' ) . ' = ' . $db->quoteName ( 'ut.tower_id' ) . ')' );
			$query->where ( 'ut.user_id = ' . $userid );
		}
		
		$query->where ( 'm.tower_id = ' . $this->towerId );
		
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
				$towerId,
				$year,
				$userId,
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
		$invoiceId = $db->loadResult ();
		
		error_log ( "Result from getting last insert id: " . $invoiceId);
		
		foreach ( $members as $member ) {
			if (! $this->addMemberToInvoice ( $invoiceId, $member )) {
				return 0;
			}
		}
		
		return $invoiceId;
	}
	
	protected function addMemberToInvoice($invoiceId, $memberId) {
		error_log("in addMemberToInvoice(" . $invoiceId . ", " . $memberId . ")");
		$db = JFactory::getDbo ();
		
		// Create a new query object.
		$query = $db->getQuery ( true );
		
		// Insert values.
		
		$dml = '#__md_invoicemember (invoice_id, member_id, member_type_id, fee) 
		SELECT ' . $invoiceId . ', m.id, m.member_type_id, mt.fee 
		from #__md_member m 
		INNER JOIN #__md_member_type mt on m.member_type_id = mt.id 
		where m.id = ' . $memberId;
		
		$query->insert($dml);
		
		// Set the query using our newly populated query object and execute it.
		$db->setQuery ( $query );
		$result = $db->execute ();
		
		if (! $result) {
			error_log ( "Result from executing dml to addMemberToInvoice: " . serialize ( $result ) );
			
			return 0;
		}
		
		return $result;
	}
}
