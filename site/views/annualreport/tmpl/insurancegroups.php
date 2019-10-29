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

                $unspecified_grand_total = 0;
                $under_grand_total = 0;
                $adult_grand_total = 0;
                $over_grand_total = 0;
	
				foreach ( $towersbyinsurancegroup as $tower ) :
			  		if ($currentdistrictid != $tower->district_id) :
                        

			  			if ($currentdistrictid != 0) : ?>
                            <tr>
						        <td><strong>Totals</strong></td>
        						<td style="text-align:right"> <?php echo $unspecified_total ?></td>
		        				<td style="text-align:right"><?php echo $under_total ?></td>
		        				<td style="text-align:right"><?php echo $adult_total ?></td>
		        				<td style="text-align:right"><?php echo $over_total ?></td>
		        				<td style="text-align:right"><strong><?php echo $unspecified_total + $under_total + $adult_total + $over_total ?> </strong></td>
					        </tr>
			  				</table>
			  			<?php 

                            $unspecified_grand_total += $unspecified_total;
                            $under_grand_total += $under_total;
                            $adult_grand_total += $adult_total;
                            $over_grand_total += $over_total;


                        endif; 

                        $unspecified_total = 0;
                        $under_total = 0;
                        $adult_total = 0;
                        $over_total = 0;
						
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

			
					<?php endif;
                        $unspecified_total += $tower->unspecified;
                        $under_total += $tower->under16;
                        $adult_total += $tower->over16;
                        $over_total += $tower->over70;
                     ?>
	
					<tr>
						<td><strong><?php echo $tower->tower ?></strong></td>
						<td style="text-align:right; <?php if ($tower->unspecified != 0) echo "color:red;"?>"><?php echo $tower->unspecified ?></td>
						<td style="text-align:right"><?php echo $tower->under16 ?></td>
						<td style="text-align:right"><?php echo $tower->over16 ?></td>
						<td style="text-align:right"><?php echo $tower->over70 ?></td>
						<td style="text-align:right"><strong><?php echo $tower->total ?></strong></td>
					</tr>
	<?php endforeach; 
                            $unspecified_grand_total += $unspecified_total;
                            $under_grand_total += $under_total;
                            $adult_grand_total += $adult_total;
                            $over_grand_total += $over_total;
    ?>

                    <tr>
						<td><strong>Totals</strong></td>
						<td style="text-align:right"> <?php echo $unspecified_total ?></td>
						<td style="text-align:right"><?php echo $under_total ?></td>
						<td style="text-align:right"><?php echo $adult_total ?></td>
						<td style="text-align:right"><?php echo $over_total ?></td>
						<td style="text-align:right"><strong><?php echo $unspecified_total + $under_total + $adult_total + $over_total ?></strong></td>
					</tr>	           
                    <tr></tr>
                    <tr>
						<td><strong>Association Grand Totals</strong></td>
						<td style="text-align:right"> <?php echo $unspecified_grand_total ?></td>
						<td style="text-align:right"><?php echo $under_grand_total ?></td>
						<td style="text-align:right"><?php echo $adult_grand_total ?></td>
						<td style="text-align:right"><?php echo $over_grand_total ?></td>
						<td style="text-align:right"><strong><?php echo $unspecified_grand_total + $under_grand_total + $adult_grand_total + $over_grand_total ?></strong></td>
                    </tr>
     
		
	</table>

</div>
</div>
</div>
