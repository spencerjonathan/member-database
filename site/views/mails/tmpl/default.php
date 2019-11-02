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
<form action="index.php?option=com_memberdatabase&view=mails" method="post" id="adminForm" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo JText::_('Mails Filter'); ?>
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
			<th width="20%">
				<?php echo JHtml::_('grid.sort', 'Sent', 'mod_date', $listDirn, $listOrder) ;?>
			</th>
			<th width="20%">
				<?php echo JHtml::_('grid.sort', 'From', 'reply_to_email', $listDirn, $listOrder) ;?>
			</th>
			<th width="20%">
				<?php echo JHtml::_('grid.sort', 'To', 'tower', $listDirn, $listOrder) ;?>
			</th>
			<th width="20%">
				<?php echo JHtml::_('grid.sort', 'Subject', 'subject', $listDirn, $listOrder) ;?>
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
					$link = JRoute::_('index.php?option=com_memberdatabase&task=mail.view&id=' . $row->id);
 				?>
					<tr>
						<td>
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
						<td>
							<?php echo JHtml::_('grid.id', $i, $row->id); ?>
						</td>
						<td>
							<a href="<?php echo $link; ?>" title="<?php echo JText::_('Sent'); ?>">
							<?php echo $row->mod_date; ?>
							</a>
						</td>
						<td>
							<a href="<?php echo $link; ?>" title="<?php echo JText::_('From'); ?>">
							<?php echo $row->reply_to_name; ?>
							</a>
						</td>
						<td>
							<a href="<?php echo $link; ?>" title="<?php echo JText::_('To'); ?>">
							<?php echo $row->tower; ?>
							</a>
						</td>
						<td>
							<a href="<?php echo $link; ?>" title="<?php echo JText::_('Subject'); ?>">
							<?php echo $row->subject; ?>
							</a>
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
