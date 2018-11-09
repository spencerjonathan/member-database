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
 * HelloWorld Model
 *
 * @since 0.0.1
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
}