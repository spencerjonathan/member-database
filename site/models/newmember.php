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

// jimport ('joomla.filesystem.file');

// JLoader::import('QueryHelper', JPATH_COMPONENT . "/helpers/");

/**
 * Newmember Model
 *
 * @since 0.0.1
 */
class MemberDatabaseModelNewmember extends JModelAdmin
{

    /**
     * Method to get a table object, load it if necessary.
     *
     * @param string $type
     *            The table name. Optional.
     * @param string $prefix
     *            The class prefix. Optional.
     * @param array $config
     *            Configuration array for model. Optional.
     *            
     * @return JTable A JTable object
     *        
     * @since 1.6
     */
    public function getTable($type = 'Newmember', $prefix = 'MemberDatabaseTable', $config = array())
    {
        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_memberdatabase/tables');
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param array $data
     *            Data for the form.
     * @param boolean $loadData
     *            True if the form is to load its own data (default case), false if not.
     *            
     * @return mixed A JForm object on success, false on failure
     *        
     * @since 1.6
     */
    public function getForm($data = array(), $loadData = true)
    {

        $form_name = '';
        $form_file = '';
        
        // Check if user is privilaged to view the newmember
   		if (JFactory::getUser()->authorise ( 'core.admin', 'com_memberdatabase' )) {
            $form_name = 'com_memberdatabase.newmember';    
            $form_file = 'newmember';
        } else {
            // Must be someone registering so get the appropriate form for the
            // stage of their application

            $jinput = JFactory::getApplication()->input;

            $stage = $jinput->get('stage', "initial", 'ALNUM');

            $form_name = 'com_memberdatabase.newmember_' . $stage;
            $form_file = 'newmember_' . $stage;
        }

        $form = $this->loadForm($form_name, $form_file, array(
            'control' => 'jform',
            'load_data' => $loadData
        ));

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return mixed The data for the form.
     *        
     * @since 0.1.288
     *       
     */
    protected function loadFormData()
    {
        
        error_log("In ModelNewmember::loadFormData");
        
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_memberdatabase.edit.newmember.data', array());

        if (empty($data)) {
            $jinput = JFactory::getApplication()->input;
            $token = $jinput->get('token', null, 'ALNUM');

            $pk = $this->getPK($token);

            $data = $this->getItem($pk);
        }

        return $data;
    }

    public function getPK($token)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('max(m.id)')   // Ensure that we only get one record (should only get one anyway)
            ->from($db->quoteName('#__md_new_member', 'm'))
            ->join('INNER', $db->quoteName('#__md_member_token', 'memt') . ' ON (' . $db->quoteName('m.email') . ' = ' . $db->quoteName('memt.email') . ')')
            ->where('memt.hash_token = ' . $db->quote($token));

        error_log("getPK Query: " . $query->__toString());

        $db->setQuery($query);
        return $db->loadResult();
    }

    public function saveProposers($data, $pk)
    {
        error_log("In newmember::saveProposers().  Data = " . json_encode($data));

        $db = JFactory::getDbo();
        $currentDate = date('Y-m-d H:i:s');

        foreach ([
            'proposer_email',
            'seconder_email'
        ] as $proposer) {

            $token = JApplicationHelper::getHash(JUserHelper::genRandomPassword());

            // Create a new query object.
            $query = $db->getQuery(true);

            // Insert columns.
            $columns = array(
                'newmember_id',
                'email',
                'hash_token',
                'created_date'
            );

            $email = $data[$proposer];

            // Insert values.
            $values = array(
                (int) $pk,
                $db->quote($email),
                $db->quote($token),
                $db->quote($currentDate)
            );

            // Prepare the insert query.
            $query->insert($db->quoteName('#__md_new_member_proposer'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));

            error_log("Query = " . $query->__toString());

            // Set the query using our newly populated query object and execute it.
            $db->setQuery($query);
            $result = $db->execute();

            $this->generateAndSendLinkToProposer($email, $pk, $token);
        }
    }

    public function generateAndSendLink($email)
    {

        // Set the confirmation token.
        $token = JApplicationHelper::getHash(JUserHelper::genRandomPassword());

        // 2) store the hash and email address
        if (! EmailHelper::storeToken($email, $token)) {
            $this->setError(JText::sprintf('Could not store unique token'), 500);
            return false;
        }

        // 3) send the email
        $link = 'index.php?option=com_memberdatabase&view=newmember&layout=edit&stage=main&token=' . $token;

        $config = JFactory::getConfig();
        $mode = $config->get('force_ssl', 0) == 2 ? 1 : (- 1);
        $link_text = JRoute::_($link, false, $mode);
        $body = JText::sprintf('Use this link %s to access your SCACR membership account record', $link_text);

        $subject = 'Link To Your Membership Record';

        $send = EmailHelper::sendEmail($email, $subject, $body);
        if ($send !== true) {
            $this->setError(JText::sprintf('Could not send email to %s', $email), 500);
            return false;
        }

        return true;
    }

    public function generateAndSendLinkToProposer($email, $pk, $token)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('m.forenames, m.surname, m.email, concat_ws(", ", t.place, t.designation) as tower')
            ->from($db->quoteName('#__md_new_member', 'm'))
            ->join('INNER', $db->quoteName('#__md_tower', 't') . ' ON (' . $db->quoteName('m.tower_id') . ' = ' . $db->quoteName('t.id') . ')')
            ->where('m.id = ' . $db->quote($pk));

        $db->setQuery($query);
        $member = $db->loadAssoc();

        $link = 'index.php?option=com_memberdatabase&view=newmemberproposer&token=' . $token . '&id=' . $pk;

        $config = JFactory::getConfig();
        $mode = $config->get('force_ssl', 0) == 2 ? 1 : (- 1);
        $link_text = JRoute::_($link, false, $mode);
        error_log("Nominator Link: " . $link_text);

        $body = JText::sprintf('%s %s has requested membership to SCACR and has given your name as proposer.' . 
            '  Please use this link %s to either confirm or decline the request.', $member['forenames'], $member['surname'], $link_text);

        $subject = 'New member has given your name as proposer';

        $send = EmailHelper::sendEmail($email, $subject, $body);
        if ($send !== true) {
            $this->setError(JText::sprintf('Could not send email to %s', $email), 500);
            return false;
        }

        return true;
    }
    
