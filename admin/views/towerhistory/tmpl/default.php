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
<a href="<?php echo JRoute::_('index.php/?option=com_memberdatabase&view=tower&layout=edit&id=' . (int) $this->towerId); ?>" class="btn btn-small"><span class="icon-edit"></span> Back to Edit</a>
</div>
<hr>

<div style="overflow-x:scroll;"><table class="table table-bordered">
	
<?php 

$fieldNames = array(
		history_id => "History ID",
		id => "Member ID",
		place => "Place",
		designation => "Designation",	
		district	=> "District",
		bells => "Bells",
		tenor	=> "Tenor", 
		grid_ref	=> "Grid Ref",
		ground_floor => "Ground Floor?",
		anti_clockwise => "Anti-Clockwise?",	
		unringable => "Unringable?",	
		email	=> "Email Address",
		street => "Address - Street",	
		town => "Address - Town",	
		country => "Address - County",
		postcode => "Address - Postcode",
		country => "Country",
		longitude => "Longitude",
		latitude => "Latitude",
		website => "Tower Website",
		church_website => "Church Website",
		doves_guide => "Doves Entry",
		contact_person => "Contact Person",
		email2 => "Email 2",
		tower_description => "Tower Description",
		wc => "WC?",
		sunday_ringing => "Sunday Ringing",
		active => "Active",
		web_tower_id => "Web Tower ID",
		multi_towers => "Multi Towers",
		practice_night => "Practice Night",
		practice_details => "Practice Details",
		incl_capt => "Incl Capt",
		incl_corresp => "Incl Corresp",
		correspondent => "Correspondent",
		captain => "Captain",
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
