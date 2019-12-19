<?php
/**
 * @subpackage  com_memberdatabase
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * MemberDatabase Model
 *
 * @since  0.0.1
 */
class MemberDatabaseModelMembershipRenewals extends JModelItem
{
	/**
	 * @var string message
	 */
	
	// Returns the number of records requiring attention
	public function getTowerData() {

		$memberDatabaseModel = JModelLegacy::getInstance("MemberDatabase", "MemberDatabaseModel", array());

		return $memberDatabaseModel->getMemberWithoutInvoiceCount();
		
	}

    private function getCoveringLetter() {
        $article_alias = JComponentHelper::getParams('com_memberdatabase')->get('covering_letter_alias');

        error_log("Preparing membership renewal email to tower correspondent.  Using article alias '$article_alias'");

        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select($db->quoteName('introtext'))
		->from($db->quoteName('#__content', 'c'))
        ->where("c.alias = '" . $article_alias . "'");

        $db->setQuery ( $query );
        return $db->loadResult ();

    }

    public function sendinvoice($towerId) {

        if (! JFactory::getUser ()->authorise ( 'core.admin', 'com_memberdatabase' )) {
            $this->setError("You are not authorised to perform this action!");
            return "error";
        } 
        
        $invoiceModel = JModelLegacy::getInstance("Invoice", "MemberDatabaseModel", array());

        // Uses towerId URL parameter to return members that should be included in the invoice
        $members = $invoiceModel->getMembers();

        $invoice = "<table style='border-collapse: collapse' border='1' width='100%'><tr><th style='text-align: left'>Member</th><th style='text-align: left'>Member Type</th><th style='text-align: left'>Insurance Group</th><th style='text-align: right'>Fee £</th></tr>";

        $invoice_total = 0;
        
        foreach ($members as $member) {
            $member_type = $member->member_type;
            if ($member->long_service > 0) {
                $member_type .= " (Long Service)";
            }
            $invoice .= "<tr><td>$member->name</td><td>$member_type</td><td>$member->insurance_group</td><td style='text-align: right'>$member->fee</td></tr>";

            $invoice_total += $member->fee;
        }

        $invoice .= "<tr><td colspan='3'><strong>Total</strong></td><td style='text-align: right'><strong>$invoice_total</strong></td></tr>";

        $invoice .= "</table>";

        $message = $this->getCoveringLetter() . "<br><br>" . $invoice;

        $mailData = array();
        $mailData['tower_id'] = $towerId;
        $mailData['subject'] = "Membership Renewals";
        $mailData['message'] = $message;
        $mailData['reply_to_email'] = "membership@scacr.org";
        $mailData['reply_to_name'] = "Jonathan Spencer (SCACR Membership Coordinator)"; 

        $mailModel = JModelLegacy::getInstance("Mail", "MemberDatabaseModel", array());

		if ($mailModel->send($mailData, true, false, false))
		{
			$type = 'message';
		}
		else
		{
			$type = 'error';
		}

		$this->setError($mailModel->getError());

        return $type;

    }
	
}
