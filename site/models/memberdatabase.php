<?php
/**
 * @subpackage  com_memberdatabase
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * MemberDatabase Model
 *
 * @since  0.0.1
 */
class MemberDatabaseModelMemberDatabase extends JModelItem
{
	/**
	 * @var string message
	 */
	
	public function getStatus() {
		jimport('joomla.application.component.helper');
		
		$status = array();
		
		$status['unverified_members'] = $this->getMemberStatus();
		$status['towers_no_invoices'] = $this->getMemberWithoutInvoiceCount();
		
		return $status;
	}
	
	// Returns the number of records requiring attention
	protected function getMemberStatus() {
		
		$verification_required_since = JComponentHelper::getParams('com_memberdatabase')->get('verification_required_since');
		
		// Initialize variables.
		$db    = JFactory::getDbo();
		$userid = JFactory::getUser()->id;
		$query = $db->getQuery(true);
		
		$verifiedSubQuery = '(SELECT member_id, max(verified_date) as `verified_date` FROM `#__md_member_verified` where verified_date >= "' . $verification_required_since . '" group by member_id) v';
		
		// Create the base select statement.
		$query->select('count(*)')
		->from($db->quoteName('#__md_member', 'm'))
		->join('LEFT', $verifiedSubQuery . ' ON (' . $db->quoteName('m.id') . ' = ' . $db->quoteName('v.member_id') . ')');
		
		if (! JFactory::getUser ()->authorise ( 'core.manage', 'com_memberdatabase' )) {
			$query->join ( 'INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (' . $db->quoteName ( 'm.tower_id' ) . ' = ' . $db->quoteName ( 'ut.tower_id' ) . ')' );
			$query->where ( 'ut.user_id = ' . $userid );
		}
		
		$query->where('ut.user_id = ' . $userid . ' and v.member_id is null');
		
		$db->setQuery ( $query );
		
		return $db->loadResult();
	}
	
	protected function getMemberWithoutInvoiceCount() {
		$db    = JFactory::getDbo();
		$userid = JFactory::getUser()->id;
		$query = $db->getQuery(true);
		
		$verification_required_since = JComponentHelper::getParams('com_memberdatabase')->get('verification_required_since');
		$date = DateTime::createFromFormat("Y-m-d", $verification_required_since);
		$year = $date->format("Y");
		
		// Create the base select statement.
		$query->select('t.id, concat_ws(", ", t.place, t.designation) as tower_name, count(m.id) as number_of_members')
		->from($db->quoteName('#__md_member', 'm'))
		->join('INNER', $db->quoteName('#__md_tower', 't') . ' ON (' . $db->quoteName('m.tower_id') . ' = ' . $db->quoteName('t.id') . ')')
		->join('LEFT', '(select imsub.id, member_id, year from c1jr0_md_invoicemember imsub LEFT JOIN c1jr0_md_invoice AS i ON (imsub.invoice_id = i.id) where year = ' . $year . ') as im on m.id = im.member_id');
		
		if (! JFactory::getUser ()->authorise ( 'core.manage', 'com_memberdatabase' )) {
			$query->join ( 'INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (' . $db->quoteName ( 'm.tower_id' ) . ' = ' . $db->quoteName ( 'ut.tower_id' ) . ')' );
			$query->where ( 'ut.user_id = ' . $userid );
		}
		
		$query->where ( 'im.id is null' );
		
		$query->group('t.id, concat_ws(", ", t.place, t.designation)');
		
		$db->setQuery ( $query );
		
		return $db->loadObjectList ();
		
	}
	
}
