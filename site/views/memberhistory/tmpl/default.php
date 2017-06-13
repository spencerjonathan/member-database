<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

?>

<div style="overflow-x:scroll;">

	<table class="table table-bordered">
<?php error_log("Starting to loop through history records"); foreach ( $this->history as $version ) : ?>
		<tr> 
	<?php foreach ($version as $key => $value) : ?>
			<td><?php echo $value; ?></td>
	<?php endforeach; ?>
		</tr>
<?php endforeach; error_log("Finished looping through history records"); ?>
		
	</table>


</div>
