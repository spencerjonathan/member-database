<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.core');
JHtml::_('formbehavior.chosen', 'select');

//JFactory::getDocument()->addScriptDeclaration($script);
?>

<legend>AGM 2020 - Ratification of Officers</legend>

	<button onclick="Joomla.submitbutton('election.cancel')"
		class="btn btn-small">
		<span class="icon-cancel"></span> Cancel
	</button>
<hr>
Please select your voting options for each of the nominations below then click Submit at the bottom of the form to submit your vote.<br><br>
There has only been one nominee received for each post.<br><br>
In case of difficulty voting, contact Jon Spencer at membership@scacr.org
<br><br>
<form action="<?php echo JRoute::_('index.php?option=com_memberdatabase&view=election'); ?>" name="adminForm" method="post" id="adminForm">
	<div class="row-fluid">
		<div class="span12">
			<fieldset class="adminform">

				<?php echo $this->form->getInput('member_id'); ?>				
				<?php echo $this->form->getInput('hash_token'); ?>

				<table width="100%">
				<tr>
					<td><div class="control-label"><?php echo $this->form->getLabel('master_response'); ?></div></td>
					<td>Rob Lane</td>
					<td><div class="controls"><?php echo $this->form->getInput('master_response'); ?></div></td>
				</tr>
				<tr>
					<td><div class="control-label"><?php echo $this->form->getLabel('treasurer_response'); ?></div></td>
					<td>Sue Gadd</td>
					<td><div class="controls"><?php echo $this->form->getInput('treasurer_response'); ?></div></td>
				</tr>
				<tr>
					<td><div class="control-label"><?php echo $this->form->getLabel('secretary_response'); ?></div></td>
					<td>Hamish McNaughton</td>
					<td><div class="controls"><?php echo $this->form->getInput('secretary_response'); ?></div></td>
				</tr>
				<tr>
					<td><div class="control-label"><?php echo $this->form->getLabel('brf_secretary_response'); ?></div></td>
					<td>Graham Hills</td>
					<td><div class="controls"><?php echo $this->form->getInput('brf_secretary_response'); ?></div></td>
				</tr>
				<tr>
					<td><div class="control-label"><?php echo $this->form->getLabel('safeguarding_response'); ?></div></td>
					<td>Sue Child</td>
					<td><div class="controls"><?php echo $this->form->getInput('safeguarding_response'); ?></div></td>
				</tr>
				<tr>
					<td><div class="control-label"><?php echo $this->form->getLabel('trustee_response'); ?></div></td>
					<td>Mark Dawkins</td>
					<td><div class="controls"><?php echo $this->form->getInput('trustee_response'); ?></div></td>
				</tr>
				<tr>
					<td><div class="control-label"><?php echo $this->form->getLabel('eastern_cccbr_response'); ?></div></td>
					<td>Alison Everett</td>
					<td><div class="controls"><?php echo $this->form->getInput('eastern_cccbr_response'); ?></div></td>
				</tr>
				<tr><td colspan=3><br><br>At the March 2020 General Committee Meeting a nomination for Alan Collings to receive Honorary Life Membership was made by Alison Everett. This was subsequently seconded by Sue Gadd. The General Committee Meeting voted to put this forward to the AGM.  Alison’s supporting statement is at the bottom of this page.<br><br></td></tr>
				<tr>
					<td><div class="control-label"><?php echo $this->form->getLabel('hon_life_response'); ?></div></td>
					<td>Alan Collings</td>
					<td><div class="controls"><?php echo $this->form->getInput('hon_life_response'); ?></div></td>
				</tr>
				<tr><td colspan=3><br>No Abstention:  The Association’s rules for electing an Honorary Life member make no distinction between an abstention and a vote against.  For this reason, you are given the options to vote for or against Alan's election.</td></tr>
				</table>
                <?php if ($this->form->getField('captcha')) : ?>
				<div class="control-group">
					<div class="controls"><?php echo $this->form->getField('captcha')->renderField(); ?></div>
				</div>
                <?php endif; ?>
			</fieldset>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>
	<button onclick="Joomla.submitbutton('election.submit')" id="submit_button"
		class="btn btn-small btn-success">
		<span class="icon-mail"></span> Submit Your Vote
	</button>

<br><br>
<h2>Alan's Nomination for Life Membership</h2>
At the March 2020 General Committee Meeting a nomination for Alan Collings to receive Honorary Life Membership was made by Alison Everett. This was subsequently seconded by Sue Gadd. The General Committee Meeting voted to put this forward to the AGM.
<br><br>
The proposal was received from Alison Everett, and below she has kindly written a supporting statement:

Alan came to Sussex 18 years ago via Surrey, originally from Devon.  He has been ringing since he was 16, over 60 years ago.
<br><br>
He has contributed a lot to ringing here, particularly through the establishment of the mini ring, Dewby Bells. This resource has been made freely available to ringers at Alan’s home, and has also been used extensively as a tool for raising the profile of ringing at a number of outside events. This has involved on Alan’s part a lot of hard work and effort which he has always contributed quietly and ungrudgingly. He built the mini-ring himself, and said he thought he knew a lot about bells before he started, but learned a lot more!
<br><br>
The first successful quarter peal on the mini ring was in December 2005. Since then over 1,000 quarters have been rung on them, which must be some kind of record over a mere 14 years!
<br><br>
Handbell Recovery is Alan’s handbell restoration business, he is to be found at both Association of Ringing Teachers (ART) and the Central Council’s AGM’s conferences and ringing roadshows. Earlier this year in February 2020 he held a handbell restoration and repair workshop covering the basic skills necessary to keep handbells in good order for the very reasonable price of £15 – which included a pastie lunch of course!
<br><br>
Each year Dewby Bells sponsors entry for a number of Association members to attend the annual ART Conference, with the money from quarter peals - Alan is keen to promote bellringing.
<br><br>
Mary Collings, Alan’s wife, sadly passed away in August 2019 she will be sorely missed. No-one could forget the look of glee on her face as she mastered the controls of a mobility scooter at the South of England Show last year! We understand Alan has generously offered to fund refurbishment of Hooe church bells including a new treble, in honour of Mary, he awaits the decision of the PCC.
<br><br>
Alan has 3 daughters and 7 grandchildren. He has a cat called Slider, who keeps him company at home, and he is keeping in touch with his extended family via Zoom.
