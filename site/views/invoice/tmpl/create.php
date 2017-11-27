<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

$this->members = $this->get('Members');
$this->tower = $this->get('Tower');

$document = JFactory::getDocument ();
$document->addScriptDeclaration ( '
        function recalculate(id) {
			var checkbox = document.getElementById("checkbox-" + id);
			var fee = document.getElementById("fee-" + id);
			var total_element = document.getElementById("total");

			var total_value = parseFloat(total_element.getAttribute("fee"));
			var fee_value = parseFloat(checkbox.getAttribute("fee"));
	
			if (checkbox.checked) {
				total_value += fee_value;
				fee.style.textDecoration = "none";
				
			} else {
				total_value -= fee_value;
				fee.style.textDecoration = "line-through";
			}

			total_element.setAttribute("fee", total_value);
			total.innerHTML = total_value.toFixed(2);
        };
' );

?>


<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">

<button onclick="Joomla.submitbutton('invoice.add')" id="add_button"
	class="btn btn-small btn-success">
	<span class="icon-new icon-white"></span> Add & Close
</button>
<button onclick="Joomla.submitbutton('invoice.cancel')"
	class="btn btn-small">
	<span class="icon-cancel"></span> Close
</button>
<!--  <button onclick="var elements = document.getElementsByClassName('excl-checkbox'); Array.prototype.forEach.call(elements, function (element) { element.disabled = false; } );"
	class="btn btn-small">
	<span class="icon-unlock"></span> Exclude Some Members
</button>   -->

<hr>
<h1> <?php echo $this->year; ?> Invoice for <?php echo $this->tower->name; ?></h1>
Want to include a new member on the invoice? 
<a
		href="<?php echo JRoute::_('index.php/component/memberdatabase/?view=member&layout=edit&list_view=invoice'); ?>"
		class="btn btn-success"><span	class="icon-new icon-white"></span> Add New Member</a><br>
Remember, if you add a new member you also need to complete a New Member Nomination form and sent it to the Membership Coordinator.

			<form class="adminform" name="adminForm" id="adminForm" method="post" action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=invoice&layout=create&towerId=' . (int) $this->tower->id); ?>">

				<table class="table table-bordered" width="100%">
					<tr>
						<th>Include?</th>
						<th>Member</th>
						<th>Member Type</th>
						<th style="text-align: right">Subscription Fee</th>
					</tr>
			
			<?php
			$members = $this->get('Members');
			$total_fee = 0.0;
			
			$id = 1;
			
			foreach ( $members as $member ) :
				$total_fee = $total_fee + $member->fee;
			
				?>
					<tr>
						<td><input id="checkbox-<?php echo $id; ?>" fee="<?php echo $member->fee; ?>" onchange="recalculate(<?php echo $id; ?>)" type="checkbox" class="excl-checkbox" name="cid[]" value="<?php echo $member->id; ?>" checked></td>
						<td><?php echo $member->name; ?></td>
						<td><?php echo $member->member_type; ?></td>
						<td id="fee-<?php echo $id; ?>" style="text-align: right"><?php echo number_format((float)$member->fee, 2, '.', ''); ?></td>
					</tr>

			<?php 
			$id = $id + 1; 
			endforeach; ?>
					<tr>
						<td colspan=4 id="total" fee="<?php echo $total_fee; ?>" style="text-align: right"><?php echo number_format((float)$total_fee, 2, '.', ''); ?></td>
					</tr>
				</table>
				<input type="hidden" name="task" value="invoice.add" />
				<input type="hidden" name="tower-id" value="<?php echo $this->tower->id; ?>" />
				<input type="hidden" name="year" value="<?php echo $this->year; ?>" />
			</form>
		</div>
	</div>
</div>
