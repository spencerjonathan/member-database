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
 * MemberDatabaseList Model
 *
 * @since  0.0.1
 */
class MemberDatabaseModelInvoices extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id',
				'tower_name',
				'created_by_user',
				/* 'email', */
				'created_date'
			);
		}
 
		parent::__construct($config);
	}

	
	public function getInvoices($memberId = null) {
		$db    = JFactory::getDbo();
		$query = $this->getListQuery();
		
		if ($memberId) {
			$query->join('INNER', $db->quoteName('#__md_invoicemember', 'm') . ' ON (' . $db->quoteName('inv.id') . ' = ' . $db->quoteName('m.invoice_id') . ')');
			$query->where("m.member_id = " . (int) $memberId);
		}
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		return $results;
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$userid = JFactory::getUser()->id;
		$query = $db->getQuery(true);
		
		// Create the base select statement.
		$query->select('inv.id, inv.tower_id, inv.year, inv.created_by_user_id, u.name as created_by_user, inv.created_date, inv.payment_method, inv.payment_reference, concat_ws(", ", t.place, t.designation) as tower_name, t.place, sum(im.fee) as fee, inv.paid, inv.paid_date')
                ->from($db->quoteName('#__md_invoice', 'inv'))
		->join('INNER', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('inv.created_by_user_id') . ' = ' . $db->quoteName('u.id') . ')')
		->join('INNER', $db->quoteName('#__md_invoicemember', 'im') . ' ON (' . $db->quoteName('inv.id') . ' = ' . $db->quoteName('im.invoice_id') . ')')
		->join('INNER', $db->quoteName('#__md_tower', 't') . ' ON (' . $db->quoteName('inv.tower_id') . ' = ' . $db->quoteName('t.id') . ')');

		if (! JFactory::getUser ()->authorise ( 'invoice.view', 'com_memberdatabase' )) {
			$query->join ( 'INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (' . $db->quoteName ( 'inv.tower_id' ) . ' = ' . $db->quoteName ( 'ut.tower_id' ) . ')' );
			$query->where ( 'ut.user_id = ' . $userid );
		}
		
		$query->group('inv.id, inv.tower_id, inv.year, inv.created_by_user_id, inv.created_date, inv.payment_method, inv.payment_reference, concat_ws(", ", t.place, t.designation)');

		// Filter: like / search
		$search = $this->getState('filter.search');
 
		if (!empty($search))
		{
			$like = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('concat_ws(\'/\', t.place, inv.id, inv.payment_reference) LIKE ' . $like);
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 't.place, t.designation, inv.created_date');
		$orderDirn 	= $this->state->get('list.direction', 'asc');
 
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
 
		return $query;
	}
}
