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

    public function sendinvoice($towerId) {

		

        //$towerId = $this->input->get('towerId', null, "INT");
        
        $invoiceModel = JModelLegacy::getInstance("Invoice", "MemberDatabaseModel", array());

        // Uses towerId URL parameter to return members that should be included in the invoice
        $members = $invoiceModel->getMembers();

        $invoice = "<table width='100%'><tr><th>Member</th><th>Member Type</th><th>Insurance Group</th><th style='text-align: right'>Fee</th></tr>";

        foreach ($members as $member) {
            $member_type = $member->member_type;
            if ($member->long_service > 0) {
                $member_type .= " (Long Service)";
            }
            $invoice .= "<tr><td>$member->name</td><td>$member_type</td><td>$member->insurance_group</td><td style='text-align: right'>$member->fee</td></tr>";
        }

        $invoice .= "</table>";

        $mailData = array();
        $mailData['tower_id'] = $towerId;
        $mailData['subject'] = "Membership Renewals";
        $mailData['message'] = $invoice;
        $mailData['reply_to_email'] = "membership@scacr.org";
        $mailData['reply_to_name'] = "Jonathan Spencer (SCACR Membership Coordinator)"; 

        $mailModel = JModelLegacy::getInstance("Mail", "MemberDatabaseModel", array());

		if ($mailModel->send($mailData, true))
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
