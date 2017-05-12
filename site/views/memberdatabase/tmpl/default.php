<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

if ($this->status ['unverified_members'] > 0) {
	$member_status = '<span class="label label-warning">Pending <span class="badge">' . $this->status ['unverified_members'] . '</span></span>';
	$member_explanation = $this->status ['unverified_members'] . ' members have not been verified since ' . $this->verification_required_since . '.  Further action required!';
} else {
	$member_status = '<span class="label label-success">Complete</span>';
	$member_explanation = 'All members at your tower(s) have been verified since ' . $this->verification_required_since . '.  No further action required.';
}

if (count ( $this->status ['towers_no_invoices'] ) > 0) {
	$tower_invoice_status = '<span class="label label-warning">Pending <span class="badge">' . count ( $this->status ['towers_no_invoices'] ) . '</span></span>';
	$tower_invoice_explanation = count ( $this->status ['towers_no_invoices'] ) . ' towers have members that are not included on a subscription fee invoice for ' . $this->year . '.  Further action required.';
} else {
	$tower_invoice_status = '<span class="label label-success">Complete</span>';
	$tower_invoice_explanation = 'All members at your tower(s) are included on an subscription fee invoice for ' . $this->year . '.  No further action required.';
}

?>
<h1>Status for <?php echo $this->year?></h1>
<hr>


<table width="100%">
	<col width="50%">
	<col width="50%">
	<tr>
		<td><h2>Member Details</h2></td>
		<td style="text-align: right"><strong>Status: </strong><?php echo $member_status; ?></td>
	</tr>
</table>

<p><?php echo $member_explanation?></p>
<br>
<a class="btn" href="index.php/component/memberdatabase/?view=members"><span
	class="icon-eye-open"></span> View Members</a>


<hr>

<!-- Members without Invoice -->

<table width="100%">
	<col width="50%">
	<col width="50%">
	<tr>
		<td><h2>Members Without Subscription Invoice</h2></td>
		<td style="text-align: right"><strong>Status: </strong><?php echo $tower_invoice_status; ?></td>
	</tr>
</table>

<?php echo $tower_invoice_explanation?>

<table>
<?php

foreach ( $this->status ['towers_no_invoices'] as $tower ) :
	
	$create_invoice_link = JRoute::_ ( 'index.php?option=com_memberdatabase&view=invoice&layout=create&towerId=' . $tower->id );
	
	?>
	
	<tr>
		<td><?php echo $tower->tower_name; ?> <span class="badge"><?php echo $tower->number_of_members; ?></span></td>
		
		<td><a class="btn btn-success"
			href="<?php echo $create_invoice_link; ?>"><span class="icon-add"></span>
				Create Invoice</a></td>
	
	
	<tr>

<?php endforeach; ?>




</table>

<br>
<a class="btn" href="index.php/component/memberdatabase/?view=invoices"><span
	class="icon-eye-open"></span> View Invoices</a>


<hr>
