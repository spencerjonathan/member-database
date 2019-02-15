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

            <table class="table-bordered" width=100%><tr>
                <th>ID</th>
                <th>Type</th>
                <th>Name</th>
                <th>Joining Date</th>
                <th>Tower</th>
                <th>District</th>
            </tr>

			<?php
				$data = $this->get("NewMembers");
            				
				foreach ( $data as $member ) {
                    
                    echo "<tr>";
                    echo "<td>" . $member->id . "</td>";
                    echo "<td>" . $member->type . "</td>";
                    echo "<td>" . $member->surname . ", " . $member->forenames . "</td>";
                    echo "<td>" . $member->member_since . "</td>";
                    echo "<td>" . $member->tower . "</td>";
                    echo "<td>" . $member->district . "</td>";
                    echo "</tr>";
                }

            ?>

	</table>

</div>
</div>
</div>
