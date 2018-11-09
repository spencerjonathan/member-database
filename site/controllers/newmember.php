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

JLoader::import('EmailHelper', JPATH_COMPONENT . "/helpers/");

/**
 * Newmember Controller
 *
 * @package Joomla.Administrator
 * @subpackage com_memberdatabase
 * @since 0.0.9
 */
class MemberDatabaseControllerNewmember extends JControllerForm
{

    /**
     * Method to check if you can add a new record.
     *
     * Extended classes can override this if necessary.
     *
     * @param array $data
     *            An array of input data.
     *            
     * @return boolean
     *
     * @since 1.6
     */
    protected function allowAdd($data = array())
    {
        return true;
    }

    protected function allowEdit($data = array(), $key = 'id')
    {
        // Needs to be updated to check that valid token has been provided
        return false;
    }

    /* protected function postSaveHook(\JModelLegacy $model, $validData = array())
    {
        error_log("In Newmember::postSaveHook");

        error_log("data = " . json_encode($validData));

        // $email = $this->input->get ( 'email', '', 'STRING');

        // $model = $this->getModel();
        $model->generateAndSendLink($validData['email']);

        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=newmember&layout=default&email=' . $validData['email'], false));
    } */

    public function saveinitial($key = null, $urlVar = null)
    {
        error_log("In Newmember::saveinitial");
        
        $model = $this->getModel();
        
        $validData = $model->checkEmailAddressNotAlreadyInUse($form, $data);
        
        if ($validData === false)
        {
            // Get the validation messages.
            $this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');
            
            $app   = \JFactory::getApplication();
            
            // Save the data in the session.
            $app->setUserState($context . '.data', $data);
            
            // Redirect back to the edit screen.
            $this->setRedirect(
                \JRoute::_(
                    'index.php?option=' . $this->option . '&view=' . $this->view_item . "&layout=edit", false
                    )
                );
            
            return false;
        }
        
        if( !parent::save($key, $urlVar) ) {
            $this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');
            
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=newmember&layout=edit', false));
            return false;
        }
        
        error_log("data = " . json_encode($validData));
        
        $data = $this->input->post->get('jform', array(), 'array');
        
        $model->generateAndSendLink($data['email']);
        
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=newmember&layout=default&email=' . $data['email'], false));
    }
    
    public function savemain($key = null, $urlVar = null)
    {
        error_log("In Newmember::saveinitermediate");
        
        parent::save($key, $urlVar);
        
        //$data = $this->input->post->get('jform', array(), 'array');
        
        $id = $this->input->post->getInt('id');
        $token = $this->input->get('token', null, "ALNUM");
        
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=newmember&layout=edit&token=' . $token . '&stage=final', false));
    }
    
    public function savefinal($key = null, $urlVar = null)
    {
        error_log("In Newmember::savefinal");
        
        $data  = $this->input->post->get('jform', array(), 'array');
        
        $model = $this->getModel();
        
        $form = $model->getForm($data, false);
        $validData = $model->validateEmailAddresses($form, $data);
        
        $context = "$this->option.edit.$this->context";
        
        $jinput = JFactory::getApplication ()->input;
        $token = $jinput->get ( 'token', null, 'ALNUM' );
        
        $pk = $model->getPK($token);
        
        error_log("this->input = " . json_encode($this->input));
        error_log("jinput = " . json_encode((array) $jinput));
        error_log("data = " . json_encode($data));
        
        if ($validData === false)
        {
            // Get the validation messages.
            $this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');
            
            $app   = \JFactory::getApplication();
            
            // Save the data in the session.
            $app->setUserState($context . '.data', $data);
            
            // Redirect back to the edit screen.
            $this->setRedirect(
                \JRoute::_(
                    'index.php?option=' . $this->option . '&view=' . $this->view_item . "&stage=final&layout=edit&token=" . $token, false
                    )
                );
            
            return false;
        }
        
        $model->saveProposers($validData, $pk);
        //$data = $this->input->post->get('jform', array(), 'array');
        
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=newmember&layout=default', false));
    }
}