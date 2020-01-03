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

// Check that the user is logged-in
if (JFactory::getUser()->get('id') == 0)
{
	$app = JFactory::getApplication();
	$app->setUserState('users.login.form.data', array('return' => JUri::getInstance()->toString()));
	
	$url = JRoute::_('index.php?option=com_users&view=login', false);
	
	$app->redirect($url);
}

?>


<h1>Membership Renewals for <?php echo $this->year?></h1>

<div class="panel panel-default">
	<div class="panel-heading">
		<table width="100%">
			<col width="50%">
			<col width="50%">
			<tr>
				<td><h2>Towers With Members With No Invoice</h2></td>
				<td style="text-align: right"><strong>Status:</strong> </td>
			</tr>
		</table>
	</div>
	<div class="panel-body members-without-invoice">

		<table>
<?php

foreach ( $this->data as $tower ) :
    if ($this->towerEmailAssoc[$tower->id]) { $disabled = ""; } else {$disabled = " disabled";}

    error_log("Tower: $tower->id; Email: " . $this->towerEmailAssoc[$tower->id] . "; Disabled: $disabled");

	$email_invoice_link = JRoute::_ ( 'index.php?option=com_memberdatabase&view=membershiprenewal&task=membershiprenewals.sendinvoice&towerId=' . $tower->id );
	?>
	
			<tr>
				<td><?php echo $tower->tower_name; ?> <span class="badge"><?php echo $tower->number_of_members; ?></span></td>
				<td><a class="btn btn-success" <?php echo $disabled; ?> title="<?php echo $this->towerEmailAssoc[$tower->id]; ?>"
					href="<?php echo $email_invoice_link; ?>"><span
						class="icon-mail icon-white"></span> Email Invoice</a></td>
			
			
			<tr>

<?php endforeach; ?>
		
		</table>
	</div>
</div>

