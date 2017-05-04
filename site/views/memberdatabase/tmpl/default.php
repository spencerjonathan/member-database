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

if ($this->status ['members'] > 0) {
	$member_status = '<span class="label label-warning">Pending <span class="badge">' . $this->status ['members'] . '</span></span>';
	$member_explanation = $this->status ['members'] . ' have not been verified since ' . $this->verification_required_since . '. Further action required.';
} else {
	$member_status = '<span class="label label-success">Complete</span>';
	$member_explanation = 'All members at your tower(s) have been verified since ' . $this->verification_required_since . '.  No further action required.';
}

?>
<h1>Status for <?php echo $this->year?></h1>
<hr>


<table width="100%">
	<col width="50%">
	<col width="50%">
	<tr>
		<td><h2>Member Details</h2></td>
		<td style="text-align: right"><strong>Status: </strong><?php echo $member_status; ?></td>
	</tr>
</table>

<?php echo $member_explanation?>
<br>
<a class="btn" href="index.php/component/memberdatabase/?view=members"><span
	class="icon-eye-open"></span> View Members</a>


<hr>
