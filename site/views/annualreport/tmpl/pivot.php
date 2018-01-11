<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted Access' );


$document = JFactory::getDocument();
$document->addStyleSheet ( './components/com_memberdatabase/css/orb.min.css' );
$document->addScript('./components/com_memberdatabase/js/react.js');
$document->addScript('./components/com_memberdatabase/js/orb.min.js');

$members = $this->getModel ( "Members" )->getMembersInclInvoices($this->year);

$data = [];

foreach ($members as $member) {
	$dbs = "N";  if ($member->dbs_date) $dbs = "Y";
	$db_form_received = "N"; if ($member->db_form_received) $db_form_received = "Y";
	$accept_privacy_policy = "N"; if ($member->accept_privicy_policy) $accept_privacy_policy = "Y";
	$member_link = "index.php/component/memberdatabase/?view=member&layout=edit&id=" . $member->id;
	$tower_link = "index.php/towers?view=tower&layout=edit&id=" . $member->tower_id;
	$invoice_id = $member->invoice_id;
	$invoice_paid = $member->invoice_paid;
	$record = [ $member->id, $member->member_type, "<a href='$tower_link'>$member->tower</a>", "<a href='$member_link'>$member->name</a>", $member->newsletters, $dbs, $member->mod_user_id, $db_form_received, $accept_privacy_policy, $member->district, $invoice_id, $invoice_paid ];
	
	array_push($data, $record);
}

$data_json = json_encode($data);

$document->addScriptDeclaration("var data = $data_json;
		
// pivot grid options
  var config = {
    dataSource: data,
    dataHeadersLocation: 'columns',
    theme: 'blue',
    toolbar: {
        visible: true
    },
    grandTotal: {
        rowsvisible: true,
        columnsvisible: true
    },
    subTotal: {
        visible: true,
        collapsed: true
    },
    fields: [
		{
            name: '0',
            caption: 'Count',
            dataSettings: {
                  aggregateFunc: 'count'
                  
            }
        },
        { name: '1', caption: 'Member Type' },
        { name: '2', caption: 'Tower' },
        { name: '3', caption: 'Name' },
        { name: '4', caption: 'Comms Pref'},
        { name: '5', caption: 'DBS Checked' },
		{ name: '6', caption: 'Mod User' },
		{ name: '7', caption: 'DB Form' },
		{ name: '8', caption: 'Privacy Policy' },
		{ name: '9', caption: 'District' },
		{ name: '10', caption: 'Inv #' },
		{ name: '11', caption: 'Inv Status' }
        
    ],
    rows    : [ 'Member Type' ],
    columns : [ 'Comms Pref' ],
    data    : [ 'Count' ]
  };
		
  window.onload = function() {
  // instantiate and show the pivot grid
  	new orb.pgridwidget(config).render(document.getElementById('pgrid'));
  };
");

?>

<div id="pgrid"></div>
