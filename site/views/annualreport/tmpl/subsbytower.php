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
				$currentdistrict = "";
				$firstdistrict = 1;
				
				$headings = array(
						"tower" => "Tower",
						"count_Junior" => "Number of Juniors",
						"sum_Junior" => "Juniors Fees Due",
						"count_Adult" => "Number of Adults",
						"sum_Adult" => "Adult Fees Due",
						""
						
				);
				
				$data = $this->get("MembersSubs", "Members");
				
				foreach ( $data["resultset"] as $tower ) :
			  		if ($currentdistrict != $tower['district']) :
			  			if ($currentdistrict != "") :
			  			
			  			$towerCountTotal = $districtTotals['count_Junior'] + $districtTotals['count_Adult'] + $districtTotals['count_Associate'] + $districtTotals['count_Long Service'] + $districtTotals['count_Honorary Life'] + $districtTotals['count_Non-Member'];
			  			$towerSumTotal = $districtTotals['sum_Junior'] + $districtTotals['sum_Adult'] + $districtTotals['sum_Associate'] + $districtTotals['sum_Long Service'] + $districtTotals['sum_Honorary Life'] + $districtTotals['sum_Non-Member'];
					?>
	
					<tr>
						<td><strong>Totals</strong></td>
						<td style="text-align:right"><?php echo $districtTotals['count_Junior']; ?></td>
						<td style="text-align:right">£<?php echo number_format((float)$districtTotals['sum_Junior'], 2, '.', ''); ?></td>
						<td style="text-align:right"><?php echo $districtTotals['count_Adult']; ?></td>
						<td style="text-align:right">£<?php echo number_format((float)$districtTotals['sum_Adult'], 2, '.', ''); ?></td>
						<td style="text-align:right"><?php echo $districtTotals['count_Associate']; ?></td>
						<td style="text-align:right">£<?php echo number_format((float)$districtTotals['sum_Associate'], 2, '.', ''); ?></td>
						<td style="text-align:right"><?php echo $districtTotals['count_Long Service']; ?></td>
						<td style="text-align:right">£<?php echo number_format((float)$districtTotals['sum_Long Service'], 2, '.', ''); ?></td>
						<td style="text-align:right"><?php echo $districtTotals['count_Honorary Life']; ?></td>
						<td style="text-align:right">£<?php echo number_format((float)$districtTotals['sum_Honorary Life'], 2, '.', ''); ?></td>
						<td style="text-align:right"><?php echo $districtTotals['count_Non-Member']; ?></td>
						<td style="text-align:right">£<?php echo number_format((float)$districtTotals['sum_Non-Member'], 2, '.', ''); ?></td>
						<td style="text-align:right"><strong><?php echo $towerCountTotal; ?></strong></td>
						<td style="text-align:right">£<?php echo number_format((float)$towerSumTotal, 2, '.', ','); ?></td>
					</tr>
			  			
			  				</table>
			  			<?php endif; 
						
			  			$currentdistrict = $tower['district'];
						?>
						
							<h1
							<?php if ($firstdistrict) { $firstdistrict=0; } else { echo 'class="page-break-before-always"'; } ?>
							align="center"><?php echo $tower['district']; ?></h1>
						
							<h2 align="center">SUBS BY TOWER AND MEMBER TYPE FOR <?php echo $this->year; ?></h2>
							
			<table style="table-layout: fixed;" width="100%" class="table table-striped table-bordered">
			<col width="30%" />
			<col width="3%" />
			<col width="7%" />
			<col width="3%" />
			<col width="7%" />
			<col width="3%" />
			<col width="7%" />
			<col width="3%" />
			<col width="7%" />
			<col width="3%" />
			<col width="7%" />
			<col width="3%" />
			<col width="7%" />
			<col width="3%" />
			<col width="7%" />
			
						<tr>
							<th>Tower</th>
							<th colspan=2 style="text-align:center">Junior</th>
							<th colspan=2 style="text-align:center">Adult</th>
							<th colspan=2 style="text-align:center">Associate</th>
							<th colspan=2 style="text-align:center">Long Service</th>
							<th colspan=2 style="text-align:center">Honorary Life</th>
							<th colspan=2 style="text-align:center">Non-Member</th>
							<th colspan=2 style="text-align:center">Total</th>
						</tr>
			
					<?php 
					
						$districtTotals = array('count_Junior' => 0,
								'sum_Junior' => 0,
								'count_Adult' => 0,
								'sum_Adult' => 0,
								'count_Associate' => 0,
								'sum_Associate' => 0,
								'count_Long Service' => 0,
								'sum_Long Service' => 0,
								'count_Honorary Life' => 0,
								'sum_Honorary Life' => 0,
								'count_Non-Member' => 0,
								'sum_Non-Member' => 0
						);
						
					endif;
					
					foreach ($districtTotals as $key => &$value) {
						$value += $tower[$key];
					}
					
					$towerCountTotal = $tower['count_Junior'] + $tower['count_Adult'] + $tower['count_Associate'] + $tower['count_Long Service'] + $tower['count_Honorary Life'] + $tower['count_Non-Member'];
					$towerSumTotal = $tower['sum_Junior'] + $tower['sum_Adult'] + $tower['sum_Associate'] + $tower['sum_Long Service'] + $tower['sum_Honorary Life'] + $tower['sum_Non-Member'];
					?>
	
					<tr>
						<td><strong><?php echo $tower['tower'] ?></strong></td>
						<td style="text-align:right"><?php echo $tower['count_Junior']; ?></td>
						<td style="text-align:right">£<?php echo number_format((float)$tower['sum_Junior'], 2, '.', ''); ?></td>
						<td style="text-align:right"><?php echo $tower['count_Adult']; ?></td>
						<td style="text-align:right">£<?php echo number_format((float)$tower['sum_Adult'], 2, '.', ''); ?></td>
						<td style="text-align:right"><?php echo $tower['count_Associate']; ?></td>
						<td style="text-align:right">£<?php echo number_format((float)$tower['sum_Associate'], 2, '.', ''); ?></td>
						<td style="text-align:right"><?php echo $tower['count_Long Service']; ?></td>
						<td style="text-align:right">£<?php echo number_format((float)$tower['sum_Long Service'], 2, '.', ''); ?></td>
						<td style="text-align:right"><?php echo $tower['count_Honorary Life']; ?></td>
						<td style="text-align:right">£<?php echo number_format((float)$tower['sum_Honorary Life'], 2, '.', ''); ?></td>
						<td style="text-align:right"><?php echo $tower['count_Non-Member']; ?></td>
						<td style="text-align:right">£<?php echo number_format((float)$tower['sum_Non-Member'], 2, '.', ''); ?></td>
						<td style="text-align:right"><strong><?php echo $towerCountTotal; ?></strong></td>
						<td style="text-align:right">£<?php echo number_format((float)$towerSumTotal, 2, '.', ','); ?></td>
					</tr>
	<?php endforeach; ?>
	
		
	</table>

</div>
</div>
</div>
