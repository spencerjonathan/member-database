<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

$towers = $this->get ( "TowersAssocArray" );

foreach ( $this->members as $member ) :
	echo '<div class="page-break-after-always" style="font-size:20px">';
	echo $towers [$member->tower_id];
	echo '<h1 align="center">' . $this->association_name . ' Member Details ' . $this->year . '</h1>';
	
	$address = $member->address1;
	if ($member->address2 != "") {
		$address = $address . ", " . $member->address2;
	}
	if ($member->address3 != "") {
		$address = $address . ", " . $member->address3;
	}
	if ($member->county != "") {
		$address = $address . ", " . $member->county;
	}
	if ($member->postcode != "") {
		$address = $address . ", " . $member->postcode;
	}
	if ($member->country != "") {
		$address = $address . ", " . $member->country;
	}
	
	$underline = "__________________";
	?>

<table style="table-layout: fixed; text-align: left" width="100%"
	class="table-spaced">
	<col width="4%" />
	<col width="24%" />
	<col width="24%" />
	<col width="24%" />
	<col width="24%" />
	<tr>
		<td colspan=5>This is the information that we currently hold. Please
			check these details and amend if necessary.</td>
	</tr>
	<tr>
		<td></td>
		<th>Name:</th>
		<td colspan=3><?php echo $member->name?></td>
	</tr>
	<tr>
		<td></td>
		<th>Address:</th>
		<td colspan=3><?php echo $address?></td>
	</tr>
	<tr>
		<td></td>
		<th>Telephone:</th>
		<td colspan=3><?php echo $member->telephone?></td>
	</tr>
	<tr>
		<td></td>
		<th>Email:</th>
		<td colspan=3><?php echo $member->email?></td>
	</tr>
	<tr>
		<td></td>
		<th>Member Type:</th>
		<td colspan=3><?php echo $member->member_type?></td>
	</tr>
	<tr>
		<td></td>
		<th>Correspondence (incl Newsletter):</th>
		<td>Email = <strong><?php if ($member->newsletters == "Email") { echo 'YES'; } else echo 'NO'; ?></strong></td>
		<td>Post = <strong><?php if ($member->newsletters == "Post") { echo 'YES'; } else echo 'NO';?></strong></td>
		<td>Neither = <strong><?php if ($member->newsletters == "Neither") { echo 'YES'; } else echo 'NO';?></strong></td>
	</tr>
	<tr>
		<td></td>
		<th>Insurance Group:</th>
		<td>Under 16 = <strong><?php if ($member->insurance_group == "Under 16") { echo 'YES'; } else echo 'NO'; ?></strong></td>
		<td>16-70 = <strong><?php if ($member->insurance_group == "16-70") { echo 'YES'; } else echo 'NO'; ?></strong></td>
		<td>Over 70 = <strong><?php if ($member->insurance_group == "Over 70") { echo 'YES'; } else echo 'NO'; ?></strong></td>
	</tr>
	<tr>
		<td></td>
		<th colspan=2>You would like to receive an annual report (free):</th>
		<td colspan=2><strong><?php if ($member->annual_report == true) { echo 'YES'; } else echo 'NO'; ?></strong></td>
	</tr>
	<tr>
		<td></td>
		<th colspan=4><u>SAFEGUARDING</u></th>
	</tr>
	<tr>
		<td></td>
		<th colspan=3>Do you currently hold a PCC DBS certificate?</th>
		<td>YES/NO</td>
	</tr>
	<tr>
		<td></td>
		<td colspan=4>If YES please complete the following:</td>
	</tr>
	<tr>
		<td></td>
		<th colspan=2>Date of DBS Check:</th>
		<td colspan=2>
			<?php if ($member->dbs_date != "") { echo $member->dbs_date; } else { echo $underline; } ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<th colspan=2>Date of last update session:</th>
		<td colspan=2>
		<?php if ($member->dbs_update != "") { echo $member->dbs_update; } else { echo $underline; } ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td colspan=4><hr></td>
	</tr>
	<tr>
		<td></td>
		<td style="text-align: left" colspan=4>TOWER CAPTAINS AND
			CORRESPONDENTS ONLY</td>
	</tr>
	<tr>
		<td></td>
		<td style="text-align: left" colspan=4>I am happy to have my name and
			telephone number published on the SCACR website and in the Annual
			Report and to receive tower correspondence (your personal email
			address will not be shared)?</td>
	</tr>
	<tr>
		<td></td>
		<td colspan=4>Please return this form to your tower correspondent.</td>
	</tr>

</table>
</div>
<?php endforeach; ?>
	
		
	

