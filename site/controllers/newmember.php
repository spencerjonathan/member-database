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
 * Newmember Controller
 *
 * @package Joomla.Administrator
 * @subpackage com_memberdatabase
 * @since 0.0.9
 */
class MemberDatabaseControllerNewmember extends JControllerForm {

    public function add($key = null, $urlVar = null) {
        error_log ( "In Newmember add controller" );
        parent::add($key, $urlVar);
        $this->setRedirect ( JRoute::_ ( 'index.php?option=' . $this->option . '&view=newmember&id=' . $recordId . $token_text, false ) );
    }
    
}