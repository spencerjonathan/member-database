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
				$sixteen_grand_total = 0;
				$twentyfive_grand_total = 0;
				$seventy_grand_total = 0;
				$eighty_grand_total = 0;
	
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
                            $sixteen_grand_total += $sixteen_total;
                            $twentyfive_grand_total += $twentyfive_total;
                            $seventy_grand_total += $seventy_total;
                            $eighty_grand_total += $eighty_total;


                        endif; 

                        $unspecified_total = 0;
                        $under_total = 0;
                        $sixteen_total = 0;
                        $twentyfive_total = 0;
                        $seventy_total = 0;
                        $eighty_total = 0;
						
			  			$currentdistrictid = $tower->district_id;
						?>
						
							<h1
							<?php if ($firstdistrict) { echo 'class="firstdistrict"'; $firstdistrict=0; } ?>
							align="center"><?php echo $this->districts[$tower->district_id] ?></h1>
						
							<h2 align="center">MEMBERS BY TOWER AND INSURANCE GROUP FOR <?php echo $this->year; ?></h2>
							
			<table style="table-layout: fixed;" width="100%" class="table table-striped">
			<col width="30%" />
			<col width="10%" />
			<col width="10%" />
			<col width="10%" />
			<col width="10%" />
			<col width="10%" />
			<col width="10%" />
			<col width="10%" />
						<tr>
							<th>Tower</th>
							<th style="text-align:right">Unspecified</th>
							<th style="text-align:right">Under 16</th>
							<th style="text-align:right">16-24</th>
							<th style="text-align:right">25-69</th>
							<th style="text-align:right">70-79</th>
							<th style="text-align:right">80 and over</th>
							<th style="text-align:right">Total</th>
						</tr>

			
					<?php endif;
                        $unspecified_total += $tower->unspecified;
                        $under_total += $tower->under16;
                        $sixteen_total += $tower->over16;
                        $twentyfive_total += $tower->over25;
                        $overseventy_total += $tower->over70;
                        $overeighty_total += $tower->over80;
                     ?>
	
					<tr>
						<td><strong><?php echo $tower->tower ?></strong></td>
						<td style="text-align:right; <?php if ($tower->unspecified != 0) echo "color:red;"?>"><?php echo $tower->unspecified ?></td>
						<td style="text-align:right"><?php echo $tower->under16 ?></td>
						<td style="text-align:right"><?php echo $tower->over16 ?></td>
						<td style="text-align:right"><?php echo $tower->over25 ?></td>
						<td style="text-align:right"><?php echo $tower->over70 ?></td>
						<td style="text-align:right"><?php echo $tower->over80 ?></td>
						<td style="text-align:right"><strong><?php echo $tower->total ?></strong></td>
					</tr>
	<?php endforeach; 
                            $unspecified_grand_total += $unspecified_total;
                            $under_grand_total += $under_total;
                            $sixteen_grand_total += $sixteen_total;
                            $twentyfive_grand_total += $twentyfive_total;
                            $seventy_grand_total += $seventy_total;
                            $eighty_grand_total += $eighty_total;
    ?>

                    <tr>
						<td><strong>Totals</strong></td>
						<td style="text-align:right"> <?php echo $unspecified_total ?></td>
						<td style="text-align:right"><?php echo $under_total ?></td>
						<td style="text-align:right"><?php echo $sixteen_total ?></td>
						<td style="text-align:right"><?php echo $twentyfive_total ?></td>
						<td style="text-align:right"><?php echo $seventy_total ?></td>
						<td style="text-align:right"><?php echo $eighty_total ?></td>
						<td style="text-align:right"><strong><?php echo $unspecified_total + $under_total + $sixteen_total + $twentyfive_total + $seventy_total + $eighty_total?></strong></td>
					</tr>	           
                    <tr></tr>
                    <tr>
						<td><strong>Association Grand Totals</strong></td>
						<td style="text-align:right"> <?php echo $unspecified_grand_total ?></td>
						<td style="text-align:right"><?php echo $under_grand_total ?></td>
						<td style="text-align:right"><?php echo $sixteen_grand_total ?></td>
						<td style="text-align:right"><?php echo $twentyfive_grand_total ?></td>
						<td style="text-align:right"><?php echo $seventy_grand_total ?></td>
						<td style="text-align:right"><?php echo $eighty_grand_total ?></td>
						<td style="text-align:right"><strong><?php echo $unspecified_total + $under_total + $sixteen_total + $twentyfive_total + $seventy_total + $eighty_total?></strong></td>
                    </tr>
     
		
	</table>

</div>
</div>
</div>
