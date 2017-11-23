<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

foreach ( $this->towers as $tower ) : ?>
<div class="page-break-inside-avoid page-break-after-always">
<h3 align="center">Sussex County Association of Change Ringers</h3>
<h3 align="center">Registered Charity No. 268588</h3>
<h2 align="center">SUBSCRIPTIONS FOR <?php echo $this->year?></h2>
<h3><?php echo "$tower->place, $tower->designation"?></h3>

<table  style="table-layout: fixed; font-size:12px" width="100%" class="table table-bordered">
	<col width="30%" />
	<col width="8%" />
	<col width="8%" />
	<col width="8%" />
	<col width="8%" />
	<col width="8%" />
	<col width="15%" />
	<col width="15%" />
	
	<tr>
		<th rowspan=2>NAME</th>
		<th colspan=5 style="text-align:center">SUBSCRIPTION TYPE</th>
		<th rowspan=2 style="text-align:center">TICK to request a 50-year membership certificate</th>
		<th rowspan=2 style="text-align:center">Member details form enclosed (tick)</th>

	</tr>
	<tr>
		<th>Adult<br>£8
		</th>
		<th>Junior<br>£4
		</th>
		<th>Associate<br>£8
		</th>
		<th>Hon. Life<br>Free
		</th>
		<th>Long Service<br>Free
		</th>
	</tr>

			<?php
	
	foreach ( $this->members as $member ) {
		if ($member->tower_id == $tower->id) {
			echo "<tr style=\"height:20px\"><td>$member->surname, $member->forenames<td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			
			</tr>";
		}
		;
	}
	;
	
	// A couple of empty lines for new members
	echo '<tr style="height:30px;"><td><td><td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td></tr>';
	echo '<tr style="height:30px;">
			<td><td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td></tr>';
	echo "</table>";
	?>

<table style="table-layout: fixed;" width="100%">
<col width="60%" />
<col width="10%" />
<col width="10%" />
<col width="15%" />
<col width="5%" />

<tr>
<td>TOTAL AMOUNT ENCLOSED £____________</td>
<td>Cheque</td>
<td><input type="checkbox"></td>
<td>PCC Payment</td>
<td><input type="checkbox"></td>
</tr>
<tr>
<td colspan=3></td>
<td>Bank Transfer</td>
<td><input type="checkbox"></td>
</tr>
</table><br>
(Cheques payable to SCACR or pay by BACS to - Sort Code: 40-52-40, Account No: 00002642 with
clear reference ie SUBS (Tower name))<br><br>
Please return to Membership Coordinator at 44 Paddockhall Road, Haywards Heath, West Sussex, RH16 1HW
</div>
<?php endforeach; ?>

