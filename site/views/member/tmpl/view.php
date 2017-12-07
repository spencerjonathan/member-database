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
	<button onclick="Joomla.submitbutton('member.cancel')"
		class="btn btn-small">
		<span class="icon-cancel"></span> Close
	</button>

</div>
<hr>

	<div class="form-horizontal">
		<fieldset class="adminform">
			
                    <?php foreach ($this->form->getFieldset('main') as $field):
                    	//var_dump($field);
                    	if ($field->type != "Hidden") :?>
                        <div class="control-group">
							<div class="control-label"><?php echo $field->label; ?></div>
							<div class="controls">
								<?php 
								//echo "fieldname = $field->fieldname; lookups = " . json_encode($this->lookups);
								if (array_key_exists ( $field->fieldname, $this->lookups ))
									echo $this->lookups[$field->fieldname];
									else echo $field->value; 
								?>
							</div>
						</div>
                    <?php endif; endforeach; ?>
                

		</fieldset>
	</div>


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

<?php echo JHtml::_('bootstrap.endTabSet'); 
endif;
?>
