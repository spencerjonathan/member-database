<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

if ($this->item->id) {
	$task = "newmember.save";
} else {
	$task = "newmember.add";
}

$jinput = JFactory::getApplication ()->input;

?>
<legend><?php echo JText::_('Member Database - Member Details'); ?></legend>

<div>
	
	<button onclick="Joomla.submitbutton('<?php echo $task ?>')" id="save_button"
		class="btn btn-small">
		<span class="icon-save"></span> Save & Close
	</button>
	
	<button onclick="Joomla.submitbutton('newmember.cancel')"
		class="btn btn-small">
		<span class="icon-cancel"></span> Close
	</button>

</div>
<hr>

<form class="form-validate"
	action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=newmember&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
			
		<?php
			$sections = array(
				array("main", "Membership Details"),
				array("contact_details", "Contact Details"),
				array("communication_preferences", "Communication Preferences"),
				array("safeguarding", "Safeguarding"),
				array("privacy", "Privacy")
			);											
					
			foreach ($sections as $section) :	
			// Get an appropriate set of fields to display
			$fieldset = $this->form->getFieldset ( $section[0] );
				
			if ($fieldset) : 
				echo '<div><fieldset class="form-horizontal"><legend>' . $section[1] . '</legend>';
				
				if ($section[0] == 'privacy') {
					echo 'General Data Protection Regulation (GDPR) requires that<br> 1) We document in the Association\'s Privicy Policy the lawful basis for processing your personal information, and <br> 2) That you concent to us processing your personal data for the reasons set out in the Association Privicy Policy.<br><br> <a	href="https://scacr.org/documents/membership/SCACR_Data_Protection_Policy.pdf">Read the Association Privicy Policy Here</a><br><br> Concenting to the SCACR processing your personal data for the reasons set out in the Association\'s Privicy Policy is a requirement for membership to the SCACR.<br><br>';
				}
					
				foreach ( $fieldset as $field ) :
			?>

					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?></div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				<?php endforeach; 

				echo '</fieldset></div>';
                    	endif;
                    endforeach;
                    ?>                
	</div>

	<input type="hidden" name="task" value="<?php echo $task?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>

