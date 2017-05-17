<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

$document = JFactory::getDocument ();
$document->addScript ( './media/system/js/core.js' );

$invoice_data = $this->getModel()->getInvoiceData();


?>


<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">


			<h1><?php echo $this->year; ?> Invoice for SCACR Membership Fees</h1>

			<p><strong>Invoice #:</strong> Invoice-<?php echo $invoice_data->id; ?></p>
			<p><strong>Tower:</strong> <?php echo $invoice_data->tower_name; ?></p>
			
			<table class="table table-bordered" width="100%">
				<tr>
					<th>Member</th>
					<th>Member Type</th>
					<th style="text-align: right">Subscription Fee</th>
				</tr>
			
			<?php
			
			$total_fee = 0;
			
			foreach ( $invoice_data->members as $member ) :
				$total_fee = $total_fee + $member->fee;
				
				?>
					<tr>
					<td><?php echo $member->name; ?></td>
					<td><?php echo $member->member_type; ?></td>
					<td style="text-align: right">£<?php echo number_format((float)$member->fee, 2, '.', ''); ?></td>
				</tr>

			<?php endforeach; ?>
					<tr>
					<td colspan=2 style="text-align: left"><strong>Total To Pay:</strong></td>
					<td colspan=4 style="text-align: right"><strong>£<?php echo number_format((float)$total_fee, 2, '.', ''); ?></strong></td>
				</tr>
			</table>


		</div>
	</div>
</div>
