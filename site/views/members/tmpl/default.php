<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * <form id="member-<?php echo $row->id; ?>"><input type="number" name="id" value="<?php echo $row->id; ?>" /></form>
 * onclick="Joomla.submitbutton('member.verify', document.getElementById('member-<?php echo $row->id; ?>'))" 
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

JHtml::_ ( 'formbehavior.chosen', 'select' );

$listOrder = $this->escape ( $this->filter_order );
$listDirn = $this->escape ( $this->filter_order_Dir );

$jinput = JFactory::getApplication ()->input;
$token = $jinput->get ( 'token', null, 'STRING' );
$token_text = "";
$user_editing = false;

if (isset ( $token )) {
	$token_text = "&token=" . $token;
	$user_editing = true;
}

?>


<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			
			<?php if (!$user_editing) : ?>
			<button onclick="Joomla.submitbutton('member.add')"
				class="btn btn-success">
				<span class="icon-new icon-white"></span> New
			</button>
			<?php endif; ?>
			<button
				onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list.');}else{ Joomla.submitbutton('member.edit')}"
				class="btn">
				<span class="icon-edit"></span> Edit
			</button>
			<?php if (JFactory::getUser ()->authorise ( 'core.admin', 'com_memberdatabase' )) :?>
			<button
				onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list.');}else{ Joomla.submitbutton('members.delete')}"
				class="btn">
				<span class="icon-delete"></span> Delete
			</button>
			<button
				onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list.');}else{ Joomla.submitbutton('members.verify')}"
				class="btn btn-success">
				<span class="icon-ok icon-white"></span> Everything Here Is Correct
			</button>
			<?php endif; ?>
			
		</div>
	</div>
</div>


<form
	action="index.php?option=com_memberdatabase&view=members<?php echo $token_text; ?>"
	method="post" id="adminForm" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo JText::_('Members Filter'); ?>
			<?php
			echo JLayoutHelper::render ( 'joomla.searchtools.default', array (
					'view' => $this 
			) );
			?>
		</div>
	</div>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th width="1%"><?php echo JText::_('Record Number'); ?></th>
				<th width="2%">
				<?php echo JHtml::_('grid.checkall'); ?>
			</th>
				<th width="25%">
				<?php echo JHtml::_('grid.sort', 'Name', 'name', $listDirn, $listOrder) ;?>
			</th>
				<th width="25%">
				<?php echo JHtml::_('grid.sort', 'Tower', 'tower', $listDirn, $listOrder) ;?>
			</th>
				<th width="25%">
				<?php echo JHtml::_('grid.sort', 'Verified Date', 'verified_date', $listDirn, $listOrder) ;?>
			</th>
				<th width="22%">Verify</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<?php
					
					echo $this->pagination->getListFooter ();
					?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php if (!empty($this->items)) : ?>
				<?php
				
				jimport ( 'joomla.application.component.helper' );
				$verification_required_since = JComponentHelper::getParams ( 'com_memberdatabase' )->get ( 'verification_required_since' );
				$verification_required_since_time = strtotime ( $verification_required_since );
				
				foreach ( $this->items as $i => $row ) :
					$link = JRoute::_ ( 'index.php?option=com_memberdatabase&task=member.edit&id=' . $row->id . $token_text );
					$verify = JRoute::_ ( 'index.php?option=com_memberdatabase&task=member.verify&id=' . $row->id . $token_text );
					
					$verified_time = strtotime ( $row->verified_date );
					?>
					<tr>
				<td>
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
				<td>
							<?php echo JHtml::_('grid.id', $i, $row->id); ?>
						</td>
				<td><a href="<?php echo $link; ?>"
					title="<?php echo JText::_('Edit Member'); ?>">
							<?php echo $row->name; ?>
						</td>
				<td>
							<?php echo $row->tower; ?>
						</td>
				<td>
							<?php
					
					if (! $row->verified_date) {
						echo '<span style="color: red">Unverified!</span>';
					} else {
						if ($verified_time < $verification_required_since_time) {
							echo '<span style="color: red">' . $row->verified_date . '</span>';
						} else {
							echo $row->verified_date;
						}
					}
					
					?>
						</td>
				<td>
				
				<?php if (JFactory::getUser ()->authorise ( 'core.admin', 'com_memberdatabase' )) :?>
				<button class="btn btn-success"
						onclick="if (confirm('Are you sure you want to verify that the information held about this member is correct?')) { document.getElementById('v<?php echo $row->id ?>').click(); }">
						<span class="icon-ok"></span> This Record Is Correct
					</button> <a id='v<?php echo $row->id ?>'
					href='<?php echo $verify; ?>' /a>
				<?php endif; ?>
				
				
				
				
				
				
				</td>
			</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="" /> <input type="hidden"
		name="boxchecked" value="0" /> <input type="hidden"
		name="filter_order" value="<?php echo $listOrder; ?>" /> <input
		type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
