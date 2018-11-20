<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$script = "\t" . 'Joomla.submitbutton = function(pressbutton) {' . "\n";
$script .= "\t\t" . 'var form = document.adminForm;' . "\n";
$script .= "\t\t" . 'if (pressbutton == \'mail.cancel\') {' . "\n";
$script .= "\t\t\t" . 'Joomla.submitform(pressbutton);' . "\n";
$script .= "\t\t\t" . 'return;' . "\n";
$script .= "\t\t" . '}' . "\n";
$script .= "\t\t" . '// do field validation' . "\n";
$script .= "\t\t" . 'if (form.jform_subject.value == ""){' . "\n";
$script .= "\t\t\t" . 'alert("Please fill in the subject");' . "\n";
$script .= "\t\t" . '} else if (getSelectedValue(\'adminForm\',\'jform[tower_id]\') == ""){' . "\n";
$script .= "\t\t\t" . 'alert("Please select a tower to contact");' . "\n";
$script .= "\t\t" . '} else if (form.jform_reply_to_email.value == ""){' . "\n";
$script .= "\t\t\t" . 'alert("Please provide your email address");' . "\n";
$script .= "\t\t" . '} else if (form.jform_reply_to_name.value == ""){' . "\n";
$script .= "\t\t\t" . 'alert("Please fill in your name");' . "\n";
$script .= "\t\t" . '} else if (form.jform_message.value == ""){' . "\n";
$script .= "\t\t\t" . 'alert("Please fill in the message");' . "\n";
$script .= "\t\t" . '} else {' . "\n";
$script .= "\t\t\t" . 'Joomla.submitform(pressbutton);' . "\n";
$script .= "\t\t" . '}' . "\n";
$script .= "\t\t" . '}' . "\n";

JHtml::_('behavior.core');
JHtml::_('formbehavior.chosen', 'select');

JFactory::getDocument()->addScriptDeclaration($script);
?>

<legend>Contact A SCACR Tower</legend>

	<button onclick="Joomla.submitbutton('mail.send')" id="send_button"
		class="btn btn-small btn-success">
		<span class="icon-mail"></span> Send
	</button>
	
	<button onclick="Joomla.submitbutton('mail.cancel')"
		class="btn btn-small">
		<span class="icon-cancel"></span> Cancel
	</button>
<hr>
<form action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=mail'); ?>" name="adminForm" method="post" id="adminForm">
	<div class="row-fluid">
		<div class="span12">
			<fieldset class="adminform">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('tower_id'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('tower_id'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('reply_to_email'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('reply_to_email'); ?></div>
				</div>
                <div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('reply_to_name'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('reply_to_name'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('subject'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('subject'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('message'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('message'); ?></div>
				</div>
                <?php if ($this->captchaEnabled): ?>
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
