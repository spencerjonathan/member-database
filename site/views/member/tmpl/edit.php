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

if ($this->item->id) {
	$task = "member.edit";
} else {
	$task = "member.add";
}

$jinput = JFactory::getApplication ()->input;
$token = $jinput->get ( 'token', null, 'STRING' );
$token_text = "";
$user_editing = false;

if (isset ( $token )) {
	$token_text = "&token=" . $token;
	$user_editing = true;
}

?>
<legend><?php echo JText::_('Member Database - Member Details'); ?></legend>

<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', 'Member Details'); ?>

<div>
	
	<?php if (!$user_editing) : ?>
	<button onclick="Joomla.submitbutton('member.save')" id="save_button"
		class="btn btn-small">
		<span class="icon-save"></span> Save & Close
	</button>
	<?php endif; ?>
	
	<button onclick="Joomla.submitbutton('member.cancel')"
		class="btn btn-small">
		<span class="icon-cancel"></span> Close
	</button>

	<?php if (!$user_editing) : ?>
	<a
		href="<?php echo JRoute::_('index.php/?option=com_memberdatabase&view=memberhistory&memberId=' . (int) $this->item->id); ?>"
		class="btn btn-small"><span class="icon-eye-open"></span> View History</a>
	<a
		href="<?php echo JRoute::_('index.php/?option=com_memberdatabase&view=member&layout=view&id=' . (int) $this->item->id); ?>"
		class="btn btn-small"><span class="icon-eye-open"></span> View Only</a>
	<a
		href="<?php echo JRoute::_('index.php/?option=com_memberdatabase&view=annualreport&layout=memberdetails&memberId=' . (int) $this->item->id); ?>"
		class="btn btn-small"><span class="icon-eye-open"></span> Annual
		Report View</a>
	<?php endif; ?>
	
</div>
<hr>

<?php $list_view_parameter = ""; if ($this->list_view != "") { $list_view_parameter = "&list_view=" . $this->list_view; }; ?>

<form class="form-validate"
	action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=member&layout=edit&id=' . (int) $this->item->id . $list_view_parameter . $token_text); ?>"
	method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
			
                    <?php
						$sections = array(
							array("main", "Membership Details"),
							array("contact_details", "Contact Details"),
								array("communication_preferences", "Communication Preferences"),
								array("safeguarding", "Safeguarding"),
							
							
								array("privacy", "Privacy")
							
						);											
						
					foreach ($sections as $section) :	
					// Get an appropriate set of fields to display
					$fieldset = $this->form->getFieldset ( $section[0] );
					
					if ($fieldset) : 
					echo '<div><fieldset class="form-horizontal"><legend>' . $section[1] . '</legend>';
					
					if ($section[0] == 'privacy') {
						echo 'General Data Protection Regulation (GDPR) requires that<br> 1) We document in the Association\'s Privicy Policy the lawful basis for processing your personal information, and <br> 2) That you concent to us processing your personal data for the reasons set out in the Association Privicy Policy.<br><br> <a	href="https://scacr.org/documents/membership/SCACR_Data_Protection_Policy.pdf">Read the Association Privicy Policy Here</a><br><br> Concenting to the SCACR processing your personal data for the reasons set out in the Association\'s Privicy Policy is a requirement for membership to the SCACR.<br><br>';
					}
					
					foreach ( $fieldset as $field ) :
																					?>
                        <div class="control-group">
						<div class="control-label"><?php echo $field->label; ?></div>
						<div class="controls"><?php echo $field->input; ?></div>
						</div>
                    <?php endforeach; 
                    echo '</fieldset></div>';
                    endif;
                    endforeach;
                    ?>                
	</div>

	<input type="hidden" name="task" value="<?php echo $task?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>

<?php if (JFactory::getUser ()->authorise ( 'core.admin', 'com_memberdatabase' ) ||
		$user_editing) :?>
		
<div class="span12">
	<hr>
	<button
		onclick="if (confirm('Are you sure you want to verify that the information held about this member is correct?')) { Joomla.submitbutton('member.saveandverify'); }"
		id="saveandverify_button" class="btn btn-small btn-success">
		<span class="icon-ok"></span> Everything Here Is Correct
	</button>
