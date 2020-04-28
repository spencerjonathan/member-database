<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.core');
JHtml::_('formbehavior.chosen', 'select');

//JFactory::getDocument()->addScriptDeclaration($script);
?>

<legend>Annual Elections</legend>

	<button onclick="Joomla.submitbutton('election.submit')" id="submit_button"
		class="btn btn-small btn-success">
		<span class="icon-mail"></span> Send
	</button>
	
	<button onclick="Joomla.submitbutton('election.cancel')"
		class="btn btn-small">
		<span class="icon-cancel"></span> Cancel
	</button>
<hr>
<form action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=election'); ?>" name="adminForm" method="post" id="adminForm">
	<div class="row-fluid">
		<div class="span12">
			<fieldset class="adminform">
				<table width="100%">
				<tr>
					<td><div class="control-label"><?php echo $this->form->getLabel('master_response'); ?></div></td>
					<td>Rob Lane</td>
					<td><div class="controls"><?php echo $this->form->getInput('master_response'); ?></div></td>
				</tr>
				<tr>
					<td><div class="control-label"><?php echo $this->form->getLabel('secretary_response'); ?></div></td>
					<td>Hamish McNorton</td>
					<td><div class="controls"><?php echo $this->form->getInput('secretary_response'); ?></div></td>
				</tr>
				</table>
                <?php if ($this->form->getField('captcha')) : ?>
				<div class="control-group">
					<div class="controls"><?php echo $this->form->getField('captcha')->renderField(); ?></div>
				</div>
                <?php endif; ?>
			</fieldset>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>
