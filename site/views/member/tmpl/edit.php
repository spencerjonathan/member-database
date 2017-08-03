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




<!-- The form itself -->



<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', 'Member Details'); ?>

<div>
	<button
		onclick="if (confirm('Are you sure you want to verify that the information held about this member is correct?')) { Joomla.submitbutton('member.saveandverify'); }"
		id="saveandverify_button" class="btn btn-small btn-success">
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

	<a
		href="<?php echo JRoute::_('index.php/?option=com_memberdatabase&view=memberhistory&memberId=' . (int) $this->item->id); ?>"
		class="btn btn-small"><span class="icon-eye-open"></span> View History</a>
	<a
		href="<?php echo JRoute::_('index.php/?option=com_memberdatabase&view=annualreport&layout=memberdetails&memberId=' . (int) $this->item->id); ?>"
		class="btn btn-small"><span class="icon-eye-open"></span> View Only</a>

</div>
<hr>

<form class="form-validate"
	action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=member&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" name="adminForm" id="adminForm">
	<div class="form-horizontal">
		<fieldset class="adminform">
			
                    <?php foreach ($this->form->getFieldset('detail') as $field): ?>
                        <div class="control-group">
						<div class="control-label"><?php echo $field->label; ?></div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
                    <?php endforeach; ?>
                

		</fieldset>
	</div>

	<input type="hidden" name="task" value="member.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>

<?php echo JHtml::_('bootstrap.endTab'); ?>

<?php 
if ($this->item->id) :
	echo JHtml::_('bootstrap.addTab', 'myTab', 'attachments', 'Member Attachments'); ?>

<h2>Attachments</h2>
<div>
		<table width="100%" class="table table-striped">
		<tr>
			<th>Added By</th>
			<th>Added Date</th>
			<th>File Name</th>
			<th>Description</th>
			<th>Actions</th>
		</tr>
		
		<?php foreach ($this->attachments as $attachment) {
		$viewLink = JRoute::_('index.php/?option=com_memberdatabase&view=memberattachment&attachmentId=' . (int) $attachment->id);
		
		echo "<tr>";
			echo "<td>$attachment->mod_user</td>";
			echo "<td>$attachment->mod_date</td>";
			echo "<td>$attachment->name</td>";
			echo "<td>$attachment->description</td>";
			echo "<td><a href='$viewLink' class='btn btn-small'><span class='icon-eye-open'></span> View</a></td>";
		echo "</tr>";
		
		}; ?>
		
		</table>
</div>
<hr>
<form class="form-horizontal" name="attachmentAdminForm" id="attachmentAdminForm" enctype="multipart/form-data" method="post"
	action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=member&layout=edit&id=' . (int) $this->item->id); ?>">
	<h2>Add Attachment</h2>
	
	<fieldset class="adminform">
	<?php foreach ($this->form->getFieldset('attachment') as $field): ?>
		<div class="control-group">
		<div class="control-label"><?php echo $field->label; ?></div>
		<div class="controls"><?php echo $field->input; ?></div>
		</div>				
    <?php endforeach; ?>
	</fieldset>
	<input type="hidden" name="jform[id]"
		value="<?php echo (int) $this->item->id; ?>"> <input type="hidden"
		name="task" value="member.addattachment" />
    <?php echo JHtml::_('form.token'); ?>
</form>

<div>
	<button
		onclick="Joomla.submitbutton('member.addattachment', document.getElementById('attachmentAdminForm') );"
		id="addattachment_button" class="btn btn-small btn-success">
		<span class="icon-new icon-white"></span> Add Attachment
	</button>
</div>
<hr>	

<?php echo JHtml::_('bootstrap.endTabSet'); 
endif;
?>
