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
<h1>Request Access To Your Personal Data</h1>
To view/modify your membership record enter your email address in the box below and click submit.  An email will be sent to you containing a link which will give you access to your membership record.  The link will be enabled for 5 days.<br><br>
<form class="form-validate"
	action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=requestlink'); ?>"
	method="post" name="adminForm" id="adminForm">
	<div class="control-group">
		<div class="control-label">Please enter your email address:</div>
		<div class="controls">
			<input name="email" class="validate-email inputbox required"
				id="email" value="" size="40" type="email">
		</div>
		<button type="submit" onclick="alert('A link has been sent to your email address which will grant you access to your membership record.');"
			id="submit_button" class="btn btn-small btn-success btn-submit-request">
			<span class="icon-mail"></span> Submit
		</button>
	</div>
	<input type="hidden" name="task" value="members.requestlink" />
	<?php echo JHtml::_('form.token'); ?>
</form>
