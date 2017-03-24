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

$document = JFactory::getDocument();


$document->addScriptDeclaration('
	function updateTowerId() {
		var towername = document.getElementById("tower_name");
		var towerid = document.getElementById("jform_tower_id");
		towerid.value = towername.value;
	};

	
');

 
?>
<legend><?php echo JText::_('Member Database - Member Details'); ?></legend>

<form action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=member&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="adminForm">
    <div class="form-horizontal">
        <fieldset class="adminform">
            <div class="row-fluid">
                <div class="span6">

                	<div class="control-group">
                            <div class="control-label"> <label class="control-label">Tower</label> </div>
                            <div class="controls"><select id="tower_name" name="tower_name" onchange="updateTowerId()"> 
				<?php foreach ($this->towers as $tower): ?>
				    <option value=<?php echo $tower->id ?> >
					<?php echo $tower->city ?>, <?php echo $tower->designation ?> 
				    </option> 
                    		<?php endforeach; ?>
			    </select></div>
			</div>

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
                        	<button onclick="Joomla.submitbutton('member.save')" class="btn btn-small">
                                	<span class="icon-save"></span>
                                	Save & Close</button>
                        	<button onclick="Joomla.submitbutton('member.cancel')" class="btn btn-small">
                                	<span class="icon-cancel"></span>
                        	Close</button>
    <input type="hidden" name="task" value="member.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
