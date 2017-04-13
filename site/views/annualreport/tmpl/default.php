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

	<?php
	
	$currentdistrictid = 0;
	$firstdistrict = 1;
	foreach ( $this->towers as $tower ) :
		if ($currentdistrictid != $tower->district_id) :
			$currentdistrictid = $tower->district_id;
			?>
			<h1
				<?php if ($firstdistrict) { echo 'class="firstdistrict"'; $firstdistrict=0; } ?>
				align="center"><?php echo $this->districts[$tower->district_id] ?></h1>
			<h2 align="center">TOWER DETAILS AND MEMBERS FOR 2017</h2>
		<?php endif ?>
	<div class="tower">
				<table style="table-layout: fixed;" width="100%">
					<col width="29%" />
					<col width="5%" />
					<col width="17%" />
					<col width="17%" />
					<col width="17%" />
					<col width="15%" />
					<tbody>
						<tr>
							<td><strong><?php echo $tower->place ?>, <?php echo $tower->designation ?></strong></td>
							<td align="center"><?php echo $tower->bells ?></td>
							<td align="centre"><?php echo $tower->tenor ?></td>
							<td align="centre"><?php if ($tower->ground_floor) { echo 'GF'; } ?></td>
							<td COLSPAN=2 align="right">OS: <?php echo $tower->grid_ref . ' / ' . $tower->post_code ?></td>
						</tr>
						<tr>
							<td COLSPAN=2>Practice: <?php echo $tower->practice_night . ' ' . $tower->practice_details?></td>
							<td COLSPAN=4 align="right">Sunday: <?php echo $tower->sunday_ringing ?></td>
						</tr>
						<tr>
							<td COLSPAN=6>Corresp: <?php echo $tower->corresp_title . ' ' . substr($tower->corresp_forenames, 0, 1) . ' ' . $tower->corresp_surname . ', ' . $tower->corresp_telephone . ' - ' . $tower->email ?></td>
						</tr>
						<tr>
							<td COLSPAN=6>Captain: <?php echo $tower->captain_title . ' ' . substr($tower->captain_forenames, 0, 1) . ' ' . $tower->captain_surname . ', ' . $tower->captain_telephone ?></td>
						</tr>
		<?php $i = 1; foreach ($this->members as $member) : ?>
			<?php if ($member->tower_id == $tower->id) : ?>
				<?php if ($member->tower_id == $tower->id) : ?>
				<?php if ($i == 1) : ?>
					<tr>
					<?php endif ?>
					<td align="center" COLSPAN=2><?php echo $member->name ?></td>
						<?php if ($i == 3) : $i = 1; ?>
					</tr>
					<?php else : $i = $i + 1; endif ?>
				<?php endif ?>
			<?php endif ?>
		<?php
				
			
		endforeach
		;
		if ($i != 1) :
			?>
			</tr> 
		<?php endif ?>
		</tbody>
				</table>
			</div>
	<?php endforeach; ?>

</div>
	</div>
</div>
