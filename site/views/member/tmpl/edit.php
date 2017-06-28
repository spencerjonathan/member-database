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
<legend><?php echo JText::_('Member Database - Member Details'); ?></legend>


<!-- Add the toolbar at the top  -->

<div>
<button onclick="if (confirm('Are you sure you want to verify that the information held about this member is correct?')) { Joomla.submitbutton('member.saveandverify'); }" id="saveandverify_button"
	class="btn btn-small btn-success">
	<span class="icon-ok"></span> Save & Verify
</button>
<button onclick="Joomla.submitbutton('member.save')" id="save_button"
	class="btn btn-small">
	<span class="icon-save"></span> Save & Close
</button>
<button onclick="Joomla.submitbutton('member.cancel')"
	class="btn btn-small">
	<span class="icon-cancel"></span> Close
</button>

<a href="<?php echo JRoute::_('index.php/?option=com_memberdatabase&view=memberhistory&memberId=' . (int) $this->item->id); ?>" class="btn btn-small"><span class="icon-eye-open"></span> View History</a>
<a href="<?php echo JRoute::_('index.php/?option=com_memberdatabase&view=annualreport&layout=memberdetails&memberId=' . (int) $this->item->id); ?>" class="btn btn-small"><span class="icon-eye-open"></span> View Only</a>

</div>
<hr>


<!-- The form itself -->

<form class="form-validate"
	action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=member&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" name="adminForm" id="adminForm">
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
	
	<input type="hidden" name="task" value="member.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
