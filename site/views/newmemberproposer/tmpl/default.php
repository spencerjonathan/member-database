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
 
?>
<legend><?php echo JText::_('Member Database - Verify New Member Proposal'); ?></legend>
<?php if (!$this->item->approved_flag) {
    echo 
        "Do you wish to propose " . $this->newmember->forenames . " " . $this->newmember->surname . 
        " of tower " . $this->tower->place . ", " . $this->tower->designation; 
} else {
    echo "You have already responded.";
} 

?>
<br>


<form action="<?php echo JRoute::_('index.php?option=com_memberdatabase&id=' . (int) $this->item->id . "&token=" . $this->token); ?>"
    method="post" name="adminForm" id="adminForm">
    <div class="form-horizontal">
        <fieldset class="adminform">
            <div class="row-fluid">
                <div class="span6">
                    <?php foreach ($this->form->getFieldset() as $field): ?>
                        <div class="control-group">
                            <div class="control-label"><?php echo $field->label; ?></div>
                            <div class="controls"><?php echo $field->input; ?></div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </fieldset>
    </div>
    <input type="hidden" name="task" value="newmemberproposer.save" />
    <?php echo JHtml::_('form.token'); ?>
    <?php if (!$this->item->approved_flag) : ?>
    <button type="submit" id="add_button" name="add_button"
		class="btn btn-success btn-save-newmemberproposer">
		<span class="icon-new icon-white"></span> Submit
	</button>
    <?php endif ?>
</form>
