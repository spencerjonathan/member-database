<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

?>



<table style="table-layout: fixed;" width="700px" class="table-bordered">
	<col width="33%" />
	<col width="33%" />
	<col width="33%" />
				

	<?php
	
	$column = 1;
	$row = 1;
	$needClosingTr = false;
	$needClosingTable = false;
	
	foreach ($this->members as $member) { 
		
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
		echo "<td height='145px'>";
		
		echo "$member->name";
		
		echo "</td>";
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
	
	

	