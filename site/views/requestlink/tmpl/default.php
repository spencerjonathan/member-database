<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

?>
<h1>Request Link</h1>
<form class="form-validate"
	action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=requestlink'); ?>"
	method="post" name="adminForm" id="adminForm">
	<div class="control-group">
		<div class="control-label">Enter your email address</div>
		<div class="controls">
			<input name="email" class="validate-email inputbox required"
				id="email" value="" size="40" type="email">
		</div>
		<button onclick="Joomla.submitbutton('members.requestlink'); alert('A link has been sent to your email address which will grant you access to your membership record.');"
			id="submit_button" class="btn btn-small btn-success">
			<span class="icon-mail"></span> Submit
		</button>
	</div>
	<input type="hidden" name="task" value="members.requestlink" />
</form>
