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
	
	
	public function getTowerData() {

		$memberDatabaseModel = JModelLegacy::getInstance("MemberDatabase", "MemberDatabaseModel", array());

		return $memberDatabaseModel->getMemberWithoutInvoiceCount();
		
	}

    public function getTowerName($towerId) {

		$memberDatabaseModel = JModelLegacy::getInstance("AnnualReport", "MemberDatabaseModel", array());

		$data = $memberDatabaseModel->getTowerDetails($towerId);

        $tower = $data[0];
		
        return "$tower->place, $tower->designation";
    }

    public function getTowerEmailAssoc() {

		$memberDatabaseModel = JModelLegacy::getInstance("Towers", "MemberDatabaseModel", array());

		return $memberDatabaseModel->getTowerEmailAssoc();
    }
    
    public function getMissingEmailData($towerId) {

		$memberDatabaseModel = JModelLegacy::getInstance("Members", "MemberDatabaseModel", array());

		$missing_email_data = $memberDatabaseModel->getMissingEmailData($towerId);

        error_log("Missing Email Data = " . serialize($missing_email_data) . "; towerid is $towerId");

        $missing_emails = "We hold email addresses for each member so no action is required.";   

        if (count($missing_email_data) > 0) {
            
            
            $missing_emails = "We would like to hold email addresses for each member of the association so that we can contact them if we need to, however we don't currently hold email addresses for the following members at your tower.  Please can you ask them to get in touch with me at membership@scacr.org to provide an email address where we can contact them.<br><ul>";
            
            foreach ($missing_email_data as $member) {
                $missing_emails .= "<li>" . $member->name . "</li>";                   
            }
            
            $missing_emails .= "</ul>";
        } 
        
        return $missing_emails;
        
    }

	public function getTowerDetailData($towerId) {

		$memberDatabaseModel = JModelLegacy::getInstance("AnnualReport", "MemberDatabaseModel", array());

		$data = $memberDatabaseModel->getTowerDetails($towerId);
		
        $tower = $data[0];

        if ($tower->incl_capt > 0) { $incl_capt = "<strong>Include name in Annual Report</strong>"; } else
            { $incl_capt = "<strong>Do not include name or phone number in Annual Report</strong>"; }

        if ($tower->incl_corresp > 0) { $incl_corresp = "<strong>Include name and phone number in Annual Report</strong>"; } else
            { $incl_corresp = "<strong>Do not include name or phone number in Annual Report</strong>"; }

        $email = $tower->tower_email ? $tower->tower_email : $tower->corresp_email;

        $tower_detail = "<table width='100%'>
    <col width='25%' />
	<col width='75%' />
	
    <tr>
		<th style='text-align: left'>Name:</th>
		<td>$tower->place, $tower->designation</td>
	</tr>

	<tr>
		<th style='text-align: left'>Number of Bells:</th>
		<td>$tower->bells</td>
	</tr>
	<tr>
		<th style='text-align: left'>Tenor Weight:</th>
		<td>$tower->tenor</td>
	</tr>
	<tr>
		<th style='text-align: left'>Tower Postcode:</th>
		<td>$tower->post_code</td>
	</tr>
	<tr>
		<th style='text-align: left'>Tower Practice Night and Time:</th>
		<td>$tower->practice_night $tower->practice_details</td>
	</tr>
	<tr>
		<th style='text-align: left'>Sunday Ringing:</th>
		<td>$tower->sunday_ringing</td>
	</tr>
	<tr>
		<th style='text-align: left'>Captain:</th>
		<td>$tower->captain_title $tower->captain_forenames $tower->captain_surname, $tower->captain_telephone ($incl_capt)</td>
    </tr>
	<tr>
		<th style='text-align: left'>Correspondent:</th>
		<td>$tower->corresp_title $tower->corresp_forenames $tower->corresp_surname, $tower->corresp_telephone $email ($incl_corresp)</td>
	</tr>
	
</table>";

        return $tower_detail;
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

        $tower_name = $this->getTowerName($towerId);

        $invoice = "<h2>$tower_name Membership Subscription Invoice</h2><table style='border-collapse: collapse' border='1' width='100%'><tr><th style='text-align: left'>Member</th><th style='text-align: left'>Member Type</th><th style='text-align: left'>Insurance Group</th><th style='text-align: left'>Receive Annual Report</th><th style='text-align: right'>Fee £</th></tr>";

        $invoice_total = 0;
        
        foreach ($members as $member) {
            $member_type = $member->member_type;
            if ($member->long_service > 0) {
                $member_type .= " (Long Service)";
				$member->fee = 0;
			}
          
            $annual_report = "No";
            if ($member->annual_report > 0) {
                $annual_report = "Yes";
            }

            $invoice .= "<tr><td>$member->name</td><td>$member_type</td><td>$member->insurance_group</td><td>$annual_report</td><td style='text-align: right'>$member->fee</td></tr>";

            $invoice_total += $member->fee;
        }

        $invoice .= "<tr><td colspan='4'><strong>Total</strong></td><td style='text-align: right'><strong>$invoice_total</strong></td></tr>";

        $invoice .= "</table>";

        $message = $this->getCoveringLetter() . "<br><br>" . $invoice . "<br><br>";

        $message .= "<h2>Tower Details</h2>" . $this->getTowerDetailData($towerId) . "<br><br>";

        $message .= "<h2>Missing Email Addresses</h2>" . $this->getMissingEmailData($towerId) . "<br><br>";

        $message .= "Kind Regards,<br><br>Jonathan Spencer<br><i>SCACR Membership Coordinator | membership@scacr.org | 07597 781190</i>"; 

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
