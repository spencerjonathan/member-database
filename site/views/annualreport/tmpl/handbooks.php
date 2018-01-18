<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

$towers = $this->get ( "Towers" );

$contents = array (
		"",
		"" 
);

$current_column = 0;

$totalhandbooks = 0;

foreach ( $towers as $tower ) {
	$handbooks = 0;
	
	$table = "<tr><td colspan=2>$tower->corresp_forenames $tower->corresp_surname $tower->corresp_email</td></tr>";
	
	$table = $table . "<tr><td><br></td></tr><tr><td><strong>Name</strong></td><td><strong>Annual Report?</strong></td></tr>";
	foreach ( $this->members as $member ) {
		if ($member->tower_id == $tower->id) {
			$table = $table . "<tr><td>$member->surname, $member->forenames</td><td>";
			if ($member->annual_report) {
				$table = $table . "Yes";
				$handbooks = $handbooks + 1;
			}
			$table = $table . "</td></tr>";
		}
	}
	
	$table = $table . "<tr><td><br></td></tr></table>";
	
	$heading = "<table class='page-break-inside-avoid' width=100%><col width=80%/><col width=20%/><tr style='border-bottom: 1px solid'><td><strong>$tower->place, $tower->designation</strong></td><td><strong>$handbooks</strong></td></tr>";
	$contents [$current_column] = $contents [$current_column] . $heading . $table;
	$current_column = ! $current_column;
	$totalhandbooks = $totalhandbooks + $handbooks;
}
?>
	<h1>Total Handbooks: <?php echo $totalhandbooks?></h1>
	<div class="column" style="margin-right: 20px;"><?php echo $contents[0];?></div>
	<div class="column" style="margin-left: 20px;"><?php echo $contents[1];?></div>

