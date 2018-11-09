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
$script .= "\t\t\t" . 'alert("' . JText::_('COM_USERS_MAIL_PLEASE_FILL_IN_THE_SUBJECT', true) . '");' . "\n";
$script .= "\t\t" . '} else if (getSelectedValue(\'adminForm\',\'jform[group]\') < 0){' . "\n";
$script .= "\t\t\t" . 'alert("' . JText::_('COM_USERS_MAIL_PLEASE_SELECT_A_GROUP', true) . '");' . "\n";
$script .= "\t\t" . '} else if (form.jform_message.value == ""){' . "\n";
$script .= "\t\t\t" . 'alert("' . JText::_('COM_USERS_MAIL_PLEASE_FILL_IN_THE_MESSAGE', true) . '");' . "\n";
$script .= "\t\t" . '} else {' . "\n";
$script .= "\t\t\t" . 'Joomla.submitform(pressbutton);' . "\n";
$script .= "\t\t" . '}' . "\n";
$script .= "\t\t" . '}' . "\n";

JHtml::_('behavior.core');
JHtml::_('formbehavior.chosen', 'select');

JFactory::getDocument()->addScriptDeclaration($script);
?>

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
					<div class="control-label"><?php echo $this->form->getLabel('subject'); ?></div>
					<div class="controls"><?php echo JComponentHelper::getParams('com_memberdatabase')->get('mailSubjectPrefix'); ?>
						<?php echo $this->form->getInput('subject'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('message'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('message'); ?><br>
						<?php echo JComponentHelper::getParams('com_memberdatabase')->get('mailBodySuffix'); ?></div>
				</div>
				<div class="control-group">
					<div class="controls"><?php echo $this->form->getField('captcha')->renderField(); ?></div>
				</div>
			</fieldset>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>
