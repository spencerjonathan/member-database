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
<legend><?php echo JText::_('Member Database - Member Details'); ?></legend>

<form action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=member&layout=edit&id=' . (int) $this->item->id); ?>"
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
                	<div class="btn-wrapper" id="toolbar-save">
                        	<button onclick="Joomla.submitbutton('member.save')" class="btn btn-small">
                                	<span class="icon-save"></span>
                                	Save & Close</button>
                	</div>
                	<div class="btn-wrapper" id="toolbar-cancel">
                        	<button onclick="Joomla.submitbutton('member.cancel')" class="btn btn-small">
                                	<span class="icon-cancel"></span>
                        	Close</button>
                	</div>
    <input type="hidden" name="task" value="member.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
