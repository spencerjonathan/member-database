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

JLoader::import('QueryHelper', JPATH_COMPONENT . "/helpers/");
JLoader::import('EmailHelper', JPATH_COMPONENT . "/helpers/");

/**
 * MemberDatabaseList Model
 *
 * @since 0.0.1
 */
class MemberDatabaseModelMembers extends JModelList {
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
		if (empty ( $config ['filter_fields'] )) {
			$config ['filter_fields'] = array (
					'id',
					'tower',
					'name',
					'verified_date' 
			);
		}
		
		parent::__construct ( $config );
	}
	
	public function getMembersSubs() {
		$db = JFactory::getDbo ();
		$query = $this->getExtendedQuery($db);
		$query->order ( 't.district_id, t.place asc' );
		$table = $query->__toString();
		$pivotColumn = "member_type"; 
		$groupByColumns = array("district", "tower");
		$aggregationColumns = array("fee" => "sum", "tower" => "count");
		
		$pivot_results = QueryHelper::pivot($table, $pivotColumn, $groupByColumns, $aggregationColumns);
		
		return $pivot_results;
	}
	
	private function getExtendedQuery($db) {
		
		$query = $this->getBaseQuery($db);
		
		QueryHelper::addMemberTypeJoin($db, $query);
		QueryHelper::addDistrictJoin($db, $query);
		
		$query = QueryHelper::addDataPermissionConstraints($db, $query);
		
		return $query;
	}
	
	private function getBaseQuery($db) {
		
		$query = $db->getQuery ( true );
		
		$verifiedSubQuery = '(SELECT member_id, max(verified_date) as `verified_date` FROM `#__md_member_verified` group by member_id) v';
		
		// Create the base select statement.
		$query->select ( 'm.*, concat_ws(\', \',place, designation) as tower, concat_ws(\', \',surname, forenames) as name, m.annual_report, v.verified_date, dbs_date' );
		$query->from ( $db->quoteName ( '#__md_member', 'm' ) );
		$query->join ( 'LEFT', $db->quoteName ( '#__md_tower', 't' ) . ' ON (' . $db->quoteName ( 'm.tower_id' ) . ' = ' . $db->quoteName ( 't.id' ) . ')' );
		$query->join ( 'LEFT', $verifiedSubQuery . ' ON (' . $db->quoteName ( 'm.id' ) . ' = ' . $db->quoteName ( 'v.member_id' ) . ')' );
		
		//$query = QueryHelper::addDataPermissionConstraints($db, $query);
		
		return $query;
		
	}
	
	public function getMembersByUniqueAddress($districtId) {
		
		$db = JFactory::getDbo ();
		$query = $this->getExtendedQuery($db);
		$query->select('t.place');
		
		if ($districtId) {
			$query->where( "t.district_id = " . (int) $districtId);
		}
		
		$query->where( 'm.newsletters in ("Postal", "Both")');
		$query->where( 'm.member_type_id not in (4, 7, 8)');
		$query->order('m.postcode, m.address1, m.address2, m.address3');
		
		$db->setQuery ( $query );
		$db_results = $db->loadObjectList ();
		
		$results = [];
		
		$previous = null;
		
		foreach ($db_results as $member) {
			if ($previous) {
				if ($previous->postcode == $member->postcode && $previous->address1 == $member->address1) {
					$previous->title = $previous->title . " & $member->title " . substr($member->forenames, 0, 1);
					$previous->forenames = $previous->forenames . " & $member->forenames";
				} else {
					$member->title = "$member->title " . substr($member->forenames, 0, 1);
					array_push($results, $member);
					$previous = $member;
				}
			} else {
				$member->title = "$member->title " . substr($member->forenames, 0, 1);
				array_push($results, $member);
				$previous = $member;
			}
		}
		
		return $results;
		
	}

	public function getMembersByUniqueEmailAddress($districtId) {
		
		$db = JFactory::getDbo ();
		$query = $this->getBaseQuery($db);
		$query->join('INNER', $db->quoteName ( '#__md_district', 'd' ) . ' ON ((' . $db->quoteName ( 't.district_id' ) . ' = ' . $db->quoteName ( 'd.id' ) . ') or (m.district_newsletters = 1 and d.id != 5))');
		QueryHelper::addDataPermissionConstraints($db, $query);
		$query->select('t.place, t.district_id, d.name as district');
		
		if ($districtId) {
			$query->where( "d.id = " . (int) $districtId);
		}
		
		$query->where( 'm.newsletters in ("Email", "Both")');
		$query->where( 'm.member_type_id not in (4, 7, 8)');
		$query->order('d.name, t.place, m.surname, m.email');
		
		$db->setQuery ( $query );
		$db_results = $db->loadObjectList ();
		
		$results = [];
		
		$previous = null;
		
		foreach ($db_results as $member) {
			if ($previous) {
				if ($previous->email == $member->email && $previous->district == $member->district) {
					$previous->title = $previous->title . " & $member->title " . substr($member->forenames, 0, 1);
				} else {
					$member->title = "$member->title " . substr($member->forenames, 0, 1);
					array_push($results, $member);
					$previous = $member;
				}
			} else {
				$member->title = "$member->title " . substr($member->forenames, 0, 1);
				array_push($results, $member);
				$previous = $member;
			}
		}
		
		return $results;
		
	}
	
	public function getCorrespondents($districtId) {
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		// Create the base select statement.
		$query->select ( 'm.title, m.forenames, m.surname, if (t.corresp_email > "", t.corresp_email, m.email) as email, m.address1, m.address2, m.address3, m.town, m.county, m.postcode, concat_ws(\', \',place, designation) as tower, t.place, d.name as district, concat_ws(", ", surname, forenames) as member_name' );
		$query->from ( $db->quoteName ( '#__md_member', 'm' ) );
		$query->join ( 'INNER', $db->quoteName ( '#__md_tower', 't' ) . ' ON (' . $db->quoteName ( 'm.id' ) . ' = ' . $db->quoteName ( 't.correspondent_id' ) . ')' );
		$query->join('INNER', $db->quoteName ( '#__md_district', 'd' ) . ' ON (' . $db->quoteName ( 't.district_id' ) . ' = ' . $db->quoteName ( 'd.id' ) . ')');
		
		if ($districtId) {
			$query->where( "t.district_id = " . (int) $districtId);
		}
		
		$query = QueryHelper::addDataPermissionConstraints($db, $query);
		
		$query->order ( 't.district_id, t.place asc' );
		
		$db->setQuery ( $query );
		
		$db_results = $db->loadObjectList ();

		foreach ($db_results as $member) {
			$member->title = "$member->title " . substr($member->forenames, 0, 1);
		}
		
		return $db_results;
		
	}

	public function getMembersForReports() {
		// Initialize variables.
		$db = JFactory::getDbo ();
		$userid = JFactory::getUser ()->id;
		$query = $this->getExtendedQuery($db);
		
		$query->where('mt.include_in_reports = true');
		
		$query->order ( 'district, tower, surname, forenames asc' );
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		return $results;
	}
	
	public function getMembers($memberId = NULL) {
		// Initialize variables.
		$db = JFactory::getDbo ();
		//$userid = JFactory::getUser ()->id;
		$query = $this->getExtendedQuery($db);
			
		if ($memberId) {
			$query->where( "m.id = " . (int) $memberId);
		}
		
		$query->order ( 'surname, forenames asc' );
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		return $results;
	}
	
	public function getMembersInclInvoices($year = NULL) {
		// Initialize variables.
		$db = JFactory::getDbo ();
		
		$query = $this->getExtendedQuery($db);
		$query->select('insurance_group', 'long_service');
		
		QueryHelper::addInvoiceOuterJoin($db, $query, (int) $year);
		
		$query->order ( 'surname, forenames asc' );
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		return $results;
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return string An SQL query
	 */
	protected function getListQuery() {
		// Initialize variables.
		$db = JFactory::getDbo ();
		$query = $this->getExtendedQuery($db);
		
		// Filter: like / search
		$search = $this->getState ( 'filter.search' );
		
		if (! empty ( $search )) {
			$like = $db->quote ( '%' . $search . '%' );
			$query->where ( 'concat_ws(\', \',surname, forenames, place, designation) LIKE ' . $like );
		}
		
		// Add the list ordering clause.
		error_log("State = " . serialize($this->state));
		
		$orderCol = $this->state->get ( 'list.ordering', 'surname, forenames' );
		$orderDirn = $this->state->get ( 'list.direction', 'asc' );
		
		$query->order ( $db->escape ( $orderCol ) . ' ' . $db->escape ( $orderDirn ) );
		
		return $query;
	}
	
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   The field to order on.
	 * @param   string  $direction  The direction to order on.
	 *
	 * @return  void.
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('site');
		
		$itemid = $app->input->get('id', 0, 'int') . ':' . $app->input->get('Itemid', 0, 'int');
		
		// Optional filter text
		$search = $app->getUserStateFromRequest('com_memberdatabase.member.list.' . $itemid . '.filter-search', 'filter-search', '', 'string');
		$this->setState('list.filter', $search);
		
		// Filter.order
		$orderCol = $app->getUserStateFromRequest('com_memberdatabase.member.list.' . $itemid . '.filter_order', 'filter_order', '', 'string');
		
		$this->setState('list.ordering', $orderCol);
		
		$listOrder = $app->getUserStateFromRequest('com_memberdatabase.member.list.' . $itemid . '.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
		
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}
		
		$this->setState('list.direction', $listOrder);
		
		parent::populateState($ordering, $direction);
	}
	
	public function getMemberTypeChanges($since) {
		// Initialize variables.
		$db = JFactory::getDbo ();
		$query = $db->getQuery($db);
		
		$query->setQuery("call #__md_MemberStatusChanges(" . $db->quote($since) . ")");
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		return $results;
		
	}
	
	public function generateAndSendLink($email) {
		
		// 1) check that email exists in database
		if (!EmailHelper::emailAddressExistsInDB($email)) {
			echo "Can't find member with that email address";
			$this->setError(JText::sprintf('No member has been found with email address ' . $email), 500);
			return false;
		}
		
		// Set the confirmation token.
		$token = JApplicationHelper::getHash(JUserHelper::genRandomPassword());
		
		// 2) store the hash and email address
		if (!EmailHelper::storeToken($email, $token)) {
			$this->setError(JText::sprintf('Could not store unique token'), 500);
			return false;
		}
		
		// 3) send the email
		$link = 'index.php?option=com_memberdatabase&view=members&token=' . $token;
		
		$config = JFactory::getConfig();
		$mode = $config->get('force_ssl', 0) == 2 ? 1 : (-1);
		$link_text = JRoute::_($link, false, $mode);
		$body = JText::sprintf(
				'Use this link %s to access your SCACR membership account record',
				$link_text
				);

		$subject = 'Link To Your Membership Record';

		$send = EmailHelper::sendEmail($email, $subject, $body);
		if ( $send !== true ) {
			$this->setError(JText::sprintf('Could not send email to %s', $email), 500);
			return false;
		} else {
			return true;
		}
		
	}
	
	public function allowDelete($memberId) {
		return JFactory::getUser ()->authorise ( 'member.delete', 'com_memberdatabase' );
	}
	
	public function delete(&$memberId) {
		
		error_log("In member.delete: memberId = " . $memberId);
		
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->delete($db->quoteName('#__md_member'));
		$query->where ( 'id = ' . (int) $memberId );
		
		$db->setQuery($query);
		$db->execute();
		
		return true;
		
	}

    public function sendStatusEmail() {

        $newMemberModel = \JModelLegacy::getInstance("NewMembers", "MemberDatabaseModel", array());

        $db    = JFactory::getDbo();
        $query = $newMemberModel->getBaseQuery($db);
		$db->setQuery ( $query );
		
		$newMembers = $db->loadObjectList ();

        $report = '<h1>New Members Still Pending Confirmation From Proposers</h1><br><br>';
        $report .= '<table><thead><tr><th>Name</th><th>Tower</th>' . 
                   '<th>Proposer Email</th><th>Seconder Email</th><th>Applied</th></tr></thead><tbody>';

        foreach ( $newMembers as $newMember ) {
            if (!($newMember->proposer_approved && $newMember->proposer_approved)) {
                $report .= '<tr><td>' . "$newMember->surname, $newMember->forenames" . "</td><td>$newMember->tower</td>";
                $report .= "<td>$newMember->proposer_email" . '</td><td>' . "$newMember->seconder_email" . '</td>';
                $report .= "<td>$newMember->mod_date" . '</td></tr>';
            }
        }

        $report .= '</tbody></table><br><br>';

        error_log("sendStatusEmail: " . $report);

        $email = "membership@scacr.org";

        $send = EmailHelper::sendEmail($email, "Member Status Report", "$report", true);
		if ( $send !== true ) {
			$this->setError(JText::sprintf('Could not send email to %s', $email), 500);
			return false;
		} else {
			return true;
		}

        return;
    }

	
}
