<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

JHtml::_ ( 'formbehavior.chosen', 'select' );

$listOrder = $this->escape ( $this->filter_order );
$listDirn = $this->escape ( $this->filter_order_Dir );

$jinput = JFactory::getApplication ()->input;

// Check that the user is logged-in
if (JFactory::getUser()->get('id') == 0)
{
	$app = JFactory::getApplication();
	$app->setUserState('users.login.form.data', array('return' => JUri::getInstance()->toString()));
	
	$url = JRoute::_('index.php?option=com_users&view=login', false);
	
	$app->redirect($url);
}

?>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			
			<?php if (JFactory::getUser ()->authorise ( 'core.admin', 'com_memberdatabase' )) :?>
			<button
				onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list.');}else{ Joomla.submitbutton('newmembers.delete')}"
				class="btn">
				<span class="icon-delete"></span> Delete
			</button>
			<?php endif; ?>
			
		</div>
	</div>
</div>


<form
	action="index.php?option=com_memberdatabase&view=newmembers"
	method="post" id="adminForm" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo JText::_('Members Filter'); ?>
			<?php
			echo JLayoutHelper::render ( 'joomla.searchtools.default', array (
					'view' => $this 
			) );
			?>
		</div>
	</div>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th width="2%">
				    <?php echo JHtml::_('grid.checkall'); ?>
                </th>
                <th width="25%">
				    <?php echo JHtml::_('grid.sort', 'Name (Id)', 'name', $listDirn, $listOrder) ;?>
			    </th>
		        <th width="25%">
    				    <?php echo JHtml::_('grid.sort', 'Tower', 'tower', $listDirn, $listOrder) ;?>
    			</th>
                <th width="5%">
	    			<?php echo JHtml::_('grid.sort', 'Member Id', 'member_id', $listDirn, $listOrder) ;?>
    			</th>
                <th width="5%">
	    			<?php echo JHtml::_('grid.sort', 'Promoted?', 'promoted', $listDirn, $listOrder) ;?>
		    	</th>
                <th width="5%">
			    	<?php echo JHtml::_('grid.sort', 'Proposer', 'proposer_email', $listDirn, $listOrder) ;?>
    			</th>
                <th width="5%">
			    	<?php echo JHtml::_('grid.sort', 'Seconder', 'seconder_email', $listDirn, $listOrder) ;?>
    			</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<?php
					
					echo $this->pagination->getListFooter ();
					?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php if (!empty($this->items)) : ?>
				<?php
				
				jimport ( 'joomla.application.component.helper' );
				$verification_required_since = JComponentHelper::getParams ( 'com_memberdatabase' )->get ( 'verification_required_since' );
				$verification_required_since_time = strtotime ( $verification_required_since );
				
				foreach ( $this->items as $i => $row ) :
					$newmember_link = JRoute::_ ( 'index.php?option=com_memberdatabase&task=newmember.edit&id=' . $row->id );
					$member_link = JRoute::_ ( 'index.php?option=com_memberdatabase&task=member.edit&id=' . $row->member_id );
					
					?>
					<tr>
				        <td>
							<?php echo JHtml::_('grid.id', $i, $row->id); ?>
						</td>
				        <td><a href="<?php echo $newmember_link; ?>" title="<?php echo JText::_('Edit New Member'); ?>">
							<?php echo $row->name . ' (' . $row->id . ')'; ?>
                            </a>
						</td>
				        <td>
							<?php echo $row->tower; ?>
						</td>
				        <td><a href="<?php echo $member_link; ?>" title="<?php echo JText::_('Edit New Member'); ?>">
							<?php echo $row->member_id; ?>
                            </a>
						</td>
				        <td>
							<?php echo $row->promoted; ?>
						</td>
                        <td>
							<?php $this->displayProposer($row->proposer_email, $row->proposer_approved, $row->proposer_token, $row->id); ?>
						</td>
                        <td>
							<?php $this->displayProposer($row->seconder_email, $row->seconder_approved, $row->seconder_token, $row->id); ?>
						</td>
			        </tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="" /> <input type="hidden"
		name="boxchecked" value="0" /> <input type="hidden"
		name="filter_order" value="<?php echo $listOrder; ?>" /> <input
		type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
