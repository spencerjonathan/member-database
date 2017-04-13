<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Form Rule class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       3.5
 */
class JFormRuleTowerid extends JFormRule
{
	/**
	 * Method to test the range for a number value using min and max attributes.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 * @param   JRegistry         $input    An optional JRegistry object with the entire data set to validate against the entire form.
	 * @param   JForm             $form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 *
	 * @since   3.5
	 */
	public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
	{
		
		if (JFactory::getUser ()->authorise ( 'core.manage', 'com_memberdatabase' )) {
			return true;
		}
		
		$towerId = (int) $value;

		$userId = JFactory::getUser()->id;

		error_log("value of \$user in test:" . json_encode(JFactory::getUser()), 0);

		$db = JFactory::getDbo();

		// Build the database query to get the rules for the asset.
		$query = $db->getQuery(true)
		->select('count(*)')
		->from($db->quoteName('#__md_usertower', 'ut'))
		->where('ut.user_id = ' . (int) $userId . ' and ut.tower_id = ' . (int) $towerId);

		// Execute the query and load the rules from the result.
		$db->setQuery($query);
		$result = $db->loadResult();

		if ($result > 0) {
		        return true;
		};

		error_log("User with id " . $userId . " does not have authorisation to save member with tower_id " . $towerId, 0);
		return false;

	}
}
