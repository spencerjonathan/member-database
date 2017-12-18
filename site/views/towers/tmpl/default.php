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

// Check that the user is logged-in
if (JFactory::getUser()->get('id') == 0)
{
	$app = JFactory::getApplication();
	$app->setUserState('users.login.form.data', array('return' => JUri::getInstance()->toString()));
	
	$url = JRoute::_('index.php?option=com_users&view=login', false);
	
	$app->redirect($url);
}

JHtml::_('formbehavior.chosen', 'select');
 
$listOrder     = $this->escape($this->filter_order);
$listDirn      = $this->escape($this->filter_order_Dir);

?>
<form action="index.php?option=com_memberdatabase&view=towers" method="post" id="adminForm" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo JText::_('Towers Filter'); ?>
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
			<th width="40%">
				<?php echo JHtml::_('grid.sort', 'Place', 'place', $listDirn, $listOrder) ;?>
			</th>
			<th width="40%">
				<?php echo JHtml::_('grid.sort', 'Designation', 'designation', $listDirn, $listOrder) ;?>
			</th>
			<th width="10%">
				<?php echo JHtml::_('grid.sort', 'Bells', 'bells', $listDirn, $listOrder) ;?>
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
					$link = JRoute::_('index.php?option=com_memberdatabase&task=tower.edit&id=' . $row->id);
 				?>
					<tr>
						<td>
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
						<td>
							<?php echo JHtml::_('grid.id', $i, $row->id); ?>
						</td>
						<td>
							<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_MEMBERDATABASE_EDIT_TOWER'); ?>">
							<?php echo $row->place; ?>
						</td>
						<td>
							<?php echo $row->designation; ?>
						</td>
						<td>
							<?php echo $row->bells; ?>
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
