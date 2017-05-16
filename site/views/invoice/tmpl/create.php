<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

$document = JFactory::getDocument();
$document->addScript('./media/system/js/core.js');

?>


<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">

<button onclick="Joomla.submitbutton('invoice.add')" id="add_button"
	class="btn btn-small btn-success">
	<span class="icon-new icon-white"></span> Add & Close
</button>
<button onclick="Joomla.submitbutton('invoice.cancel')"
	class="btn btn-small">
	<span class="icon-cancel"></span> Close
</button>
<button onclick=""
	class="btn btn-small">
	<span class="icon-unlock"></span> Exclude Some Members
</button>

<hr>
<h1> <?php echo $this->year; ?> Invoice for <?php echo $this->tower->name; ?></h1>

			<form class="adminform" name="adminForm" id="adminForm" method="post" action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=invoice&layout=create&towerId=' . (int) $this->tower->id); ?>">

				<table class="table table-bordered" width="100%">
					<tr>
						<th>Include?</th>
						<th>Member</th>
						<th>Member Type</th>
						<th style="text-align: right">Subscription Fee</th>
					</tr>
			
			<?php
			$members = $this->get('Members');
			$total_fee = 0.0;
			
			foreach ( $members as $member ) :
				$total_fee = $total_fee + $member->fee;
			
				?>
					<tr>
						<td><input type="checkbox" class="excl-checkbox" name="cid[]" value="<?php echo $member->id; ?>" checked></td>
						<td><?php echo $member->name; ?></td>
						<td><?php echo $member->member_type; ?></td>
						<td style="text-align: right"><?php echo number_format((float)$member->fee, 2, '.', ''); ?></td>
					</tr>

			<?php endforeach; ?>
					<tr>
						<td colspan=4 style="text-align: right"><?php echo number_format((float)$total_fee, 2, '.', ''); ?></td>
					</tr>
				</table>
				<input type="hidden" name="task" value="invoice.add" />
				<input type="hidden" name="tower-id" value="<?php echo $this->tower->id; ?>" />
				<input type="hidden" name="year" value="<?php echo $this->year; ?>" />
			</form>
		</div>
	</div>
</div>
