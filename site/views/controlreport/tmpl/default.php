<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

$this->items = $this->getModel("Members")->getMemberTypeChanges("2017-01-01");

?>

	<table class="table table-hover">
		<thead>
			<tr>
			<th>Name</th>
			<th>Previous Type</th>
			<th>New Type</th>
			<th>Mod User</th>
			<th>Mod Date</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach ( $this->items as $row ) :
					$link = JRoute::_ ( 'index.php?option=com_memberdatabase&view=memberhistory&memberId=' . $row->id );
					?>
					<tr>
				<td> <?php echo "<a href='$link'>$row->surname, $row->forenames</a>"; ?> </td>
				<td> <?php echo $row->prev_member_type; ?> </td>
				<td> <?php echo $row->member_type; ?> </td>
				<td> <?php echo "$row->mod_name($row->mod_username)"; ?> </td>
				<td> <?php echo $row->mod_date; ?> </td>
			</tr>
				<?php endforeach; ?>
		</tbody>
	</table>
