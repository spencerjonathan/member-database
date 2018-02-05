<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * <form id="invoice-<?php echo $row->id; ?>"><input type="number" name="id" value="<?php echo $row->id; ?>" /></form>
 * onclick="Joomla.submitbutton('invoice.verify', document.getElementById('invoice-<?php echo $row->id; ?>'))" 
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

// Check that the user is logged-in
if (JFactory::getUser()->get('id') == 0)
{
	$app = JFactory::getApplication();
	$app->setUserState('users.login.form.data', array('return' => JUri::getInstance()->toString()));
	
	$url = JRoute::_('index.php?option=com_users&view=login', false);
	
	$app->redirect($url);
}

JHtml::_ ( 'formbehavior.chosen', 'select' );

$listOrder = $this->escape ( $this->filter_order );
$listDirn = $this->escape ( $this->filter_order_Dir );

?>


<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<!-- <div class="btn-toolbar" id="toolbar" role="btn-toolbar">
				<div class="btn-group"> -->
					<button
						onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list.');}else{ Joomla.submitbutton('invoice.edit')}"
						class="btn">
						<span class="icon-edit"></span> Edit
					</button>
					<button
						onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list.');}else{ Joomla.submitbutton('invoices.delete')}"
						class="btn">
						<span class="icon-delete"></span> Delete
					</button>
				<!-- </div>
			</div> -->
		</div>
	</div>
</div>


<form action="index.php?option=com_memberdatabase&view=invoices"
	method="post" id="adminForm" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo JText::_('Invoices Filter'); ?>
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
				<th width="1%"><?php echo JText::_('#'); ?></th>
				<th width="2%"> <?php echo JHtml::_('grid.checkall'); ?> </th>
				<th width="10%"> <?php echo JHtml::_('grid.sort', 'invoice', 'id', $listDirn, $listOrder) ;?> </th>
				<th width="10%"> <?php echo JHtml::_('grid.sort', 'Tower', 'tower', $listDirn, $listOrder) ;?> </th>
				<th width="10%"> <?php echo JHtml::_('grid.sort', 'Created User', 'created_by_user', $listDirn, $listOrder) ;?> </th>
				<th width="10%"> <?php echo JHtml::_('grid.sort', 'Created Date', 'created_date', $listDirn, $listOrder) ;?> </th>
				<th width="10%" style="text-align: right"> <?php echo JHtml::_('grid.sort', 'Amount £', 'fee', $listDirn, $listOrder) ;?> </th>
				<th width="10%">Paid?</th>
				<th width="10%" >Action</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">
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
					$link = JRoute::_ ( 'index.php?option=com_memberdatabase&task=invoice.edit&id=' . $row->id );
					$view = JRoute::_ ( 'index.php?option=com_memberdatabase&view=invoice&layout=view&id=' . $row->id );
					$verify = JRoute::_ ( 'index.php?option=com_memberdatabase&task=invoice.verify&id=' . $row->id );
					
					//$created_time = strtotime ( $row->created_date );
					?>
					<tr>
				<td> <?php echo $this->pagination->getRowOffset($i); ?> </td>
				<td> <?php echo JHtml::_('grid.id', $i, $row->id); ?> </td>
				<td><a href="<?php echo $link; ?>"
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
			<?php endif; ?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="" /> <input type="hidden"
		name="boxchecked" value="0" /> <input type="hidden"
		name="filter_order" value="<?php echo $listOrder; ?>" /> <input
		type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
