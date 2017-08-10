<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

$jinput = JFactory::getApplication ()->input;
$correspFlag = $jinput->get ( 'correspFlag', 0, 'INT' );

if ($correspFlag) {
	$members = $this->getModel("Members")->getCorrespondents($this->districtId);
} else {
	$members = $this->getModel("Members")->getMembersByUniqueEmailAddress($this->districtId);
}

$file_content = '"district","tower","name","email"';

foreach ( $members as $member ) {
	$file_content = $file_content . "\n\"$member->district\",\"$member->tower\",\"$member->title $member->surname\",\"$member->email\"";
}

header ( 'Content-Description: File Transfer' );
header ( 'Content-Type: text/csv' );
header ( 'Content-Disposition: attachment; filename="' . "Email_addresses.csv" . '"' );
header ( 'Expires: 0' );
header ( 'Cache-Control: must-revalidate' );
header ( 'Pragma: public' );
header ( 'Content-Length: ' . strlen ( $file_content ) );
echo $file_content;
exit ();

?>
