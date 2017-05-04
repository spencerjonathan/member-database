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
			
				$towersbyinsurancegroup = $this->get("TowersByInsuranceGroup");
	
				foreach ( $towersbyinsurancegroup as $tower ) :
			  		if ($currentdistrictid != $tower->district_id) :
			  			if ($currentdistrictid != 0) : ?>
			  				</table>
			  			<?php endif; 
						
			  			$currentdistrictid = $tower->district_id;
						?>
						
							<h1
							<?php if ($firstdistrict) { echo 'class="firstdistrict"'; $firstdistrict=0; } ?>
							align="center"><?php echo $this->districts[$tower->district_id] ?></h1>
						
							<h2 align="center">MEMBERS BY TOWER AND INSURANCE GROUP FOR <?php echo $this->year; ?></h2>
							
			<table style="table-layout: fixed;" width="100%" class="table table-striped">
			<col width="40%" />
			<col width="12%" />
			<col width="12%" />
			<col width="12%" />
			<col width="12%" />
			<col width="12%" />
						<tr>
							<th>Tower</th>
							<th style="text-align:right">Unspecified</th>
							<th style="text-align:right">Under 16</th>
							<th style="text-align:right">16-70</th>
							<th style="text-align:right">Over-70</th>
							<th style="text-align:right">Total</th>
						</tr>
			
					<?php endif ?>
	
					<tr>
						<td><strong><?php echo $tower->tower ?></strong></td>
						<td style="text-align:right; <?php if ($tower->unspecified != 0) echo "color:red;"?>"><?php echo $tower->unspecified ?></td>
						<td style="text-align:right"><?php echo $tower->under16 ?></td>
						<td style="text-align:right"><?php echo $tower->over16 ?></td>
						<td style="text-align:right"><?php echo $tower->over70 ?></td>
						<td style="text-align:right"><strong><?php echo $tower->total ?></strong></td>
					</tr>
	<?php endforeach; ?>
	
		
	</table>

</div>
</div>
</div>
