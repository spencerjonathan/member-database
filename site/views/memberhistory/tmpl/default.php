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

?>

<div>
<a href="<?php echo JRoute::_('index.php/?option=com_memberdatabase&view=member&layout=edit&id=' . (int) $this->memberId); ?>" class="btn btn-small"><span class="icon-edit"></span> Back to Edit</a>
</div>
<hr>

<div style="overflow-x:scroll;"><table class="table table-bordered">
	
<?php 

$fieldNames = array(
		history_id => "History ID",
		id => "Member ID",
		tower => "Tower",
		forenames => "Forenames",	
		surname	=> "Surname",
		title => "Title",
		member_type	=> "Member Type", 
		insurance_group	=> "Insurance Group",
		annual_report => "Annual Report",
		telephone => "Telephone Number",	
		email => "Email Address",	
		newsletters	=> "Newsletters",
		date_elected => "Date Elected",	
		address1 => "Address 1",	
		address2 => "Address 2",
		address3 => "Address 3",
		town => "Town",
		county => "County",
		postcode => "Postcode",
		country => "Country",
		notes => "Notes",
		dbs_date => "DBS Date",
		dbs_update => "DBS Update",
		mod_user => "Last Modified By",
		mod_date => "Last Modified Date"
);

error_log("Starting to loop through history records"); 
$first = true;

/* Write out the headers */
echo "<tr>";
foreach ($fieldNames as $key => $value) {
	echo "<th>$value</th>";
}
echo "</tr>";

/* Now write out the records */
foreach ( $this->history as $version ) { 

	echo "<tr>"; 
	foreach ($fieldNames as $key => $value) {
			echo "<td>" . $version->$key . "</td>";
	}
	echo "</tr>";
	
	error_log("Finished looping through history records");
}
?>

</table></div>
