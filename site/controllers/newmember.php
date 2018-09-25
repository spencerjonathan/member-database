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

JLoader::import('EmailHelper', JPATH_COMPONENT . "/helpers/");

/**
 * Newmember Controller
 *
 * @package Joomla.Administrator
 * @subpackage com_memberdatabase
 * @since 0.0.9
 */
class MemberDatabaseControllerNewmember extends JControllerForm {

    public function add($key = null, $urlVar = null) {
        error_log ( "In Newmember add controller" );
        if (!parent::add($key, $urlVar)) return false;
        
        error_log("input = " . json_encode($this->input));
        
        $email = $this->input->get ( 'email', '', 'STRING');
        
        $model = $this->getModel();
        $model->generateAndSendLink($email);
        
        $this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=newmember&layout=default&email=' . $email, false ) );
        
        return true;
    }
    
    /**
     * Method to check if you can add a new record.
     *
     * Extended classes can override this if necessary.
     *
     * @param   array  $data  An array of input data.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowAdd($data = array())
    {
        return true;
    }
    
    public function save($key = null, $urlVar = null) {
        
        error_log ( "In Newmember save controller" );
        if (!parent::save($key, $urlVar)) return false;
        
        error_log("input = " . json_encode($this->input));
        
        $email = $this->input->get ( 'email', '', 'STRING');
        
        $model = $this->getModel();
        $model->generateAndSendLink($email);
        
        $this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=newmember&layout=default&email=' . $email, false ) );
        
        return true;
    }
}