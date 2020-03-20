<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Recommendation
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2020 ruslan qodirov
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Recommendation records.
 *
 * @since  1.6
 */
class RecommendationModelRecommendations extends \Joomla\CMS\MVC\Model\ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'created_by', 'a.created_by',
				'date', 'a.date',
				'recommendation', 'a.recommendation',
				'id_analysis', 'a.id_analysis',
			);
		}

		parent::__construct($config);
	}

        
        
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
            
           
            
        // List state information.

        parent::populateState("a.id", "ASC");

        $context = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $context);

        // Split context into component and optional section
        $parts = FieldsHelper::extract($context);

        if ($parts)
        {
            $this->setState('filter.component', $parts[0]);
            $this->setState('filter.section', $parts[1]);
        }
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
            // Create a new query object.
            $db    = $this->getDbo();
            $query = $db->getQuery(true);

            // Select the required fields from the table.
            $query->select(
                        $this->getState(
                                'list.select', 'DISTINCT a.*'
                        )
                );

            $query->from('`#__recommendation` AS a');
            

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		$query->join('LEFT', '#__analysis AS analiz_table ON analiz_table.id = a.id_analysis');
            






		require_once("myfunc.php");
		$user = Factory::getUser();
		$user_id = $user->get('id');
		$doctormi = is_in_group($user_id,"Doctor");
		
		$aim = JRequest::getVar('aim');
		if($aim == "tome"){
			//show only user's
			//echo $user_id;die;
			$query->where("analiz_table.created_by = '".$db->escape($user_id)."'");
		}elseif($aim == "fromme"){
			//check to doctor
			if($doctormi == TRUE){
				//show only doctor's
				$query->where("a.created_by = '".$db->escape($user_id)."'");
			}else{
				//not doctor, aim is fromme
				$query->where("analiz_table.id = 0");
			}
		}else{
			//show nothing
			$query->where("analiz_table.id = 0");
		}
		
		$id_analysis = JRequest::getVar('id_analysis');
		if($id_analysis > 0){
			$query->where("a.id_analysis = '".$db->escape($id_analysis)."'");
		}
		
		
		





            
            // Filter by search in title
            $search = $this->getState('filter.search');

            if (!empty($search))
            {
                if (stripos($search, 'id:') === 0)
                {
                    $query->where('a.id = ' . (int) substr($search, 3));
                }
                else
                {
                    $search = $db->Quote('%' . $db->escape($search, true) . '%');
					$query->where('( a.recommendation LIKE ' . $search . ' )');
                }
            }
            

		// Filtering date
		$filter_date_from = $this->state->get("filter.date.from");

		if ($filter_date_from !== null && !empty($filter_date_from))
		{
			$query->where("a.`date` >= '".$db->escape($filter_date_from)."'");
		}
		$filter_date_to = $this->state->get("filter.date.to");

		if ($filter_date_to !== null  && !empty($filter_date_to))
		{
			$query->where("a.`date` <= '".$db->escape($filter_date_to)."'");
		}
		
		


            // Add the list ordering clause.
            $orderCol  = $this->state->get('list.ordering', "a.id");
            $orderDirn = $this->state->get('list.direction', "ASC");

            if ($orderCol && $orderDirn)
            {
                $query->order($db->escape($orderCol . ' ' . $orderDirn));
            }

            return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = Factory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(Text::_("COM_RECOMMENDATION_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? Factory::getDate($date)->format("Y-m-d") : null;
	}
}
