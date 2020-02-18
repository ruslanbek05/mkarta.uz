<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Analysis
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2020 ruslan qodirov
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Analysis records.
 *
 * @since  1.6
 */
class AnalysisModelAnalyzes extends \Joomla\CMS\MVC\Model\ListModel
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
				'explanation', 'a.explanation',
				'type_of_analysis', 'a.type_of_analysis',
				'image', 'a.image',
				'date', 'a.date',
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

            $query->from('`#__analysis` AS a');
            

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		// Join over the foreign key 'type_of_analysis'
		$query->select('`#__type_of_analysis_3387938`.`type_of_analysis_ru` AS #__type_of_analysis_fk_value_3387938');
		$query->join('LEFT', '#__type_of_analysis AS #__type_of_analysis_3387938 ON #__type_of_analysis_3387938.`id` = a.`type_of_analysis`');
            

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
					$query->where('( a.explanation LIKE ' . $search . '  OR #__type_of_analysis_3387938.type_of_analysis_ru LIKE ' . $search . ' )');
                }
            }
            

		// Filtering type_of_analysis
		$filter_type_of_analysis = $this->state->get("filter.type_of_analysis");

		if ($filter_type_of_analysis)
		{
			$query->where("a.`type_of_analysis` = '".$db->escape($filter_type_of_analysis)."'");
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
		
		
		
		




		
		
		$user = Factory::getUser();
		$groups = $user->get('groups');
		$selecteduser = JRequest::getVar('selecteduser');
		//print_r($groups);die;
		
		
		if ($selecteduser<>null) {
			//$selecteduser given. check if user a doctor
			$db_user_group_name = JFactory::getDbo();
			if($user->get('id') > 0){
				//signed in user
				$doctormi = FALSE;
				foreach ($groups as $group)
				{
				    //echo '<p>Group = ' . $group . '</p>';
					$query_user_group_name = $db_user_group_name
					    ->getQuery(true)
					    ->select('title')
					    ->from($db_user_group_name->quoteName('#__usergroups'))
					    ->where($db_user_group_name->quoteName('id') . " = " . $db_user_group_name->quote($group));

					$db_user_group_name->setQuery($query_user_group_name);
					$result_user_group_name = $db_user_group_name->loadResult();
					//print_r($result_user_group_name);die;
					//echo $result_user_group_name['title'];die;
					if($result_user_group_name == "Doctor"){
						//echo "bu doctor";die;
						$doctormi = TRUE;
						$query->where("a.created_by = '".$db->escape($selecteduser)."'");
					}
				}
				if($doctormi){
					//doctor
					$query->where("a.created_by = '".$db->escape($selecteduser)."'");
				}else{
					//doctor emas
					//null. only own
					$query->where("a.created_by = '".$db->escape($user->get('id'))."'");	
				}
			}
			else{
				//not signed in user
				//null. only own
				$query->where("a.created_by = '".$db->escape($user->get('id'))."'");	
			}
		}else{
			//null. only own
			$query->where("a.created_by = '".$db->escape($user->get('id'))."'");	
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
		
		foreach ($items as $item)
		{

			if (isset($item->type_of_analysis))
			{

				$values    = explode(',', $item->type_of_analysis);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__type_of_analysis_3387938`.`type_of_analysis_ru`')
						->from($db->quoteName('#__type_of_analysis', '#__type_of_analysis_3387938'))
						->where($db->quoteName('#__type_of_analysis_3387938.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->type_of_analysis_ru;
					}
				}

				$item->type_of_analysis = !empty($textValue) ? implode(', ', $textValue) : $item->type_of_analysis;
			}

		}

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
			$app->enqueueMessage(Text::_("COM_ANALYSIS_SEARCH_FILTER_DATE_FORMAT"), "warning");
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
