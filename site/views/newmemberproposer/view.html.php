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

JLoader::import('ModelHelper', JPATH_COMPONENT . "/helpers/");
 
/**
 * User Tower View
 *
 * @since  0.0.1
 */
class MemberDatabaseViewNewMemberProposer extends JViewLegacy
{
	/**
	 * View form
	 *
	 * @var         form
	 */
	protected $form = null;
	
	public function __construct($config = array()) {
	    parent::__construct ( $config );
	    
	    $model = ModelHelper::loadModel ( "NewMember", "MemberDatabaseModel" );
	    $this->setModel ( $model, false );
	}
 
	/**
	 * Display the UserTower view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		// Get the Data
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->newmember = $this->getModel("NewMember")->getItem($this->item->newmember_id);
		
		error_log("newmemberproposer form data: " . json_encode((array) $this->item));
 
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
 
			return false;
		}
 
		// Display the template
		parent::display($tpl);
	}
 
}
