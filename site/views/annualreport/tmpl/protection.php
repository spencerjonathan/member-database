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
	//$firstdistrict = 1;
	
	$towers = $this->get ( 'Towers' );
	
	foreach ( $towers as $tower ) :
		if ($currentdistrictid != $tower->district_id) {
			$currentdistrictid = $tower->district_id;
            echo '<h1>' . $this->districts[$tower->district_id] . '</h1>'; $firstdistrict=0;
        }

        $juniors = array();
        $adults = array();

        foreach ($this->members as $member)  {
		    if ($member->tower_id == $tower->id) {
		        if ($member->member_type == 'Junior') { array_push($juniors, $member->name); }
                if ($member->dbs_date) { array_push($adults, $member->name); }
            }
        }

        if (sizeof($juniors)) : ?>
            
			<br><table style="table-layout: fixed;">
				<col width="50%" />
				<col width="50%" />
			<tr><td COLSPAN=2><strong>
            <?php echo $tower->name; ?>
            </strong></td></tr>
            <tr><td COLSPAN=2><strong>Correspondent: </strong>
            <?php echo $tower->corresp_forenames . " " . $tower->corresp_surname . " " . $tower->corresp_telephone . " " . $tower->corresp_email; ?>
            </td></tr>
            <tr><td><strong>Juniors:</strong>

            <?php foreach ($juniors as $junior) {
                echo "<br />" . $junior;
            }; ?>
            </td><td><strong>DBS Checked Adults:</strong>
            <?php foreach ($adults as $adult) {
                echo "<br />" . $adult;
            }; ?>
            </td></tr></table>
        <?php endif; endforeach; ?>
		</div>
	</div>
</div>

