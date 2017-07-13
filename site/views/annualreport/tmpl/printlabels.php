<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );


$members = $this->getModel("Members")->getMembersByUniqueAddress();

	$column = 1;
	$row = 1;
	$needClosingTr = false;
	$needClosingTable = false;
	
	foreach ($members as $member) { 
		
		if ($column == 1) {
			if ($row == 1) {
				echo '<table style="table-layout: fixed;" width="700px" class="table-bordered page-break-inside-avoid page-break-after-always">
			<col width="33%" />
			<col width="33%" />
			<col width="33%" />';
				
				$needClosingTable = true;			
			}
			
			echo "<tr>";
			$needClosingTr = true;
		}
		echo "<td height='145px' style='padding-left: 20px; padding-right: 20px;'>";
		
		echo '<div style="text-align:right; font-size:60%">';
		echo substr($member->district, 0, 1) . "/$member->tower";
		echo '</div><br><div style="font-size:80%">';
		
		echo "$member->title $member->surname";
		
		if ($member->address1) {
			echo "<br>$member->address1";
		}
		if ($member->address2) {
			echo "<br>$member->address2";
		}
		if ($member->address3) {
			echo "<br>$member->address3";
		}
		if ($member->town) {
			echo "<br>$member->town";
		}
		if ($member->county) {
			echo "<br>$member->county";
		}
		if ($member->postcode) {
			echo "<br>$member->postcode";
		}
		
		echo "</div></td>";
		if ($column == 3) {
			$column = 1; 
			echo "</tr>";
			$needClosingTr = false;
			
			if ($row == 7) {
				$row = 1;
				echo "</table>";
				
				$needClosingTable = false;
			} else ( $row += 1 );
		} else { $column += 1; }
	}
	
	if ($needClosingTr) {
		echo "</tr>";
	}
	
	if ($needClosingTable) {
		echo "</table>";
		
	}
	
?>
	
	

	