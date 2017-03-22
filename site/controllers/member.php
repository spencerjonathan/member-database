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
 * HelloWorld Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 * @since       0.0.9
 */
class MemberDatabaseControllerMember extends JControllerForm
{
        protected function allowEdit($data = array(), $key = 'id')
        {
		$userId = JFactory::getUser()->id;

				//$db = JFactory::getDbo();

                                // Build the database query to get the rules for the asset.
                                //$query = $db->getQuery(true)
                                        //->select('count(*)')
                                        //->from('#__md_authorised_towers')
                                        //->where('user_id = ' . (int) $userId . ' and tower_id = ' . (int) $towerId);

                                // Execute the query and load the rules from the result.
                                //$db->setQuery($query);
                                //$result = $db->loadColumn();
		return true;
        }

        protected function allowSave($data = array(), $key = 'id')
	{
		return true;
	}

        protected function allowAdd($data = array(), $key = 'id')
	{
		return true;
	}
}
