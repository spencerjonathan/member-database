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

        function updateUserId() {
                var username = document.getElementById("user_name");
                var userid = document.getElementById("jform_user_id");
                userid.value = username.value;
        };

');

 
?>
<form action="<?php echo JRoute::_('index.php?option=com_memberdatabase&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="adminForm">
    <div class="form-horizontal">
        <fieldset class="adminform">
            <legend><?php echo JText::_('Member Database - User Tower Authorisation Details'); ?></legend>
            <div class="row-fluid">
                <div class="span6">

			<div class="control-group">
			    <div class="control-label"> <label class="control-label">Tower</label> </div>
			    <div class="controls"><select id="tower_name" name="tower_name" onchange="updateTowerId()">
			        <?php foreach ($this->towers as $tower): ?>
			            <option value=<?php echo $tower->id ?>
			                <?php if ($tower->id == $this->item->tower_id) { echo "selected=\"selected\""; } ?> >
			                <?php echo $tower->city ?>, <?php echo $tower->designation ?>
			            </option>
			        <?php endforeach; ?>
			    </select></div>
			</div>

			<div class="control-group">
			    <div class="control-label"> <label class="control-label">Username</label> </div>
			    <div class="controls"><select id="user_name" name="user_name" onchange="updateUserId()">
			        <?php foreach ($this->users as $user): ?>
			            <option value=<?php echo $user->id ?>
			                <?php if ($user->id == $this->item->user_id) { echo "selected=\"selected\""; } ?> >
			                <?php echo $user->username ?> (<?php echo $user->name ?>)
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
    <input type="hidden" name="task" value="usertower.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
