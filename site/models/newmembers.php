<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_memberdatabase
 *
 */
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

JLoader::import('QueryHelper', JPATH_COMPONENT . "/helpers/");
JLoader::import('EmailHelper', JPATH_COMPONENT . "/helpers/");

/**
 * MemberDatabaseList Model
 *
 * @since 0.0.1
 */
class MemberDatabaseModelNewMembers extends JModelList {
	/**
	 * Constructor.
	 *
	 * @param array $config
	 *        	An optional associative array of configuration settings.
	 *        	
	 * @see JController
	 * @since 1.6
	 */
	public function __construct($config = array()) {
		if (empty ( $config ['filter_fields'] )) {
			$config ['filter_fields'] = array (
					'id',
					'tower',
					'name'
			);
		}
		
		parent::__construct ( $config );
	}
	
	private function getBaseQuery($db) {
		
		$query = $db->getQuery ( true );
		
        $subquery = '(select p.newmember_id, p.member_id, p.email as proposer_email, p.approved_flag as proposer_approved, p.hash_token as proposer_token, s.email as seconder_email, s.approved_flag as seconder_approved, s.hash_token as seconder_token
                     from #__md_new_member_proposer p
                     inner join #__md_new_member_proposer s on p.newmember_id = s.newmember_id
                     where s.id > p.id)';

		// Create the base select statement.
		$query->select ( 'm.*, concat_ws(\', \',place, designation) as tower, concat_ws(\', \',surname, forenames) as name, props.member_id, if(isnull(props.member_id), "No", "Yes") as promoted, ' .
        'props.proposer_email, props.proposer_approved, props.proposer_token, props.seconder_email, props.seconder_approved, props.seconder_token'  );
		$query->from ( $db->quoteName ( '#__md_new_member', 'm' ) );
		$query->join ( 'LEFT', $db->quoteName ( '#__md_tower', 't' ) . ' ON (' . $db->quoteName ( 'm.tower_id' ) . ' = ' . $db->quoteName ( 't.id' ) . ')' );
        $query->join ( 'LEFT', $subquery . ' props ON props.newmember_id = m.id');
		
		return $query;
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return string An SQL query
	 */
	protected function getListQuery() {
		// Initialize variables.
		$db = JFactory::getDbo ();
		$query = $this->getBaseQuery($db);
		
		// Filter: like / search
		$search = $this->getState ( 'filter.search' );
		
		if (! empty ( $search )) {
			$like = $db->quote ( '%' . $search . '%' );
			$query->where ( 'concat_ws(\', \',surname, forenames, place, designation) LIKE ' . $like );
		}
		
		// Add the list ordering clause.
		error_log("State = " . serialize($this->state));
		
		$orderCol = $this->state->get ( 'list.ordering', 'surname, forenames' );
		$orderDirn = $this->state->get ( 'list.direction', 'asc' );
		
		$query->order ( $db->escape ( $orderCol ) . ' ' . $db->escape ( $orderDirn ) );
		
		return $query;
	}
	
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   The field to order on.
	 * @param   string  $direction  The direction to order on.
	 *
	 * @return  void.
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('site');
		
		$itemid = $app->input->get('id', 0, 'int') . ':' . $app->input->get('Itemid', 0, 'int');
		
		// Optional filter text
		$search = $app->getUserStateFromRequest('com_memberdatabase.newmember.list.' . $itemid . '.filter-search', 'filter-search', '', 'string');
		$this->setState('list.filter', $search);
		
		// Filter.order
		$orderCol = $app->getUserStateFromRequest('com_memberdatabase.newmember.list.' . $itemid . '.filter_order', 'filter_order', '', 'string');
		
		$this->setState('list.ordering', $orderCol);
		
		$listOrder = $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
		
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}
		
		$this->setState('list.direction', $listOrder);
		
		parent::populateState($ordering, $direction);
	}
	
	public function allowDelete($newMemberId) {
		return JFactory::getUser ()->authorise ( 'member.delete', 'com_memberdatabase' );
	}
	
	public function delete(&$newMemberId) {
		
		error_log("In newmember.delete: newMemberId = " . $newMemberId);
		
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->delete($db->quoteName('#__md_new_member'));
		$query->where ( 'id = ' . (int) $newMemberId );
		
		$db->setQuery($query);
		$db->execute();
		
		return true;
		
	}
	
}
