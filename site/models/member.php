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

jimport ('joomla.filesystem.file');

JLoader::import('QueryHelper', JPATH_COMPONENT . "/helpers/");

/**
 * Member Model
 *
 * @since 0.0.1
 */
class MemberDatabaseModelMember extends JModelAdmin {
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
	public function getTable($type = 'Member', $prefix = 'MemberDatabaseTable', $config = array()) {
		JTable::addIncludePath ( JPATH_ADMINISTRATOR . '/components/com_memberdatabase/tables' );
		return JTable::getInstance ( $type, $prefix, $config );
	}
	
	protected function anonymousUserHasPrivilages($memberId) {
		$jinput = JFactory::getApplication ()->input;
		$token = $jinput->get ( 'token', null, 'ALNUM' );
		
		if (isset ( $token )) {
			$db = JFactory::getDbo ();
			
			$currentDate = date ( 'Y-m-d H:i:s' );
			// Build the database query to get the rules for the asset.
			$query = $db->getQuery ( true )
			->select ( 'count(*)' )
			->from ( $db->quoteName ( '#__md_member', 'm' ) )
			->join ( 'INNER', $db->quoteName ( '#__md_member_token', 'memt' ) . ' ON (' . $db->quoteName ( 'm.email' ) . ' = ' . $db->quoteName ( 'memt.email' ) . ')' )
			->where ( 'memt.hash_token = ' . $db->quote($token) . ' and memt.expiry_date >= ' . $db->quote ( $currentDate ) )
			->where ( 'm.id = ' . (int) $memberId);
			
			// Execute the query and load the rules from the result.
			$db->setQuery ( $query );
			$result = $db->loadResult ();
			
			if ($result > 0) {
				return true;
			}
			
			return false;
		}
	}
	
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);
		
		error_log("Item loaded is " . json_encode($item));
		
		// If the user has privilages to edit any member then return the item.
		if (JFactory::getUser ()->authorise ( 'member.edit', 'com_memberdatabase' )) {
			return $item;
		}
		
		$userId = JFactory::getUser ()->id;
		error_log ( "Member::getItem - User $userId does not have member.edit permission.");
		
		// Check if the user has privilages to edit members of a tower.
		$db = JFactory::getDbo ();
		
		// Build the database query to get the rules for the asset.
		$query = $db->getQuery ( true )->select ( 'count(*)' )
		->from ( $db->quoteName ( '#__md_usertower', 'ut' ) )
		->where ( 'ut.user_id = ' . ( int ) $userId . ' and ut.tower_id = ' . ( int ) $item->tower_id );
				
		// Execute the query and load the rules from the result.
		$db->setQuery ( $query );
		$result = $db->loadResult ();
				
		if ($result > 0) {
			return $item;
		} 
		
		if ($this->anonymousUserHasPrivilages($item->id)) return $item;
		
		return false;
		
		
	}	
	
	protected function getDistrictFromTowerID($towerId) {
		$db = JFactory::getDbo ();
		
		$query = $db->getQuery ( true )
		->select ( 'd.name' )
		->from ( $db->quoteName ( '#__md_district', 'd' ) )
		->join ( 'INNER', $db->quoteName ( '#__md_tower', 't' ) . ' ON (' . $db->quoteName ( 't.district_id' ) . ' = ' . $db->quoteName ( 'd.id' ) . ')' )
		->where ( 't.id = ' . (int) $towerId);
		
		// Execute the query and return the result.
		$db->setQuery ( $query );
		return $db->loadResult ();
	}
	
	public function save($data) {
		
		// Need to check before the record is updated in the database
		$emailAddressUpdated = $this->emailAddressUpdated($data);
		$commsPreferenceUpdated = $this->commsPreferenceUpdated($data);
		$districtCommsUpdated = $this->districtCommsUpdated($data);
		$memberTypeUpdated = $this->memberTypeUpdated($data);		

		$saveResult = parent::save($data);
		
		if ($saveResult && ($emailAddressUpdated || $commsPreferenceUpdated || $districtCommsUpdated || $memberTypeUpdated)) {
			$this->emailUpdatedHandler($data, $emailAddressUpdated, $commsPreferenceUpdated, $districtCommsUpdated, $memberTypeUpdated);
		}
		
		return $saveResult;
	}
	
	private function emailAddressUpdated($data) {
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		$query->select("count(*)");
		$query->from("#__md_member");
		$query->where("id = " . (INT) $data['id'] . " and email != " . $db->quote($data['email']) );
		
		$db->setQuery ( $query );
		error_log($query->__toString());
		return $db->loadResult () == 1;
		
	}
	
	private function commsPreferenceUpdated($data) {
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		$query->select("count(*)");
		$query->from("#__md_member");
		$query->where("id = " . (INT) $data['id'] . " and newsletters != " . $db->quote($data['newsletters']) );
		
		$db->setQuery ( $query );
		error_log($query->__toString());
		return $db->loadResult () == 1;
		
	}
	
	private function districtCommsUpdated($data) {
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		$query->select("count(*)");
		$query->from("#__md_member");
		$query->where("id = " . (INT) $data['id'] . " and district_newsletters != " . $db->quote($data['district_newsletters']) );
		
		$db->setQuery ( $query );
		error_log($query->__toString());
		return $db->loadResult () == 1;
		
	}

    private function memberTypeUpdated($data) {
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		$query->select("count(*)");
		$query->from("#__md_member");
		$query->where("id = " . (INT) $data['id'] . " and member_type_id != " . $db->quote($data['member_type_id']) );
		
		$db->setQuery ( $query );
		error_log($query->__toString());
		return $db->loadResult () == 1;
		
	}
	
	private function emailUpdatedHandler($data, $emailAddressUpdated, $commsPreferenceUpdated, $districtCommsUpdated, $memberTypeUpdated) {
		
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();
		
		$email_change_dist = JComponentHelper::getParams ( 'com_memberdatabase' )->get ( 'email_change_dist' );
		$email = explode ( ";" , $email_change_dist);
		
		$site = $config->get('sitename');
		
		$body = JText::sprintf(
				"The following information has been updated for <strong>%s, %s</strong> (%s):<br><ul>",
				$data['surname'],
				$data['forenames'],
				$this->getDistrictFromTowerID($data['tower_id'])
				);
		
		if ($emailAddressUpdated) {
		$body = $body . JText::sprintf(
				"<li>Email address has been updated to <strong>%s</strong></li>",
				$data['email']
				);
		};
		
		if ($commsPreferenceUpdated) {
			$body = $body . JText::sprintf(
					"<li>Communications method preference has been updated to <strong>%s</strong></li>",
					$data['newsletters']
					);
		};
		
		$which_districts = "Own District";
		if ($data['district_newsletters']) {
			$which_districts = "All Districts";
		}
		
		if ($districtCommsUpdated) {	
			$body = $body . JText::sprintf(
					"<li>Subscription for comms has been updated to <strong>%s</strong></li>",
					$which_districts
					);
		};

        $memberType = $this->getMemberType($data['member_type_id']);
        if ($memberTypeUpdated) {	
			$body = $body . JText::sprintf(
					"<li>Membership Type has been updated to <strong>%s</strong></li>",
                    $memberType
					);
		};
		
		$body = $body . JText::sprintf(
				"</ul><br>Comms preference are now:<br><br>Membership Type: <strong>%s</strong><br>Email Address: <strong>%s</strong><br>Communication Method: <strong>%s</strong><br>Which Districts' comms: <strong>%s</strong>",
                $memberType,      				
                $data['email'],
				$data['newsletters'],
				$which_districts
				);
		
		$subject = $site . " - Member's Communication Preferences Updated";

        error_log("Email Content: " . $body);
		
		$sender = array(
				$config->get( 'mailfrom' ),
				$config->get( 'fromname' )
		);
		
		$mailer->isHtml(true);
		$mailer->setSender($sender);
		$mailer->addRecipient($email);
		$mailer->setSubject($subject);
		$mailer->setBody($body);
		
		return $mailer->Send();
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
		
		$jinput = JFactory::getApplication ()->input;
		$token = $jinput->get ( 'token', null, 'STRING' );
		
		if (isset ( $token )) {
			$user_editing = true;
		} else {
			$user_editing = false;
		}
		
		// Get an appropriate set of fields to display
		if (JFactory::getUser ()->authorise ( 'core.admin', 'com_memberdatabase' )) {
			$form_name = 'com_memberdatabase.member';
			$form_file = 'member';
		} elseif ( $user_editing ) {
			$form_name = 'com_memberdatabase.member_user_edit';
			$form_file = 'member_user_edit';
		} else {
			$form_name = 'com_memberdatabase.member_minimal';
			$form_file = 'member_minimal';
		}
		
		
		$form = $this->loadForm ( $form_name, $form_file, array (
				'control' => 'jform',
				'load_data' => $loadData 
		) );
		
		if (empty ( $form )) {
			return false;
		}
		
		return $form;
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
		$data = JFactory::getApplication ()->getUserState ( 'com_memberdatabase.edit.member.data', array () );
		
		if (empty ( $data )) {
			$data = $this->getItem ();
		}
		
		return $data;
	}
	public function getTowers() {
		$db = JFactory::getDbo ();
		$userId = JFactory::getUser ()->id;
		
		$query = $db->getQuery ( true );
		
		$query->select ( 't.id, concat_ws(\', \', t.place, t.designation) as tower' )->from ( $db->quoteName ( '#__md_tower', 't' ) );
		
		if (! JFactory::getUser ()->authorise ( 'member.view', 'com_memberdatabase' )) {
			$query->join ( 'INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (t.id = ut.tower_id)' );
			$query->where ( 'ut.user_id = ' . ( int ) $userId );
		}
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		return $results;
	}

    public function getMemberType($memberType) {
		$db = JFactory::getDbo ();
		
		$query = $db->getQuery ( true );
		
		$query->select ( 'name' )->from ( $db->quoteName ( '#__md_member_type', 'mt' ) );
		$query->where ( 'mt.id = ' . ( int ) $memberType );

        $db->setQuery ( $query );
		return $db->loadResult ();
	}
	
	public function getAttachments($memberId, $attachmentId = 0) {
		$db = JFactory::getDbo ();
		$userId = JFactory::getUser ()->id;
		
		$query = $db->getQuery ( true );
		
		$query->select('a.*, concat(u.name, " (", u.username, ")") as mod_user');
		$query->from($db->quoteName ( '#__md_member_attachment', 'a' ));
		$query->join('INNER', $db->quoteName ( '#__md_member', 'm' ) . ' ON (a.member_id = m.id)' );
		$query->join('INNER', $db->quoteName ( '#__md_tower', 't' ) . ' ON (m.tower_id = t.id)' );
		$query->join('INNER', $db->quoteName ( '#__users', 'u' ) . ' ON (a.mod_user_id = u.id)' );
		QueryHelper::addDistrictJoin($db, $query);
		
		if ($memberId) {
			$query->where('a.member_id = ' . (int) $memberId);
		} elseif ($attachmentId) {
			$query->where('a.id = ' . (int) $attachmentId);
		} else {
			$this->setError("Must specifiy a memberId or attachmentId when retrieving MemberAttachment details");
			return -1;
		}
		
		$query = QueryHelper::addDataPermissionConstraints($db, $query);
		
		$db->setQuery ( $query );
		$results = $db->loadObjectList ();;
		
		return $results;
	}
	
	public function getHistory($memberId) {
		$db = JFactory::getDbo ();
		$userId = JFactory::getUser ()->id;
		
		$query = $db->getQuery ( true );
		
		$query_string = "(select null as history_id, #__md_member.* 
		from #__md_member 
		where id = " . (int) $memberId . 
		" UNION ALL 
		select #__md_member_history.* 
		from #__md_member_history 
		where id = " . (int) $memberId . " 
		) mh";
		
		$query->select ( 'mh.*, concat_ws(", ", t.place, t.designation) as tower, mt.name as member_type, IFNULL(u.name, memt.email) as mod_user' );
		$query->from ( $query_string );
		$query->join ( 'LEFT', $db->quoteName ( '#__md_tower', 't' ) . 'ON (mh.tower_id = t.id)' );
		$query->join ( 'LEFT', $db->quoteName ( '#__md_member_type', 'mt' ) . 'ON (mh.member_type_id = mt.id)' );
		$query->join ( 'LEFT', $db->quoteName ( '#__users', 'u' ) . 'ON (mh.mod_user_id = u.id)' );
		$query->join ( 'LEFT', $db->quoteName ( '#__md_member_token', 'memt' ) . 'ON (ABS(mh.mod_user_id) = memt.id)' );
		
		/*
		 * $query->select ( $query_string );
		 */
		if (! JFactory::getUser ()->authorise ( 'member.view', 'com_memberdatabase' )) {
			$query->join ( 'INNER', $db->quoteName ( '#__md_usertower', 'ut' ) . ' ON (mh.tower_id = ut.tower_id)' );
			$query->where ( 'ut.user_id = ' . ( int ) $userId );
		}
		
		$query->order("mod_date DESC");
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		return $results;
	}
	public function markAsVerified($memberId) {
		$db = JFactory::getDbo ();
		$userId = JFactory::getUser ()->id;
		$currentDate = date ( 'Y-m-d H:i:s' );
		
		// Create a new query object.
		$query = $db->getQuery ( true );
		
		// Insert columns.
		$columns = array (
				'member_id',
				'user_id',
				'verified_date' 
		);
		
		// Insert values.
		$values = array (
				(int) $memberId,
				$userId,
				$db->quote ( $currentDate ) 
		);
		
		// Prepare the insert query.
		$query->insert ( $db->quoteName ( '#__md_member_verified' ) )->columns ( $db->quoteName ( $columns ) )->values ( implode ( ',', $values ) );
		
		// Set the query using our newly populated query object and execute it.
		$db->setQuery ( $query );
		$result = $db->execute ();
		
		error_log ( "Result from executing query to markAsVerified: " . serialize ( $result ) );
		
		return $result;
	}
	public function addAttachment($memberId, $filename, $type, $description, $tmp_name) {
		$db = JFactory::getDbo ();
		$userId = JFactory::getUser ()->id;
		$currentDate = date ( 'Y-m-d H:i:s' );
		
		$filename = JFile::makeSafe($filename);
		
		// Create a new query object.
		$query = $db->getQuery ( true );
		
		// Insert columns.
		$columns = array (
				'member_id',
				'name',
				'type',
				'description',
				'mod_user_id',
				'mod_date' 
		);
		
		// Insert values.
		// $values = array($memberId, $filename, $type, mysql_real_escape_string(file_get_contents($tmp_name)) ,$userId, $db->quote($currentDate) );
		$values = array (
				(int) $memberId,
				$db->quote ( $filename ),
				$db->quote ( $type ),
				$db->quote( $description ),
				$userId,
				$db->quote ( $currentDate ) 
		);
		
		// Prepare the insert query.
		$query->insert ( $db->quoteName ( '#__md_member_attachment' ) )->columns ( $db->quoteName ( $columns ) )->values ( implode ( ',', $values ) );
		
		// Set the query using our newly populated query object and execute it.
		$db->setQuery ( $query );
		$result = $db->query ();
		
		$key = $db->insertid ();
		
		if ($key) {
			$attachment_location = JComponentHelper::getParams ( 'com_memberdatabase' )->get ( 'attachment_location' );
			$dest = $attachment_location . "/" . $key;
			
			error_log ( "member.addAtachment: copying file from $tmp_name to $dest" );
			
			if (!JFile::copy ( $tmp_name, $dest )) {
				
				// If the file copy was not successful then remove the record from the db table
				$query = $db->getQuery ( true );
				$query->delete ( $db->quoteName ( '#__md_member_attachment' ) );
				$query->where ("id = $key");
				$db->query ();
				
				return false;
			}
		}
		
		error_log ( "Result from executing query to addAtachment: " . $result );
		
		return $result;
	}
	
	public function getLookups($id) {
		$db = JFactory::getDbo ();

		// Create a new query object.
		$query = $db->getQuery ( true );

		$query->select("mt.name as member_type_id, 
						concat_ws(', ', place, designation) as tower_id,
						concat(u.name, ' (', u.username, ')') as mod_user_id, 
						CASE m.annual_report when 1 then 'Y' else 'N' end as annual_report,
						CASE m.district_newsletters when 0 then 'Own District' when 1 then 'All Districts' end as district_newsletters");
		$query->from("#__md_member m");
		$query->join("LEFT", "#__md_member_type mt on (mt.id = m.member_type_id)");
		$query->join("LEFT", "#__md_tower t on (t.id = m.tower_id)");
		$query->join("LEFT", "#__users u on (u.id = m.mod_user_id)");
		$query->where("m.id = " . (int) $id);
		$db->setQuery($query);
		
		return $db->loadAssoc();
	}
	
	public function getCurrentUserId() {
		
		$jinput = JFactory::getApplication ()->input;
		$token = $jinput->get ( 'token', null, 'ALNUM' );
		$token_text = "";
		$user_editing = false;
		
		if (isset ( $token )) {
			$db = JFactory::getDbo ();
			$query = $db->getQuery(true);
			
			$query->select('id as id')
			->from("#__md_member_token memt")
			->where("memt.hash_token = " . $db->quote($token));
			
			$db->setQuery ( $query );
			$id = $db->loadResult ();
			
			return $id * -1;
			
		} else {
			return $this->mod_user_id = JFactory::getUser ()->id;
		}
	}
	
	
	
}
