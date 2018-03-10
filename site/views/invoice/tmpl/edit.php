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

?>

<legend><?php echo JText::_('Member Database - Invoice Details'); ?></legend>


<!-- Add the toolbar at the top  -->




<!-- The form itself -->

<form class="form-validate"
	action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=invoice&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" name="adminForm" id="adminForm">
	<div>
		<button type="submit" id="save_button" name="save_button"
			class="btn btn-small btn-success btn-save-invoice">
			<span class="icon-save"></span> Save & Close
		</button>
		<button onclick="Joomla.submitbutton('invoice.cancel')"
			class="btn btn-small">
			<span class="icon-cancel"></span> Close
		</button>
	</div>
	<hr>

	<div class="form-horizontal">
		<fieldset class="adminform">
			<div class="row-fluid">
				<div class="span6">
					<?php foreach ($this->form->getFieldset() as $field): ?>
						<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?></div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
                    			<?php endforeach; ?>
                		</div>
			</div>

		</fieldset>
	</div>

	<input type="hidden" name="task" value="invoice.save" />
    <?php echo JHtml::_('form.token'); ?>
</form>
