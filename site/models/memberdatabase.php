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
class MemberDatabaseModelMemberDatabase extends JModelItem
{
	/**
	 * @var string message
	 */
	protected $message;
 
	/**
	 * Get the message
         *
	 * @return  string  The message to be displayed to the user
	 */
	public function getMsg()
	{
		if (!isset($this->message))
		{
			$jinput = JFactory::getApplication()->input;
			$id     = $jinput->get('id', 1, 'INT');
 
			switch ($id)
			{
				case 2:
					$this->message = 'Good bye World!';
					break;
				default:
				case 1:
					$this->message = 'Hello World!';
					break;
			}
		}
 
		return $this->message;
	}
	
	public function getStatus() {
		$status = array();
		
		$status['members'] = $this->getMemberStatus();
		
		return $status;
	}
	
	// Returns the number of records requiring attention
	protected function getMemberStatus() {
		jimport('joomla.application.component.helper');
		$verification_required_since = JComponentHelper::getParams('com_memberdatabase')->get('verification_required_since');
		//$date = DateTime::createFromFormat("Y-m-d", $verification_required_since);
			
		
		// Initialize variables.
		$db    = JFactory::getDbo();
		$userid = JFactory::getUser()->id;
		$query = $db->getQuery(true);
		
		$verifiedSubQuery = '(SELECT member_id, max(verified_date) as `verified_date` FROM `#__md_member_verified` where verified_date >= "' . $verification_required_since . '" group by member_id) v';
		
		// Create the base select statement.
		$query->select('count(*)')
		->from($db->quoteName('#__md_member', 'm'))
		->join('INNER', $db->quoteName('#__md_usertower', 'ut') . ' ON (' . $db->quoteName('m.tower_id') . ' = ' . $db->quoteName('ut.tower_id') . ')')
		->join('LEFT', $verifiedSubQuery . ' ON (' . $db->quoteName('m.id') . ' = ' . $db->quoteName('v.member_id') . ')')
		->where('ut.user_id = ' . $userid . ' and v.member_id is null');
		
		$db->setQuery ( $query );
		
		return $db->loadResult();
	}
	
}
