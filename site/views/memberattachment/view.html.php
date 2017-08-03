<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * MembersDatabase View
 *
 * @since 0.0.1
 */
class MemberDatabaseViewMemberAttachment extends JViewLegacy {
	
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
		parent::__construct ( $config );
		
		$model = $this->createModel ( "Member", "MemberDatabaseModel" );
		$this->setModel ( $model, false );
	}
	
	/**
	 * Display the Members view
	 *
	 * @param string $tpl
	 *        	The name of the template file to parse; automatically searches through the template paths.
	 *        	
	 * @return void
	 */
	function display($tpl = null) {
		
		$jinput = JFactory::getApplication ()->input;
		$attachmentId = $jinput->get ( 'attachmentId', 0, 'INT' );

		$this->attachment = $this->getModel("Member")->getAttachments(0, $attachmentId);
		
		if (count($this->attachment) != 1) {
			$this->setError("Cannot retrieve attachement details for attachment ID $attachmentId");
			return -1;
		}
		
		$this->attachment = $this->attachment[0];
		
		// Display the template
		parent::display ( $tpl );
		
	}
	
	/**
	 * Method to load and return a model object.
	 *
	 * @param string $name
	 *        	The name of the model.
	 * @param string $prefix
	 *        	Optional model prefix.
	 * @param array $config
	 *        	Configuration array for the model. Optional.
	 *        	
	 * @return JModelLegacy|boolean Model object on success; otherwise false on failure.
	 *        
	 * @since 12.2
	 */
	protected function createModel($name, $prefix = '', $config = array()) {
		// Clean the model name
		$modelName = preg_replace ( '/[^A-Z0-9_]/i', '', $name );
		$classPrefix = preg_replace ( '/[^A-Z0-9_]/i', '', $prefix );
		
		return JModelLegacy::getInstance ( $modelName, $classPrefix, $config );
	}
}
