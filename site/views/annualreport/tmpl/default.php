<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

?>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">

			<table style="table-layout: fixed;">
				<col width="5%" />
				<col width="24%" />
				<col width="5%" />
				<col width="17%" />
				<col width="17%" />
				<col width="17%" />
				<col width="15%" />

	<?php
	
	$currentdistrictid = 0;
	$firstdistrict = 1;
	
	$towers = $this->get ( 'Towers' );
	
	foreach ( $towers as $tower ) :
		if ($currentdistrictid != $tower->district_id) :
			$currentdistrictid = $tower->district_id;
			?>
			<tr>
					<td colspan=7>
						<h1
							<?php if ($firstdistrict) { echo 'class="page-break-before-avoid"'; $firstdistrict=0; } else { echo 'class="page-break-before-always"'; } ?>
							align="center"><?php echo $this->districts[$tower->district_id] ?></h1>
					</td>
				</tr>
				<tr>
					<td colspan=7><h2 align="center">TOWER DETAILS AND MEMBERS FOR <?php echo $this->year?></h2></td>
				</tr>
		<?php endif ?>
	<div class="tower">
					
						<tr>
							<td COLSPAN=2><strong><?php 
								echo $tower->place; 
								if ($tower->designation) { echo ", " . $tower->designation; } ?></strong></td>
							<td align="center"><?php if ($tower->bells > 0) { echo $tower->bells; } ?></td>
							<td align="centre"><?php echo $tower->tenor ?></td>
							<td align="centre"><?php if ($tower->ground_floor) { echo 'GF'; } ?></td>
							<td COLSPAN=2 align="right"><?php if ($tower->post_code) { echo "OS: " . $tower->grid_ref . ' / ' . $tower->post_code; } ?></td>
						</tr>
						
						<?php if ($tower->practice_night || $tower->sunday_ringing) : ?>						
						<tr>
							<td COLSPAN=3>Practice: <?php echo $tower->practice_night . ' ' . $tower->practice_details?></td>
							<td COLSPAN=4 align="right"><?php if ($tower->sunday_ringing) { echo "Sunday: " . $tower->sunday_ringing; } ?></td>
						</tr>
						<?php endif ?>
						
						<?php if ($tower->email) :?>
						<tr>
							
							<td COLSPAN=7>Corresp: <?php 
							if ($tower->incl_corresp && $tower->corresp_surname) {
								echo $tower->corresp_title . ' ' . substr($tower->corresp_forenames, 0, 1) . ' ' . $tower->corresp_surname . ', ' . $tower->corresp_telephone . ' - ';
							}
							echo $tower->email; 
							?></td>
						</tr>
						<?php endif ?>
						
						<?php if ($tower->incl_capt && $tower->captain_surname) :?>
						<tr>
							<td COLSPAN=7>Captain: <?php echo $tower->captain_title . ' ' . substr($tower->captain_forenames, 0, 1) . ' ' . $tower->captain_surname . ', ' . $tower->captain_telephone ?></td>
						</tr>
						<?php endif ?>
						
		<?php $i = 1; foreach ($this->members as $member) : ?>
			<?php if ($member->tower_id == $tower->id) : ?>
				<?php if ($member->tower_id == $tower->id) : ?>
				<?php if ($i == 1) : ?>
					<tr><td></td>
					<?php endif ?>
					<td align="left" COLSPAN=2><?php echo $member->name ?></td>
						<?php if ($i == 3) : $i = 1; ?>
					</tr>
					<?php else : $i = $i + 1; endif ?>
				<?php endif ?>
			<?php endif ?>
		<?php
			
		endforeach;
		
		if ($i != 1) :
		?>
				
				</tr> 
		<?php endif ?>
		<tr><td><br></td></tr>
				
	<?php endforeach; ?>
</table>
		</div>
	</div>
</div>
