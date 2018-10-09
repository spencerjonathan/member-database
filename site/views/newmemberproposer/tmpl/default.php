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
Do you wish to propose <?php echo $this->newmember->forenames . " " . $this->newmember->surname; ?> of tower <?php echo $this->newmember->tower_id ?>?<br>
<form action="<?php echo JRoute::_('index.php?option=com_memberdatabase&id=' . (int) $this->item->id); ?>"
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
    
    <button type="submit" id="add_button" name="add_button"
		class="btn btn-success btn-save-newmemberproposer">
		<span class="icon-new icon-white"></span> Submit
	</button>
</form>
