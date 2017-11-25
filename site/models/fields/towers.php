<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

// Added to enable loading of administrator model from site
JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_memberdatabase/models');

/**
 * Form Field class for the Joomla Platform.
 * Supports a generic list of options.
 *
 * @since  11.1
 */
class JFormFieldTowers extends JFormFieldList
{

	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Towers';
	
	protected function getTowers()
	{

		$model = $this->createModel ( "Towers", "MemberDatabaseModel" );
		
		return $model->getTowers();
	}
	
	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$options = array();

		foreach ($this->getTowers() as $tower)
		{
			// Filter requirements

			$value = (integer) $tower->id;
			$text = trim((string) $tower->tower);

			$tmp = array(
					'value'    => $value,
					'text'     => $text
				);

			// Add the option object to the result set.
			$options[] = (object) $tmp;
		}

		reset($options);

		return $options;
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
