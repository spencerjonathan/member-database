<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

if ($this->item->id) {
	$task = "newmember.edit";
} else {
	$task = "newmember.add";
}

$jinput = JFactory::getApplication ()->input;

?>
You're on the default page for newmember