    public function validateEmailAddresses($form, $data, $group = null) {
        if (!parent::validate($form, $data, $group)) return false;
        
        if (strtolower(trim($data['proposer_email'])) == strtolower(trim($data['seconder_email']))) {
            $this->setError("Proposer and Seconder email addresses must be different!");
            return false;
        }
        
        foreach ([
            'proposer_email',
            'seconder_email'
        ] as $proposer) {
            
            $db = JFactory::getDbo();
            
            $query = $db->getQuery(true)
            ->select('count(*)')
            ->from($db->quoteName('#__md_member', 'm'))
            ->join ( 'INNER', $db->quoteName ( '#__md_member_type', 'mt' ) . ' ON (' . $db->quoteName ( 'm.member_type_id' ) . ' = ' . $db->quoteName ( 'mt.id' ) . ')' )
            ->where('lower(trim(m.email)) = ' . $db->quote(strtolower(trim($data[$proposer]))))
            ->where('mt.include_in_reports = 1');
            
            $db->setQuery($query);
            if (!$db->loadResult()) {
                $this->setError("No current members have email address " . $data[$proposer]);
                return false;
            }
            
        }
        
        return $data;
    }
    
    public function checkEmailAddressNotAlreadyInUse($form, $data) {
        $db = JFactory::getDbo();
        
        $query = $db->getQuery(true)
        ->select('count(*)')
        ->from($db->quoteName('#__md_member', 'm'))
        ->where('m.email = ' . $db->quote($data['email']));
        
        $db->setQuery($query);
        if ($db->loadResult()) {
            $this->setError("A member with this email address already exists " . $data["email"]);
            return false;
        }
        
        $query = $db->getQuery(true)
        ->select('count(*)')
        ->from($db->quoteName('#__md_new_member', 'm'))
        ->where('m.email = ' . $db->quote($data['email']));
        
        $db->setQuery($query);
        if ($db->loadResult()) {
            $this->setError("A membership application request has already been submitted using this email address " . $data["email"]);
            return false;
        }
        
        return $data;
        
    }

    public function hasApplicationBeenSubmitted($token) {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
        ->select('count(*)')
        ->from($db->quoteName('#__md_member_token', 'mt'))
        ->join('INNER', $db->quoteName ( '#__md_new_member', 'nm' ) . ' ON (nm.email = mt.email)')
        ->join('INNER', $db->quoteName ( '#__md_new_member_proposer', 'nmp' ) . ' ON (nmp.newmember_id = nm.id)')
        ->where('mt.hash_token = ' . $db->quote($token));
        
        $db->setQuery($query);
        return ($db->loadResult() > 0 );

    }
    
    
}
