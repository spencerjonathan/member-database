<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );

$attachment_location = JComponentHelper::getParams ( 'com_memberdatabase' )->get ( 'attachment_location' );
$filename = $attachment_location . "/" . $this->attachment->id;
$filesize = filesize($filename);

header ( 'Content-Description: File Transfer' );
header ( 'Content-Type: ' . $this->attachment->type );
header ( 'Content-Disposition: attachment; filename="' . $this->attachment->name . '"' );
header ( 'Expires: 0' );
header ( 'Cache-Control: must-revalidate' );
header ( 'Pragma: public' );
header ( 'Content-Length: ' . $filesize );

readfile($filename);

exit ();

?>
