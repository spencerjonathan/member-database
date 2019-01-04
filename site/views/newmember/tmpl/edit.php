<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

$task = "newmember.save" . $this->stage;

?>
<h1><?php echo JText::_('Member Database - Member Details'); ?></h1>

<div>

	<button onclick="Joomla.submitbutton('newmember.cancel')"
		class="btn btn-small">
		<span class="icon-cancel"></span> Close
	</button>

</div>
<hr>

<?php if ($this->stage == "initial") : ?>
Thankyou for applying for membership online.  <strong>Before you start</strong>, you will need your own email address and the email addresses of the two association members proposing you for membership.<br><hr>
<?php endif; ?>

<form class="form-validate"
	action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=newmember&layout=edit&token=' . $this->token . '&stage=' . $this->stage); ?>"
	method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
			
		<?php
$sections = array(
    array(
        "main",
        "Membership Details"
    ),
    array(
        "contact_details",
        "Contact Details"
    ),
    array(
        "communication_preferences",
        "Communication Preferences"
    ),
    array(
        "safeguarding",
        "Safeguarding"
    ),
    array(
        "privacy",
        "Privacy"
    ),
    array(
        "proposers",
        "Proposers"
    )
);

foreach ($sections as $section) :
    // Get an appropriate set of fields to display
    $fieldset = $this->form->getFieldset($section[0]);

    if ($fieldset) :
        echo '<div><fieldset class="form-horizontal"><legend>' . $section[1] . '</legend>';

        if ($section[0] == 'privacy') {
            echo 'General Data Protection Regulation (GDPR) requires that<br> 1) We document in the Association\'s Privicy Policy the lawful basis for processing your personal information, and <br> 2) That you concent to us processing your personal data for the reasons set out in the Association Privicy Policy.<br><br> <a	href="https://scacr.org/documents/membership/Privacy_Policy.pdf">Read the Association Privicy Policy Here</a><br><br> Concenting to the SCACR processing your personal data for the reasons set out in the Association\'s Privicy Policy is a requirement for membership to the SCACR.<br><br>';
        }
        
        if ($section[0] == 'proposers') {
            echo 'Please provide the email addresses of the two existing members that are proposing your membership.  They will both be emailed to confirm that they support your nomination.<br><br>';
        }

        foreach ($fieldset as $field) :
            ?>

			<div class="control-group">
			<?php if ($field->label != "Captcha") :?>
				<div class="control-label"><?php echo $field->label; ?></div>
			<?php endif; ?>
			<div class="controls"><?php echo $field->input; ?></div>
			</div>
		<?php endforeach;

        echo '</fieldset></div>';
    endif;

endforeach
;
?>                
	</div>

	<input type="hidden" name="task" value="<?php echo $task?>" />
    <?php echo JHtml::_('form.token'); ?>
    
    <button type="submit" id="add_button" name="add_button"
		class="btn btn-success btn-save-newmember">
		<span class="icon-new icon-white"></span> Submit
	</button>
	
</form>

