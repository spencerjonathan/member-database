<?php

abstract class ActionlogsHelper
{

    public static function logAction($itemId, $itemName, $type, $action) {

	$userId =  JFactory::getUser()->id;
	$userName =  JFactory::getUser()->username;
	$currentDate = date('Y-m-d H:i:s');

    $itemUrl = JRoute::_('index.php?option=com_memberdatabase&layout=edit&view=' . strtolower($type) . '&id=' . $itemId);

	$userlink = '<a href=\'index.php?option=com_users&task=user.edit&id=' . $userId . "'>$userName</a>";
	$itemlink = '<a href=\'' . $itemUrl . '\'>' . "$itemName ($itemId)" . '</a>';
	$message = "{\"msg\" : \"User $userlink $action $type $itemlink\"}";

	// Get a db connection.
	$db = JFactory::getDbo();

	// Create a new query object.
	$query = $db->getQuery(true);

	// Insert columns.
	$columns = array('message_language_key', 'message', 'log_date', 'extension', 'user_id', 'item_id', 'ip_address');

	// Insert values.
	$values = array($db->quote('{msg}'), $db->quote($message), $db->quote($currentDate), $db->quote('com_memberdatabase'), (int) $userId, (int) $itemId, $db->quote('COM_ACTIONLOGS_DISABLED'));

	// Prepare the insert query.
	$query
	    ->insert($db->quoteName('#__action_logs'))
	    ->columns($db->quoteName($columns))
	    ->values(implode(',', $values));

	// Set the query using our newly populated query object and execute it.
	$db->setQuery($query);
	$db->execute();


    }
    
}

?>
