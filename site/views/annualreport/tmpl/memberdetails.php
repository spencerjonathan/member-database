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
if ($member->newsletters != "Email") :
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
	<col width="12%" />
	<col width="12%" />
	<col width="12%" />
	<col width="12%" />
	<col width="12%" />
	<col width="12%" />
	<tr>
		<td colspan=8>This is the information that we currently hold. Please
			check these details and amend if necessary.</td>
	</tr>
	<tr>
		<td></td>
		<th>Name:</th>
		<td colspan=6><?php echo $member->name?></td>
	</tr>
	<tr>
		<td></td>
		<th>Address:</th>
		<td colspan=6><?php echo $address?></td>
	</tr>
	<tr>
		<td></td>
		<th>Telephone:</th>
		<td colspan=6><?php echo $member->telephone?></td>
	</tr>
	<tr>
		<td></td>
		<th>Email:</th>
		<td colspan=6><?php echo $member->email?></td>
	</tr>
	<tr>
		<td></td>
		<th>Member Type:</th>
		<td colspan=6><?php echo $member->member_type?></td>
	</tr>
	<tr>
		<td></td>
		<th>Correspondence (incl Newsletter):</th>
		<td colspan=2>Email = <strong><?php if ($member->newsletters == "Email") { echo 'YES'; } else echo 'NO'; ?></strong></td>
		<td colspan=2>Post = <strong><?php if ($member->newsletters == "Post") { echo 'YES'; } else echo 'NO';?></strong></td>
		<td colspan=2>Neither = <strong><?php if ($member->newsletters == "Neither") { echo 'YES'; } else echo 'NO';?></strong></td>
	</tr>
	<tr>
		<td></td>
		<th>Insurance Group:</th>
		<td>Under 16 = <strong><?php if ($member->insurance_group == "Under 16") { echo 'YES'; } else echo 'NO'; ?></strong></td>
		<td>16-24 = <strong><?php if ($member->insurance_group == "16-24") { echo 'YES'; } else echo 'NO'; ?></strong></td>
		<td>25-69 = <strong><?php if ($member->insurance_group == "25-69") { echo 'YES'; } else echo 'NO'; ?></strong></td>
		<td>70-79 = <strong><?php if ($member->insurance_group == "70-79") { echo 'YES'; } else echo 'NO'; ?></strong></td>
		<td colspan=2>80 and over = <strong><?php if ($member->insurance_group == "80 and over") { echo 'YES'; } else echo 'NO'; ?></strong></td>
	</tr>
	<tr>
		<td></td>
		<th colspan=3>You would like to receive an annual report (free):</th>
		<td colspan=3><strong><?php if ($member->annual_report == true) { echo 'YES'; } else echo 'NO'; ?></strong></td>
	</tr>
	<tr>
		<td></td>
		<th colspan=7><u>SAFEGUARDING</u></th>
	</tr>
	<tr>
		<td></td>
		<th colspan=3>Do you currently hold a PCC DBS certificate?</th>
		<td colspan=3>YES/NO</td>
	</tr>
	<tr>
		<td></td>
		<td colspan=7>If YES please complete the following:</td>
	</tr>
	<tr>
		<td></td>
		<th colspan=3>Date of DBS Check:</th>
		<td colspan=3>
			<?php if ($member->dbs_date != "") { echo $member->dbs_date; } else { echo $underline; } ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<th colspan=3>Date of last update session:</th>
		<td colspan=3>
		<?php if ($member->dbs_update != "") { echo $member->dbs_update; } else { echo $underline; } ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td colspan=7><hr></td>
	</tr>
	<tr>
		<td></td>
		<td style="text-align: left" colspan=7>TOWER CAPTAINS AND
			CORRESPONDENTS ONLY</td>
	</tr>
	<tr>
		<td></td>
		<td style="text-align: left" colspan=7>I am happy to have my name and
			telephone number published on the SCACR website and in the Annual
			Report and to receive tower correspondence (your personal email
			address will not be shared)?</td>
	</tr>
	<tr>
		<td></td>
		<td colspan=7>Please return this form to your tower correspondent.</td>
	</tr>

</table>
</div>
<?php endif; endforeach; ?>
	
		
	

