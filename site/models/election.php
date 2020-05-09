<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::import('EmailHelper', JPATH_COMPONENT . "/helpers/");

/**
 * Users mail model.
 *
 * @since  1.6
 */
class MemberDatabaseModelElection extends JModelAdmin
{

    public function hasVoteBeenSubmitted($member_id) {
        $db = JFactory::getDbo ();
            
        $query = $db->getQuery ( true );

        // Get the list of member ids.
        $query->select ( 'count(*)' );
        $query->from ( $db->quoteName ( '#__md_election', 'e' ) );
        $query->where ( 'e.member_id = ' . $member_id);
        
        $db->setQuery($query);
        
        if ($db->loadResult() > 0) {
            return true;
        } 
            
        return false;

    }

    public function getItem($pk = null) {
        $item = parent::getItem($pk);
        
        $jinput = JFactory::getApplication()->input;
		$token = $jinput->get('token', null, 'ALNUM');

        $table = JTable::getInstance ( "Electiontoken", "MemberDatabaseTable", array() );

		if (!$table->load($token) || !$table->member_id) {
			error_log("Could not load electiontoken for token " . $token);
			$this->setError("Could not locate your record!");
			return false;
		}
		
		error_log("getForm - table contents: " . json_encode((array) $table));

		return $table;
    }


    public function sendelectionemails() {
        
            $db = JFactory::getDbo ();
            
            $query = $db->getQuery ( true );

            // Get the list of member ids.
            $query->select ( 'm.id, m.email, m.forenames' );
            $query->from ( $db->quoteName ( '#__md_member', 'm' ) );
            $query->join ( 'LEFT', $db->quoteName ( '#__md_member_type', 'mt' ) . ' ON (' . $db->quoteName ( 'm.member_type_id' ) . ' = ' . $db->quoteName ( 'mt.id' ) . ')' );
            $query->where ( 'm.id not in (select member_id from #__md_election_token)');
            $query->where ( 'mt.include_in_reports = 1');
            $query->where ( 'm.email is not null');
            $query->where ( 'trim(m.email) != ""');
            //$query->where ( 'm.id in (22, 78, 261, 324, 450, 541, 871, 1209, 1512 )');
            
            $query->setLimit(200);
            
            $db->setQuery($query);
            $members = $db->loadObjectList ();
            
            foreach ($members as $member) {
          
                $token = JApplicationHelper::getHash(JUserHelper::genRandomPassword());

                // Create a new query object.
                $query = $db->getQuery(true);

                // Insert columns.
                $columns = array(
                    'token',
                    'member_id'
                );

                // Insert values.
                $values = array(
                    $db->quote($token),
                    $member->id
                );

                // Prepare the insert query.
                $query->insert($db->quoteName('#__md_election_token'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));

                error_log("Query = " . $query->__toString());

                // Set the query using our newly populated query object and execute it.
                $db->setQuery($query);
                $result = $db->execute();
                
                $this->sendEmail($member, $token);   
            }
            
            error_log("Sent " . count($members) . " election emails");
            
            return count($members);
        
    }
    
    public function sendEmail($member, $token) {
        
        $link = 'index.php?option=com_memberdatabase&view=election&token=' . $token;

        $config = JFactory::getConfig();
        $mode = $config->get('force_ssl', 0) == 2 ? 1 : (- 1);
        $link_text = JRoute::_($link, false, $mode);
        
        $body = JText::sprintf("Dear %s,\n\nYou will have recently received an email from the Master inviting you to the 2020 SCACR AGM.  In his email he referred to the opportunity to vote in the elections of officers.  This email contains your personal link to your voting form.\n\nPlease take this opportunity to vote in the 2020 SCACR AGM.  It's your opportunity to provide your view on the suitability of the nominated candates.  If you don't vote, you'll have had no say over who will be making decisions affecting the association.\n\n%s\n\nKind regards,\nHamish", $member->forenames, $link_text);

        $subject = 'Online Voting Link';
        
        error_log("Sending election email to " . $member->email);

        $send = EmailHelper::sendEmail($member->email, $subject, $body, false, "SCACR AGM 2020", "secretary@scacr.org", "SCACR Secretary");
        if ($send !== true) {
            $this->setError(JText::sprintf('Could not send email to %s', $email), 500);
            return false;
        }
        
        return true;
    }

	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm	A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_memberdatabase.election', 'election', array('control' => 'jform', 'load_data' => $loadData));

		error_log("In Election getForm");

		if (empty($form))
		{
			return false;
		}

        /*
		$jinput = JFactory::getApplication()->input;
		$token = $jinput->get('token', null, 'ALNUM');

                //JTable::addIncludePath ( JPATH_ADMINISTRATOR . '/components/com_memberdatabase/tables' );
                $table = JTable::getInstance ( "Electiontoken", "MemberDatabaseTable", array() );

		if (!$table->load($token) || !$table->member_id) {
			error_log("Could not load electiontoken for token " . $token);
			$this->setError("Could not locate your record!");
			//return false;
		}
		
		error_log("getForm - table contents: " . json_encode((array) $table));

		$form->setValue("member_id", null, $table->member_id);
		error_log("getForm " . json_encode((array) $form));
		*/

		return $form;
	}

	public function getTable($type = 'Election', $prefix = 'MemberDatabaseTable', $config = array()) {
		//JTable::addIncludePath ( JPATH_ADMINISTRATOR . '/components/com_memberdatabase/tables' );
		return JTable::getInstance ( $type, $prefix, $config );
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{

        $id = (int) $this->getState($this->getName() . '.id');
        error_log("loadForm - id = " . $id);

        if ($id > 0) {
    		$data = $this->getItem ();
        } else {
            $data = JFactory::getApplication()->getUserState('com_memberdatabase.display.election.data', array());
        }

        error_log("Election loadFormData: " . json_encode((array) $data));

		return $data;
	}

	/**
	 * Method to preprocess the form
	 *
	 * @param   JForm   $form   A form object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @since   1.6
	 * @throws  Exception if there is an error loading the form.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'user')
	{
		parent::preprocessForm($form, $data, $group);
	}

	public function store($data)
	{
		$table = $this->getTable();
		$table->bind($data);
		$table->store();
	}

}
