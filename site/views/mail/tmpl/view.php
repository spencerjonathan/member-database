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

// Check that user is privilaged to view this form
if (!(JFactory::getUser()->authorise ( 'mail.view', 'com_memberdatabase' ))) {
    $app = JFactory::getApplication();
    $app->redirect(JUri::root(true));
}

?>

<legend>Message Sent To A Tower</legend>
	
	<button onclick="Joomla.submitbutton('mail.cancel')"
		class="btn btn-small">
		<span class="icon-cancel"></span> Cancel
	</button>
<hr>

<form action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=mail'); ?>" name="adminForm" method="post" id="adminForm">
	<div class="row-fluid">
		<div class="span12">
			<fieldset class="adminform">
            <?php $fieldset = $this->form->getFieldset ( "view" );  
                foreach ( $fieldset as $field ) : ?>
                    <div class="control-group">
				        <div class="control-label"><?php echo $field->label; ?></div>
					    <div class="controls"><?php echo $field->input; ?></div>
					</div>
                <?php endforeach; ?>
			</fieldset>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>
