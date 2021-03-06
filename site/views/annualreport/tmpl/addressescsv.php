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
	$members = $this->getModel("Members")->getMembersByUniqueAddress($this->districtId);
}

$file_content = '"tower","district/tower","full name","forename","surname","address1","address2","address3","town","county","postcode"';

foreach ( $members as $member ) {
	$file_content = $file_content . "\n\"$member->place\",\"" . substr($member->district, 0, 1) . "/$member->tower" . '"';
	$file_content = $file_content . ",\"$member->title $member->surname\",\"$member->forenames\",\"$member->surname\",\"$member->address1\",\"$member->address2\",\"$member->address3\",\"$member->town\",\"$member->county\",\"$member->postcode\"";
}

header ( 'Content-Description: File Transfer' );
header ( 'Content-Type: text/csv' );
header ( 'Content-Disposition: attachment; filename="' . "Addresses.csv" . '"' );
header ( 'Expires: 0' );
header ( 'Cache-Control: must-revalidate' );
header ( 'Pragma: public' );
header ( 'Content-Length: ' . strlen ( $file_content ) );
echo $file_content;
exit ();

?>
