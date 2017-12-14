<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

$towers = $this->get ( "TowerDetails" );

foreach ( $towers as $tower ) :
?>

<div class="page-break-after-always">
<div style="text-align: center">
<h1>HANDBOOK <?php echo $this->year?></h1>
<h2>TOWER DETAILS</h2>
Please check and amend if necessary.
</div>
<hr>
<h2><?php echo $tower->name; ?></h2>

<table style="table-layout: fixed; text-align: left" width="100%"
	class="table-spaced">
	<col width="25%" />
	<col width="75%" />
	
	<tr>
		<th>Number of Bells:</th>
		<td><?php echo $tower->bells?></td>
	</tr>
	<tr>
		<th>Tenor Weight:</th>
		<td><?php echo $tower->tenor?></td>
	</tr>
	<tr>
		<th>Tower Postcode:</th>
		<td><?php echo $tower->post_code?></td>
	</tr>
	<tr>
		<th>Tower Practice Night and Time:</th>
		<td><?php echo $tower->practice_night?> <?php echo $tower->practice_details?></td>
	</tr>
	<tr>
		<th>Sunday Ringing:</th>
		<td><?php echo $tower->sunday_ringing?></td>
	</tr>
	<tr>
		<th>Captain:</th>
		<td><?php echo "$tower->captain_title $tower->captain_forenames $tower->captain_surname";?></td>
	</tr>
	<tr>
		<th>Correspondent:</th>
		<td><?php echo "$tower->corresp_title $tower->corresp_forenames $tower->corresp_surname, $tower->corresp_telephone. $tower->email"?><br>
		<input type="checkbox" /> I am happy to have my name and telephone number published in the annual report and on the association website<br><br>
		Signature: __________________</td>
	</tr>
	
</table>
<br><br>
<hr>
<br>
Please return all forms together in the SAE to the Membership Coordinator at 44 Paddockhall Road, Haywards Heath, West Sussex, RH16 1HW by 1 February. 
</div>
<?php endforeach; ?>
	
		
	