</div>

<?php endif; ?>

<?php echo JHtml::_('bootstrap.endTab'); ?>

<?php
if ($this->item->id) :
	echo JHtml::_ ( 'bootstrap.addTab', 'myTab', 'attachments', 'Member Attachments' );
	?>

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
		
		<?php
	
	foreach ( $this->attachments as $attachment ) {
		$viewLink = JRoute::_ ( 'index.php/?option=com_memberdatabase&view=memberattachment&attachmentId=' . ( int ) $attachment->id . $token_text );
		
		echo "<tr>";
		echo "<td>$attachment->mod_user</td>";
		echo "<td>$attachment->mod_date</td>";
		echo "<td>$attachment->name</td>";
		echo "<td>$attachment->description</td>";
		echo "<td><a href='$viewLink' class='btn btn-small'><span class='icon-eye-open'></span> View</a></td>";
		echo "</tr>";
	}
	;
	?>
		
		</table>
</div>


<?php if(!$user_editing) : ?>

<hr>

<form class="form-horizontal" name="attachmentAdminForm"
	id="attachmentAdminForm" enctype="multipart/form-data" method="post"
	action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=member&layout=edit&id=' . (int) $this->item->id . $token_text); ?>">
	<h2>Add Attachment</h2>

	<fieldset class="adminform">
	<?php foreach ($this->form->getFieldset('attachment') as $field): ?>
		<div class="control-group">
			<div class="control-label"><?php echo $field->label; ?></div>
			<div class="controls"><?php echo $field->input; ?></div>
		</div>				
    <?php endforeach; ?>
	</fieldset>
	<input type="hidden"
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
<?php endif; ?>

<?php echo JHtml::_('bootstrap.endTab'); 
endif; ?>

<!--  ----------------- Invoices Tab ----------------------------- -->

<?php
if ($this->item->id && !$user_editing ) :
	echo JHtml::_ ( 'bootstrap.addTab', 'myTab', 'invoices', 'Invoices' );
	?>

	<table class="table table-striped table-hover invoice-list">
		<thead>
			<tr>
				<th width="20%">Invoice #</th>
				<th width="20%">Tower</th>
				<th width="10%">Created User</th>
				<th width="10%">Created Date</th>
				<th width="10%" style="text-align: right">Amount £</th>
				<th width="10%">Paid?</th>
				<th width="20%">Action</th>
			</tr>
		</thead>
		<tbody>
			
				<?php
				foreach ( $this->invoices as $i => $row ) :
					$link = JRoute::_ ( 'index.php?option=com_memberdatabase&task=invoice.edit&id=' . $row->id, false );
					$view = JRoute::_ ( 'index.php?option=com_memberdatabase&view=invoice&layout=view&id=' . $row->id, false );
					?>
					<tr>
				<td><a href="<?php echo $link; ?>" name="<?php echo "$row->place/$row->id"; ?>"
					title="<?php echo JText::_('Edit Invoice'); ?>"><?php echo "$row->place/$row->id"; ?></a></td>
				<td> <?php echo $row->tower_name; ?> </td>
				<td> <?php echo $row->created_by_user; ?> </td>
				<td> <?php echo $row->created_date; ?> </td>
				<td style="text-align: right">£<?php echo number_format((float)$row->fee, 2, '.', ''); ?></td>
				<td><?php if ($row->paid) { echo '<span class="label label-success"><span class="icon-ok icon-white"></span> Paid</span>'; } ?></td>
				<td><a class="btn" description="View the Invoice" href="<?php echo $view; ?>"><span class="icon-eye-open"></span></a>
				<a class="btn" description="Edit the Invoice" href="<?php echo $link; ?>"><span class="icon-edit"></span></a></td>
			</tr>
				<?php endforeach; ?>
			
		</tbody>
	</table>

<?php echo JHtml::_('bootstrap.endTab'); 
endif; ?>



<div class="span12"><hr></div>
<?php

	echo JHtml::_ ( 'bootstrap.endTabSet' ); 


?>
