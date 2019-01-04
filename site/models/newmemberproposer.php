<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * NewMemberProposer Model
 *
 */
class MemberDatabaseModelNewMemberProposer extends JModelAdmin
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
    public function getTable($type = 'NewMemberProposer', $prefix = 'MemberDatabaseTable', $config = array())
    {
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
        // Get the form.
        $form = $this->loadForm('com_memberdatabase.newmemberproposer', 'newmemberproposer', array(
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
     * @since 1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_memberdatabase.edit.newmemberproposer.data', array());

        if (empty($data)) {
            /*
             * $jinput = JFactory::getApplication()->input;
             * $token = $jinput->get('token', null, 'ALNUM');
             *
             * $pk = $this->getPK($token);
             * $data = $this->getItem($pk);
             */
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        if (! $pk) {
            $jinput = JFactory::getApplication()->input;
            $token = $jinput->get('token', null, 'ALNUM');

            $pk = $this->getPK($token);
        }

        return parent::getItem($pk);
    }

    protected function getPK($token)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('p.id')
            ->from($db->quoteName('#__md_new_member_proposer', 'p'))
            ->where('p.hash_token = ' . $db->quote($token));

        error_log("getPK Query: " . $query->__toString());

        $db->setQuery($query);
        return $db->loadResult();
    }
    
    public function promoteToMember()
    {
        $jinput = JFactory::getApplication()->input;
        $token = $jinput->get('token', null, 'ALNUM');
        
        $db = JFactory::getDbo();
        
        $query = $db->getQuery(true)
        ->select('count(*) c, p.newmember_id')
        ->from($db->quoteName('#__md_new_member_proposer', 'p'))
        ->innerJoin($db->quoteName('#__md_new_member_proposer', 'sub_p') . " on p.newmember_id = sub_p.newmember_id" )
        ->where('sub_p.hash_token = ' . $db->quote($token))
        ->where('p.approved_flag = 1')
        ->group($db->quoteName('p.newmember_id'));
        
        $db->setQuery($query);
        $row = $db->loadAssoc();
        
        if ($row['c'] != 2) // If both proposers have not approved yet, don't do anything else
        {
            return true;
        }
        
        $newmember = JTable::getInstance('NewMember', 'MemberDatabaseTable', array());

        $newmember->load($row['newmember_id']);
        unset($newmember->id);
        
        $newmember->mod_date = date('Y-m-d H:i:s');
        $newmember->date_elected = date('Y-m-d');
        $newmember->db_form_received = 1;
        
        $result = JFactory::getDbo()->insertObject('#__md_member', $newmember, 'primary_key');

        // Fields to update.
        $fields = array(
            $db->quoteName('member_id') . ' = ' . $newmember->primary_key
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('newmember_id') . " = " . $row['newmember_id'] 
        );

        $query = $db->getQuery(true)
        ->update($db->quoteName('#__md_new_member_proposer', 'p'))
        ->set($fields)->where($conditions);

        $db->setQuery($query);

        return $db->execute();
        
    }
}
