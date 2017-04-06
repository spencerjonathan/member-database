<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JHtml::_('formbehavior.chosen', 'select');
 
$listOrder     = $this->escape($this->filter_order);
$listDirn      = $this->escape($this->filter_order_Dir);

?>


<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="btn-toolbar" id="toolbar" role="btn-toolbar">
				<div class="btn-group">
					<button onclick="Joomla.submitbutton('member.add')" class="btn btn-success">
						<span class="icon-new icon-white"></span>
					New</button>
					<button onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list.');}else{ Joomla.submitbutton('member.edit')}" class="btn">
						<span class="icon-edit"></span>
					Edit</button>
					<button onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list.');}else{ Joomla.submitbutton('members.delete')}" class="btn">
						<span class="icon-delete"></span>
					Delete</button>
				</div>
			</div>
		</div>
	</div>
</div>


<form action="index.php?option=com_memberdatabase&view=members" method="post" id="adminForm" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo JText::_('Members Filter'); ?>
			<?php
				echo JLayoutHelper::render(
					'joomla.searchtools.default',
					array('view' => $this)
				);
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
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'Name', 'name', $listDirn, $listOrder) ;?>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'Tower', 'tower', $listDirn, $listOrder) ;?>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'Email', 'email', $listDirn, $listOrder) ;?>
			</th>
			<th width="7%">
				<?php echo JText::_('ID'); ?>
			</th>
		</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<?php echo $this->pagination->getListFooter(); 
					?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php if (!empty($this->items)) : ?>
				<?php foreach ($this->items as $i => $row) : 
					$link = JRoute::_('index.php?option=com_memberdatabase&task=member.edit&id=' . $row->id);
 				?>
					<tr>
						<td>
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
						<td>
							<?php echo JHtml::_('grid.id', $i, $row->id); ?>
						</td>
						<td>
							<a href="<?php echo $link; ?>" title="<?php echo JText::_('Edit Member'); ?>">
							<?php echo $row->name; ?>
						</td>
						<td>
							<?php echo $row->tower; ?>
						</td>
						<td>
							<?php echo $row->email; ?>
						</td>
						<td align="center">
							<?php echo $row->id; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
